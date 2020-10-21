<?php
/**
 * @var \app\models\Todo $todos
 * @var int $pageCount
 */
?>

<div class="row">
    <h1>Список задач:</h1>

    <p>
        <a href="/?page=create" class="btn btn-success">Добавить задачу</a>
    </p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <?php $order = !empty($_GET['order']) ? ($_GET['order'] === 'asc' ? 'desc' : 'asc') : 'desc' ?>
                <?php $pageNum = !empty($_GET['pageNum']) ? $_GET['pageNum'] : 1 ?>
                <th>
                    Status
                    <a href="/?sort=status&order=<?= $order ?>&pageNum=<?= $pageNum ?>">
                        <span class="glyphicon glyphicon-sort-by-alphabet<?= $order === 'asc' ? '-alt' : '' ?>"></span>
                    </a>
                </th>
                <th>
                    User Name
                    <a href="/?sort=username&order=<?= $order ?>&pageNum=<?= $pageNum ?>">
                        <span class="glyphicon glyphicon-sort-by-alphabet<?= $order === 'asc' ? '-alt' : '' ?>"></span>
                    </a>
                </th>
                <th>
                    Email
                    <a href="/?sort=email&order=<?= $order ?>&pageNum=<?= $pageNum ?>">
                        <span class="glyphicon glyphicon-sort-by-alphabet<?= $order === 'asc' ? '-alt' : '' ?>"></span>
                    </a>
                </th>
                <th>Text</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($todos as $todo): ?>
                <tr class="<?= $todo->status ? 'success' : '' ?>">
                    <td><?= $todo->status ? 'Готова' : 'В работе' ?></td>
                    <td><?= $todo->username ?></td>
                    <td><?= $todo->email ?></td>
                    <td><?= htmlentities($todo->text) ?></td>
                    <td>
                        <?php if (!\app\models\User::isGuest()): ?>
                            <a href="/?page=edit&id=<?= $todo->id ?>"><span class="glyphicon glyphicon-edit"></span></a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                <li>
                    <a href="/?pageNum=<?= $i . (!empty($_GET['sort']) ? '&sort=' . $_GET['sort'] . '&order=' . $_GET['order'] : '') ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>