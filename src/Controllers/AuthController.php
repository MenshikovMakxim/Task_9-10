<?php

namespace Me\Task7\Controllers;

class AuthController
{
    public function handle($handler, $vars)
    {
        if ((!empty($_SESSION['login']) && $_SESSION['login'] === '1234')&&(!empty($_SESSION['name']) && $_SESSION['name'] === 'Test')) {
            return call_user_func($handler, $vars);
        } else {
            header('Location: /login'); // Перенаправити на сторінку входу
            exit;
        }
    }
}
