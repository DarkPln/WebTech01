<?php
// Lukas, Tim, Niclas 

class Router {
    private array $routes = [];
    // array mit 3 werten: method, pattern, handler

    public function get(string $pattern, array $handler): void {
        $this->routes[] = ['GET', $pattern, $handler];
    }

    public function post(string $pattern, array $handler): void {
        $this->routes[] = ['POST', $pattern, $handler];
    }

    // matched uri mit pattern; damit man nicht für jedes auto eine eigene route registrieren muss  
    private function match(string $pattern, string $uri): array|false {
        $patternSegments = explode('/', trim($pattern, '/'));
        $uriSegments     = explode('/', trim($uri, '/'));

        if (count($patternSegments) !== count($uriSegments)) {
            return false;
        }

        $params = [];
        foreach ($patternSegments as $i => $segment) {
            if (str_starts_with($segment, '{') && str_ends_with($segment, '}')) {
                $params[trim($segment, '{}')] = $uriSegments[$i];
            } elseif ($segment !== $uriSegments[$i]) {
                return false;
            }
        }

        return $params;
    }


// Pfad auflösen 

    public function dispatch(): void {
      
    
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// abschneiden damit /WebTech01/cars zb zu /cars wird für matching

        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($basePath !== '' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . ltrim($uri, '/');

// Lukas, Tim 
// registrierte Routen werden durchgegangen ob Methode passt ob Pattern passt; wenn beides passt -> controller erstellen und meth aurufen

        foreach ($this->routes as [$routeMethod, $pattern, $handler]) {
            if ($routeMethod !== $method) continue;

            $params = $this->match($pattern, $uri);
            if ($params === false) continue;

            [$controllerClass, $methodName] = $handler;
            $controller = new $controllerClass();
            $controller->$methodName(...array_values($params));
            return;
        }

        http_response_code(404);
        $viewPath = BASE_PATH . 'app/views/errors/404.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo '404 - Seite nicht gefunden';
        }
    }
}
