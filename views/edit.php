<?php
/**
 * @var \app\models\Todo $todo
 */
?>

<form action="/?page=edit&id=<?= $todo->id ?>" method="post">
    <div class="form-group">
        <label for="todo-text-input">Text</label>
        <textarea class="form-control" name="todo[text]" id="todo-text-input" rows="5"><?= $todo->text ?></textarea>
    </div>

    <div class="form-group">
        <label>
            Выполнена
            <input type="checkbox" name="todo[status]" <?= !!$todo->status ? 'checked' : '' ?> value="1">
        </label>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>
