<?php

namespace App\Models;

use PDO;

class Post extends \Core\Model {

    public static function getAll() {
        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT course_number, course_name, credit_hours FROM course ORDER BY department');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}

?>