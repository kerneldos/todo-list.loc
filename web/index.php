<?php


namespace app\web;

use app\components\exception\NotFoundException;
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
        'app\components\Model' => '/../components/Model.php',
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
        error_reporting(E_ERROR);
        mb_internal_encoding('UTF-8');
        set_exception_handler([$this, 'handleException']);
        spl_autoload_register([$this, 'loadClass']);
        // session
        session_start();
    }

    /**
     * Run the application!
     * @throws NotFoundException
     */
    public function run() {
        $content = call_user_func([new AppController(), 'action' . ucwords($this->getPage())]);

        if (!$content) {
            throw new NotFoundException('Page not found.');
        }

        echo $content;
    }

    /**
     * Exception handler.
     */
    public function handleException($ex) {
        if ($ex instanceof NotFoundException) {
            header('HTTP/1.0 404 Not Found');
            echo call_user_func_array([new AppController(), 'actionError'], ['message' => $ex->getMessage()]);
        } else {
            // TODO log exception
            header('HTTP/1.1 500 Internal Server Error');
            echo 'error 500';
        }
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
