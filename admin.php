<?php
  require_once('logincheck.php');
  if (!$loggedin){
  header("Location: /login.php");
  die();
  }
  if($privilege !="admin"){
    header("Location: /login.php");
  }
  $uname=$passwd=$email="";


 if($_SERVER["REQUEST_METHOD"] == "POST"){
   if(isset($_POST['uname']) && $_POST['uname']!="" && isset($_POST['passwd']) && $_POST['passwd']!="" && isset($_POST['email']) && $_POST['email']!=""){
      if(strlen($_POST['passwd'])>8){
        $uname=$_POST['uname'];
        $passwd=$_POST['passwd'];
        $email=$_POST['email'];
        $privilege=$_POST['privilege'];
        $verified=1;
        $plain_verification="verify".$uname.strval(rand(100000,999999999));
        $verification_code=md5($plain_verification); 
        if(filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)){
          $query= "insert into users(username,email,password,verification_code,privilege,verified) VALUES (?,?,?,?,?,?)";
          if($stmt = mysqli_prepare($link, $query)){
            mysqli_stmt_bind_param($stmt, "sssssi", $uname,$email,$passwd,$verification_code,$privilege,$verified);
            if(mysqli_stmt_execute($stmt)){
                echo ("<h1>User added</h1>");
              //header("Location: /register.php?error=registered successfully");
            }else{
                echo "ERROR: Username already in use". mysqli_error($link);
                //header('Location: /register.php?error=wrong password');
            }
          }
        }
        else{
            echo "email invalid";
        }
      }
      else{
          echo "password too short";
      }
    }
      else{
        echo("fill all data");
      }
    }
?>
<html><head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head><body style="background-color:#ececec;">
<?php
  require_once('menu.php')
  ?>
  <div class="container">
    <div class="row">
      <div class="col-lg-4"></div>
      <div class="col-lg-4" style="background-color:#c1c1c1; height:100%; margin:5% 0%">
        <h1 style="text-align:center">Add User</h1>
<form name="login" id="newone" action="admin.php" method="post">
<div class="mb-3 mt-3">
                <label for="uname" class="form-label">Username:</label>
                <input name="uname" type="text" class="form-control" required></input>
</div>
<div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input name="email" type="text" class="form-control" required></input>
</div>
<div class="mb-3 mt-3">
                <label for="passwd" class="form-label">Password:</label>
                <input name="passwd" type="password" class="form-control" required></input>
</div>
  <select id="privilege" name="privilege">
    <option value="normal" selected>Normal user</option>
    <option value="librarian">Librarian</option>
    <option value="admin">Admin</option>
</select>
                <button type="submit" class="btn btn-primary">Register</button>
        </form></div></div></div>
      </body>
</html>