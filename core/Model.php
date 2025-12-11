<?php
abstract class Model
//modelo principal
{
    protected mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}
