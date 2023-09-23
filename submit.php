<?php

$sname= "localhost";
$unmae= "root";
$password = "Id:19016729";

$db_name = "test";

$conn = mysqli_connect($sname, $unmae, $password, $db_name, 3307);
if (!$conn) {
	echo "Connection failed!";
}

// CREATE table in DB if not exists
$create_db = "CREATE TABLE IF NOT EXISTS Users  (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(30) NOT NULL
    )";

$conn->query($create_db);

// CRUD based on server request
switch($_SERVER["REQUEST_METHOD"]){
    case "POST":

        if($_POST['action'] == 'edit'){
            Get_User($conn, $_POST);
        }else if(!isset($_POST['id'])){
            Create_User($conn);
        }else{
            $id = $_POST['id'];
            Delete_User($conn, $id);
        }
        break;
    case "GET":
        List_Users($conn);
        break;
}

function Create_User($conn){
    // Retrieve form data
    $name = $_POST["name"];

    // Insert username to DB table
    $query = "INSERT INTO users (fullname)  VALUES ('$name')";
    $conn->query($query);
    header("location: form.html");
}

function List_Users($conn){
    $query = "SELECT * FROM `users`";
    $result = $conn->query($query);
    if(mysqli_num_rows($result) > 0){
        $result_array = array();
        while($row = mysqli_fetch_assoc($result)){
            array_push($result_array, $row);
        }

    }
    echo json_encode($result_array);
}

function Delete_User($conn, $id){
    $query = "DELETE FROM `users` WHERE `id` ='".$id."'";
    $conn->query($query);
}

function Get_User($conn, $post){
        $id = $post['id'];
        $query = "SELECT * FROM `users` WHERE `id` ='".$id."'";
        $result = $conn->query($query);
        if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        Edit_User($conn, $row, $post);
    }
}

function Edit_User($conn, $user, $edited_data){
        $id = $edited_data['id'];
        $edits = array();
        foreach ($user as $key => $value) {
        if($value != $edited_data[$key]){
            $edits[$key] = $edited_data[$key];
        }
    }

    foreach ($edits as $key => $value) {
        echo $key . "  " . $value;
        $query = "UPDATE `users` SET `$key` = '$value' WHERE `id` ='".$id."'";
        $conn->query($query);    
    }
        header("location: form.html");
}