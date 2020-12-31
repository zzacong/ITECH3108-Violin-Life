<?php

session_start();
require_once('includes/config.php');
require_once('includes/sql.php');
require_once('includes/db_connect.php');
require_once('includes/auth.php');
require_once('includes/utils.php');

$name = $username = $email = $password = $location = '';
$errors = ['name' => '', 'username' => '', 'email' => '', 'password' => ''];

if (isset($_POST['submit'])) {
  $name = trim($_POST['name']) ?? '';
  $username = $_POST['username'] ?? '';
  $email = trim($_POST['email']) ?? '';
  $password = $_POST['password'] ?? '';
  $location = trim($_POST['location']) ?? '';

  // * Validate name
  if ($name) {
    if (!preg_match('/^[a-zA-Z.\s-]+$/', $name)) {
      $errors['name'] = "Name can contain letters and '-' character only.";
    }
  } else {
    $errors['name'] = "Name is required.";
  }

  // * Validate username
  if ($username) {
    $username = $_POST['username'];
    if (preg_match('/^[a-zA-Z0-9._-]+$/', $username)) {
      $query = "SELECT * FROM `user` WHERE username = :username";
      $bindings = [':username' => $username];
      $stmt = query_execute($db, $query, $bindings);

      if ($stmt->fetch()) {
        $errors['username'] = "This username has been taken.";
      }
    } else {
      $errors['username'] = "Username can contain letters, numbers and special characters ('-', '_', '.') only.";
    }
  } else {
    $errors['username'] = "Username is required.";
  }

  // * Validate email
  if ($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $query = "SELECT * FROM `user` WHERE email = :email";
      $bindings = [':email' => $email];
      $stmt = query_execute($db, $query, $bindings);

      if ($stmt->fetch()) {
        $errors['email'] = "This email has been taken.";
      }
    } else {
      $errors['email'] = "Email must be a valid email address.";
    }
  } else {
    $errors['email'] = "Email is required.";
  }

  // * Validate password
  if ($password) {
    $password = $_POST['password'];

    if (strlen($password) < 5) {
      $errors['password'] = "Password must have at least 5 characters.";
    }
  } else {
    $errors['password'] = "Password is required.";
  }

  if (!array_filter($errors)) {
    // * Form is valid

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $bindings = [
      ':name' => $name,
      ':username' => $username,
      ':email' => $email,
      ':hashed_password' => $hashed_password,
      ':location' => $location
    ];
    $stmt = query_execute($db, $sign_up_sql, $bindings);

    if ($stmt->rowCount()) {
      login($username);
      header('Location: index.php');
    } else {
      print_r($stmt->errorInfo());
      $errors['general'] = 'Sign up failed. Please try again later.';
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require('templates/head.php') ?>
</head>

<body>
  <?php require('templates/navbar.php') ?>

  <main class="container w-75">
    <h1 class="my-3">Sign Up</h1>

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

      <label for="inputName" class="form-label">Full Name: </label>
      <input type="text" name="name" id="inputName" placeholder="name" maxlength="255" value="<?php echo html($name); ?>" class="form-control">
      <p class="text-danger"><?php echo $errors['name'] ?></p>

      <label for="inputUsername" class="form-label">Username: </label>
      <input type="text" name="username" id="inputUsername" placeholder="username" maxlength="255" value="<?php echo html($username); ?>" class="form-control">
      <p class="text-danger"><?php echo $errors['username'] ?></p>

      <label for="inputEmail" class="form-label">Email: </label>
      <input type="email" name="email" id="inputEmail" placeholder="email" maxlength="255" value="<?php echo html($email); ?>" class="form-control">
      <p class="text-danger"><?php echo $errors['email'] ?></p>

      <label for="input_password" class="form-label">Password: </label>
      <input type="password" name="password" id="input_password" placeholder="password" maxlength="255" value="<?php echo html($password); ?>" class="form-control">
      <p class="text-danger"><?php echo $errors['password'] ?></p>

      <label for="inputLocation" class="form-label">Location: </label>
      <input type="text" name="location" id="inputLocation" placeholder="location" maxlength="255" value="<?php echo html($location); ?>" class="form-control">

      <button name="submit" class="btn btn-primary my-4 px-4">Register</button>
      <span class="mx-2">Already a user? <a href="login.php">Log in here.</a></span>

    </form>
  </main>

</body>

</html>