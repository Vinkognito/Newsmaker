<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    /*
     * Метод возвращает строку запроса
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
// Получить строку запроса

        $uri = $this->getURI();
//Проверить налчие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path) {

            // Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {

                /*echo '<br> где ищем(запрос который набрал пользователь): '.$uri;
                echo '<br> Что ищем(совпадение из правила): '.$uriPattern;
                echo '<br> Кто обрабатывает: '.$path;*/
                //Получаем внутренний путь из внешнего соласно правилу
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                // Определить контроллер, экшн и параметры

                $segments = explode('/', $internalRoute);
                unset($segments[0]);// убрать первый элемент из массива
                $controllerName = array_shift($segments). 'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action' . ucfirst(array_shift($segments));

                $parameters = $segments;

                // Подключить файл класса-контроллера
                $controllerFile = ROOT . '/controllers/' .
                    $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                 //Создать обьект вызвать метод(экшн)
                $controllerObject = new $controllerName;

                $result = call_user_func_array(array($controllerObject,$actionName), $parameters);

                if($result != null){
                    break;

            }


            }
        }
    }

}



//