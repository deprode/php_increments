<?php

namespace App;

class Security
{

    public function outputSecureHeader()
    {
        header('X-Frame-Options: SAMEORIGIN');
        header("X-XSS-Protection: 1; mode=block");
        header('X-Content-Type-Options: nosniff');
        header('Content-Type: text/html; charset=UTF-8');
    }

    public function generateToken(?string $token = ''): string
    {
        if (empty($token)) {
            try {
                $token = bin2hex(random_bytes(24));
            } catch (Exception $e) {
                exit(1);
            }
            $_SESSION['token'] = $token;
        } else {
            $token = $_SESSION['token'];
        }
        return $token;
    }

    public function checkToken(string $token, string $saved_token = '')
    {
        if (empty($saved_token) || $token !== $saved_token) {
            die('正規の画面から投稿してください');
        }
    }
}