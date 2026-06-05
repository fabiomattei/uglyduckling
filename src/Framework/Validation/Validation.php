<?php

namespace Fabiom\UglyDuckling\Framework\Validation;

class Validation {

    private array $validationRules = [];
    private array $filterRules = [];
    private array $errors = [];

    public function __construct(string $lang = 'en') {}

    /**
     * Sanitize input: strips null bytes and HTML tags.
     * Does NOT encode quotes — store raw data, escape at output time.
     */
    public function sanitize(array $input, array $fields = [], bool $utf8_encode = true): array {
        if (empty($fields)) {
            $fields = array_keys($input);
        }

        $result = [];
        foreach ($fields as $field) {
            if (!isset($input[$field])) {
                continue;
            }
            $value = $input[$field];
            if (is_array($value)) {
                $value = $this->sanitize($value, [], $utf8_encode);
            } elseif (is_string($value)) {
                if ($utf8_encode && function_exists('iconv') && function_exists('mb_detect_encoding')) {
                    $encoding = mb_detect_encoding($value);
                    if ($encoding !== false && $encoding !== 'UTF-8' && $encoding !== 'UTF-16') {
                        $value = iconv($encoding, 'UTF-8', $value);
                    }
                }
                $value = preg_replace('/\x00|<[^>]*>?/', '', $value);
            }
            $result[$field] = $value;
        }
        return $result;
    }

    public function validation_rules(array $rules): void {
        $this->validationRules = $rules;
    }

    public function filter_rules(array $rules): void {
        $this->filterRules = $rules;
    }

    /**
     * Apply filters then validate. Returns filtered array on success, false on failure.
     */
    public function run(array $input): array|false {
        $this->errors = [];

        $filtered = $this->applyFilters($input);

        foreach ($this->validationRules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $filtered[$field] ?? null;
            foreach ($rules as $rule) {
                $error = $this->applyValidation($field, $value, $rule);
                if ($error !== null) {
                    $this->errors[$field] = $error;
                    break;
                }
            }
        }

        if (!empty($this->errors)) {
            return false;
        }
        return $filtered;
    }

    public function errors(): array {
        return $this->errors;
    }

    public function get_readable_errors(bool $format = false): string {
        if (empty($this->errors)) {
            return '';
        }
        $messages = array_values($this->errors);
        return implode(', ', $messages);
    }

    private function applyFilters(array $input): array {
        $result = $input;
        foreach ($this->filterRules as $field => $filterString) {
            if (!isset($result[$field])) {
                continue;
            }
            $filters = explode('|', $filterString);
            foreach ($filters as $filter) {
                $result[$field] = $this->applyFilter($result[$field], $filter);
            }
        }
        return $result;
    }

    private function applyFilter(mixed $value, string $filter): mixed {
        return match ($filter) {
            'trim'             => is_string($value) ? trim($value) : $value,
            'sanitize_string'  => is_string($value) ? preg_replace('/\x00|<[^>]*>?/', '', $value) : $value,
            'sanitize_email'   => is_string($value) ? filter_var($value, FILTER_SANITIZE_EMAIL) : $value,
            'lowercase'        => is_string($value) ? strtolower($value) : $value,
            'uppercase'        => is_string($value) ? strtoupper($value) : $value,
            default            => $value,
        };
    }

    private function applyValidation(string $field, mixed $value, string $rule): ?string {
        if (str_contains($rule, ',')) {
            [$ruleName, $param] = explode(',', $rule, 2);
        } else {
            $ruleName = $rule;
            $param = null;
        }

        return match ($ruleName) {
            'required'           => $this->validateRequired($field, $value),
            'max_len'            => $this->validateMaxLen($field, $value, (int)$param),
            'min_len'            => $this->validateMinLen($field, $value, (int)$param),
            'between_len'        => $this->validateBetweenLen($field, $value, $param),
            'alpha_numeric_dash' => $this->validateAlphaNumericDash($field, $value),
            'alpha_numeric'      => $this->validateAlphaNumeric($field, $value),
            'numeric'            => $this->validateNumeric($field, $value),
            'valid_email'        => $this->validateEmail($field, $value),
            default              => null,
        };
    }

    private function validateRequired(string $field, mixed $value): ?string {
        if ($value === null || $value === '') {
            return "The field {$field} is required.";
        }
        return null;
    }

    private function validateMaxLen(string $field, mixed $value, int $max): ?string {
        if ($value !== null && strlen((string)$value) > $max) {
            return "The field {$field} must be at most {$max} characters.";
        }
        return null;
    }

    private function validateMinLen(string $field, mixed $value, int $min): ?string {
        if ($value !== null && strlen((string)$value) < $min) {
            return "The field {$field} must be at least {$min} characters.";
        }
        return null;
    }

    private function validateBetweenLen(string $field, mixed $value, ?string $param): ?string {
        if ($param === null) return null;
        [$min, $max] = explode(';', $param, 2);
        $len = strlen((string)$value);
        if ($len < (int)$min || $len > (int)$max) {
            return "The field {$field} must be between {$min} and {$max} characters.";
        }
        return null;
    }

    private function validateAlphaNumericDash(string $field, mixed $value): ?string {
        if ($value !== null && $value !== '' && !preg_match('/^[a-zA-Z0-9_\-]+$/', (string)$value)) {
            return "The field {$field} may only contain letters, numbers, dashes and underscores.";
        }
        return null;
    }

    private function validateAlphaNumeric(string $field, mixed $value): ?string {
        if ($value !== null && $value !== '' && !ctype_alnum((string)$value)) {
            return "The field {$field} may only contain letters and numbers.";
        }
        return null;
    }

    private function validateNumeric(string $field, mixed $value): ?string {
        if ($value !== null && $value !== '' && !is_numeric($value)) {
            return "The field {$field} must be numeric.";
        }
        return null;
    }

    private function validateEmail(string $field, mixed $value): ?string {
        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "The field {$field} must be a valid email address.";
        }
        return null;
    }

}
