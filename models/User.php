<?php


namespace app\models;


use app\components\Model;

class User extends Model {
    const USERS = [
        [
            'id' => 1,
            'username' => 'admin',
            'password' => '123',
        ],
        [
            'id' => 2,
            'username' => 'author',
            'password' => '1234',
        ],
    ];

    public $id;
    public $username;
    public $password;

    public static function isGuest() {
        return
            (!isset($_SESSION['user_id']) || $_SESSION['user_id'] === 0) ||
            (!isset($_SESSION['user_session_id']) || $_SESSION['user_session_id'] === 0);
    }

    public static function findByUserName($username) {
        foreach (self::USERS as $user) {
            if ($user['username'] === $username) {
                return new static($user);
            }
        }

        return false;
    }

    public static function findById($id) {
        foreach (self::USERS as $user) {
            if ($user['id'] === $id) {
                return new static($user);
            }
        }

        return false;
    }

    public function login($password) {
        if ($this->password === $password) {
            $_SESSION['user_id'] = $this->getId();
            $_SESSION['user_session_id'] = $_COOKIE['PHPSESSID'];

            return true;
        }

        return false;
    }

    public static function logout() {
        $_SESSION['user_id'] = 0;
        $_SESSION['user_session_id'] = 0;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }
}