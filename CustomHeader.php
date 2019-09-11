<?php

use \Psr\Http\Message\ServerRequestInterface;

final class CustomHeader{

    private $title;
    private $value;

    public function __construct($title, $value)
    {
        $this->title = $title;
        $this->value = $value;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $responce = $next($request);

        return $responce->withHeader($this->title, $this->value);
    }
}