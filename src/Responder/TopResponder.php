<?php

namespace App\Responder;

use Psr\Http\Message\ResponseInterface;
use App\Interfaces\ViewInterface;

class TopResponder
{
    private $view;
    private $filename;
    const DEFAULT_VIEW_FILE = 'index.twig';

    public function __construct(ViewInterface $view, string $file = self::DEFAULT_VIEW_FILE)
    {
        $this->view = $view;
        $this->filename = $file;
    }

    public function render(ResponseInterface $response, array $param): ResponseInterface
    {
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
        $response = $response->withStatus(200);
        $response->getBody()->write($this->view->render($this->filename, $param));

        return $response;
    }
}