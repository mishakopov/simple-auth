<?php

require_once 'mode.php';
connectToDB();
$request = $_POST;

$errors = [];

if (!$request['email']){
    $errors['email'] = "Email is required";
}
if (!$request['password']){
    $errors['password'] = "Password is required";
}

if (!$errors){
    if (userExist($request['email'])){
        $userDetails = getUser($request['email']);
        if (password_verify($request['password'], $userDetails['password'])){
            $_SESSION['user'] = [
                'name' => $userDetails['name'],
                'email' => $userDetails['email'],
            ];
            header('Location:index.php');
            die;
        }else{
            $errors['password'] = "Password doesn't match";
        }
    }else{
        $errors['email'] = "Email doesn't match";
    }
}

if(!empty($errors)){
    $_SESSION['errors'] = $errors;
    $_SESSION['old_inputs'] = $request;
    header('Location:login.php');
    die;
}