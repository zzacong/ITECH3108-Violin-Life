<?php

require_once('includes/auth.php')

?>

<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a href="index.php" class="navbar-brand ms-2">Violin Life</a>
    <div class="d-flex flex-row justify-content-end">
      <?php if (is_logged_in()) :; ?>
        <a href="#" class="nav-link"><?php echo logged_in_user() ?></a>
        <form action="logout.php" method="POST" class="nav-item">
          <button class="nav-link btn btn-outline-primary" name="logout">Log out</button>
        </form>
      <?php else :; ?>
        <a href="login.php" class="nav-link">Log In</a>
        <a href="signup.php" class="nav-link btn btn-outline-primary">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</nav>