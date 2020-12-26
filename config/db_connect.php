<?php
$db = mysqli_connect('localhost', 'admin', 'password', 'itech3108_30360914_a1');

if (!$db) {
  echo 'Connection error: ' . mysqli_connect_error();
}
