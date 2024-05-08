<?php
session_start();
$_SESSION = array(); // Destroy all session data
session_destroy(); // Destroy the session itself
header("Location: ../index.php");
exit;
