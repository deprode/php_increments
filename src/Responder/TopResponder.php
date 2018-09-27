<?php

namespace App\Responder;

use Psr\Http\Message\ResponseInterface;
use Twig_Environment;

class TopResponder
{
    private $twig;
    private $filename;
    const DEFAULT_VIEW_FILE = 'index.twig';

    public function __construct(Twig_Environment $twig, string $file = self::DEFAULT_VIEW_FILE)
    {
        $this->twig = $twig;
        $this->filename = $file;
    }

    public function render(ResponseInterface $response, array $param): ResponseInterface
    {
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
        $response = $response->withStatus(200);
        $response->getBody()->write($this->twig->render($this->filename, $param));

        return $response;
    }
}