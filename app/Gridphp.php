<?php

namespace App;

use InvalidArgumentException;

// Set database configuration with fallbacks
define("PHPGRID_DBTYPE", "mysqli");
$dbPort = env("DB_PORT", 3306); // Adjusted to use 'DB_PORT' env variable with a fallback
$dbHost = env("DB_HOST", "127.0.0.1");
$dbUser = env("DB_USERNAME", "root");
$dbPass = env("DB_PASSWORD", "");
$dbName = env("DB_DATABASE", "kennedy");

if (empty($dbHost) || empty($dbUser) || empty($dbName)) {
    throw new InvalidArgumentException('Database configuration values cannot be empty.');
}

define("PHPGRID_DBHOST", $dbHost);
define("PHPGRID_DBUSER", $dbUser);
define("PHPGRID_DBPASS", $dbPass);
define("PHPGRID_DBNAME", $dbName);

// Define path for PHPGrid library
define("PHPGRID_LIBPATH", base_path("app/Classes/Gridphp/"));

class Gridphp
{
    public static function get()
    {
        // Ensure the library file exists before including
        if (!file_exists(PHPGRID_LIBPATH . "jqgrid_dist.php")) {
            throw new \Exception("PHPGrid library file not found at " . PHPGRID_LIBPATH . "jqgrid_dist.php");
        }

        require_once PHPGRID_LIBPATH . "jqgrid_dist.php";

        // Initialize and return jqgrid object
        return new \jqgrid();
    }
}
