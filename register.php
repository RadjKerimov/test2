<?php
  session_start();
  require('function.php');

  $pdo = new PDO('mysql:host=localhost; dbname=marlin; charset=utf8;', 'root', 'root');
  $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
  $pass = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));




  // проверка: есть ли такой эл. адрес в базе?
  if (get_user_by_email($email, $pdo)['email'] == $email) {
    set_flash_message('danger', 'Этот эл. адрес уже занят другим пользователем.');
    set_flash_message('email', $email);
    redirect_to('page_register.php');
    exit();
  }else{
    add_user($email, $pass, $pdo);
    set_flash_message('success', 'Регистрация успешна');
    set_flash_message('email', $email);
    redirect_to('page_login.php');
  }
 
  
