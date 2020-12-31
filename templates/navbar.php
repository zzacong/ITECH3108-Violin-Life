<?php

require_once('includes/auth.php');
require_once('includes/utils.php');

?>

<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <div class="d-flex ps-4">
      <a href="index.php" class="navbar-brand">Violin Life</a>
      <?php if (authenticated()) :; ?>
        <a href="offers.php" class="nav-link">View Offers</a>
        <a href="add_violin.php" class="nav-link">Add Violin</a>
        <a href="message.php" class="nav-link">Messages</a>
      <?php endif; ?>
    </div>
    <div class="d-flex flex-row pe-4">
      <?php if (authenticated()) :; ?>
        <span class="nav-link text-dark"><?php echo html(current_user()) ?></span>
        <form action="logout.php" method="post" class="nav-item">
          <button class="nav-link btn btn-outline-primary" name="logout">Log out</button>
        </form>
      <?php else :; ?>
        <a href="login.php" class="nav-link">Log In</a>
        <a href="signup.php" class="nav-link btn btn-outline-primary">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</nav>