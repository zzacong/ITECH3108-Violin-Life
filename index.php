<?php

session_start();
require_once('includes/auth.php');
require_once('includes/config.php');
require_once('includes/db_connect.php');
require_once('includes/utils.php');

if (is_logged_in()) {
  $query = "SELECT name FROM `user` WHERE username = :username";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':username', logged_in_user());
  $stmt->execute();

  $res = $stmt->fetch(PDO::FETCH_ASSOC);
  $name = $res['name'];
}

$violin_number = 1;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require('templates/head.php') ?>
</head>

<body>
  <?php require('templates/navbar.php') ?>
  <main class="container">
    <div class="my-2">
      <?php if (is_logged_in()) :; ?>
        <h1>Hi, <?php echo html($name) ?>.</h1>
      <?php else :; ?>
        <h1>Welcome to Violin Life</h1>
      <?php endif; ?>
    </div>

    <div class="my-5">
      <h3>There <?php echo ($violin_number > 1) ? "are $violin_number violins" : "is $violin_number violin" ?> on the site.</h3>
    </div>
    <table class="table table-striped w-75">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Violin</th>
          <th scope="col">No of offers</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</td>
          <td>Apple</td>
          <td>10</td>
        </tr>
        <tr>
          <th scope="row">2</td>
          <td>Orange</td>
          <td>20</td>
        </tr>

        <!-- <?php foreach ($stmt as $i => $row) :; ?>
          <tr>
            <th scope="row"><?php echo $i ?></td>
            <td><?php echo html($row['title']); ?></td>
            <td><?php echo $no_of_offer; ?></td>
          </tr>
        <?php endforeach; ?> -->
      </tbody>
    </table>
  </main>
</body>

</html>