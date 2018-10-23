<?php

namespace App;

class Security
{
    /**
     * @return string
     * @throws \Exception
     */
    protected function getToken(): string
    {
        return bin2hex(random_bytes(24));
    }

    public function generateToken(): string
    {
        try {
            $token = $this->getToken();
            $_SESSION['token'] = $token;
        } catch (\Exception $e) {
            exit(1);
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