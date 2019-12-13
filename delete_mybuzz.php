<?php
include 'action.php';

$buzz_id = $_GET['buzz_id'];

$User->deleteMyBuzz($buzz_id);

?>