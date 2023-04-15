<?php
 require_once('logincheck.php');
 if ($loggedin){
  header("Location: /index.php");
  die();
 }
 $uname=$passwd="";


 if($_SERVER["REQUEST_METHOD"] == "POST"){
 
 $uname=$_POST['uname'];
 $passwd=$_POST['passwd'];
$verified=0;
 $query= "select * from users where username=? and password=?";
  if($stmt = mysqli_prepare($link, $query)){
    mysqli_stmt_bind_param($stmt, "ss", $uname,$passwd);
    if(mysqli_stmt_execute($stmt)){
	    $resulttt = mysqli_stmt_get_result($stmt);
	    if(mysqli_num_rows($resulttt) == 1){
		    while($rows=mysqli_fetch_assoc($resulttt)){
			    $userid=$rows['id'];
			    $verified=$rows['verified'];
		    }
        if($verified!=1){
          header('Location: /login.php?error=2');
          die();
        }
        $squery= "delete from sessions where uid=?";
        $sstmt = mysqli_prepare($link, $squery);
        mysqli_stmt_bind_param($sstmt, "i", $userid);
        mysqli_stmt_execute($sstmt);

		    $sess_plain=$uname.strval(rand(100000,999999999));
		    //var_dump($resulttt);  
		    $query2="insert into sessions(uid,session) values(?,?)";
        $sess_enc=md5($sess_plain);
		    $stmt2=mysqli_prepare($link, $query2);
		    mysqli_stmt_bind_param($stmt2,"is",$userid,$sess_enc);
        mysqli_stmt_execute($stmt2);
        setcookie("session",$sess_enc, time()+3600);
        if (isset($_POST['remember']) && $_POST['remember']) {
          $remembercookie=$uname.":".md5($userid);
          setcookie("remember_me", base64_encode($remembercookie), time() + (10 * 365 * 24 * 60 * 60));
      } else {
          setcookie("remember_me", false, time() - 3600);
      }
        header("Location: /index.php");
        die();
	    }else{
        header('Location: /login.php?error');
        die();
	     }
    }
  }
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
    if($err=='1'){
      echo("<h1>Wrong username or password</h1>");
    }
    elseif($err=='2'){
      echo("<h1>Please verify your account</h1>");
    }
  }?>
  <div class="container">
    <div class="row">
      <div class="col-lg-4"></div>
      <div class="col-lg-4" style="background-color:#c1c1c1; height:100%; margin:5% 0%">
        <h1 style="text-align:center">Login</h1>
<form name="login" id="newone" action="login.php" method="post">
<div class="mb-3 mt-3">
                <label for="uname" class="form-label">Username:</label>
                <input name="uname" type="text" class="form-control"></input>
</div>
<div class="mb-3 mt-3">
                <label for="passwd" class="form-label">Password:</label>
                <input name="passwd" type="password" class="form-control"></input>
</div>
<div class="mb-3 mt-3">
                <input type="checkbox" id="rememberme" name="remember" value="true">
                <label for="remember"> Remember Me</label><br>
</div>

                <button type="submit" class="btn btn-primary">LOGIN</button>
        </form>
      <div><a href="/forgot_password.php">Forgot Password</a></div>
      </div>
      </div></div>
</body>
</html>
