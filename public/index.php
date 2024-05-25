<?php
session_start();
    require __DIR__ . '/../vendor/autoload.php';
    $db = new Me\Task7\Database();

    $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

        $homeController = new Me\Task7\Controllers\HomeController();
        $aboutController = new Me\Task7\Controllers\AboutController();
        $contactsController = new Me\Task7\Controllers\ContactsController();
        $catalogueController = new Me\Task7\Controllers\CatalogueController();
        $loginController = new Me\Task7\Controllers\LoginController();
        $authController = new Me\Task7\Controllers\AuthController();
// Define your routes here
        $r->addRoute('GET', '/login', [$loginController, 'index']);
        $r->addRoute('POST', '/login', [$loginController, 'auth']);
        $r->addRoute('POST', '/home', [$homeController, 'handleForm']);
        $r->addRoute('GET', '/home/delete', [$homeController, 'handleFormDelete']);
        $r->addRoute('GET', '/logout', function ($vars) {
            session_destroy();
            header('Location: /login');
        });

        $r->addRoute('GET', '/contacts', function ($vars) use ($authController, $contactsController) {
            return $authController->handle([$contactsController, 'index'], $vars);
        });
        $r->addRoute('GET', '/catalogue', function ($vars) use ($authController, $catalogueController) {
            return $authController->handle([$catalogueController, 'index'], $vars);
        });
        $r->addRoute('GET', '/about', function ($vars) use ($authController, $aboutController) {
            return $authController->handle([$aboutController, 'index'], $vars);
        });
        $r->addRoute('GET', '/home', function ($vars) use ($authController, $homeController) {
            return $authController->handle([$homeController, 'index'], $vars);
        });
    });

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            // ... 404 Not Found
            header('Location: /home');
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            // ... 405 Method Not Allowed
            header('Location: /home');
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            if (is_callable($handler)) {
                call_user_func($handler, $vars);
            } else {
                $handler->handle($handler, $vars);
            }
            break;
    }
