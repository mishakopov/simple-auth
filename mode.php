<?php
$configs = require_once "conf.php";

$connection = "";

function connectToDB(){
    global $configs , $connection;
    $connection = mysqli_connect($configs['db']['servername'], $configs['db']['username'], $configs['db']['password'] ,$configs['db']['dbname']);
    if (!$connection){
        die("Connection failed " .  mysqli_connect_error());
    }
}

function registerUser(array $userDetails){
    global $connection;
    $sql = "INSERT INTO `users` (`name`, `email`, `password`, `created_at`) VALUES 
      ('" . $userDetails['name'] . "' , '"
           . $userDetails['email'] . "', '"
           . password_hash($userDetails['password'], PASSWORD_BCRYPT) . "','"
           . date('Y-m-d H:i:s', time()) . "')";

    $result = mysqli_query( $connection, $sql);

    return $result;
}



function userExist($email){
    global $connection;
    $sql = " Select * FROM `users` WHERE email='$email' ";
    $result = mysqli_query($connection, $sql);
    $result = mysqli_fetch_assoc($result);
    return $result ?  true : false ;
}

function getUser($email){
    global $connection;
    $sql = "Select * from `users` WHERE email = '$email' ";
    $result = mysqli_query($connection, $sql);
    $result = mysqli_fetch_assoc($result);
    return $result;
}

function updateUser($data){
    global $connection;
    $sql = "UPDATE users SET name = '" . $data['name'] . "' , email = '" . $data['email'] . "'  WHERE email = '" . $_SESSION['user']['email'] . "'";
    $result = mysqli_query($connection, $sql);
    return $result;
}

function resetPassword($data){
    global $connection;
    $sql = "UPDATE users SET password  = '" . password_hash($data['newpass'], PASSWORD_BCRYPT) . "' WHERE email = '" . $_SESSION["user"]["email"]. "'";
    $result = mysqli_query($connection, $sql);
    return $result;
}

function createPost($data){
    global $connection;
    $sql = "INSERT INTO posts (`user_id`, `title`, `body`, `image`, `created_at`) VALUES(" . $data['user_id'] . " , '" . $data['title'] . "', '" . $data['body'] . "', '" . $data['image'] . "', '" . $data['created_at'] . "')";
    $result = mysqli_query($connection, $sql);
    return $result;
}
function getPosts(){
    global $connection;
    $sql = "Select posts.*, users.name  from posts join users on posts.user_id = users.id";
    $result = mysqli_query($connection , $sql);
    $data = [];
    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    return $data;
}
function getSinglePost($postid){
    global $connection;
    $sql = "Select posts.*, users.name  from posts join users on posts.user_id = users.id WHERE posts.id = $postid";
    $result = mysqli_query($connection , $sql);
    $result = mysqli_fetch_assoc($result);
    return $result;
}

