<?php

namespace App;

use Twig_Environment;
use App\Interfaces\ViewInterface;

class Twig implements ViewInterface
{
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $filename
     * @param array $param
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     */
    public function render(string $filename, array $param): string
    {
        try {
            return $this->twig->render($filename, $param);
        } catch (\Twig_Error_Syntax $e) {
            return 'テンプレート構文エラー';
        }
    }
}