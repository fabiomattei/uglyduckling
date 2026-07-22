<?php

namespace Fabiom\UglyDuckling\Framework\Routing;

use Fabiom\UglyDuckling\Framework\Components\BaseComponent;

/**
 * Scans configured directories for classes carrying a #[Route] attribute and writes
 * the resulting name => [slug, controller|component] table out to a plain PHP array
 * file, so RouteTable::load() only ever reads a plain array at boot, never reflection.
 *
 * Driven by bin/ud-routes; not invoked at request time.
 */
final class RouteTableGenerator {

    /**
     * @param array<array{namespace: string, directory: string}> $scanTargets
     */
    public function generate(array $scanTargets, string $outputPath): void {
        $routes = [];
        $seenSlugs = [];

        foreach ($scanTargets as $target) {
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
                $routes[$route->name] = ['slug' => $route->slug, $kind => $class];
            }
        }

        file_put_contents($outputPath, "<?php\n\nreturn " . var_export($routes, true) . ";\n");
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
            $relativePath = substr($file->getPathname(), strlen($directory) + 1);
            $relativeClass = str_replace(DIRECTORY_SEPARATOR, '\\', substr($relativePath, 0, -4));
            yield $namespace . $relativeClass;
        }
    }

}
