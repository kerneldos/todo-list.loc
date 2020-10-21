<?php


namespace app\web;

use app\controllers\AppController;

/**
 * Main application class.
 */
final class Application {

    const DEFAULT_PAGE = 'index';

    private static $CLASSES = [
        'app\config\Config' => '/../config/Config.php',
        'app\components\flash\Flash' => '/../components/flash/Flash.php',
        'app\components\exception\NotFoundException' => '/../components/exception/NotFoundException.php',
        'app\components\dao\TodoDao' => '/../components/dao/TodoDao.php',
        'app\components\Controller' => '/../components/Controller.php',
        'app\controllers\AppController' => '/../controllers/AppController.php',
        'app\models\Todo' => '/../models/Todo.php',
        'app\models\User' => '/../models/User.php',
    ];


    /**
     * System config.
     */
    public function init() {
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');

        spl_autoload_register([$this, 'loadClass']);
        // session
        session_start();
    }

    /**
     * Run the application!
     */
    public function run() {
        call_user_func([new AppController(), 'action' . ucwords($this->getPage())]);
    }

    /**
     * Class loader.
     */
    public function loadClass($name) {
        if (!array_key_exists($name, self::$CLASSES)) {
            die('Class "' . $name . '" not found.');
        }
        require_once __DIR__ . self::$CLASSES[$name];
    }

    private function getPage() {
        $page = self::DEFAULT_PAGE;

        if (array_key_exists('page', $_GET)) {
            $page = $_GET['page'];
        }

        return $page;
    }

}

$app = new Application();
$app->init();
// run application!
$app->run();
