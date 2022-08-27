<?php

// $arr = array("title" => "victor", "description" => "22", "owner" => "programador", "date_insert" => "2022-08-24 14:00:00", "image" => "image.png");

// $data = "";
// $columns = "";
// foreach($arr as $assoc => $field){
//     $columns .= "$assoc,";
//     $data .= "\"$field\",";
// }

// $columns = substr($columns, 0, strlen($columns) - 1);
// $data = substr($data, 0, strlen($data) - 1);

// $sql = "INSERT INTO posts ($columns) VALUES ($data)";
$id = 1;
$sql = "SELECT * FROM $table WHERE id = $id";

echo $sql;