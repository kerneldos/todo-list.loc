<?php

/**
 * @var mixed $content
 */

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>TODO List</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >

    <meta name="description" content="" >
    <meta name="keywords" content="" >
    <meta name="author" content="" >

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet" type="text/css" >

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/main.js"></script>

</head>
<body id="page">
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Todo List</a>
            </div>
            <?php if (\app\models\User::isGuest()): ?>
                <div id="navbar" class="navbar-collapse collapse">
                    <form action="/?page=login" class="navbar-form navbar-right" method="post">
                        <div class="form-group">
                            <input type="text" name="user[username]" placeholder="Login" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" name="user[password]" placeholder="Password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Log in</button>
                    </form>
                </div><!--/.navbar-collapse -->
            <?php else: ?>
                <div id="navbar" class="navbar-collapse collapse">
                    <form action="/?page=logout" class="navbar-form navbar-right" method="post">
                        <button name="logout" value="1" type="submit" class="btn btn-success">Log out</button>
                    </form>
                </div><!--/.navbar-collapse -->
            <?php endif; ?>
        </div>
    </nav>

    <?php $flashes = \app\components\flash\Flash::getFlashes(); ?>
    <?php if (!empty($flashes)): ?>
        <div class="container">
            <div class="row">
                <?php foreach ($flashes as $flash): ?>
                    <div class="alert alert-info flash" role="alert"><?= $flash ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <?= $content ?>
    </div> <!-- /container -->

    <div class="container">
        <div class="row">
            <hr>
            <footer>
                <p>Todo List application</p>
            </footer>
        </div>
    </div>
</body>
</html>
