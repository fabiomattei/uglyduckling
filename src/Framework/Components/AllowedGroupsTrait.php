<?php

namespace Fabiom\UglyDuckling\Framework\Components;

trait AllowedGroupsTrait {

    // Mirrors a JSON resource's "allowedgroups": empty means accessible to every logged-in group.
    protected array $allowedGroups = [];

    protected function isGroupAllowed(): bool {
        if (empty($this->allowedGroups)) {
            return true;
        }
        return in_array($_SESSION['group'] ?? null, $this->allowedGroups, true);
    }

}
