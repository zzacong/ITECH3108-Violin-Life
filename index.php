<?php

session_start();
require_once('includes/auth.php');
require_once('includes/sql.php');
require_once('includes/config.php');
require_once('includes/db_connect.php');
require_once('includes/utils.php');

if (authenticated()) {
  $query = "SELECT name FROM `user` WHERE username = :username";
  $bindings = [':username' => current_user()];
  $stmt = query_execute($db, $query, $bindings);

  $res = $stmt->fetch(PDO::FETCH_ASSOC);
  $name = $res['name'];
}

$query = "SELECT COUNT(*) AS no_of_violin FROM violin";
$stmt = query_execute($db, $query);
$res = $stmt->fetch(PDO::FETCH_ASSOC);
$no_of_violin = $res['no_of_violin'];

$stmt = query_execute($db, $top3_violins_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require('templates/head.php') ?>
</head>

<body>
  <?php require('templates/navbar.php') ?>
  <main class="container">

    <div class="my-4">
      <?php if (authenticated()) :; ?>
        <h1>Hi, <?php echo html($name) ?>.</h1>
      <?php else :; ?>
        <h1>Welcome to Violin Life</h1>
      <?php endif; ?>
    </div>

    <div class="my-5">
      <h3>There <?php echo ($no_of_violin > 1) ? "are $no_of_violin violins" : "is $no_of_violin violin" ?> on the site.</h3>
    </div>

    <section class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">The top 3 most-wanted violins</h4>
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Violin</th>
              <th scope="col">No of offers</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($stmt as $row) :; ?>
              <tr>
                <th scope="row"><?php echo $row['violin_id'] ?></td>
                <td><?php echo html($row['title']); ?></td>
                <td><?php echo $row['no_of_offer']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</body>

</html>