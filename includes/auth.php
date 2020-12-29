
<?php

function login($username) {
  $_SESSION['username'] = $username;
}

function logout() {
  unset($_SESSION['username']);
}

function is_logged_in() {
  return isset($_SESSION['username']);
}

function logged_in_user() {
  return $_SESSION['username'];
}

function require_login() {
  if (!is_logged_in()) {
    header('Location: login.php');
    exit();
  }
}

?>