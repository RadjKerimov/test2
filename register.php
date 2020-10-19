<?php
  session_start();

  $pdo = new PDO('mysql:host=localhost; dbname=marlin; charset=utf8;', 'root', 'root');
  $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
  $pass = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));

  //Проверяет сушествует ли такой пользыватель 
  function get_user_by_email ($email, $pdo){
    $prepare = $pdo->prepare("SELECT `email` FROM `users` WHERE `email` = :email");
    $prepare->execute(['email' => $email]);
    return $prepare->fetch(PDO::FETCH_ASSOC);
  };

  // Добовляет пользывателя
  function add_user($email, $pass, $pdo){
    $hash = 'dg5e6rpoet';
    $pass = md5($hash . $pass);
    $prepare = $pdo->prepare('INSERT INTO users(email, pass) VALUES (:email, :pass)');
    $prepare->execute(['email' => $email,'pass' => $pass]);
    return $pdo->lastInsertId();
  };

 
  //Подготовка сообщения 
  function set_flash_message($name, $message){
    $_SESSION[$name] = $message;
  };

  //function display_flash_message($name){ };

  function redirect_to($path){
    header("Location: $path");
  };


  // проверка: есть ли такой эл. адрес в базе?
  if (get_user_by_email($email, $pdo)['email'] == $email) {
    set_flash_message('error_email', '<div class="alert alert-danger text-dark" role="alert"><strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.</div>');
    set_flash_message('email', $email);
    redirect_to('page_register.php');
    exit();
  }else{
    add_user($email, $pass, $pdo);
    set_flash_message('ok_register', '<div class="alert alert-success">Регистрация успешна</div>');
    set_flash_message('email', $email);
    redirect_to('page_login.php');
  }
 
  
