<?php

use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

// Enable debugging mode for developer friendly error messages
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views'
]);

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => Yaml::parse(file_get_contents(__DIR__ . '/config/db-options.yml'))
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
    
    $sql = "SELECT
                `id`, `username` AS `by`, `type`, `timestamp` AS `time`, `title`, `score`,
                IFNULL(`url`, '') AS `url`,
                IFNULL(`content`, '') AS `text`,
                IFNULL(`descendants`, 0) AS `descendants`,
                (
                    SELECT COUNT(`id`) 
                    FROM `items` AS `child` 
                    WHERE `child`.`parent_id` = `parent`.`id`
                
                ) AS `comments`
            FROM
                `items` AS `parent`
            WHERE
                `parent_id` IS NULL
            ORDER BY
                `score` DESC";
    
    $items = $app['db']->fetchAll($sql);
    
    return $app['twig']->render('page.html.twig', ['items' => $items]);
    
})->bind('items');

$app->run();