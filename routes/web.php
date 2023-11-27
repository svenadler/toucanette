<?php declare(strict_types=1);

use App\Controller\HomeController;
use App\Controller\PostsController;
use Sadl\Framework\Http\Response;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/posts/{id:\d+}', [PostsController::class, 'show']],
    ['GET', '/posts', [PostsController::class, 'create']],
    ['POST', '/posts', [PostsController::class, 'store']],
    ['GET', '/register', [\App\Controller\RegistrationController::class, 'index',
        [
            \Sadl\Framework\Http\Middleware\Guest::class
        ]
    ]
    ],
    ['POST', '/register', [\App\Controller\RegistrationController::class, 'register']],
    ['GET', '/login', [\App\Controller\LoginController::class, 'index',
        [
            \Sadl\Framework\Http\Middleware\Guest::class
        ]
    ]
    ],
    ['POST', '/login', [\App\Controller\LoginController::class, 'login']],
    ['GET', '/logout', [\App\Controller\LoginController::class, 'logout',
        [
            // already authenticated in order to be able to visit the logout route
            \Sadl\Framework\Http\Middleware\Authenticate::class
        ]
    ]
    ],
    ['GET', '/dashboard', [\App\Controller\DashboardController::class, 'index',
        [
            \Sadl\Framework\Http\Middleware\Authenticate::class,
        ]
    ]
    ],
    ['GET', '/hello/{name:.+}', function(string $name) {
        return new Response("Hello $name");
    }]
];
