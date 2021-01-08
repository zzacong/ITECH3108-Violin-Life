
<?php

function html($string) {
  return htmlentities($string);
}

function query_execute($db, $query, $args = null) {
  $stmt = $db->prepare($query);
  if ($args) {
    foreach ($args as $param => $value) {
      $stmt->bindValue($param, $value);
    }
  }
  $stmt->execute();
  return $stmt;
}

function get_user_id($db, $sql) {
  $stmt = query_execute($db, $sql, [':username' => current_user()]);
  $res = $stmt->fetch();
  return $res['id'];
}

?>