<?php
use App\Application;
use Cake\Http\Server;

//check platform requirements
require dirname(__DIR__) . '/config/requirements.php';
require dirname(__DIR__) . '/vendor/autoload.php';

//bind your application to the server
$server = new Server(new Application(dirname(__DIR__) . '/config'));

//run the request/response through the application and emit the response
$server->emit($server->run());