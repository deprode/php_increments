<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    public function create();

    public function delete();

    public function update();

    public function fetchAll();

    public function fetch(int $id);
}