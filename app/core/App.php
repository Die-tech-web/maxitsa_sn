<?php

namespace App\Core;


class App
{
    private static array $dependencies = [];

    public static function init()
    {
        self::$dependencies = [
            "core" => [
                "router" => Router::class,
                "database" => Database::getInstance(),
            ],
            "services" => [
                // Vos services ici
            ],
            "repositories" => [
                // Vos repositories ici
            ],
        ];
    }

    public static function getDependency(string $key)
    {
        $keys = explode('.', $key);
        $result = self::$dependencies;
        
        foreach ($keys as $k) {
            if (isset($result[$k])) {
                $result = $result[$k];
            } else {
                return null;
            }
        }
        
        return $result;
    }
}