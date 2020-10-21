<?php
namespace app\models;

/**
 * Model class representing one TODO item.
 */
final class Todo {

    /** @var int */
    public $id;

    /** @var int */
    public $status;

    /** @var string */
    public $username;

    /** @var string */
    public $email;

    /** @var string */
    public $text;

    /**
     * Todo constructor.
     * @param $values
     */
    public function __construct($values = []) {
        if (is_array($values) && !empty($values)) {
            foreach ($values as $name => $value) {
                $this->$name = $value;
            }
        }
    }

    /**
     * @return int <i>null</i> if not persistent
     */
    public function getId() {
        return $this->id;
    }

}
