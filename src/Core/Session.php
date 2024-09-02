<?php

namespace App\Core;

use App\Repositories\UserRepository;
use App\Utils\Tokenizer;
use Exception;

/**
 * Session management
 */
class Session
{
    /**
     * Start session
     * 
     * @return void
     */
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Create session instance 
     * 
     * @param string $token Access token
     * @return void
     */
    public static function create(string $token): void
    {
        self::start();

        $_SESSION["token"] = $token;

        self::regenerate();
    }

    /**
     * Get currently authorized user
     * 
     * @return array|null
     */
    public static function user(): ?array
    {
        $token = self::get("token");

        return $token["user"] ?? null;
    }

    /**
     * Get session key
     * 
     * @param string $key Key name
     * @return mixed Key value
     */
    public static function get(string $key): mixed
    {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    /**
     * Set session key
     * 
     * @param string $key Name of key
     * @param $value Key value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Destroy session
     * 
     * @return void
     */
    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * Regenerate session
     * 
     * @return void
     */
    private static function regenerate(): void
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}
