<?php
/* A tiny, dependency-free router. Supports {param} placeholders. */

class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $pattern, callable $handler): void  { $this->add('GET', $pattern, $handler); }
    public function post(string $pattern, callable $handler): void { $this->add('POST', $pattern, $handler); }

    private function add(string $method, string $pattern, callable $handler): void
    {
        $this->routes[$method][] = ['pattern' => $pattern, 'handler' => $handler];
    }

    public function dispatch(string $method, string $path)
    {
        $path = '/' . trim($path, '/');
        foreach ($this->routes[$method] ?? [] as $route) {
            $regex = $this->toRegex($route['pattern']);
            if (preg_match($regex, $path, $m)) {
                $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                return call_user_func_array($route['handler'], array_values($params));
            }
        }
        http_response_code(404);
        return render('public/error', [
            'code' => 404,
            'title' => 'Page not found',
            'message' => 'The page you are looking for has moved or never existed.',
        ]);
    }

    private function toRegex(string $pattern): string
    {
        $pattern = '/' . trim($pattern, '/');
        $regex = preg_replace('~\{([a-zA-Z_][a-zA-Z0-9_]*)\}~', '(?P<$1>[^/]+)', $pattern);
        return '~^' . $regex . '$~';
    }
}
