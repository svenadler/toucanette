<?php declare(strict_types=1);

use Sadl\Framework\Http\Kernel;
use Sadl\Framework\Routing\Router;
use Sadl\Framework\Http\Request;

define("BASE_PATH", dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$container = require BASE_PATH . '/config/services.php';

$request = Request::createFromGlobals();

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();