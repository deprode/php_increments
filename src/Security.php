<?php

namespace App;

class Security
{
    public function generateToken(?string $token = ''): string
    {
        if (empty($token)) {
            try {
                $token = bin2hex(random_bytes(24));
            } catch (\Exception $e) {
                exit(1);
            }
            $_SESSION['token'] = $token;
        } else {
            $token = $_SESSION['token'];
        }
        return $token;
    }

    public function validToken(string $token, string $saved_token = ''): bool
    {
        if (empty($saved_token)) {
            $saved_token = $_SESSION['token'] ?? '';
        }

        if ($token !== $saved_token) {
            return false;
        }

        return true;
    }
}