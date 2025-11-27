<?php
/**
 * Router - Manejo de rutas
 */

class Router {
    private $routes = [];

    public function addRoute($path, $controller, $action) {
        $this->routes[$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($url) {
        // Limpiar la URL
        $url = rtrim($url, '/');
        if (empty($url)) {
            $url = '';
        }

        // Buscar la ruta
        if (isset($this->routes[$url])) {
            $route = $this->routes[$url];
            $controllerName = $route['controller'];
            $action = $route['action'];

            // Verificar si el controlador existe
            $controllerFile = BASE_PATH . 'controllers/' . $controllerName . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    
                    if (method_exists($controller, $action)) {
                        $controller->$action();
                    } else {
                        $this->notFound("Método {$action} no encontrado en {$controllerName}");
                    }
                } else {
                    $this->notFound("Clase {$controllerName} no encontrada");
                }
            } else {
                $this->notFound("Controlador {$controllerName} no encontrado");
            }
        } else {
            $this->notFound("Ruta no encontrada: {$url}");
        }
    }

    private function notFound($message = '') {
        http_response_code(404);
        require_once BASE_PATH . 'views/errors/404.php';
        exit;
    }
}

