<?php 
  require_once("dbconfig.php");
  if(isset($_GET['code']) and $_GET['code']!="" and isset($_GET['email']) and $_GET['email']!=""){
    $verification_code=$_GET['code'];
    $email=$_GET['email'];
    echo($verification_code);
    $query= "select * from users where verification_code=? and email=?";
    if($stmt = mysqli_prepare($link, $query)){
      mysqli_stmt_bind_param($stmt, "ss", $verification_code,$email);
      if(mysqli_stmt_execute($stmt)){
        $resulttt = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($resulttt) == 1){
          while($rows=mysqli_fetch_assoc($resulttt)){
            $userid=$rows['id'];
            $verified=$rows['verified'];
          }
          echo($userid);
          if($verified===1){
            header('Location: /login.php?error=Previously Verified');
            die();
          }
          else{
            $query2="update users set verified=1 where id=$userid";
            if(mysqli_query($link,$query2)){
              header('Location: /login.php?error=Verified Successfully');
              die();
            }
          }
        }
        else{
          header('Location: /login.php?error=Verification Failed');
        }
      }
    }
  }
?>
