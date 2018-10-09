<?php

namespace App\Interfaces;

interface ViewInterface{
    public function render(string $filepath, array $params);
}