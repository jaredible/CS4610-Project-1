<?php

namespace App\Controllers;

use \Core\View;

class Admin extends \Core\Controller {

    protected function before() {
    }

    protected function after() {
    }

    public function indexAction() {
        $posts = Post::getAll();

        View::renderTemplate('Admin/index.html');
    }

}

?>