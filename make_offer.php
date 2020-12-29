<?php

session_start();
require_once('includes/config.php');
require_once('includes/sql.php');
require_once('includes/auth.php');
require_once('includes/utils.php');
require_once('includes/db_connect.php');

auth_redirect();

if (!isset($_POST['violin_id'])) {
  header('Location: dashboard.php');
  exit();
}

$violin_id = $_POST['violin_id'];
$offer = '';
$errors = ['offer' => ''];

if (isset($_POST['submit'])) {
  $offer = $_POST['offer'] ?? '';

  if (!$offer) {
    $errors['offer'] = 'You must describe your offer.';
  }

  if (!array_filter($errors)) {
    $query = "SELECT id FROM `user` WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->execute([':username' => current_user()]);
    $res = $stmt->fetch();
    $user_id = $res['id'];

    $query = $make_offer_sql;
    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':violin_id', $violin_id);
    $stmt->bindValue(':offer', $offer);

    if ($stmt->execute()) {
      header('Location: dashboard.php');
      exit();
    };
  }
}

$query = "SELECT title FROM violin WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindValue(':id', $violin_id);
$stmt->execute();
$res = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require('templates/head.php') ?>
</head>

<body>
  <?php require('templates/navbar.php') ?>
  <main class="container">
    <h1 class="my-3">Make an Offer</h1>

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

      <h5>Offer for <?php echo html($res['title']); ?> </h5>
      <label for="input_offer" class="form-label">Offer: </label>
      <textarea name="offer" id="input_offer" placeholder="what are you willing to offer?" value="<?php echo html($offer); ?>" class="form-control"></textarea>
      <p class="text-danger"><?php echo $errors['offer'] ?></p>

      <input type="hidden" name="violin_id" value="<?php echo $violin_id ?>">

      <input type="submit" name="submit" value="Submit Offer" class="btn btn-primary my-4 px-4">

    </form>
    </div>
</body>

</html>