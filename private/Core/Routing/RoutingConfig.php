<?php


namespace GameOfThronesMonopoly\Core\Routing;


use GameOfThronesMonopoly\Core\Routing\Repositories\RoutingRepository;
use Exception;

/**
 * Class RoutingConfig
 * gets all Routes saved in route
 * this routes can be executed when ::run() is called
 */
class RoutingConfig
{

    /** add all routes found in database
     * if not defined set method to default value
     * build function, that will be called, when URL is used
     * call function Route::add()
     * @throws Exception
     */
    public static function addRoutes()
    {

        $routeConfig = RoutingRepository::getAllRoutes(); // get all saved routes from database
        // do for each routeConfig
        foreach ($routeConfig as $config) {
            // if method is not defined #} default value 'get'
            if (!isset($config["method"])) {
                $config["method"] = 'get';
            }

            // function will be called in run() (Route.php)
            $function = function ($config, $matches) {
                $functionName = $config["action"]; // name of action in controller
                $controller = new $config["controller"]; // namespace of controller
                if (empty($matches)) { // no variables for function
                    $controller->$functionName();
                } else { // individual variables for function
                    call_user_func_array(array($controller, $functionName), $matches);
                }
            };

            Route::add($config["url"], $function, $config, $config["method"]); // add route
        }
    }
}