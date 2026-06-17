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
                if ($value) {
                    $parts = explode(',', $param);
                    $table = $parts[0] ?? null;
                    $column = $parts[1] ?? $field;
                    if ($this->isNotUnique($table, $column, $value)) {
                        $this->addError($field, "Это значение уже используется");
                    }
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
        try {
            // Валидируем имена таблицы и поля (должны быть алфавитно-цифровыми с подчеркиванием)
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)) {
                return false; // Некорректное имя таблицы
            }
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $field)) {
                return false; // Некорректное имя поля
            }
            
            $db = Database::getInstance();
            $result = $db->fetchOne(
                "SELECT id FROM `$table` WHERE `$field` = ? LIMIT 1",
                [$value]
            );
            return !empty($result);
        } catch (Exception $e) {
            // Если возникла ошибка при проверке (например, таблица не существует),
            // логируем и считаем значение уникальным (разрешаем регистрацию)
            Logger::warning('Validation check failed: ' . $e->getMessage());
            return false;
        }
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
