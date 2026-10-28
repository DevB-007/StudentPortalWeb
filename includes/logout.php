<?php
session_start();
session_unset();
session_destroy();
header("Location: ../pages/login.html?status=logged_out");
exit();
?>