<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

// Enable debugging mode for developer friendly error messages
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views',
]);

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    $twig->addExtension(new VanPattenMedia\Twig\Pluralize());
    $twig->addExtension(new Twig_Extensions_Extension_Date());
    return $twig;
});

$app->get('/', function () use ($app) {
    return 'Hacker News';
});

$app->get('/news', function () use ($app) {
    return $app['twig']->render('page.html.twig', [
        'items' => [
            [
                'by' => 'dhouston',
                'descendants' => 71,
                'id' => 8863,
                'kids' => [
                    9224, 8952, 8917, 8884, 8887, 8943, 8869, 8940, 8908, 8958, 9005, 8873, 9671, 
                    9067, 9055, 8865, 8881, 8872, 8955, 10403, 8903, 8928, 9125, 8998, 8901, 8902, 
                    8907, 8894, 8870, 8878, 8980, 8934, 8876
                ],
                'score' => 104,
                'time' => 1175714200,
                'title' => 'My YC app: Dropbox - Throw away your USB drive',
                'type' => 'story',
                'url' => 'http://www.getdropbox.com/u/2/screencast.html'
            ]
        ]
    ]);
})->bind('items');

$app->run();