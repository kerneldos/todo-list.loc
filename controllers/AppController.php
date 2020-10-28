<?php


namespace app\controllers;


use app\components\Controller;
use app\components\dao\TodoDao;
use app\components\flash\Flash;
use app\models\Todo;
use app\models\User;

class AppController extends Controller {

    public function actionIndex() {
        $dao = new TodoDao();

        // data for template
        $todos = $dao->find();

        return $this->render('index', [
            'todos' => $todos,
            'pageCount' => intval(($dao->getRowCount() - 1) / 3) + 1,
        ]);
    }

    public function actionEdit() {
        $dao = new TodoDao();

        $todo = $dao->findById($_GET['id']);

        if (!empty($_POST['todo']) && !User::isGuest()) {
            $todo->status = (int) !empty($_POST['todo']['status']);
            $todo->edit = (int) ($todo->text !== $_POST['todo']['text'] || $todo->edit);
            $todo->text = $_POST['todo']['text'];

            $errors = $dao->validate($todo);

            if (empty($errors)) {
                $dao->save($todo);

                Flash::addFlash('Изменения сохранены');

                header('Location: /');
                die();
            }

            foreach ($errors as $error) {
                Flash::addFlash($error);
            }
        } elseif (User::isGuest()) {
            Flash::addFlash('Вы не авторизованы. Пожалуйста, авторизуйтесь для продолжения.');
        }

        return $this->render('edit', [
            'todo' => $todo,
        ]);
    }

    public function actionCreate() {
        $dao = new TodoDao();
        $todo = new Todo(!empty($_POST['todo']) ? $_POST['todo'] : []);

        if (!empty($_POST['todo'])) {
            $errors = $dao->validate($todo);

            if (empty($errors)) {
                $dao->save($todo);

                Flash::addFlash('Задача успешно создана');

                header('Location: /');
                die();
            }

            foreach ($errors as $error) {
                Flash::addFlash($error);
            }
        }

        return $this->render('create', [
            'todo' => $todo,
        ]);
    }

    public function actionLogin() {
        $postUser = $_POST['user'];

        if (!empty($postUser)) {
            if (($user = User::findByUserName($postUser['username'])) !== false && $user->login($postUser['password'])) {
                Flash::addFlash('Вы успешно авторизовались');
            } else {
                Flash::addFlash('Не верные логин или пароль');
            }
        }

        return $this->redirect('index');
    }

    public function actionLogout() {
        if (!empty($_POST['logout'])) {
            User::logout();
            Flash::addFlash('Вы вышли из системы');

            header('Location: /');
            exit();
        }
    }

    public function actionError($message) {
        return $this->render('error', [
            'message' => $message,
        ]);
    }

}