<?php


namespace app\models;


class User {
    const USERS = [
        [
            'id' => 1,
            'username' => 'admin',
            'password' => '123',
        ],
    ];

    public $username;
    public $password;

    public static function isGuest() {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] === 0;
    }

    public static function login($postUser) {
        $user = array_filter(static::USERS, function ($item) use ($postUser) {
            return $postUser['username'] === $item['username'];
        });

        if (!empty($user)) {
            if ($user[0]['password'] === $postUser['password']) {
                $_SESSION['user_id'] = $user[0]['id'];

                return true;
            }
        }

        return false;
    }

    public static function logout() {
        $_SESSION['user_id'] = 0;
    }
}