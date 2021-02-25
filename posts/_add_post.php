<?php
session_start();
require_once '../mode.php';
connectToDB();
$request = $_POST;

$errors = [];

if(!$request['title']){
    $errors['title'] = "Enter title";
}

if (!$request['body']){
    $errors['body'] = "Fill out Body";
}

if (!$_FILES['image']){
    $errors['image'] = "Upload image";
}else{
    $target_name = basename($_FILES['image']['name']);
    $image_type = pathinfo($target_name, PATHINFO_EXTENSION);
    if ($image_type !== 'jpg' && $image_type !== "png" && $image_type = "jpeg" && $image_type = "gif"){
        $errors['file_type'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }
}

if ($errors){
    $_SESSION['errors'] = $errors;
    header('Location:add_post.php');
    die;
}


$filename  = time() . '.'  . $image_type;
$data = $request;
if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $filename)){
    $data['image'] = $filename;
    $data['user_id'] = getUser($_SESSION['user']['email'])['id'];
    $data['created_at'] = date('Y-m-d H:i:s');
    createPost($data);
    $_SESSION['success'] = "Post is created";
    header('Location:add_post.php');
    die;
}
