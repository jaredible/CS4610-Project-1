<?php

namespace App\Controllers;

use \Core\View;

class Portal extends \Core\Controller {

    protected function before() {
    }

    protected function after() {
    }

    public function indexAction() {
        View::renderTemplate('Portal/index.html');
    }

}

?>