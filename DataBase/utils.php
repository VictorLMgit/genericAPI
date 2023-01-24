<?php
include_once '../configs.php';
include_once '../model/coins.php';
class DataBase {

    protected $db_connection = null;
    
    private function getDbConnection($db_name){

        if ($db_name == null) {
            $db_name = DB_NAME;
        }

        if ($this->db_connection !== null) {
            $this->db_connection -> exec("set names utf8");
            return $this->db_connection;
        }
        
        try{

            $this->db_connection = new PDO("mysql:host=".DB_HOST.";dbname=".$db_name, DB_USER, DB_PASS);
            $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db_connection -> exec("set names utf8");

        } catch(PDOException $e){

            logMsg("Problemas ao conectar com a base: " . $e->getMessage());
            
        }
       
        return $this->db_connection;
    }

    public function close(){
        $this->db_connection = null;
    }

    public function save($data, $table, $db_name = null) : bool
    {
        $dbconnection = $this->getDbConnection($db_name);
        
        $sql = $this->formatSql($data, $table);

        $sth = $dbconnection->prepare($sql);

        $i=1;
        foreach($data as $field) {
            $sth->bindValue($i, $field);
            $i++;
        }

        $resultQuery = $sth->execute();
        if(!$resultQuery) return false;
        return true;
    
    }

    public function get_limit($table , $condicao = 'processamento = 0', $db_name = null)
    {
        $dbconnection = $this->getDbConnection($db_name);
        
        $sql = "SELECT * FROM $table WHERE $condicao order by id desc limit 1";
        // echo $sql;
        $sth = $dbconnection->prepare($sql);
        $resultQuery = $sth->execute();
        if (!$resultQuery) return false;
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    
    }

    public function lastInsertId($table, $db_name = null){
        $dbconnection = $this->getDbConnection($db_name);
        $sql = "SELECT id FROM $table order by id desc limit 1";
        $sth = $dbconnection->prepare($sql);
        $resultQuery = $sth->execute();
        if (!$resultQuery) return false;
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function get($table , $condicao = 'processamento = 0', $db_name = null , $params = null){
        
        $dbconnection = $this->getDbConnection($db_name);
        if (!$params) {
            if ($condicao) {
                $sql = "SELECT * FROM $table WHERE $condicao";
                // echo $sql;
                $sth = $dbconnection->prepare($sql);
                $resultQuery = $sth->execute();
                if (!$resultQuery) return false;
                return $sth->fetchAll(PDO::FETCH_ASSOC);
            }

            $sql = "SELECT * FROM $table";
            $sth = $dbconnection->prepare($sql);
            $resultQuery = $sth->execute();

            if (!$resultQuery) return false;
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }

        if ($condicao) {
            $sql = "SELECT $params FROM $table WHERE $condicao";
            // echo $sql . '<br>';
          
            $sth = $dbconnection->prepare($sql);
            $resultQuery = $sth->execute();

            if (!$resultQuery) return false;
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }

        $sql = "SELECT $params FROM $table";
        $sth = $dbconnection->prepare($sql);
        $resultQuery = $sth->execute();
        if (!$resultQuery) return false;
        return $sth->fetchAll(PDO::FETCH_ASSOC);
        

    }

    public function update($table, $set, $where, $db_name = null){
        $dbconnection = self::getDbConnection($db_name);
        $sql = "UPDATE $table SET $set WHERE $where";
        logMsg($sql);
        $sth = $dbconnection->prepare($sql);
        $resultQuery = $sth->execute();
        if (!$resultQuery) return false;
        return true;

    }



    public function delete($table, $where, $db_name = null){
        $dbconnection = self::getDbConnection($db_name);
        $sql = "DELETE FROM $table WHERE $where";
        logMsg($sql);
        $sth = $dbconnection->prepare($sql);
        $resultQuery = $sth->execute();
        if (!$resultQuery) return false;
        return true;

    }

    public function sql($sql , $db_name = NULL){
        $dbconnection = self::getDbConnection($db_name);
        $sth = $dbconnection->prepare($sql);
        $resultQuery = $sth->execute();
        if (!$resultQuery) return false;
        return $sth->fetchAll(PDO::FETCH_ASSOC);

    }
    
    private function formatSql($arr, $table)
    {
        $data = "";
        $columns = "";
        foreach ($arr as $assoc => $field) {
            $columns .= " `$assoc`,";
            $data .= " '$field',";
        }
    
        $columns = substr($columns, 0, strlen($columns) - 1);
        $data = substr($data, 0, strlen($data) - 1);
    
        $sql = "INSERT INTO $table ($columns) VALUES ($data)";
    
        return $sql;
    }

}


