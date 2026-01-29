<?php
namespace App\Helpers;

class Flash
{
    public static function add($type, $message)
    {
        $_SESSION['flash'][$type][] = $message;
    }

    public static function get()
    {
        if (!isset($_SESSION['flash'])) {
            return [];
        }

        $messages = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $messages;
    }
}