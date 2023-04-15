<?php 
  require_once("dbconfig.php");
  $code="";
  $username="";
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['uname']) && $_POST['uname']!="" && isset($_POST['passwd']) && $_POST['passwd']!="" && isset($_POST['code']) && $_POST['code']!=""){
      $reset_code=$_POST['code'];
      $password=$_POST['passwd'];
      $username=$_POST['uname'];
      $query= "select * from passwdreset where code=?";
      if($stmt = mysqli_prepare($link, $query)){
        mysqli_stmt_bind_param($stmt, "s", $reset_code);
        if(mysqli_stmt_execute($stmt)){
          $resulttt = mysqli_stmt_get_result($stmt);
          if(mysqli_num_rows($resulttt) != 0){
            $query2= "select * from users where username=?";
            if($stmt2= mysqli_prepare($link, $query2)){
              mysqli_stmt_bind_param($stmt2, "s", $username);
              if(mysqli_stmt_execute($stmt2)){
                $resulttt2 = mysqli_stmt_get_result($stmt2);
                if(mysqli_num_rows($resulttt2) == 1){
                  while($rows=mysqli_fetch_assoc($resulttt2)){
                    $userid=$rows['id'];
                  }
                }
              }
            }
            $query3= "update users set password=? where id=?";
            if($stmt3 = mysqli_prepare($link, $query3)){
              mysqli_stmt_bind_param($stmt3, "si", $password,$userid);
              if(mysqli_stmt_execute($stmt3)){
                $resulttt3 = mysqli_stmt_get_result($stmt3);
                echo("Password Changed");
                $query4= "delete from passwdreset where code=?";
                if($stmt4 = mysqli_prepare($link, $query4)){
                  mysqli_stmt_bind_param($stmt4, "s", $reset_code);
                  mysqli_stmt_execute($stmt4);
                }
              }
              else{
                echo("Something went wrong!!");
              }
            }
            else{
              echo("Something went wrong!!");
            }
          }
          else{
            echo("WRONG CODE");
          }
        }
      }
    }
  }
  else if(isset($_GET['code']) and $_GET['code']!="" and isset($_GET['username']) and $_GET['username']!=""){
    $code=$_GET['code'];
    $username=$_GET['username'];
  }
?>
<html><head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head><body style="background-color:#ececec;">
<?php
  require_once('menu.php')
  ?>
  <?php if(isset($_GET['error']) and $_GET['error']!=""){
    $err=$_GET['error'];
    $err=htmlspecialchars($err, ENT_QUOTES);
    echo("<h1>$err</h1>");
  }?>
  <div class="container">
    <div class="row">
      <div class="col-lg-4"></div>
      <div class="col-lg-4" style="background-color:#c1c1c1; height:100%; margin:5% 0%">
        <h1 style="text-align:center">Set New Password</h1>
<form name="login" id="newone" action="reset.php" method="post">
                <input name="uname" type="text" class="form-control" value='<?php echo($username)?>' hidden></input>
                <input name="code" type="text" class="form-control" value='<?php echo($code)?>' hidden></input>

<div class="mb-3 mt-3">
                <label for="passwd" class="form-label">Password:</label>
                <input name="passwd" type="password" class="form-control" required></input>
</div>
<div class="mb-3 mt-3">
                <label for="passwdconfirm" class="form-label">Confirm Password:</label>
                <input name="passwdconfirm" type="password" class="form-control" required></input>
</div>
                <button type="submit" class="btn btn-primary">Reset</button>
        </form></div></div></div>
</body>
</html>