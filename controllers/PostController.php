<?php

include_once('../DataBase/utils.php');

class PostController{
    
    
    static function insertNewPost($data){
        $db_connection = new DataBase();
        if (!$db_connection->insert($data, "coins")) return json_encode(array("mensagem" => "falha ao inserir dados"));
        return json_encode(array("mensagem" => "atualizacao inserida com sucesso!"));
    }

    static function deletePostById($id){
        $db_connection = new DataBase();
        if(!$db_connection->delete($id, 'coins')) return json_encode(array("mensagem" => "falha ao excluir dados"));
        return json_encode(array("mensagem" => "atualizacao removida com sucesso!"));
    }

    static function getPost($id = null, $params = null){
        $db_connection = new DataBase();
        if(!$db_connection->get('coins', $id, $params)) return json_encode(array("mensagem" => "falha ao recuperar dados"));
        return json_encode($db_connection->get('coins', $id, $params));
        

    }

}

