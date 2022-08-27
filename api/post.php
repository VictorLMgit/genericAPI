<?php
include_once '../controllers/PostController.php'; 

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $response = PostController::insertNewPost($data);
        echo $response;
        break;
    
    case "DELETE":
        $id = $_GET['id'];
        $response = PostController::deletePostById($id);
        echo $response;
        break;
    
    case "GET":
        isset($_GET['id']) ? $id = $_GET['id'] : $id = null;
        isset($_GET['params']) ? $params = $_GET['params'] : $params = null;
        $response = PostController::getPost($id,$params);
        echo $response;
        break;
    default:
        # code...
        break;
}
