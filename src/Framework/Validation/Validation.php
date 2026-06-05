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
 *   valid_url            Value must be a syntactically valid URL (http or https).
 *   valid_date           Value must be a valid calendar date in YYYY-MM-DD format.
 *   past_date            Value must be a valid YYYY-MM-DD date strictly before today.
 *   future_date          Value must be a valid YYYY-MM-DD date strictly after today.
 *   required_file        A file must have been uploaded without errors.
 *   extension,ext1;ext2  The uploaded file's extension must be in the semicolon-separated list.
 *
 * FILE UPLOAD RULES
 * -----------------
 * File fields from $_FILES are merged into the input array by the framework
 * before run() is called. Each file value is an array with the standard PHP
 * keys (name, type, tmp_name, error, size). The file rules read from that
 * array directly — do not pass the filename string.
 *
 * Required file — upload is mandatory:
 *
 *   $v->validation_rules([
 *       'avatar' => 'required_file|extension,jpg;jpeg;png;gif',
 *   ]);
 *   $result = $v->run(array_merge($_POST, $_FILES));
 *
 * Optional file — upload may be skipped; if a file is provided its extension
 * is still validated. Omit required_file and use extension alone:
 *
 *   $v->validation_rules([
 *       'avatar' => 'extension,jpg;jpeg;png;gif',
 *   ]);
 *   $result = $v->run(array_merge($_POST, $_FILES));
 *   float                Value must be a valid float number (decimals are allowed).
 *   strong_password      Value must contain at least one uppercase letter, one lowercase
 *                        letter, one digit and one special character (!@#$%^&* etc.).
 *   integer              Value must be an integer (no decimals, no float notation).
 *   integer_between,MIN;MAX  Value must be an integer and fall within [MIN, MAX] inclusive.
 *   min_numeric,N        Value must be numeric and greater than or equal to N.
 *   max_numeric,N        Value must be numeric and lower than or equal to N.
 *
 * FILTER RULES
 * ------------
 * Filters transform values before validation. Multiple filters per field are
 * separated by a pipe character and applied left to right.
 *
 *   trim              Remove leading and trailing whitespace.
 *   sanitize_string   Strip null bytes and HTML tags. Does NOT encode quotes.
 *   sanitize_email    Remove characters that are not valid in an email address.
 *   sanitize_numbers  Remove all characters that are not digits.
 *   sanitize_floats   Remove all characters that are not digits, dot, plus or minus.
 *   rmpunctuation     Remove all punctuation characters from the string.
 *   urlencode         Percent-encode the string for safe use in a URL.
 *   htmlencode        Convert HTML special characters to their HTML entities.
 *   boolean           Convert truthy values (1, 'true', 'yes', 'on') to true, everything else to false.
 *   basic_tags        Strip all HTML tags except a safe subset (b, i, u, p, br, strong, em, a, ul, ol, li, span).
 *   slug              Convert the value to a URL-friendly slug (e.g. "Hello World!" → "hello-world").
 *   lowercase         Convert the value to lower case.
 *   uppercase         Convert the value to upper case.
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

    private function toSlug(string $value): string {
        // Transliterate accented/non-ASCII characters to ASCII equivalents.
        $slug = transliterator_transliterate('Any-Latin; Latin-ASCII', $value) ?? $value;
        $slug = strtolower($slug);
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);  // keep only letters, digits, spaces, hyphens
        $slug = preg_replace('/[\s\-]+/', '-', $slug);        // collapse whitespace and hyphens
        return trim($slug, '-');
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
        if (!is_string($value) && $filter !== 'boolean') {
            return $value;
        }
        return match ($filter) {
            'trim'             => trim($value),
            'sanitize_string'  => preg_replace('/\x00|<[^>]*>?/', '', $value),
            'sanitize_email'   => filter_var($value, FILTER_SANITIZE_EMAIL),
            'sanitize_numbers' => preg_replace('/[^0-9]/', '', $value),
            'sanitize_floats'  => preg_replace('/[^0-9\.\+\-]/', '', $value),
            'rmpunctuation'    => preg_replace('/\p{P}/u', '', $value),
            'urlencode'        => urlencode($value),
            'htmlencode'       => htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'boolean'          => in_array($value, [1, '1', 'true', true, 'yes', 'on'], true),
            'basic_tags'       => strip_tags($value, '<b><i><u><p><br><strong><em><a><ul><ol><li><span>'),
            'slug'             => $this->toSlug($value),
            'lowercase'        => strtolower($value),
            'uppercase'        => strtoupper($value),
            default            => $value,
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
            'valid_url'          => $this->validateUrl($field, $value),
            'valid_date'         => $this->validateDate($field, $value),
            'past_date'          => $this->validatePastDate($field, $value),
            'future_date'        => $this->validateFutureDate($field, $value),
            'required_file'      => $this->validateRequiredFile($field, $value),
            'extension'          => $this->validateExtension($field, $value, $param),
            'float'              => $this->validateFloat($field, $value),
            'strong_password'    => $this->validateStrongPassword($field, $value),
            'integer'            => $this->validateInteger($field, $value),
            'integer_between'    => $this->validateIntegerBetween($field, $value, $param),
            'min_numeric'        => $this->validateMinNumeric($field, $value, $param),
            'max_numeric'        => $this->validateMaxNumeric($field, $value, $param),
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

    private function validateRequiredFile(string $field, mixed $value): ?string {
        if (!is_array($value)
            || !isset($value['error'])
            || $value['error'] !== UPLOAD_ERR_OK
            || empty($value['size'])) {
            return $this->msg('required_file', ['field' => $field]);
        }
        return null;
    }

    private function validateExtension(string $field, mixed $value, ?string $param): ?string {
        // No file uploaded (optional) — skip validation.
        if (!is_array($value) || $value['error'] !== UPLOAD_ERR_OK || $param === null) return null;
        $allowed = array_map('strtolower', explode(';', $param));
        $ext = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));
        return in_array($ext, $allowed, true)
            ? null
            : $this->msg('extension', ['field' => $field, 'extensions' => implode(', ', $allowed)]);
    }

    private function validateFutureDate(string $field, mixed $value): ?string {
        if ($value === null || $value === '') return null;
        $d = \DateTime::createFromFormat('Y-m-d', (string)$value);
        if (!$d || $d->format('Y-m-d') !== (string)$value) {
            return $this->msg('future_date', ['field' => $field]);
        }
        $today = new \DateTime('today');
        return ($d > $today)
            ? null
            : $this->msg('future_date', ['field' => $field]);
    }

    private function validatePastDate(string $field, mixed $value): ?string {
        if ($value === null || $value === '') return null;
        $d = \DateTime::createFromFormat('Y-m-d', (string)$value);
        if (!$d || $d->format('Y-m-d') !== (string)$value) {
            return $this->msg('past_date', ['field' => $field]);
        }
        $today = new \DateTime('today');
        return ($d < $today)
            ? null
            : $this->msg('past_date', ['field' => $field]);
    }

    private function validateDate(string $field, mixed $value): ?string {
        if ($value === null || $value === '') return null;
        $d = \DateTime::createFromFormat('Y-m-d', (string)$value);
        return ($d && $d->format('Y-m-d') === (string)$value)
            ? null
            : $this->msg('valid_date', ['field' => $field]);
    }

    private function validateUrl(string $field, mixed $value): ?string {
        if ($value === null || $value === '') return null;
        return filter_var($value, FILTER_VALIDATE_URL) !== false
            ? null
            : $this->msg('valid_url', ['field' => $field]);
    }

    private function validateStrongPassword(string $field, mixed $value): ?string {
        if ($value === null || $value === '') return null;
        $v = (string)$value;
        if (preg_match('/[A-Z]/', $v)
            && preg_match('/[a-z]/', $v)
            && preg_match('/[0-9]/', $v)
            && preg_match('/[\W_]/', $v)) {
            return null;
        }
        return $this->msg('strong_password', ['field' => $field]);
    }

    private function validateFloat(string $field, mixed $value): ?string {
        if ($value === null || $value === '') return null;
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false
            ? null
            : $this->msg('float', ['field' => $field]);
    }

    private function validateInteger(string $field, mixed $value): ?string {
        if ($value === null || $value === '') return null;
        return ctype_digit(ltrim((string)$value, '-'))
            ? null
            : $this->msg('integer', ['field' => $field]);
    }

    private function validateMinNumeric(string $field, mixed $value, ?string $param): ?string {
        if ($value === null || $value === '' || $param === null) return null;
        if (!is_numeric($value)) return $this->msg('min_numeric', ['field' => $field, 'min' => $param]);
        return ((float)$value >= (float)$param)
            ? null
            : $this->msg('min_numeric', ['field' => $field, 'min' => $param]);
    }

    private function validateMaxNumeric(string $field, mixed $value, ?string $param): ?string {
        if ($value === null || $value === '' || $param === null) return null;
        if (!is_numeric($value)) return $this->msg('max_numeric', ['field' => $field, 'max' => $param]);
        return ((float)$value <= (float)$param)
            ? null
            : $this->msg('max_numeric', ['field' => $field, 'max' => $param]);
    }

    private function validateIntegerBetween(string $field, mixed $value, ?string $param): ?string {
        if ($param === null || $value === null || $value === '') return null;
        [$min, $max] = explode(';', $param, 2);
        if (!ctype_digit(ltrim((string)$value, '-')) || !is_numeric($value)) {
            return $this->msg('integer_between', ['field' => $field, 'min' => $min, 'max' => $max]);
        }
        $int = (int)$value;
        return ($int < (int)$min || $int > (int)$max)
            ? $this->msg('integer_between', ['field' => $field, 'min' => $min, 'max' => $max])
            : null;
    }

    private function validateEmail(string $field, mixed $value): ?string {
        return ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL))
            ? $this->msg('valid_email', ['field' => $field])
            : null;
    }

}
