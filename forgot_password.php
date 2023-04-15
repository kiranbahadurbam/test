<?php
require_once('logincheck.php');
 if ($loggedin){
  header("Location: /index.php");
  die();
 }
 $uname=$passwd=$email="";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(isset($_POST['email']) && $_POST['email']!="") {
		$email = $_POST['email'];
		if(filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)){
			$query= "select * from users where email=?";
    			if($stmt = mysqli_prepare($link, $query)){
      				mysqli_stmt_bind_param($stmt, "s",$email);
      				if(mysqli_stmt_execute($stmt)){
        				$resulttt = mysqli_stmt_get_result($stmt);
        				if(mysqli_num_rows($resulttt) == 1){
									while($rows=mysqli_fetch_assoc($resulttt)){
						       	$uname=$rows['username'];
						}
					}
				}
			}
			if ($uname!=""){
				$plain_reset="reset".$uname.strval(rand(100000,999999999));
				$reset_code=md5($plain_verification);
				$query2= "insert into passwdreset(username,email,code) VALUES (?,?,?)";
				if($stmt2 = mysqli_prepare($link, $query2)){
					mysqli_stmt_bind_param($stmt2, "sss", $uname,$email,$reset_code);
					if(mysqli_stmt_execute($stmt2)){
							$myfile = fopen("emails.html", "a");
							$text="$email:\n<br> to reset your password visit this link <a href='//localhost/reset.php?code=$reset_code&username=$uname'>localhost/verify.php?code=$reset_code&email=$email</a>\n\n<br><br><p style='text-align:center'>**********</p><br>";
							fwrite($myfile,$text);
							echo ("<h1>Reset Requested Successfully. Check email</h1>");
							echo("emails are currently written to the location <a href='/emails.html'>emails</a>");
						//header("Location: /register.php?error=registered successfully");
					}
				}
			}
     	echo "Thank you for requesting a password reset. Please check your email for further instructions.";
		}
	}
}
?>
<html><head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet
" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="a
nonymous">
</head><body style="background-color:#ececec;">
<?php
  require_once('menu.php')
  ?>
 <div class="container">
    <div class="row">
      <div class="col-lg-4"></div>
      <div class="col-lg-4" style="background-color:#c1c1c1; height:100%; margin:5% 0%">
        <h1 style="text-align:center">Forgot Password</h1>
    <form method="post">
	<div class="mb-3 mt-3">
	<label for="email" class="form-label">Email</label>
                <input name="email" type="text" class="form-control"></input>
	</div>
        <button type="submit" class="btn btn-primary">Reset</button>
    </form>
</div></div></div></div></body></html>


