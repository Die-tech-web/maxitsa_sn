<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
// require_once __DIR__ . '/../app/config/env.php';
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../routes/route.web.php';









