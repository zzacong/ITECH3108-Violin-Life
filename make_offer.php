<?php

session_start();
require_once('includes/config.php');
require_once('includes/sql.php');
require_once('includes/auth.php');
require_once('includes/utils.php');
require_once('includes/db_connect.php');

auth_redirect();

if (!isset($_POST['violin_id'])) :

  header('Location: offers.php');
  exit();

else :

  $offer = '';
  $errors = ['offer' => '', 'general' => ''];
  $violin_id = $_POST['violin_id'];

  $stmt = query_execute($db, $violin_details_sql, [':violin_id' => $violin_id]);
  if (!$res = $stmt->fetch(PDO::FETCH_ASSOC)) {
    header('Location: offers.php');
    exit();
  }

  if (isset($_POST['submit'])) {
    $offer = $_POST['offer'] ?? '';

    if (!$offer) {
      $errors['offer'] = 'You must describe your offer.';
    }

    $query = "
    SELECT
      accepted
    FROM
      offer
    WHERE
        violin_id = :violin_id
      AND
        accepted IS NOT NULL
    ";
    $stmt = query_execute($db, $query, [':violin_id' => $violin_id]);
    if ($stmt->fetch()) {
      $errors['general'] = 'Cannot make offer on this violin. This violin already has an accepted offer.';
    }

    if (!array_filter($errors)) {
      $stmt = query_execute($db, $get_user_id_sql, [':username' => current_user()]);
      $res = $stmt->fetch();
      $user_id = $res['id'];

      $bindings = [
        ':user_id' => $user_id,
        ':violin_id' => $violin_id,
        ':offer' => $offer
      ];
      $stmt = query_execute($db, $make_offer_sql, $bindings);

      if ($stmt->rowCount()) {
        header('Location: offers.php');
        exit();
      } else {
        print_r($stmt->errorInfo());
        $errors['general'] = 'Make offer failed. Please try again later.';
      };
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
      <h1 class="my-3">Make an Offer</h1>

      <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="mt-5">

        <h4>Violin: <?php echo html($res['title']); ?> </h4>
        <p class="text-muted"><?php echo html($res['seeking']); ?></p>
        <p><?php echo html($res['description']); ?></p>

        <label for="input_offer" class="form-label">Offer: </label>
        <textarea name="offer" id="input_offer" placeholder="what are you willing to offer?" value="<?php echo html($offer); ?>" class="form-control"></textarea>
        <p class="text-danger"><?php echo $errors['offer'] ?></p>
        <p class="text-danger"><?php echo $errors['general'] ?></p>

        <input type="hidden" name="violin_id" value="<?php echo html($violin_id); ?>">
        <button name="submit" class="btn btn-primary my-4 px-4">Submit Offer</button>

      </form>
      </div>
  </body>

  </html>

<?php endif; ?>