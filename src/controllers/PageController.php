<?php
require_once __DIR__ . '/BaseController.php';

class PageController extends BaseController {
    public function about() {
        $this->view('pages/about', ['title' => 'À Propos']);
    }

    public function contact() {
        $this->view('pages/contact', ['title' => 'Contact']);
    }

    public function faq() {
        $this->view('pages/faq', ['title' => 'FAQ']);
    }

    public function shipping() {
        $this->view('pages/shipping', ['title' => 'Livraison']);
    }

    public function returns() {
        $this->view('pages/returns', ['title' => 'Retours']);
    }

    public function terms() {
        $this->view('pages/terms', ['title' => 'CGV']);
    }

    public function legal() {
        $this->view('pages/legal', ['title' => 'Mentions Légales']);
    }

    public function privacy() {
        $this->view('pages/privacy', ['title' => 'Confidentialité']);
    }
}


