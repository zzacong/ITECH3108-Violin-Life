<?php

session_start();
require_once('includes/config.php');
require_once('includes/db_connect.php');
require_once('includes/auth.php');
require_once('includes/utils.php');

$username_or_email = $password = '';
$errors = ['username_or_email' => '', 'password' => ''];

if (isset($_POST['submit'])) {
  $username_or_email = $_POST['username_or_email'] ?? '';
  $password = $_POST['password'] ?? '';

  // * Validate username or email
  if ($username_or_email) {
    $method = filter_var($username_or_email, FILTER_VALIDATE_EMAIL) ? "email" : "username";

    $query = "SELECT username, password FROM `user` WHERE $method = :username_or_email";
    $bindings = [':username_or_email' => $username_or_email];
    $stmt = query_execute($db, $query, $bindings);

    if (!$user = $stmt->fetch()) {
      $errors['username_or_email'] = "This username or email does not exist.";
    }
  } else {
    $errors['username_or_email'] = "Please enter your username or email to log in.";
  }

  // * Validate password
  if (!$password) {
    $errors['password'] = "Please enter your password to log in.";
  }

  if (!array_filter($errors)) {
    // * Form is valid, Log In
    if (password_verify($password, $user['password'])) {
      // Succesfully logged in
      login($user['username']);
      header('Location: index.php');
    } else {
      $errors['password'] = "Invalid login. Please try again.";
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
    <h1 class="my-3">Log In</h1>

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

      <label for="input_username_or_email" class="form-label">Username or Email: </label>
      <input type="text" name="username_or_email" id="input_username_or_email" placeholder="username or email" maxlength="255" value="<?php echo html($username_or_email); ?>" class="form-control">
      <p class="text-danger"><?php echo $errors['username_or_email'] ?></p>

      <label for="input_password" class="form-label">Password: </label>
      <input type="password" name="password" id="input_password" placeholder="password" maxlength="255" value="<?php echo html($password); ?>" class="form-control">
      <p class="text-danger"><?php echo $errors['password'] ?></p>

      <button name="submit" class="btn btn-primary my-4 px-4">Log In</button>
      <span class="mx-2">Not a user? <a href="signup.php">Sign up here.</a></span>

    </form>
  </main>

</body>

</html>