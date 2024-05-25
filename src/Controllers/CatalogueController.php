<?php

namespace Me\Task7\Controllers;

use Me\Task7\Viewer;
class CatalogueController
{
    public function index(): void
    {
        $page = 'catalogue';
        $title = 'Catalogue';
        $content = 'Каталог';
        $info = "У комп'ютерній термінології катало́г, директо́рія (англ. directory) чи те́ка, па́пка (англ. folder) — 
        елемент файлової системи, призначений для організації ієрархії файлової системи обчислювального пристрою шляхом
         групування файлів та інших каталогів.";

        $view = new Viewer(
            [
                'page' => $page,
                'title' => $title,
                'content' => $content,
                'info' => $info,
            ]
        );

        $view->render();
    }
}