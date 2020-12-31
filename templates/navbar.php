<?php

require_once('includes/auth.php');
require_once('includes/utils.php');

?>

<nav class="navbar navbar-expand-md navbar-light bg-light">
  <div class="container-fluid">
    <!-- <div class="d-flex ps-4"> -->
    <a href="index.php" class="navbar-brand">Violin Life</a>
    <?php if (authenticated()) :; ?>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="offers.php" class="nav-link text-primary">View Offers</a>
          </li>
          <li class="nav-item">
            <a href="add_violin.php" class="nav-link text-primary">Add Violin</a>
          </li>
          <li class="nav-item">
            <a href="message.php" class="nav-link text-primary">Messages</a>
          </li>
        </ul>
        <span class="navbar-text d-none d-md-block me-4"><?php echo html(current_user()) ?></span>
        <form action="logout.php" method="post">
          <button class="nav-link btn btn-sm btn-outline-primary" name="logout">Log out</button>
        </form>
      </div>
    <?php else :; ?>
      <div class="d-flex">
        <a href="login.php" class="nav-link">Log In</a>
        <a href="signup.php" class="nav-link btn btn-sm btn-outline-primary">Sign Up</a>
      </div>
    <?php endif; ?>
  </div>
</nav>