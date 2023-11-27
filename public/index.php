<?php declare(strict_types=1);

use Sadl\Framework\Http\Kernel;
use Sadl\Framework\Http\Request;

define("BASE_PATH", dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$container = require BASE_PATH . '/config/services.php';

// bootstrapping e.g. EventServiceProvider
require BASE_PATH . '/bootstrap/bootstrap.php';
bootstrap($container);

// request received
$request = Request::createFromGlobals();

$kernel = $container->get(Kernel::class);

// send response (string of content)
$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);