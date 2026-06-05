<?php

namespace Fabiom\UglyDuckling\Framework\Validation;

/**
 * Validation — a lightweight input sanitizer and validator.
 *
 * Replaces the GUMP library with a drop-in compatible interface that fixes
 * the PHP 8.1+ quote-encoding problem: input is stored raw (apostrophes and
 * quotes are never converted to HTML entities) and escaped only at output time.
 *
 * BASIC USAGE
 * -----------
 * Instantiate, declare rules, call run():
 *
 *   $v = new Validation();
 *   $v->validation_rules(['email' => 'required|valid_email', 'name' => 'required|max_len,100']);
 *   $v->filter_rules(['email' => 'trim|sanitize_email', 'name' => 'trim|sanitize_string']);
 *   $result = $v->run($_POST);
 *   if ($result === false) {
 *       echo $v->get_readable_errors();
 *   } else {
 *       // $result is the filtered and validated array
 *   }
 *
 * SANITIZE
 * --------
 * Call sanitize() on raw input before run() to strip null bytes and HTML tags.
 * Quotes and apostrophes are intentionally left untouched:
 *
 *   $clean = $v->sanitize($_POST);
 *   $result = $v->run($clean);
 *
 * VALIDATION RULES
 * ----------------
 * Rules are declared as a field-name => rule-string map. Multiple rules for
 * the same field are separated by a pipe character. Validation stops at the
 * first failing rule for each field.
 *
 *   required             Field must be present and non-empty.
 *   max_len,N            Value length must not exceed N characters.
 *   min_len,N            Value length must be at least N characters.
 *   between_len,MIN;MAX  Value length must be between MIN and MAX characters.
 *   alpha_numeric_dash   Only letters, digits, hyphens and underscores allowed.
 *   alpha_numeric        Only letters and digits allowed.
 *   numeric              Value must be numeric (integer or float).
 *   valid_email          Value must be a syntactically valid email address.
 *
 * FILTER RULES
 * ------------
 * Filters transform values before validation. Multiple filters per field are
 * separated by a pipe character and applied left to right.
 *
 *   trim             Remove leading and trailing whitespace.
 *   sanitize_string  Strip null bytes and HTML tags. Does NOT encode quotes.
 *   sanitize_email   Remove characters that are not valid in an email address.
 *   lowercase        Convert the value to lower case.
 *   uppercase        Convert the value to upper case.
 *
 * MULTI-LANGUAGE
 * --------------
 * Pass a language code to the constructor. A matching file must exist under
 * src/Framework/Validation/lang/<code>.php. Falls back to 'en' if the file
 * is not found. Currently bundled: 'en', 'it'.
 *
 *   $v = new Validation('it');
 *
 * Adding a new language: create lang/fr.php returning an array with the same
 * keys as lang/en.php, with translated message templates. Placeholders
 * available in templates: {field}, {min}, {max}.
 *
 *   // lang/fr.php
 *   return [
 *       'required' => 'Le champ {field} est obligatoire.',
 *       'max_len'  => 'Le champ {field} ne peut pas dépasser {max} caractères.',
 *       ...
 *   ];
 *
 * IN CONTROLLERS
 * --------------
 * Controllers that extend BaseController or Controller already have a
 * $this->gump property pre-wired to a Validation instance. Declare rules as
 * public properties and the framework calls run() automatically:
 *
 *   public $post_validation_rules = ['name' => 'required|max_len,255'];
 *   public $post_filter_rules     = ['name' => 'trim|sanitize_string'];
 *
 *   public function postRequest() {
 *       // $this->postParameters holds the filtered, validated values
 *       $name = $this->postParameters['name'];
 *   }
 */
class Validation {

    private array $validationRules = [];
    private array $filterRules = [];
    private array $errors = [];
    private array $messages = [];

    public function __construct(string $lang = 'en') {
        $this->messages = $this->loadLang($lang);
    }

    /**
     * Sanitize an input array: strips null bytes and HTML tags from every
     * string value. Encoding of quotes and apostrophes is deliberately
     * omitted — escape at output time instead.
     *
     * When $fields is empty every key present in $input is processed.
     * Arrays are sanitized recursively.
     *
     * @param array $input       Raw input, e.g. $_POST or $_GET.
     * @param array $fields      Subset of keys to process; empty = all keys.
     * @param bool  $utf8_encode Convert non-UTF-8 strings to UTF-8 when true.
     * @return array             Sanitized copy of $input.
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

    /**
     * Set the validation rules to apply on the next run() call.
     * Each key is a field name; the value is a pipe-separated rule string.
     *
     *   $v->validation_rules([
     *       'username' => 'required|alpha_numeric_dash|max_len,50',
     *       'email'    => 'required|valid_email',
     *   ]);
     */
    public function validation_rules(array $rules): void {
        $this->validationRules = $rules;
    }

    /**
     * Set the filter rules to apply on the next run() call.
     * Filters run before validation, so validators see the transformed value.
     *
     *   $v->filter_rules([
     *       'username' => 'trim|sanitize_string',
     *       'email'    => 'trim|sanitize_email|lowercase',
     *   ]);
     */
    public function filter_rules(array $rules): void {
        $this->filterRules = $rules;
    }

    /**
     * Apply filters then validate.
     *
     * Returns the filtered array when all validation rules pass, or false when
     * at least one rule fails. Retrieve error messages with errors() or
     * get_readable_errors() after a false return.
     *
     * @param array $input  The input to filter and validate.
     * @return array|false  Filtered values on success, false on failure.
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

        return empty($this->errors) ? $filtered : false;
    }

    /**
     * Return the raw error array from the last run() call.
     * Keys are field names; values are translated error message strings.
     *
     * @return array  Empty array when the last run() succeeded.
     */
    public function errors(): array {
        return $this->errors;
    }

    /**
     * Return all errors from the last run() as a single comma-separated string.
     * The $format parameter is accepted for GUMP compatibility but unused.
     *
     * @param bool $format  Unused; kept for interface compatibility.
     * @return string       Empty string when the last run() succeeded.
     */
    public function get_readable_errors(bool $format = false): string {
        return implode(', ', array_values($this->errors));
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function loadLang(string $lang): array {
        $file = __DIR__ . '/lang/' . $lang . '.php';
        if (!file_exists($file)) {
            $file = __DIR__ . '/lang/en.php';
        }
        return require $file;
    }

    /** Replace {placeholder} tokens in a message template. */
    private function msg(string $rule, array $vars = []): string {
        $template = $this->messages[$rule] ?? "Validation failed for field {field} ({$rule}).";
        foreach ($vars as $key => $val) {
            $template = str_replace('{' . $key . '}', $val, $template);
        }
        return $template;
    }

    private function applyFilters(array $input): array {
        $result = $input;
        foreach ($this->filterRules as $field => $filterString) {
            if (!isset($result[$field])) {
                continue;
            }
            foreach (explode('|', $filterString) as $filter) {
                $result[$field] = $this->applyFilter($result[$field], $filter);
            }
        }
        return $result;
    }

    private function applyFilter(mixed $value, string $filter): mixed {
        return match ($filter) {
            'trim'            => is_string($value) ? trim($value) : $value,
            'sanitize_string' => is_string($value) ? preg_replace('/\x00|<[^>]*>?/', '', $value) : $value,
            'sanitize_email'  => is_string($value) ? filter_var($value, FILTER_SANITIZE_EMAIL) : $value,
            'lowercase'       => is_string($value) ? strtolower($value) : $value,
            'uppercase'       => is_string($value) ? strtoupper($value) : $value,
            default           => $value,
        };
    }

    private function applyValidation(string $field, mixed $value, string $rule): ?string {
        [$ruleName, $param] = str_contains($rule, ',')
            ? explode(',', $rule, 2)
            : [$rule, null];

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
        return ($value === null || $value === '')
            ? $this->msg('required', ['field' => $field])
            : null;
    }

    private function validateMaxLen(string $field, mixed $value, int $max): ?string {
        return ($value !== null && strlen((string)$value) > $max)
            ? $this->msg('max_len', ['field' => $field, 'max' => $max])
            : null;
    }

    private function validateMinLen(string $field, mixed $value, int $min): ?string {
        return ($value !== null && strlen((string)$value) < $min)
            ? $this->msg('min_len', ['field' => $field, 'min' => $min])
            : null;
    }

    private function validateBetweenLen(string $field, mixed $value, ?string $param): ?string {
        if ($param === null) return null;
        [$min, $max] = explode(';', $param, 2);
        $len = strlen((string)$value);
        return ($len < (int)$min || $len > (int)$max)
            ? $this->msg('between_len', ['field' => $field, 'min' => $min, 'max' => $max])
            : null;
    }

    private function validateAlphaNumericDash(string $field, mixed $value): ?string {
        return ($value !== null && $value !== '' && !preg_match('/^[a-zA-Z0-9_\-]+$/', (string)$value))
            ? $this->msg('alpha_numeric_dash', ['field' => $field])
            : null;
    }

    private function validateAlphaNumeric(string $field, mixed $value): ?string {
        return ($value !== null && $value !== '' && !ctype_alnum((string)$value))
            ? $this->msg('alpha_numeric', ['field' => $field])
            : null;
    }

    private function validateNumeric(string $field, mixed $value): ?string {
        return ($value !== null && $value !== '' && !is_numeric($value))
            ? $this->msg('numeric', ['field' => $field])
            : null;
    }

    private function validateEmail(string $field, mixed $value): ?string {
        return ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL))
            ? $this->msg('valid_email', ['field' => $field])
            : null;
    }

}
