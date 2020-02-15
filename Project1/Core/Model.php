<?php

namespace Core;

use PDO;
use App\Config;

abstract class Model {

    protected static function getDB() {
        static $db = null;

        if ($db === null) {
            $db = new PDO(Config::DB_URL, Config::DB_USER, Config::DB_PASSWORD);

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }

}

?>