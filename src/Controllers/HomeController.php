<?php

namespace Me\Task7\Controllers;

use Me\Task7\Viewer;
use PDO;
use Me\Task7\Database;


class HomeController
{
    public function index(): void
    {
        $page = 'home';
        $title = 'Home';
        $content = 'Головна';

        $query = "SELECT * FROM ".Database::$table." ORDER BY quantity";
        $stmt = Database::executeQuery($query);

        $data = [

        ];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        $view = new Viewer([
            'page' => $page,
            'title' => $title,
            'content' => $content,
            'data' => $data
        ]);

        $view->render();
    }
    public function handleForm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /home');
            return;
        }

        $name = $this->filterPost('name');
        $quantity = $this->filterPost('quantity');

        if ($name === null || $quantity === null) {
            header('Location: /');
            return;
        }

        $query = "INSERT INTO ".Database::$table." (name, quantity) VALUES (:name, :quantity)";
        Database::executeQuery($query, ['name' => $name, 'quantity' => $quantity]);

        header('Location: /');
    }

    public function handleFormDelete(): void
    {
        $id = $_GET['id'] ?? null;

        $query = "DELETE FROM ".Database::$table." WHERE id = :id";
        Database::executeQuery($query, ['id' => $id]);

        header('Location: /');
    }

    private function filterPost(string $key): ?string
    {
        return isset($_POST[$key]) && is_string($_POST[$key]) ? htmlspecialchars($_POST[$key]) : null;
    }
}