<?php

namespace Fabiom\UglyDuckling\Framework\Components;

class BaseInfoComponent extends BaseComponent {

    protected string $title = '';

    // Each entry describes one field, grouped into rows via the optional 'row' key (default 1).
    // ['type'=>'text|textarea|currency|date|paragraph|raw', 'label'=>'…', 'name'=>'…' (entity key),
    //  'value'=>callable(array $entity):string, 'default'=>'…', 'width'=>12, 'row'=>1, 'cssclass'=>'…',
    //  'raw'=>bool (skip escaping), 'html'=>string|callable(array $entity):string (raw)]
    protected array $fields = [];

    protected function entity(): array {
        return [];
    }

    protected function get_request(): array {
        return $this->entity();
    }

    public function render(array $data): void {
        $entity = $data;
        $rows = [];
        foreach ($this->fields as $field) {
            $rows[$field['row'] ?? 1][] = $field;
        }
        ?>
        <div class="dj-panel">
            <?php if ($this->title !== ''): ?><h2><?= htmlspecialchars($this->title) ?></h2><?php endif; ?>
            <?php foreach ($rows as $rowFields): ?>
                <div class="row">
                    <?php foreach ($rowFields as $field) {
                        $this->renderField($field, $entity);
                    } ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    protected function resolveValue(array $field, array $entity): string {
        if (isset($field['value']) && is_callable($field['value'])) {
            return (string) $field['value']($entity);
        }
        return (string) ($entity[$field['name'] ?? ''] ?? $field['default'] ?? '');
    }

    protected function colClass(array $field): string {
        $width = $field['width'] ?? '';
        return $width === '' ? '' : 'col-md-' . htmlspecialchars((string) $width);
    }

    protected function renderField(array $field, array $entity): void {
        $type = $field['type'] ?? 'text';
        $col = $this->colClass($field);
        $css = $field['cssclass'] ?? '';

        if ($type === 'raw') {
            echo (isset($field['html']) && is_callable($field['html'])) ? $field['html']($entity) : ($field['html'] ?? '');
            return;
        }

        if ($type === 'paragraph') {
            $text = $this->resolveValue($field, $entity);
            ?>
            <div class="<?= htmlspecialchars(trim($col . ' ' . $css)) ?>">
                <p><?= !empty($field['raw']) ? $text : htmlspecialchars($text) ?></p>
            </div>
            <?php
            return;
        }

        $label = $field['label'] ?? '';
        $value = $this->resolveValue($field, $entity);
        if ($type === 'date' && $value !== '') {
            $value = date('d/m/Y', strtotime($value));
        }
        ?>
        <div class="<?= htmlspecialchars(trim($col . ' ' . $css)) ?> mb-2">
            <span class="text-muted small"><?= htmlspecialchars($label) ?></span>
            <div><?= !empty($field['raw']) ? $value : htmlspecialchars($value) ?></div>
        </div>
        <?php
    }

}
