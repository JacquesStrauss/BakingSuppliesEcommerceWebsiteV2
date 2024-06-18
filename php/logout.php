
<?php
session_start();
session_destroy();
// Set the cookie's expiration date to the past to delete it
setcookie('isLoggedIn', '', time() - 3600, '/');
header('Location: ../index.html');
exit;
?>
