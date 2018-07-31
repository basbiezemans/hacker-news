<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/functions.php';

$app = new Silex\Application();

// Enable debugging mode for developer friendly error messages
$app['debug'] = true;

// Register Twig
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views'
]);

// Twig extensions
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    $twig->addExtension(new VanPattenMedia\Twig\Pluralize());
    $twig->addExtension(new Twig_Extensions_Extension_Date());
    return $twig;
});

$app->mount('/', include __DIR__ . '/src/items.php');

$app->mount('/comments/{item_id}', include __DIR__ . '/src/comments.php');

$app->run();