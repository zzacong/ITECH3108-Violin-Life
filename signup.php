<?php

$name = $username = $email = $password = $location = '';
$errors = ['name' => '', 'username' => '', 'email' => '', 'password' => ''];

if (isset($_POST['submit'])) {
  require('./config/db_connect.php');

  // * Validate name
  if (!empty($_POST['name'])) {
    $name = trim($_POST['name']);
    if (!preg_match('/^[a-zA-Z.\s-]+$/', $name)) {
      $errors['name'] = "Name can contain letters and '-' character only.";
    }
  } else {
    $errors['name'] = "Name is required.";
  }

  // * Validate username
  if (!empty($_POST['username'])) {
    $username = $_POST['username'];
    if (preg_match('/^[a-zA-Z0-9._-]+$/', $username)) {
      $result = mysqli_query($db, "SELECT * FROM `user` WHERE username = '$username';");
      if (mysqli_num_rows($result)) {
        $errors['username'] = "This username has been taken.";
      }
    } else {
      $errors['username'] = "Username can contain letters, numbers and special characters ('-', '_', '.') only.";
    }
  } else {
    $errors['username'] = "Username is required.";
  }

  // * Validate email
  if (!empty($_POST['email'])) {
    $email = trim($_POST['email']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $result = mysqli_query($db, "SELECT * FROM `user` WHERE email = '$email';");
      if (mysqli_num_rows($result)) {
        $errors['email'] = "This email has been taken.";
      }
    } else {
      $errors['email'] = "Email must be a valid email address.";
    }
  } else {
    $errors['email'] = "Email is required.";
  }

  // * Validate password
  if (!empty($_POST['password'])) {
    $password = $_POST['password'];

    if (strlen($password) < 5) {
      $errors['password'] = "Password must have at least 5 characters.";
    }
  } else {
    $errors['password'] = "Password is required.";
  }

  $location = trim($_POST['location']);

  if (!array_filter($errors)) {
    // * Form is valid
    $name = mysqli_real_escape_string($db, $name);
    $username = mysqli_real_escape_string($db, $username);
    $email = mysqli_real_escape_string($db, $email);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedPassword = mysqli_real_escape_string($db, $hashedPassword);
    $location = mysqli_real_escape_string($db, $location);

    $query = "INSERT INTO `user` (name, username, email, password, location) VALUES ('$name', '$username', '$email', '$hashedPassword', '$location');";
    if (mysqli_query($db, $query)) {
      header('Location: index.php');
    } else {
      echo 'query error: ' . mysqli_error($db);
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
    <h1 class="my-3">Sign Up</h1>

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

      <label for="inputName" class="form-label">Full Name: </label>
      <input type="text" name="name" id="inputName" placeholder="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control" required>
      <p class="text-danger"><?php echo $errors['name'] ?></p>

      <label for="inputUsername" class="form-label">Username: </label>
      <input type="text" name="username" id="inputUsername" placeholder="username" value="<?php echo htmlspecialchars($username); ?>" class="form-control" required>
      <p class="text-danger"><?php echo $errors['username'] ?></p>

      <label for="inputEmail" class="form-label">Email: </label>
      <input type="email" name="email" id="inputEmail" placeholder="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" required>
      <p class="text-danger"><?php echo $errors['email'] ?></p>

      <label for="inputPassword" class="form-label">Password: </label>
      <input type="password" name="password" id="inputPassword" placeholder="password" value="<?php echo htmlspecialchars($password); ?>" class="form-control" required>
      <p class="text-danger"><?php echo $errors['password'] ?></p>

      <label for="inputLocation" class="form-label">Location: </label>
      <input type="text" name="location" id="inputLocation" placeholder="location" value="<?php echo htmlspecialchars($location); ?>" class="form-control">

      <input type="submit" name="submit" value="Register" class="btn btn-primary my-4 px-4">

    </form>
  </div>

</body>

</html>