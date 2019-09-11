<?php

require 'vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface;

$loop = \React\EventLoop\Factory::create();
$posts = require 'posts.php';

$server = new \React\Http\Server(function(ServerRequestInterface $request) use ($posts) {
    $params = $request->getQueryParams();
    $tag = $params['tag'] ?? null;

    $filteredPosts = array_filter($posts, function (array $post) use ($tag) {
        if($tag){
            return in_array($tag, $post['tags']);
        }

        return true;
    });

    $page = $params['page'] ?? 1;
    $filteredPosts = array_chunk($filteredPosts, 2);
    $filteredPosts = $filteredPosts[$page - 1] ?? [];

    return new \React\Http\Response(200, ['Content-Type' => 'application/json'], json_encode($filteredPosts));
});

$socket = new \React\Socket\Server('127.0.0.1:8000', $loop);

$server->listen($socket);

$loop->run();