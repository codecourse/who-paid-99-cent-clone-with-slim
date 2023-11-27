<?php

session_start();

use DI\Container;
use Dotenv\Dotenv;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Stripe\StripeClient;
use App\Views\EnvExtension;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
use Twig\Extension\DebugExtension;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

AppFactory::setContainer($container = new Container());

$container->set('view', function() {
    $twig = Twig::create(__DIR__ . '/../resources/views', [
        'cache' => false,
        'debug' => $_ENV['APP_ENV'] === 'local'
    ]);

    $twig->addExtension(new EnvExtension());

    if ($_ENV['APP_ENV'] === 'local') {
        $twig->addExtension(new DebugExtension());
    }

    return $twig;
});

$container->set('stripe', function () {
    return new StripeClient($_ENV['STRIPE_SECRET']);
});

$container->set('flash', function () {
    return new Messages();
});

$app = AppFactory::create();

$app->add(TwigMiddleware::createFromContainer($app));

require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/database.php';
