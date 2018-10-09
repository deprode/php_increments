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

    public function render(string $filename, array $param): string
    {
        try {
            return $this->twig->render($filename, $param);
        } catch (\Twig_Error_Loader $e) {
            exit;
        } catch (\Twig_Error_Runtime $e) {
            exit;
        } catch (\Twig_Error_Syntax $e) {
            return 'テンプレート構文エラー';
        }
    }
}