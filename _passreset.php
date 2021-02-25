<?php
session_start();
require_once  'mode.php';

connectToDB();
$request = $_POST;

$errors = [];
if (!$request['oldpass']){
    $errors['oldpass'] = "Enter your password";
}elseif(!password_verify ($request['oldpass'], getUser($_SESSION['user']['email'])['password'])){
    $errors['oldpass'] = "Old password doesn't equal";
}
if (!$request['newpass']){
    $errors['newpass'] = "Enter new password";
}elseif ($request['newpass'] !== $request['confirmnewpass']){
    $errors['newpass'] = "Passwords doesn't match";
}
if(!empty($errors)){
    $_SESSION['errors'] = $errors;
    $_SESSION['old_inputs'] = $request;
    header('Location:passreset.php');
    die;
}

$result = resetPassword($request);

if ($result){
    $_SESSION['user'] = $request;
    $_SESSION['success'] = 'Your password successfuly was reset';
}else{
    $_SESSION['errors']['error'] = "Something went wrong";
}
header('Location:passreset.php');
die;