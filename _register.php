<?php

require_once ("mode.php");

connectToDB();
$request = $_POST;



$errors = [];
if(!$request['name']){
    $errors['name'] = "Name is required.";
}

if(!$request['email']){
    $errors['email'] = "Email is required.";
}elseif(!filter_var($request['email'], FILTER_VALIDATE_EMAIL)){
    $errors['email'] = "Email is not valid.";
}elseif(userExist($request['email'])){
    $errors['email'] = "Email was taken";
}

if(!$request['password']){
    $errors['password'] = "Password is required.";
}elseif ($request['password'] !== $request['password_confirmation']){
    $errors['password'] = "Passwords doesn't match.";
}

if(!empty($errors)){
    $_SESSION['errors'] = $errors;
    $_SESSION['old_inputs'] = $request;
    header('Location:register.php');
    die;
}

$result = registerUser($request);


if ($result){
    $_SESSION['user'] = [
        'name' => $request['name'],
        'email' => $request['email']
    ];
    header('Location:index.php');
    die;
}else{
    $_SESSION['errors']['error'] = "Something went wrong please try again";
    $_SESSION['old_inputs'] = $request;
    header('Location:register.php');
    die;
}
