<?php

namespace Fabiom\UglyDuckling\Framework\Components;

class BaseFormComponent extends BaseComponent {

    protected string $title = '';
    protected string $subTitle = '';
    protected string $formId = '';
    protected string $action = '';
    protected string $method = 'POST';

    // Each entry describes one field, grouped into rows via the optional 'row' key (default 1).
    // ['type'=>'text|number|password|textarea|select|checkbox|radio|hidden|file|date|time|info|raw|submit',
    //  'name'=>'…', 'label'=>'…', 'value'=>callable(array $entity):string, 'default'=>'…',
    //  'width'=>12, 'row'=>1, 'attributes'=>['placeholder'=>'…','required'=>'required', …],
    //  'options'=>['value'=>'label', …] (select),
    //  'optionvalue'=>'1', 'checked'=>callable(array $entity):bool (checkbox/radio),
    //  'raw'=>bool (info: skip escaping), 'html'=>string|callable(array $entity):string (raw)]
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
            <?php if ($this->subTitle !== ''): ?><p><?= htmlspecialchars($this->subTitle) ?></p><?php endif; ?>
            <form action="<?= htmlspecialchars($this->action) ?>"
                  <?= $this->formId !== '' ? 'id="' . htmlspecialchars($this->formId) . '"' : '' ?>
                  method="<?= htmlspecialchars($this->method) ?>"
                  <?= $this->hasFileField() ? 'enctype="multipart/form-data"' : '' ?>>
                <?php if (strtoupper($this->method) === 'POST'): ?>
                <input type="hidden" name="_component" value="<?= static::class ?>">
                <input type="hidden" name="csrftoken" value="<?= htmlspecialchars($_SESSION['csrftoken'] ?? '') ?>">
                <?php endif; ?>
                <?php foreach ($rows as $rowFields): ?>
                    <div class="row">
                        <?php foreach ($rowFields as $field) {
                            $this->renderField($field, $entity);
                        } ?>
                    </div>
                <?php endforeach; ?>
            </form>
        </div>
        <?php
    }

    protected function hasFileField(): bool {
        foreach ($this->fields as $field) {
            if (($field['type'] ?? 'text') === 'file') return true;
        }
        return false;
    }

    protected function resolveValue(array $field, array $entity): string {
        if (isset($field['value']) && is_callable($field['value'])) {
            return (string) $field['value']($entity);
        }
        return (string) ($entity[$field['name'] ?? ''] ?? $field['default'] ?? '');
    }

    protected function colClass(array $field): string {
        $width = $field['width'] ?? '';
        return $width === '' ? '' : ' col-md-' . htmlspecialchars((string) $width);
    }

    protected function fieldAttributes(array $field): string {
        $html = '';
        foreach ($field['attributes'] ?? [] as $key => $value) {
            $html .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars((string) $value) . '"';
        }
        return $html;
    }

    protected function renderField(array $field, array $entity): void {
        $type = $field['type'] ?? 'text';
        $name = $field['name'] ?? '';
        $label = $field['label'] ?? '';
        $value = $this->resolveValue($field, $entity);
        $col = $this->colClass($field);
        $attrs = $this->fieldAttributes($field);

        switch ($type) {

            case 'hidden': ?>
                <input type="hidden" name="<?= htmlspecialchars($name) ?>" value="<?= htmlspecialchars($value) ?>">
                <?php break;

            case 'textarea': ?>
                <div class="mb-3<?= $col ?>">
                    <label class="form-label" for="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($label) ?></label>
                    <textarea class="form-control form-control-sm" rows="5" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"<?= $attrs ?>><?= htmlspecialchars($value) ?></textarea>
                </div>
                <?php break;

            case 'select': ?>
                <div class="mb-3<?= $col ?>">
                    <label class="form-label" for="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($label) ?></label>
                    <select class="form-select form-select-sm" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"<?= $attrs ?>>
                        <?php foreach ($field['options'] ?? [] as $optValue => $optLabel): ?>
                            <option value="<?= htmlspecialchars((string) $optValue) ?>" <?= (string) $optValue === $value ? 'selected="selected"' : '' ?>><?= htmlspecialchars($optLabel) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php break;

            case 'checkbox':
            case 'radio':
                $checked = isset($field['checked']) && is_callable($field['checked']) && $field['checked']($entity);
                ?>
                <div class="form-group<?= $col ?>">
                    <input type="<?= $type ?>" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"
                           value="<?= htmlspecialchars($field['optionvalue'] ?? '1') ?>" <?= $checked ? 'checked="checked"' : '' ?><?= $attrs ?>>
                    <label for="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($label) ?></label>
                </div>
                <?php break;

            case 'file': ?>
                <div class="form-group<?= $col ?>">
                    <label for="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($label) ?></label>
                    <input class="form-control" type="file" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"<?= $attrs ?>>
                </div>
                <?php break;

            case 'info': ?>
                <div class="form-group<?= $col ?>">
                    <h5><?= htmlspecialchars($label) ?></h5>
                    <p><?= !empty($field['raw']) ? $value : htmlspecialchars($value) ?></p>
                </div>
                <?php break;

            case 'submit': ?>
                <div class="mb-3<?= $col ?>">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <input type="submit" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"
                               value="<?= htmlspecialchars($label !== '' ? $label : 'Save') ?>" class="btn btn-primary btn-sm">
                    </div>
                </div>
                <?php break;

            case 'raw':
                echo (isset($field['html']) && is_callable($field['html'])) ? $field['html']($entity) : ($field['html'] ?? '');
                break;

            default: ?>
                <div class="mb-3<?= $col ?>">
                    <label class="form-label" for="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($label) ?></label>
                    <input type="<?= htmlspecialchars($type) ?>" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"
                           class="form-control form-control-sm" value="<?= htmlspecialchars($value) ?>"<?= $attrs ?>>
                </div>
                <?php break;
        }
    }

}
