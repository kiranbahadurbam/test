<?php
setcookie("username", "", time()-3600);
setcookie("session", "", time()-3600);
setcookie("remember_me", "", time() - 3600);
header("Location: index.php?loggedout");
?>