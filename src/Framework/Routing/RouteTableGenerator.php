<?php

namespace Fabiom\UglyDuckling\Framework\Routing;

use Fabiom\UglyDuckling\Framework\Components\BaseComponent;

/**
 * Scans configured directories for classes carrying a #[Route] attribute and writes
 * the resulting name => [slug, controller|component|resource, allowedgroups] table out
 * to a plain PHP array file, so RouteTable::load() only ever reads a plain array at
 * boot, never reflection.
 *
 * Optionally also ingests a legacy JSON-resource registry (slug => JSON file path) so
 * the same generated table carries every linkable thing's access-control metadata,
 * whether it's a #[Route]-attributed class or a JSON resource.
 *
 * Driven by bin/ud-routes; not invoked at request time.
 */
final class RouteTableGenerator {

    /**
     * @param array{
     *     scan: array<array{namespace: string, directory: string}>,
     *     output: string,
     *     jsonResources?: array{registry: string, variable: string}
     * } $config
     */
    public function generate(array $config): void {
        $routes = [];
        $seenSlugs = [];

        foreach ($config['scan'] as $target) {
            foreach ($this->discoverClasses($target['namespace'], $target['directory']) as $class) {
                if (!class_exists($class)) {
                    continue;
                }

                $attributes = (new \ReflectionClass($class))->getAttributes(Route::class);
                if ($attributes === []) {
                    continue;
                }

                $route = $attributes[0]->newInstance();

                if (isset($routes[$route->name])) {
                    throw new \RuntimeException("Duplicate route name '{$route->name}' ($class)");
                }
                if (isset($seenSlugs[$route->slug])) {
                    throw new \RuntimeException("Duplicate route slug '{$route->slug}' ($class)");
                }
                $seenSlugs[$route->slug] = true;

                $kind = is_subclass_of($class, BaseComponent::class) ? 'component' : 'controller';
                $defaults = (new \ReflectionClass($class))->getDefaultProperties();
                $allowedGroups = $defaults['allowedGroups'] ?? [];
                $routes[$route->name] = ['slug' => $route->slug, $kind => $class, 'allowedgroups' => $allowedGroups];
            }
        }

        if (isset($config['jsonResources'])) {
            $this->mergeJsonResources($config['jsonResources'], $routes);
        }

        file_put_contents($config['output'], "<?php\n\nreturn " . var_export($routes, true) . ";\n");
    }

    /**
     * @param array{registry: string, variable: string} $jsonResourcesConfig
     */
    private function mergeJsonResources(array $jsonResourcesConfig, array &$routes): void {
        $registry = $this->loadRegistry($jsonResourcesConfig['registry'], $jsonResourcesConfig['variable']);

        foreach ($registry as $slug => $jsonPath) {
            if (isset($routes[$slug])) {
                // A #[Route]-attributed controller/component already claims this slug.
                // This mirrors index.php's own dispatch precedence (components > controllers
                // > json resources): the same slug can legitimately name both a full-page
                // controller and the JSON resource it loads inside itself via AJAX, and the
                // controller is what actually answers a direct navigation to that slug.
                continue;
            }

            $decoded = is_readable($jsonPath) ? json_decode(file_get_contents($jsonPath)) : null;
            // Missing "allowedgroups" key mirrors JsonResourceController's own
            // check_authorization_resource_request(): no key declared means deny everyone,
            // which is why null (not []) is stored here.
            $allowedGroups = ($decoded !== null && isset($decoded->allowedgroups)) ? $decoded->allowedgroups : null;

            $routes[$slug] = ['slug' => $slug, 'resource' => $jsonPath, 'allowedgroups' => $allowedGroups];
        }
    }

    /**
     * Requires a registry file (e.g. index_json_resources.php) in an isolated scope and
     * returns the value of the named variable it defines.
     *
     * @return array<string, string>
     */
    private function loadRegistry(string $registryFile, string $variableName): array {
        $vars = (function () use ($registryFile) {
            require $registryFile;
            return get_defined_vars();
        })();

        return $vars[$variableName] ?? [];
    }

    /**
     * @return iterable<string>
     */
    private function discoverClasses(string $namespace, string $directory): iterable {
        if (!is_dir($directory)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            // Cheap pre-filter: only files that actually declare #[Route(...)] can be
            // routes. This must run before class_exists() below - calling class_exists()
            // on every file under the scanned tree would make the autoloader require()
            // (and thus execute) any file whose path happens to match the PSR-4 pattern,
            // including plain top-level scripts like View templates, as a side effect.
            if (!str_contains(file_get_contents($file->getPathname()), '#[Route(')) {
                continue;
            }

            $relativePath = substr($file->getPathname(), strlen($directory) + 1);
            $relativeClass = str_replace(DIRECTORY_SEPARATOR, '\\', substr($relativePath, 0, -4));
            yield $namespace . $relativeClass;
        }
    }

}
