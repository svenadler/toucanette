<?php declare(strict_types=1);

use Sadl\Framework\Http\Kernel;
use Sadl\Framework\Routing\Router;
use Sadl\Framework\Http\Request;

define("BASE_PATH", dirname(__DIR__));

require_once dirname(__DIR__) . '/vendor/autoload.php';

$request = Request::createFromGlobals();

$router = new Router();

$kernel = new Kernel($router);

$response = $kernel->handle($request);

$response->send();