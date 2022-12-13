<?php

use GameOfThronesMonopoly\Core\Routing\Route;
use GameOfThronesMonopoly\Core\Routing\RoutingConfig;

include('../private/Core/Routing/Route.php');

require_once '../vendor/autoload.php';

RoutingConfig::addRoutes();

Route::run('/GameOfThronesMonopoly/');

