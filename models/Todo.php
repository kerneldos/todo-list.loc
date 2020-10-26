<?php
namespace app\models;

use app\components\Model;

/**
 * Model class representing one TODO item.
 */
final class Todo extends Model {

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

    /** @var int */
    public $edit;

    /**
     * @return int <i>null</i> if not persistent
     */
    public function getId() {
        return $this->id;
    }

}
