<?php

session_start();
require_once('includes/config.php');
require_once('includes/sql.php');
require_once('includes/auth.php');
require_once('includes/utils.php');
require_once('includes/db_connect.php');

auth_redirect();

$errors = ['general' => ''];

if (isset($_POST['accept_offer'])) {
  $violin_id = $_POST['violin_id'] ?? '';
  $offer_id = $_POST['offer_id'] ?? '';

  if (!$offer_id || !$violin_id) {
    $errors['general'] = 'No offer/violin id';
  }

  // * Check violin's owner
  $stmt = query_execute($db, $get_owner_sql, [':violin_id' => $violin_id]);
  $res = $stmt->fetch();
  $owner_username = $res['username'];
  if ($owner_username !== current_user()) {
    $errors['general'] = "This violin doesn't belong to you.";
  }

  // * Check if offer has already been accepted 
  // * (might occur due to refreshing page which causes form resubmission)
  $query = "SELECT accepted FROM offer WHERE id = :offer_id";
  $stmt = query_execute($db, $query, [':offer_id' => $offer_id]);
  $res = $stmt->fetch();

  if (!array_filter($errors) && !$res['accepted']) {
    $query = "UPDATE offer SET accepted = :now WHERE id = :offer_id";
    $mysql_timestamp = date('Y-m-d H:i:s', time());
    $bindings = [
      ':now' => $mysql_timestamp,
      ':offer_id' => $offer_id
    ];
    $stmt = query_execute($db, $query, $bindings);
    if ($stmt->rowCount()) {
    } else {
      print_r($stmt->errorInfo());
    }
  }
}

$violins = array();
$titles = array();
$counter = -1;
$stmt = query_execute($db, $view_offers_sql);
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($res as $row) {
  $violin_title = $row['title'];
  if (!array_key_exists($violin_title, $titles)) {
    $counter++;
    $titles[$violin_title] = $violin_title;
    $violin = [
      'violin_id' => $row['violin_id'],
      'title' => $violin_title,
      'owner_name' => $row['owner_name'],
      'owner_username' => $row['owner_username'],
      'seeking' => $row['seeking'],
      'description' => $row['description'],
      'submitted' => $row['submitted'],
      'accepted' => false
    ];
    $violins[] = $violin;
  }
  $offer = [
    'offer_id' => $row['offer_id'],
    'offerer_name' => $row['offerer_name'],
    'offerer_username' => $row['offerer_username'],
    'offer' => $row['offer'],
    'offered' => $row['offered'],
    'chosen' => false
  ];
  if ($row['accepted']) {
    $violins[$counter]['accepted'] = true;
    $offer['chosen'] = true;
  }
  if ($row['offer_id']) {
    $violins[$counter]['offers'][] = $offer;
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
  <main class="container">
    <?php foreach ($violins as $violin) :; ?>
      <?php $accepted = $violin['accepted'] ? true : false; ?>

      <div class="card my-4 <?php echo $accepted ? 'accepted' : ''; ?>">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-7 mb-3">
              <?php $owned = $violin['owner_username'] === current_user(); ?>
              <h4 class="card-title"><?php echo html($violin['title']); ?></h4>
              <div>
                <span class="badge bg-success"><?php echo $owned ? 'owned' : '' ?></span>
                <span class="badge bg-secondary"><?php echo $accepted ? 'unavailable' : '' ?></span>
              </div>
            </div>
            <div class="col">
              <p class="text-primary text-md-end mb-1">Owned by: <?php echo html($violin['owner_name']) . ' (' . html($violin['owner_username']) . ') '; ?></p>
              <p class="text-muted text-md-end">Submitted at: <?php echo html($violin['submitted']); ?></p>
            </div>
          </div>
          <p class="card-subtitle text-secondary fw-bold mt-md-2"><?php echo html($violin['seeking']); ?></p>
          <p class="card-text"><?php echo html($violin['description']); ?></p>
        </div>
        <div class="card-body">
          <ul class="list-group">
            <?php if (isset($violin['offers'])) :; ?>
              <?php foreach ($violin['offers'] as $offer) :; ?>
                <li class="list-group-item <?php echo ($offer['chosen']) ? 'chosen' : ''; ?>">
                  <div class="row align-items-start">
                    <div class="col-12 col-md-6">
                      <p class="fw-bold mb-1">Offered by: <?php echo html($offer['offerer_name']) . ' (' . html($offer['offerer_username']) . ') '; ?></p>
                    </div>
                    <div class="col">
                      <p class="text-muted text-md-end">Offered at: <?php echo html($offer['offered']) ?></p>
                    </div>
                  </div>
                  <p class="card-text"><?php echo html($offer['offer']); ?></p>
                  <div class="d-flex justify-content-end">
                    <?php if (!$accepted && $violin['owner_username'] === current_user()) : ?>
                      <?php if ($errors['general']) : ?>
                        <span class="badge bg-danger"><?php echo $errors['general']; ?></span>
                      <?php else : ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                          <input type="hidden" name="violin_id" value="<?php echo html($violin['violin_id']); ?>">
                          <input type="hidden" name="offer_id" value="<?php echo html($offer['offer_id']); ?>">
                          <button name="accept_offer" class="btn btn-sm btn-success">Accept</button>
                        </form>
                      <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($offer['chosen']) :; ?>
                      <span class="badge bg-secondary">Accepted</span>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endforeach; ?>
            <?php else : ?>
              <li class="list-group-item text-muted fst-italic">No offer</li>
            <?php endif; ?>
          </ul>
        </div>
        <?php if (!$accepted && $violin['owner_username'] !== current_user()) :; ?>
          <form action="make_offer.php" method="post" class="card-body align-self-end">
            <button name="violin_id" value="<?php echo html($violin['violin_id']) ?>" class="btn btn-primary">Make an Offer</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </main>
</body>

</html>