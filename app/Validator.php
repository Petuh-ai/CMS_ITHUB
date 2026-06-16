<?php
/**
 * Класс для валидации
 */

class Validator
{
    private $errors = [];
    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate($rules)
    {
        foreach ($rules as $field => $fieldRules) {
            $rules = explode('|', $fieldRules);
            foreach ($rules as $rule) {
                $this->validateField($field, $rule);
            }
        }
        return empty($this->errors);
    }

    private function validateField($field, $rule)
    {
        $value = $this->data[$field] ?? null;
        
        if (strpos($rule, ':') !== false) {
            [$ruleName, $param] = explode(':', $rule);
        } else {
            $ruleName = $rule;
            $param = null;
        }

        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, "Поле обязательно");
                }
                break;

            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Некорректный email");
                }
                break;

            case 'min':
                if ($value && strlen($value) < (int)$param) {
                    $this->addError($field, "Минимум $param символов");
                }
                break;

            case 'max':
                if ($value && strlen($value) > (int)$param) {
                    $this->addError($field, "Максимум $param символов");
                }
                break;

            case 'unique':
                if ($value && $this->isNotUnique($param, $field, $value)) {
                    $this->addError($field, "Это значение уже используется");
                }
                break;

            case 'confirmed':
                if ($value !== ($this->data[$field . '_confirmation'] ?? null)) {
                    $this->addError($field, "Значения не совпадают");
                }
                break;
        }
    }

    private function isNotUnique($table, $field, $value)
    {
        $db = Database::getInstance();
        $result = $db->fetchOne(
            "SELECT id FROM $table WHERE $field = ? LIMIT 1",
            [$value]
        );
        return !empty($result);
    }

    private function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
