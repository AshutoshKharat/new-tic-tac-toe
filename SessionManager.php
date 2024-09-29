<?php
class SessionManager
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Start the session if not already started
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? null; // Return null if the session variable does not exist
    }

    public function destroy()
    {
        session_destroy(); // Destroy the session
    }
}
