<?php
include_once '../configs.php';
include_once '../model/coins.php';
class DataBase {

    protected $connection;

    private function getDbConnection(){

        $this->connection = new PDO("mysql:host=".HOST.";dbname=".DB_NAME, DB_USER, DB_PASS); 
    
        if (!$this->connection) return false;
    
        return $this->connection;
    
    }
    
    private function closeDbConnection(){
        unset($this->connection);
    }

    public function insert($data, $table){
        $dbconnection = self::getDbConnection();
        $sql = self::formatSql($data, $table);
        $sth = $dbconnection->prepare($sql);
        $resultQuery = $sth->execute();
        self::closeDbConnection();
        if(!$resultQuery) return false;
        return true;
    
    }
    
    public function delete($id, $table){
        $dbconnection = self::getDbConnection();
        $sql = "DELETE FROM $table WHERE id = $id";
        $sth = $dbconnection->prepare($sql);
        $resultQuery = $sth->execute();
        self::closeDbConnection();
        if(!$resultQuery) return false;
        return true;
    }

    public function get($table , $id = null, $params = null){
        $dbconnection = self::getDbConnection();
        if (!$params) {
            if ($id) {
                $sql = "SELECT * FROM $table WHERE id = $id";
                $sth = $dbconnection->prepare($sql);
                $resultQuery = $sth->execute();
                self::closeDbConnection();
                if (!$resultQuery) return false;
                return $sth->fetchAll(PDO::FETCH_CLASS, "coins");
            }

            $sql = "SELECT * FROM $table";
            $sth = $dbconnection->prepare($sql);
            $resultQuery = $sth->execute();
            self::closeDbConnection();
            if (!$resultQuery) return false;
            return $sth->fetchAll(PDO::FETCH_CLASS, "coins");
        }

        if ($id) {
            $sql = "SELECT $params FROM $table WHERE id = $id";
            $sth = $dbconnection->prepare($sql);
            $resultQuery = $sth->execute();
            self::closeDbConnection();
            if (!$resultQuery) return false;
            return $sth->fetchAll(PDO::FETCH_CLASS, "coins");
        }

        $sql = "SELECT $params FROM $table";
        $sth = $dbconnection->prepare($sql);
        $resultQuery = $sth->execute();
        self::closeDbConnection();
        if (!$resultQuery) return false;
        return $sth->fetchAll(PDO::FETCH_CLASS, "coins");
        

    }

    
    private function formatSql($arr, $table)
    {
        $data = "";
        $columns = "";
        foreach ($arr as $assoc => $field) {
            $columns .= "$assoc,";
            $data .= "\"$field\",";
        }
    
        $columns = substr($columns, 0, strlen($columns) - 1);
        $data = substr($data, 0, strlen($data) - 1);
    
        $sql = "INSERT INTO $table ($columns) VALUES ($data)";
    
        return $sql;
    }
    

}

