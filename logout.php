
<?php

session_start();
require_once('includes/auth.php');

if (isset($_POST['logout'])) {
  logout();
}

header('Location: index.php');
exit();

?>