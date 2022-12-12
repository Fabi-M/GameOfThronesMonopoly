<?php /** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpArrayPushWithOneElementInspection */

/** @noinspection PhpInconsistentReturnPointsInspection */


namespace GameOfThronesMonopoly\Core\Routing;


use ErrorException;
use mysql_xdevapi\Exception;
use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Core\Routing\Exceptions\RouteException;
use GameOfThronesMonopoly\Core\Strings\ExceptionString;

/**
 * Class Route
 * Runs a Route, checks permissions, throws Exceptions if permissions are not given
 */
class Route
{
    /** @var array $routes // all added routes will be saved in this variable */
    private static $routes = array();
    private static $methodNotAllowed = null;

    /**
     * Function used to add a new route
     * @param string $expression Route string or expression
     * @param callable $function Function to call if route with allowed method is found
     * @param $config
     * @param string|array $method Either a string of allowed method or an array with string values
     */
    public static function add(string $expression, callable $function, $config, string $method = 'get')
    {
        array_push(self::$routes, array(
            'expression' => $expression,
            'function' => $function,
            'config' => $config,
            'method' => $method
        ));
    }


    /**
     * tries to find the target route and executes its action
     * might throw an exception if the needed permissions are not given by the user
     * @param string $basepath
     * @param false $case_matters
     * @param false $trailing_slash_matters
     * @param false $multimatch
     * @return mixed|void
     * @throws RouteException
     */
    public static function run(string $basepath = '', bool $case_matters = false, bool $trailing_slash_matters = false, bool $multimatch = false)
    {

        // The basepath never needs a trailing slash
        // Because the trailing slash will be added using the route expressions
        $basepath = rtrim($basepath, '/');


        // Parse current URL
        $parsed_url = parse_url($_SERVER['REQUEST_URI']); // includes url + QUERY
        $path = '/';

        // If there is a path available
        if (isset($parsed_url['path'])) {
            // If the trailing slash matters
            if ($trailing_slash_matters) {
                $path = $parsed_url['path'];
            } else {
                // If the path is not equal to the base path (including a trailing slash)
                if ($basepath . '/' != $parsed_url['path']) {
                    // Cut the trailing slash away because it does not matter
                    $path = rtrim($parsed_url['path'], '/');
                } else {
                    $path = $parsed_url['path'];
                }
            }
        }

        $path = urldecode($path);

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

        $path_match_found = false;

        $route_match_found = false;

        foreach (self::$routes as $route) {

            // If the method matches check the path

            // Add basepath to matching string
            if ($basepath != '' && $basepath != '/') {
                $route['expression'] = '(' . $basepath . ')' . $route['expression'];
            }

            // Add 'find string start' automatically
            $route['expression'] = '^' . $route['expression'];

            // Add 'find string end' automatically
            $route['expression'] = $route['expression'] . '$';

            // Check path match
            $pattern = '#' . $route['expression'] . '#' . ($case_matters ? '' : 'i') . 'u'; // pattern
            if (preg_match($pattern, $path, $matches)) {
                if (isset($parsed_url['query'])) {
                    $matches[] = $parsed_url['query'];
                }
                $path_match_found = true;
                // Cast allowed method to array if it's not one already, then run through all methods
                foreach ((array)$route['method'] as $allowedMethod) {

                    // Check method match
                    if (strtolower($method) == strtolower($allowedMethod)) {

                        array_shift($matches); // Always remove first element. This contains the whole string

                        if ($basepath != '' && $basepath != '/') {
                            array_shift($matches); // Remove basepath
                        }

                        $variables = array($route['config'], $matches);

                        if (!self::userHasPermission($variables[0]['controller'], (int)$variables[0]['permission'])) {
                            throw new RouteException(ExceptionString::PERMISSION_EXCEPTION);
                        }

                        /** call function! */
                        if ($return_value = call_user_func_array($route['function'], $variables)) {
                            return $return_value;
                        }

                        $route_match_found = true;

                        // Do not check other routes
                        break;
                    }
                }
            }

            // Break the loop if the first found route is a match
            if ($route_match_found && !$multimatch) {
                break;
            }

        }

        // echo "NO MATCH FOUND FOR ROUTE: " . $_SERVER['REQUEST_URI'];
        // No matching route was found
        if (!$route_match_found) {
            // But a matching path exists
            if ($path_match_found) {
                if (self::$methodNotAllowed) {
                    // when requested one couldn't be found
                    call_user_func_array(self::$methodNotAllowed, array($path, $method));
                }
            }
        }
    }

    /**
     * Check if the user is allowed to use this route
     * @param string $controllerNamespace
     * @param int $permission
     * @return bool
     */
    private static function userHasPermission(string $controllerNamespace, int $permission): bool
    {
        /** @var BaseController $class */
        $class = new $controllerNamespace();
        if ($permission != 0 && !$class->checkPermission($permission)) {
            return false;
        } else {
            return true;
        }
    }

}