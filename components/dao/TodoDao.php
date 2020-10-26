<?php


namespace app\components\dao;

use app\components\exception\NotFoundException;
use app\config\Config;
use app\models\Todo;
use DateTime;
use Exception;
use PDO;
use PDOStatement;

/**
 * DAO for {@link \app\models\Todo}.
 * <p>
 * It is also a service, ideally, this class should be divided into DAO and Service.
 */
final class TodoDao {

    /** @var PDO */
    private $db = null;


    public function __destruct() {
        // close db connection
        $this->db = null;
    }

    /**
     * Find all {@link Todo}s by search criteria.
     * @return array array of {@link Todo}s
     */
    public function find() {
        $result = [];
        foreach ($this->query($this->getFindSql()) as $row) {
            $todo = new Todo($row);

            $result[] = $todo;
        }

        return $result;
    }

    /**
     * Find {@link Todo} by identifier.
     * @return Todo Todo or <i>null</i> if not found
     * @throws NotFoundException|\ReflectionException
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM todo WHERE id = ' . (int) $id)->fetch();

        if (!$row) {
            throw new NotFoundException('Todo not found');
        }

        return new Todo($row);
    }

    /**
     * Save {@link Todo}.
     * @param Todo $todo {@link Todo} to be saved
     * @return Todo saved {@link Todo} instance
     * @throws Exception
     */
    public function save(Todo $todo) {
        if ($todo->getId() === null) {
            return $this->insert($todo);
        }

        return $this->update($todo);
    }

    /**
     * Delete {@link Todo} by identifier.
     * @param int $id {@link Todo} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        $sql = '
            DELETE FROM todo
            WHERE id = :id';

        $statement = $this->getDb()->prepare($sql);

        $this->executeStatement($statement, [':id' => $id]);

        return $statement->rowCount() == 1;
    }

    /**
     * @return PDO
     */
    private function getDb() {
        if ($this->db !== null) {
            return $this->db;
        }
        $config = Config::getConfig('db');
        try {
            $this->db = new PDO($config['dsn'], $config['username'], $config['password']);
        } catch (Exception $ex) {
            throw new Exception('DB connection error: ' . $ex->getMessage());
        }
        return $this->db;
    }

    private function getFindSql() {
        $sql = 'SELECT * FROM todo WHERE 1=1 ';

        $orderBy = !empty($_GET['sort']) ? join(' ', [$_GET['sort'], $_GET['order']]) : 'id';

        $num = 3;
        // Извлекаем из URL текущую страницу
        $page = !empty($_GET['pageNum']) ? $_GET['pageNum'] : 1;

        $count = $this->getRowCount();

        // Находим общее число страниц
        $total = intval(($count - 1) / $num) + 1;

        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total) $page = $total;

        $start = $page * $num - $num;

        $sql .= ' ORDER BY ' . $orderBy;
        $sql .= ' LIMIT ' . join(', ', [$start, $num]);

        return $sql;
    }

    /**
     * @param Todo $todo
     * @return Todo
     * @throws Exception
     */
    private function insert(Todo $todo) {
        $sql = '
            INSERT INTO todo (username, email, text)
            VALUES (:username, :email, :text)';

        return $this->execute($sql, $todo);
    }

    /**
     * @return Todo
     * @throws Exception
     */
    private function update(Todo $todo) {
        $sql = '
            UPDATE todo SET
                status = :status,
                username = :username,
                email = :email,
                text = :text,
                edit = :edit
            WHERE
                id = :id';

        return $this->execute($sql, $todo);
    }

    /**
     * @param $sql
     * @param Todo $todo
     * @return Todo
     * @throws Exception
     */
    private function execute($sql, Todo $todo) {
        $statement = $this->getDb()->prepare($sql);

        $this->executeStatement($statement, $this->getParams($todo));

        if (!$todo->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }

        return $todo;
    }

    private function getParams(Todo $todo) {
        return [
            ':id' => $todo->getId(),
            ':status' => $todo->status,
            ':username' => $todo->username,
            ':email' => $todo->email,
            ':text' => $todo->text,
            ':edit' => $todo->edit,
        ];
    }

    private function executeStatement(PDOStatement $statement, array $params) {
        $params = array_filter($params, function ($param) {
            return !empty($param) || $param === '0' || $param === 0 || $param === false;
        });

        if ($statement->execute($params) === false) {
            self::throwDbError($this->getDb()->errorInfo());
        }
    }

    public function validate(Todo $todo) {
        $errors = [];

        if (empty($todo->username)) {
            $errors[] = 'Необходимо заполнить поле username';
        }

        if (empty($todo->email)) {
            $errors[] = 'Необходимо заполнить поле email';
        } elseif (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $todo->email)) {
            $errors[] = 'Поле email заполнено не верно';
        }

        if (empty($todo->text)) {
            $errors[] = 'Необходимо заполнить поле text';
        }

        return $errors;
    }

    /**
     * @return PDOStatement
     */
    private function query($sql) {
        $statement = $this->getDb()->query($sql, PDO::FETCH_ASSOC);
        if ($statement === false) {
            self::throwDbError($this->getDb()->errorInfo());
        }
        return $statement;
    }

    private static function throwDbError(array $errorInfo) {
        // TODO log error, send email, etc.
        throw new Exception('DB error [' . $errorInfo[0] . ', ' . $errorInfo[1] . ']: ' . $errorInfo[2]);
    }

    public function getRowCount() {
        $query = $this->query("SELECT * FROM todo");
        return $query->rowCount();
    }
}
