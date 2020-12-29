<?php

session_start();
require_once('includes/auth.php');
require_once('includes/config.php');
require_once('includes/db_connect.php');
require_once('includes/utils.php');

if (authenticated()) {
  $query = "SELECT name FROM `user` WHERE username = :username";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':username', current_user());
  $stmt->execute();

  $res = $stmt->fetch(PDO::FETCH_ASSOC);
  $name = $res['name'];
}

$query = "SELECT COUNT(*) AS no_of_violin FROM violin";
$stmt = $db->query($query);
$res = $stmt->fetch(PDO::FETCH_ASSOC);
$no_of_violin = $res['no_of_violin'];

$query = "SELECT 
            of.violin_id, vi.title, COUNT(*) AS no_of_offer 
          FROM 
            offer of
          JOIN 
            violin vi 
          ON 
            of.violin_id = vi.id
          GROUP BY of.violin_id, vi.title
          ORDER BY no_of_offer DESC";
$stmt = $db->query($query);


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

    <div>
      <h4 class="mb-3">Top 3 most-wanted violins</h4>
      <table class="table table-striped w-75">
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
  </main>
</body>

</html>