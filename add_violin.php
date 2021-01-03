<?php

session_start();
require_once('includes/auth.php');
require_once('includes/sql.php');
require_once('includes/config.php');
require_once('includes/db_connect.php');
require_once('includes/utils.php');

auth_redirect();

$title = $description = $seeking = '';
$errors = ['title' => '', 'description' => '', 'seeking' => ''];

if (isset($_POST['submit'])) {
  $title = $_POST['title'] ?? '';
  $description = $_POST['description'] ?? '';
  $seeking = $_POST['seeking'] ?? '';

  if (!$title) {
    $errors['title'] = 'Please provide a title.';
  }

  if (!$description) {
    $errors['description'] = 'Please provide a description.';
  }

  if (!$seeking) {
    $errors['seeking'] = 'Please describe what are you willing to accept in exchange for the violin';
  }

  if (!array_filter($errors)) {
    $query = "
    INSERT INTO violin (
      user_id, title, description, seeking
    )
    VALUES (
      :user_id, :title, :description, :seeking
    )
    ";

    $user_id = get_user_id($db, $get_user_id_sql);

    $bindings = [
      ':user_id' => $user_id,
      ':title' => $title,
      ':description' => $description,
      ':seeking' => $seeking
    ];

    $stmt = query_execute($db, $query, $bindings);
    if ($stmt->rowCount()) {
      header('Location: offers.php');
      exit();
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
    <h1 class="my-3">Add a Violin</h1>

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

      <label for="input_title" class="form-label">Violin title: </label>
      <input type="text" name="title" id="input_title" placeholder="violin title" maxlength="255" value="<?php echo html($title); ?>" class="form-control" autofocus>
      <p class="text-danger"><?php echo $errors['title'] ?></p>

      <label for="input_description" class="form-label">Description: </label>
      <textarea name="description" id="input_description" value="<?php echo html($description) ?>" maxlength="65535" placeholder="promote your violin" class="form-control"></textarea>
      <p class="text-danger"><?php echo $errors['description'] ?></p>

      <label for="input_seeking" class="form-label">Seeking: </label>
      <textarea name="seeking" id="input_seeking" value="<?php echo html($description) ?>" maxlength="65535" placeholder="what are you willing to accept in exchange for the violin?" class="form-control"></textarea>
      <p class="text-danger"><?php echo $errors['seeking'] ?></p>

      <button name="submit" class="btn btn-primary my-4 px-4">Add violin</button>

    </form>
  </main>

</body>

</html>