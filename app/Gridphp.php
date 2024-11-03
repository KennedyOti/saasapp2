<?php

namespace App;

use InvalidArgumentException;

// Set database configuration with fallbacks
define("PHPGRID_DBTYPE", "mysqli");
$dbPort = env("3306");
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

define("PHPGRID_LIBPATH", base_path("app/Classes/Gridphp/"));

class Gridphp
{
    public static function get()
    {
        require_once PHPGRID_LIBPATH . "jqgrid_dist.php";
        return new \jqgrid();
    }
}
