<?php
/**
 * Core functions for the application, such as routing and view rendering.
 */

/** @var array $routes The array of registered routes (endpoints) */
$routes = [];

/**
 * Renders a view file
 * @param string $name The name of the view file (without extension)
 */
function view(string $name)
{
    $file = __DIR__.'/views/'.$name.'.view.php';
    if(file_exists($file))
    {
        include $file;
    }
    else
    {
        echo "View not found: '$name'";
    }
}

/** 
 * Register a new route (endpoint) 
 * @param string $method The HTTP method (e.g. 'GET', 'POST')
 * @param string $path The URL path (e.g. '/save/')
 * @param callable $callback The function to call when the route is matched
 */
function addRoute(string $method, string $path, callable $callback)
{
    global $routes;
    $routes[] = 
    [
        'method' => strtoupper($method),
        'path' => $path,
        'callback' => $callback
    ];
}

/** Routes the incoming request to the appropriate route */
function routeRequest()
{
    global $routes;
    $method = strtoupper($_SERVER['REQUEST_METHOD']);
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Loop through registered routes and find a match
    foreach($routes as $route)
    {
        if($route['method'] === $method && $route['path'] === $path)
        {
            call_user_func($route['callback']);
            die;
        }
    }

    // If no route matches, return 404
    http_response_code(404);
    echo "404 - Not Found";
    die;
}

/** Validates the CSRF token. Ends the script if the token is invalid. */
function validateToken()
{
    $token = $_POST['token'] ?? '';
    $sToken = $_SESSION['token'] ?? '';
    if(empty($token) || $token !== $sToken)
    {
        die;
    }
}

/** 
 * Resolves the image type based on raw image data
 * @return string The image type (e.g. 'jpg', 'png', 'bmp', 'gif', 'webp') or an empty string if the type could not be determined
 * @deprecated
 */
function getImageType(string $data)
{
    if(empty($data) || !is_string($data))
    {
        return '';
    }

    $types = [
        'jpg' => "\xFF\xD8\xFF", 
        'png' => "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a", 
        'bmp' => 'BM', 
        'gif' => 'GIF',
        'webp' => 'RIFF',
    ];

    foreach($types as $type => $signature)
    {
        if (strlen($data) > strlen($signature) && substr($data, 0, strlen($signature)) === $signature) 
        {
            return $type;
        }
    }

    return '';
}