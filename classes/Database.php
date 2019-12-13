<?php

class Database{
    private $servername = 'localhost';
    private $username = 'root';
    private $password = 'root';
    private $database_name = 'twitter';
    public $con;

    public function __construct(){
        $this->con = new mysqli($this->servername,$this->username,$this->password,$this->database_name);

        if($this->con->connect_error){
            die('connect error'.$this->con->connect_error);
        }else{
            return $this->con;
        }
    }

}

?>