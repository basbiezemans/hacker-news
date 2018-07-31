<?php

use HackerNewsApi\Service\HackerNewsServiceClient;
use HackerNewsApi\Client\HackerNewsClient;

$items = $app['controllers_factory'];

$items->get('/', function () use ($app) {

    $client = new HackerNewsClient(HackerNewsServiceClient::create());
    $page_limit = 30;
    $list = array_slice($client->getShowStories(), 0, $page_limit);
    $items = [];
    
    foreach ($list as $id) {
        $items[] = $client->getItem($id);
    }
    
    return $app['twig']->render('items.html.twig', ['items' => $items]);
    
})->bind('items');

return $items;