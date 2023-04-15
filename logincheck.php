<?php
require_once('dbconfig.php');
if(isset($_COOKIE["session"]) and $_COOKIE["session"]!=""){
  $squery= "select * from sessions where session=?";
  $sstmt = mysqli_prepare($link, $squery);
    mysqli_stmt_bind_param($sstmt, "s", $_COOKIE['session']);
   mysqli_stmt_execute($sstmt);

      $sresulttt = mysqli_stmt_get_result($sstmt);
      if(mysqli_num_rows($sresulttt) == 1){
        while($rows=mysqli_fetch_assoc($sresulttt)){
          $suid=$rows['uid'];
        }

   $uquery= "select * from users where id=?";
  $ustmt = mysqli_prepare($link, $uquery);
    mysqli_stmt_bind_param($ustmt, "i", $suid);
   mysqli_stmt_execute($ustmt);

      $uresulttt = mysqli_stmt_get_result($ustmt);
        while($rows=mysqli_fetch_assoc($uresulttt)){
          $username=$rows['username'];
          $privilege=$rows['privilege'];
        }
    $loggedin=true;
    $message="you are loggedin";
    }
    else{

      header("Location: /logout.php");
      die();
    }
  }else if(isset($_COOKIE["remember_me"]) and $_COOKIE["remember_me"]!=""){
        echo("aaaa");
        $decoded_cookie=base64_decode($_COOKIE["remember_me"]);
        $runame=explode(":",$decoded_cookie)[0];
        $hash_value=explode(":",$decoded_cookie)[1];
        $rquery= "select * from users where username=?";
        $rstmt = mysqli_prepare($link, $rquery);
        mysqli_stmt_bind_param($rstmt, "s", $runame);
        mysqli_stmt_execute($rstmt);
        $rresulttt = mysqli_stmt_get_result($rstmt);
        if(mysqli_num_rows($rresulttt) == 1){
          while($rows=mysqli_fetch_assoc($rresulttt)){
                  $ruid=$rows['id'];
                  $rverified=$rows['verified'];
          }
          if(md5($ruid)==$hash_value){
                  if($rverified!=1){
                          setcookie("remember_me", "", time() - 3600);
                          header('Location: /login.php?error=2');
                          die();
                  }
                  $lquery= "delete from sessions where uid=?";
                  $lstmt = mysqli_prepare($link, $lquery);
                  mysqli_stmt_bind_param($lstmt, "i", $ruid);
                  mysqli_stmt_execute($lstmt);
                  $sess_plain=$uname.strval(rand(100000,999999999));
                  $query2="insert into sessions(uid,session) values(?,?)";
                  $sess_enc=md5($sess_plain);
                  $stmt2=mysqli_prepare($link, $query2);
                  mysqli_stmt_bind_param($stmt2,"is",$ruid,$sess_enc);
                  mysqli_stmt_execute($stmt2);
                  setcookie("session",$sess_enc, time()+3600);
                  echo("success");
                  $loggedin=true;
                  $message="you are loggedin";
          }
          else{
                  header("Location: /logout.php");
                  die();    
          }
      }
        else{
      header("Location: /logout.php");
      die();    
        }
      }else{
    $loggedin=false;
  }
?>