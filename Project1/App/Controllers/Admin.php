<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Post;

class Admin extends \Core\Controller {

    protected function before() {
    }

    protected function after() {
    }

    public function indexAction() {
        $posts = Post::getAll();

        View::renderTemplate('Admin/index.html', [
            'name' => 'Jared',
            'colors' => ['red', 'green', 'blue'],
            'posts' => $posts
        ]);
    }

}

?>