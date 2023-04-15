<?php
  require_once('logincheck.php');
  if (!$loggedin){
    header("Location: /login.php");
    die();
  }
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['book']) && trim($_POST['book']!="") && isset($_POST['author']) && trim($_POST['author']!="") ){
        $book=trim($_POST['book']);
        $author=trim($_POST['author']);
        $query= "insert into book_requests (`user`,`book`,`author`) values (?,?,?)";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $book, $author);
        mysqli_stmt_execute($stmt);
      }
    }
  $requests=array();
  $query= "select * from book_requests where user=?";
  $stmt = mysqli_prepare($link, $query);
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($rows=mysqli_fetch_assoc($result)){
    array_push($requests,$rows);
  }
?>
<HTML>
  <head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>
  <body>
  <?php
  require_once('menu.php');
  ?>
  <div class="container">
  <div class="row">
    
    <div class="col-lg-4" style="background-color:#c1c1c1; height:100%; margin:5% 0%">
      <h1 style="text-align:center">Request a book</h1>
<form name="login" id="newone" enctype="multipart/form-data" method="post">
<div class="mb-3 mt-3">
              <label for="book" class="form-label">Bookname:</label>
              <input type="text" name="book" rows="3" class="form-control" required>
</div>
<div class="mb-3 mt-3">
              <label for="author" class="form-label">Author:</label>
              <input type="text" class="form-control" name="author" id="author" required>
</div>
              <button type="submit" class="btn btn-primary">Request</button>
      </form></div>
      <div class="col-lg-1"></div>
      <div class="col-lg-6" style="background-color:#c1c1c1;  height:500px;margin-top:5%;overflow-y:scroll">
      <h1 style="text-align:center">Your Requests</h1>
  <?php 
        foreach($requests as $request){
          
        echo("<div><br>Book: ".$request['book']."<br>Author: ".$request['author']."</div><hr><hr>");
        }
        ?>
  </div>
    </div></div>
</body>
</HTML>
