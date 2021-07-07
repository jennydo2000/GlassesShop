<?php

use phpDocumentor\Reflection\Location;

include_once 'Controller.php';
include_once __DIR__ . '/../models/User.php';

class LoginController extends Controller {
    public function index() {
        if ($this->authorize())
            header('location: index.php?action=glass&method=index');
        else
            $this->view('login');
    }

    public function login($request) {
        $post = $request['POST'];

        $username = $post['username'];
        $password = $post['password'];

        $user = new User();
        $token = $user->login($username, $password);
        if ($token != null) {
            $_SESSION['user'] = [
                'username' => $username,
                'token' => $token,
            ];
            return true;
        }
        return false;
    }

    public function logout() {
        unset($_SESSION['user']);
    }
}