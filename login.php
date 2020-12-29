<?php

session_start();
require_once('includes/config.php');
require_once('includes/db_connect.php');
require_once('includes/auth.php');

$usernameOrEmail = $password = '';
$errors = ['usernameOrEmail' => '', 'password' => ''];

if (isset($_POST['submit'])) {
  // * Validate username or email
  if (!empty($_POST['usernameOrEmail'])) {
    $usernameOrEmail = $_POST['usernameOrEmail'];

    $method = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL) ? "email" : "username";
    $query = "SELECT username, password FROM `user` WHERE $method = :usernameOrEmail";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':usernameOrEmail', $usernameOrEmail);
    $stmt->execute();

    if (!$user = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $errors['usernameOrEmail'] = "This username or email does not exist.";
    }
  } else {
    $errors['usernameOrEmail'] = "Please enter your username or email to log in.";
  }

  // * Validate password
  if (!empty($_POST['password'])) {
    $password = $_POST['password'];
  } else {
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

      <label for="inputUsernameOrEmail" class="form-label">Username or Email: </label>
      <input type="text" name="usernameOrEmail" id="inputUsernameOrEmail" placeholder="username or email" value="<?php echo htmlspecialchars($usernameOrEmail); ?>" class="form-control" required>
      <p class="text-danger"><?php echo $errors['usernameOrEmail'] ?></p>

      <label for="inputPassword" class="form-label">Password: </label>
      <input type="password" name="password" id="inputPassword" placeholder="password" value="<?php echo htmlspecialchars($password); ?>" class="form-control" required>
      <p class="text-danger"><?php echo $errors['password'] ?></p>

      <input type="submit" name="submit" value="Log In" class="btn btn-primary my-4 px-4">

    </form>
  </main>

</body>

</html>