<?php
/**
 * @var \app\models\Todo $todo
 */
?>

<form action="/?page=create" method="post">
    <div class="form-group">
        <label for="todo-username-input">User name</label>
        <input class="form-control" name="todo[username]" id="todo-username-input" value="<?= $todo->username ?>" />
    </div>

    <div class="form-group">
        <label for="todo-email-input">Email</label>
        <input class="form-control" name="todo[email]" id="todo-email-input" value="<?= $todo->email ?>" />
    </div>

    <div class="form-group">
        <label for="todo-text-input">Text</label>
        <textarea class="form-control" name="todo[text]" id="todo-text-input" rows="5"><?= $todo->text ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>
