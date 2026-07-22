<?php

namespace Fabiom\UglyDuckling\Framework\Components;

class BaseTableComponent extends BaseComponent {

    protected string $title = '';

    // Each entry: ['headline'=>'…', 'value'=>fn(array $row): string, 'raw'=>bool (optional), 'style'=>'…' (optional)]
    protected array $fields = [];

    // Each entry: ['label'=>'…','url'=>string|fn(array $row):string,'cssclass'=>'…','icon'=>'…','confirm'=>'…']
    // or a Closure(array $row): string, or a raw HTML string
    protected array $actions = [];

    // Same shape as $actions but not bound to a row (rendered once, above/below the table)
    protected array $topActions = [];
    protected array $bottomActions = [];

    protected function rows(): array {
        return [];
    }

    protected function get_request(): array {
        return $this->rows();
    }

    public function render(array $data): void {
        $rows = $data;
        $colspan = count($this->fields) + (empty($this->actions) ? 0 : 1);
        ?>
        <div class="dj-panel">
            <?php if ($this->title !== ''): ?><h2><?= htmlspecialchars($this->title) ?></h2><?php endif; ?>
            <?php $this->renderActionBar($this->topActions); ?>
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <?php foreach ($this->fields as $field): ?>
                            <th<?= isset($field['style']) ? ' style="' . htmlspecialchars($field['style']) . '"' : '' ?>><?= htmlspecialchars($field['headline']) ?></th>
                        <?php endforeach; ?>
                        <?php if (!empty($this->actions)): ?><th></th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr><td colspan="<?= $colspan ?>" class="text-center text-muted">No data</td></tr>
                    <?php endif; ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <?php foreach ($this->fields as $field): ?>
                                <td><?= $this->renderFieldValue($field, $row) ?></td>
                            <?php endforeach; ?>
                            <?php if (!empty($this->actions)): ?>
                                <td class="dj-row-actions">
                                    <?php foreach ($this->actions as $action) {
                                        echo $this->renderAction($action, $row);
                                    } ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php $this->renderActionBar($this->bottomActions); ?>
        </div>
        <?php
    }

    protected function renderFieldValue(array $field, array $row): string {
        $value = (string) ($field['value'])($row);
        return !empty($field['raw']) ? $value : htmlspecialchars($value);
    }

    protected function renderActionBar(array $actions): void {
        if (empty($actions)) return;
        echo '<div class="dj-table-actions mb-2">';
        foreach ($actions as $action) {
            echo $this->renderAction($action);
        }
        echo '</div>';
    }

    protected function renderAction(mixed $action, ?array $row = null): string {
        if ($action instanceof \Closure) {
            return (string) $action($row ?? []);
        }
        if (is_array($action)) {
            return $this->renderActionArray($action, $row);
        }
        return (string) $action;
    }

    protected function renderActionArray(array $action, ?array $row): string {
        $url = $action['url'] ?? '#';
        if ($url instanceof \Closure) {
            $url = $url($row ?? []);
        }
        $label = htmlspecialchars($action['label'] ?? '');
        $icon = isset($action['icon']) ? '<i class="' . htmlspecialchars($action['icon']) . '"></i> ' : '';
        $css = htmlspecialchars($action['cssclass'] ?? 'btn btn-sm btn-secondary');
        $confirm = isset($action['confirm'])
            ? ' onclick="return confirm(\'' . htmlspecialchars($action['confirm'], ENT_QUOTES) . '\');"'
            : '';
        return '<a class="' . $css . '" href="' . htmlspecialchars((string) $url) . '"' . $confirm . '>' . $icon . $label . '</a>';
    }

}
