<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/CSRF.php';

class AuthController extends BaseController {
    public function showLogin() {
        if (Auth::check()) {
            // Redirect admins to admin dashboard, regular users to account
            if (Auth::isAdmin()) {
                $this->redirect(route('admin.dashboard'));
            } else {
                $this->redirect(route('account.index'));
            }
            return;
        }
        
        $this->view('auth/login', [
            'title' => 'Connexion',
        ]);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validatePost()) {
            setFlash('error', 'Requête invalide');
            $this->redirect(route('auth.login'));
            return;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (Auth::login($email, $password)) {
            // Redirect admins to admin dashboard, regular users to account (or custom redirect)
            if (Auth::isAdmin()) {
                $redirect = $_GET['redirect'] ?? route('admin.dashboard');
            } else {
                $redirect = $_GET['redirect'] ?? route('account.index');
            }
            $this->redirect($redirect);
        } else {
            setFlash('error', 'Email ou mot de passe incorrect');
            $this->redirect(route('auth.login'));
        }
    }

    public function showRegister() {
        if (Auth::check()) {
            // Redirect admins to admin dashboard, regular users to account
            if (Auth::isAdmin()) {
                $this->redirect(route('admin.dashboard'));
            } else {
                $this->redirect(route('account.index'));
            }
            return;
        }
        
        $this->view('auth/register', [
            'title' => 'Inscription',
        ]);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validatePost()) {
            setFlash('error', 'Requête invalide');
            $this->redirect(route('auth.register'));
            return;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $name = $_POST['name'] ?? null;
        
        if (empty($email) || empty($password)) {
            setFlash('error', 'Email et mot de passe requis');
            $this->redirect(route('auth.register'));
            return;
        }
        
        $result = Auth::register($email, $password, $name);
        
        if ($result['success']) {
            // Redirect admins to admin dashboard, regular users to account
            if (Auth::isAdmin()) {
                $this->redirect(route('admin.dashboard'));
            } else {
                $this->redirect(route('account.index'));
            }
        } else {
            setFlash('error', $result['error'] ?? 'Erreur lors de l\'inscription');
            $this->redirect(route('auth.register'));
        }
    }

    public function logout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && CSRF::validatePost()) {
            Auth::logout();
        }
        $this->redirect(route('home'));
    }
}


