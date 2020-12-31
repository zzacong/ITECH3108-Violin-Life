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
    if (!$stmt->rowCount()) {
      print_r($stmt->errorInfo());
    }
  }
}

$message_group = [];

$stmt = query_execute($db, $get_exchanges_sql, [':current_user' => current_user()]);
if ($res = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
  $has_offers = true;
  foreach ($res as $row) {
    // $group = $row['owner_username'] === current_user() ? 'owned' : 'others';
    $message_group[] = $row;
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
    <h1 class="my-3">Messages</h1>
    <?php if ($has_offers) :; ?>
      <?php foreach ($message_group as $exchange) :; ?>
        <section class="card exchange my-4">
          <div class="card-header">
            <?php $owned = $exchange['owner_username'] === current_user(); ?>
            <h5 class="card-title py-2">
              Violin: <?php echo html($exchange['violin_title']); ?>
              <span class="badge bg-success ms-3"><?php echo $owned ? 'owned' : '' ?></span>
            </h5>
            <div class="row text-muted my-2">
              <div class="col-12 col-md-6">
                <p class="mb-1 card-text">Owned by:
                  <?php echo html($exchange['owner_name'] . ' (' . $exchange['owner_username'] . ') '); ?>
                </p>
              </div>
              <div class="col">
                <p class="mb-1 card-text">Offered by:
                  <?php echo html($exchange['offerer_name'] . ' (' . $exchange['offerer_username'] . ') '); ?>
                </p>
              </div>
            </div>
            <h6 class="card-subtitle my-2">Seeking: <?php echo html($exchange['seeking']); ?></h6>
            <h6 class="card-subtitle my-2">Offer: <?php echo html($exchange['offer']); ?></h6>
          </div>
          <div class="card-body">
            <?php
            $bindings = [':offer_id' => $exchange['offer_id']];
            $stmt = query_execute($db, $get_messages_sql, $bindings);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php if ($res) :; ?>
              <div class="d-flex justify-content-between mb-3">
                <span class="border-bottom border-secondary border-3 rounded-bottom px-3 px-md-4">
                  <?php echo html($owned ? $exchange['offerer_name'] : $exchange['owner_name']); ?>
                </span>
                <span class="border-bottom border-primary border-3 rounded-bottom px-3 px-md-4">
                  You
                </span>
              </div>
              <?php foreach ($res as $msg) :; ?>
                <?php $is_me = $msg['from_username'] === current_user(); ?>
                <div class="row justify-content-<?php echo $is_me ? 'end' : 'start'; ?> mb-3">
                  <?php if (!$is_me) : ?>
                    <div class="col-2 col-sm-1 px-0 pt-2 text-center">
                      <span class="rounded-circle text-light bg-secondary text-center p-2">
                        <?php
                        if (preg_match('/([\w-]+)\s([\w-]+)/', $msg['from_name'], $matches)) {
                          $first_letter = substr($matches[1], 0, 1);
                          $second_letter = substr($matches[2], 0, 1);
                          echo html(strtoupper($first_letter . $second_letter));
                        } else {
                          echo html(strtoupper(substr($msg['from_name'], 0, 2)));
                        }
                        ?>
                      </span>
                    </div>
                  <?php endif; ?>
                  <div class="col-9 col-md-8 px-1 px-md-3 d-flex flex-column align-items-<?php echo $is_me ? 'end' : 'start'; ?>">
                    <span class="py-2 px-4 rounded <?php echo $is_me ? 'bg-primary text-light text-end' : 'bg-light'; ?>">
                      <?php echo html($msg['text']); ?>
                    </span>
                    <span class="text-muted fst-italic mt-2"><?php echo html($msg['sent']); ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else :; ?>
              <p class="text-center text-muted fst-italic">Start messaging each other!</p>
            <?php endif; ?>
          </div>
          <div class="card-footer">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
              <input type="hidden" name="offer_id" value="<?php echo html($exchange['offer_id']) ?>">
              <input type="hidden" name="to_user" value="<?php echo html($exchange['owner_id']) ?>">
              <div class="row">
                <div class="col-12 col-md-10">
                  <input type="text" name="message" maxlength="65535" class="form-control">
                </div>
                <div class="col d-flex mt-2 mt-md-0">
                  <button name="send_message" class="flex-fill btn btn-primary">Send</button>
                </div>
              </div>
            </form>
          </div>
        </section>
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