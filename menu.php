<div style="text-align:right" align="right">
    <?php
    if ($loggedin){
      echo('<a href="index.php" style="padding:30px; font-size:1.5rem">Main</a>');
      echo('<a href="request.php" style="padding:30px; font-size:1.5rem">RequestABook</a>');
      echo('<a href="manage.php" style="padding:30px; font-size:1.5rem">ManageLibrary</a>');
      echo('<a href="logout.php" style="padding:30px; font-size:1.5rem">Logout</a>');
      if($privilege =="admin"){
      echo('<a href="admin.php" style="padding:30px; font-size:1.5rem">Admin</a>');
      }
    } else{
      echo('<a href="login.php" style="padding:30px; font-size:1.5rem">Login</a>');
      echo('<a href="register.php" style="padding:30px; font-size:1.5rem">Register</a>');
    }
    ?>
</div>