<?php

namespace App\Responder;

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

    public function render(array $param): string
    {
        return $this->twig->render($this->filename, $param);
    }
}