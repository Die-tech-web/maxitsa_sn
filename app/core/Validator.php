<?php

namespace App\Core;

class Validator
{
    private array $errors = [];

    public function __construct()
    {
        $this->errors = [];
    }

    public function isEmail(string $key, $value, string $message): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($key, $message);
        }
    }

    public function isEmpty(string $key, $value, string $message): void
    {
        if (empty($value)) {
            $this->addError($key, $message);
        }
    }

    public function isNumeric(string $key, $value, string $message): void
    {
        if (!is_numeric($value)) {
            $this->addError($key, $message);
        }
    }

    public function minLength(string $key, $value, int $min, string $message): void
    {
        if (strlen($value) < $min) {
            $this->addError($key, $message);
        }
    }

    public function confirmPassword(string $key, $password, $confirm, string $message): void
    {
        if ($password !== $confirm) {
            $this->addError($key, $message);
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $key, string $message): void
    {
        $this->errors[$key] = $message;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }
}
