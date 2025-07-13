<?php

 namespace App\Core;

 class Validator
{
    private array $errors = [];

    public function __construct()
    {
        $this->errors = [];
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

        /**
         * Méthode validator manquante - Validation générique par type
         */
        public function validator($value, string $type): void
        {
            switch ($type) {
                case 'telephone':
                    if (empty($value)) {
                        $this->addError('telephone', 'Le téléphone est obligatoire.');
                    } elseif (!preg_match('/^7[0-8]\d{7}$/', $value)) {
                        $this->addError('telephone', 'Le téléphone doit commencer par 77, 78, 70, 76, 75 et contenir 9 chiffres.');
                    }
                    break;

                case 'password':
                    if (empty($value)) {
                        $this->addError('password', 'Le mot de passe est obligatoire.');
                    } elseif (strlen($value) < 6) {
                        $this->addError('password', 'Le mot de passe doit contenir au moins 6 caractères.');
                    }
                    break;

                case 'nom':
                    if (empty($value)) {
                        $this->addError('nom', 'Le nom est obligatoire.');
                    } elseif (strlen($value) < 2) {
                        $this->addError('nom', 'Le nom doit contenir au moins 2 caractères.');
                    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $value)) {
                        $this->addError('nom', 'Le nom ne peut contenir que des lettres.');
                    }
                    break;

                case 'prenom':
                    if (empty($value)) {
                        $this->addError('prenom', 'Le prénom est obligatoire.');
                    } elseif (strlen($value) < 2) {
                        $this->addError('prenom', 'Le prénom doit contenir au moins 2 caractères.');
                    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $value)) {
                        $this->addError('prenom', 'Le prénom ne peut contenir que des lettres.');
                    }
                    break;

                case 'cni':
                    if (empty($value)) {
                        $this->addError('cni', 'Le CNI est obligatoire.');
                    } elseif (!preg_match('/^[12]\d{12}$/', $value)) {
                        $this->addError('cni', 'Le CNI doit commencer par 1 ou 2 et contenir exactement 13 chiffres.');
                    }
                    break;

                case 'adresse':
                    if (empty($value)) {
                        $this->addError('adresse', 'L\'adresse est obligatoire.');
                    } elseif (strlen($value) < 10) {
                        $this->addError('adresse', 'L\'adresse doit contenir au moins 10 caractères.');
                    }
                    break;
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

        public function hasErrors(): bool
        {
            return !empty($this->errors);
        }

        private function getDefaultMessage(string $key): string
        {
            $messages = [
                'telephone' => 'Le téléphone est requis.',
                'password' => 'Le mot de passe est requis.',
                'nom' => 'Le nom est requis.',
                'prenom' => 'Le prénom est requis.',
                'cni_senegal' => "Le numéro CNI doit contenir exactement 13 chiffres.",

                'adresse' => 'L\'adresse est requise.'
            ];

            return $messages[$key] ?? 'Erreur de validation.';
        }
}





