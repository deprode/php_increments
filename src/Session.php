<?php


namespace App;


class Session
{
    public function get(string $key)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }
}