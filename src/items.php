<?php

use HackerNewsApi\Service\HackerNewsServiceClient;
use HackerNewsApi\Client\HackerNewsClient;

$items = $app['controllers_factory'];

$items->get('/{type}', function ($type) use ($app) {

    $client = new HackerNewsClient(HackerNewsServiceClient::create());

    $items = (new HackerNews($client))->stories($type, 15);
    
    return $app['twig']->render('items.html.twig', ['items' => $items]);
    
})->bind('items')->value('type', 'top');

return $items;