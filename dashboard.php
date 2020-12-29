<?php

session_start();
require_once('includes/config.php');
require_once('includes/sql.php');
require_once('includes/auth.php');
require_once('includes/utils.php');
require_once('includes/db_connect.php');

auth_redirect();

?>

<?php

$stmt = $db->query($view_sql);

$violins = array();
$titles = array();
$counter = -1;
foreach ($stmt as $row) {
  $violin_title = $row['title'];
  if (!array_key_exists($violin_title, $titles)) {
    $counter++;
    $titles[$violin_title] = $violin_title;
    $violin = array('id' => $row['id'], 'title' => $violin_title, 'owner_name' => $row['owner_name'], 'owner_username' => $row['owner_username'], 'seeking' => $row['seeking'], 'description' => $row['description'], 'submitted' => $row['submitted'], 'accepted' => false);
    $violins[] = $violin;
  }
  $offer = array('offerer_name' => $row['offerer_name'], 'offerer_username' => $row['offerer_username'], 'offer' => $row['offer'], 'chosen' => false);
  if ($row['accepted']) {
    $violins[$counter]['accepted'] = true;
    $offer['chosen'] = true;
  }
  $violins[$counter]['offers'][] = $offer;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require('templates/head.php') ?>
</head>

<body>
  <?php require('templates/navbar.php') ?>
  <main class="container">
    <?php foreach ($violins as $violin) :; ?>
      <div class="card w-75 my-4 <?php if ($violin['accepted']) {
                                    echo "accepted";
                                  };  ?>">
        <div class="card-body">
          <div class="row">
            <h4 class="col card-title"><?php echo html($violin['title']); ?></h4>
            <span class="col text-primary text-end">Owner: <?php echo html($violin['owner_name']) . ' (' . html($violin['owner_username']) . ') '; ?></span>
          </div>
          <div class="row align-items-center">
            <h6 class="col card-subtitle text-secondary"><?php echo html($violin['seeking']); ?></h6>
            <p class="col text-muted text-end">Submitted at: <?php echo html($violin['submitted']); ?></p>
          </div>
          <p class="card-text mt-3"><?php echo html($violin['description']); ?></p>
        </div>
        <div class="card-body">
          <ul class="list-group">
            <?php foreach ($violin['offers'] as $offer) :; ?>
              <li class="list-group-item <?php if ($offer['chosen']) {
                                            echo "chosen";
                                          };  ?>">
                <p class="fw-bold">Offered by: <?php echo html($offer['offerer_name']) . ' (' . html($offer['offerer_username']) . ') '; ?></p>
                <p class="fs-6 text-muted"><?php echo html($offer['offer']); ?></p>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php if (!$violin['accepted']) :; ?>
          <form action="make_offer.php" method="post" class="card-body align-self-end">
            <button name="violin_id" value="<?php echo $violin['id'] ?>" class="btn btn-primary">Make Offer</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </main>
</body>

</html>