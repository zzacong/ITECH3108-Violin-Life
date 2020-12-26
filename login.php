<?php


$usernameOrEmail = $password = '';
$errors = ['usernameOrEmail' => '', 'password' => ''];

if (isset($_POST['submit'])) {
  require('./config/db_connect.php');

  // * Validate username or email
  if (!empty($_POST['usernameOrEmail'])) {
    $usernameOrEmail = $_POST['usernameOrEmail'];

    $method = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL) ? "email" : "username";
    $query = "SELECT * FROM `user` WHERE $method = '$usernameOrEmail';";
    $result = mysqli_query($db, $query);
    if (!mysqli_num_rows($result)) {
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
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
      // Succesfully logged in
      header('Location: index.php');
    } else {
      $errors['password'] = "Invalid password";
    }
  } else {
    echo "Form has errors";
  }

  mysqli_free_result($result);
  mysqli_close($db);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require('./templates/head.php') ?>
</head>

<body>
  <?php require('./templates/navbar.php') ?>

  <div class="container w-75">
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
  </div>

</body>

</html>