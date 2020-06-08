<?php
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

//default class to use for all routes
Router::defaultRouteClass(DashedRoute::class);

//router to provide an API for our mobile collector app
Router::scope("/", function (RouteBuilder $routes) {
	$routes->setExtensions(["json"]);
	$routes->resources("Samples");
});

Router::scope("/", function (RouteBuilder $routes) {
	//base path "/" directs to login screen
	$routes->connect("/", ["controller" => "Users", "action" => "login"]);
	
	//connect the rest of 'Pages' controller's URLs
	$routes->connect("/pages/*", ["controller" => "Pages", "action" => "display"]);
	
	//general-purpose API routing
	$routes->connect("/API/*", ["controller" => "API", "action" => "routeapi"]);

	//connect catchall routes for all controllers
	$routes->fallbacks(DashedRoute::class);
});