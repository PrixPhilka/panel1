<?php 

$db_name = 'panel';
$db_user = 'panel';
$db_password = 'iJ0fG2hM3n';
$db = new mysqli('localhost', $db_user, $db_password, $db_name);



class SQL
{
    
    function __construct()
    {
        $db = new PDO('mysql:host=localhost;dbname=panel;charset=utf8mb4', 'panel', 'iJ0fG2hM3n');
        $this->db = $db;
    }

    public function fetchAll($sql, $execute = []) {
       $get_content = $this->db->prepare($sql);
       $get_content->execute($execute);
       $result = $get_content->fetchAll(PDO::FETCH_ASSOC);
       return $result;
    }
    public function fetch($sql, $execute = []) {
       $get_content = $this->db->prepare($sql);
       $get_content->execute($execute);
       $result = $get_content->fetch(PDO::FETCH_ASSOC);
       return $result;
    }
    public function query($sql, $execute = []) {
       $get_content = $this->db->prepare($sql);
       $get_content->execute($execute);
       return true;
    }
}








?>