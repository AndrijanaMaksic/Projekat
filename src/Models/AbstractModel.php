<?php

namespace Jewelry\Moodels;

use PDO;

abstract class AbstractModel{
    private $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }
}