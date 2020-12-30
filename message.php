<?php

session_start();
require_once('includes/config.php');
require_once('includes/sql.php');
require_once('includes/auth.php');
require_once('includes/utils.php');
require_once('includes/db_connect.php');

auth_redirect();

if (isset($_POST['send_message'])) {
  $offer_id = $_POST['offer_id'] ?? '';
  $to_user = $_POST['to_user'] ?? '';
  $message = $_POST['message'] ?? '';

  if ($message) {
    $stmt = query_execute($db, $get_user_id_sql, [':username' => current_user()]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $from_user = $res['id'];
    $bindings = [
      ':from_user' => $from_user,
      ':to_user' => $to_user,
      ':offer_id' => $offer_id,
      ':text' => $message
    ];
    $stmt = query_execute($db, $send_message_sql, $bindings);
    if ($stmt->rowCount()) {
      echo 'sent';
    } else {
      echo 'send failed';
      print_r($stmt->errorInfo());
    }
  }
}

$message_group = ['owned' => [], 'others' => []];

$stmt = query_execute($db, $get_exchanges_sql, [':current_user' => current_user()]);
if ($res = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
  $has_offers = true;
  foreach ($res as $row) {
    $group = $row['owner_username'] === current_user() ? 'owned' : 'others';
    $message_group[$group][] = $row;
  }
} else {
  $has_offers = false;
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
    <?php if ($has_offers) :; ?>
      <section>
        <h3>Your Violin</h3>
        <div>
          <?php print_r($message_group['owned']) ?>
        </div>
      </section>
      <section>
        <h3>Others</h3>
        <div>
          <?php foreach ($message_group['others'] as $chat) :; ?>
            <div>
              <p>Violin: <?php echo html($chat['violin_title']); ?></p>
              <p>Offered by: <?php echo html($chat['offerer_name']); ?></p>
              <p>Offered by: <?php echo html($chat['offerer_username']); ?></p>
              <p>Owner: <?php echo html($chat['owner_name']); ?></p>
              <p>Owner: <?php echo html($chat['owner_username']); ?></p>
              <p>Offer: <?php echo html($chat['offer']); ?></p>
              <div>
                <?php
                $bindings = [':offer_id' => $chat['offer_id']];
                $stmt = query_execute($db, $get_messages_sql, $bindings);
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($res as $msg) :; ?>
                  <div>
                    <p>From: <?php echo html($msg['from_name']); ?></p>
                    <p>To: <?php echo html($msg['to_name']); ?></p>
                    <p>Message: <?php echo html($msg['text']); ?></p>
                  </div>
                <?php endforeach; ?>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                  <input type="hidden" name="offer_id" value="<?php echo html($chat['offer_id']) ?>">
                  <input type="hidden" name="to_user" value="<?php echo html($chat['owner_id']) ?>">
                  <input type="text" name="message">
                  <button name="send_message" class="btn btn-primary">Send</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php else :; ?>
      <section>
        No Messages
      </section>
    <?php endif; ?>
  </main>
</body>

</html>