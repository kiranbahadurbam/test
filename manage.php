<?php
  require_once('logincheck.php');
  if (!$loggedin){
  header("Location: /login.php");
  die();
  }
  if($privilege =="normal"){
    require_once('menu.php');
    echo("You are not an Admin or Librarian");
    die();
  }
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['bookname']) && trim($_POST['bookname'])!="" && isset($_POST['author']) && trim($_POST['author'])!=""){
      $target_dir = "books/";
      $target_file = $target_dir . basename($_FILES["file"]["name"]);
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
        $book=trim($_POST['bookname']);
        $author=trim($_POST['author']);
        $description=$_POST['description'];
        $query= "insert into booksdb (`book`,`author`,`bookdescription`,`target_file`) values (?,?,?,?)";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $book, $author, $description, $target_file);
        mysqli_stmt_execute($stmt);
        echo("Book added");
      }
    }
  }
  $requests=array();
  $query= "select * from book_requests";
  $stmt = mysqli_prepare($link, $query);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($rows=mysqli_fetch_assoc($result)){
    array_push($requests,$rows);
  }
?>
<html><head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head><body style="background-color:#ececec;">
<?php
  require_once('menu.php')
  ?>
  <div class="container">
    <div style="text-align:center">
     <h1>Hello Librarian</h1></div>
    <div class="row">
      <div class="col-lg-4" style="background-color:#c1c1c1; height:100%; margin-top:5%">
      <h3 style="margin:5% 0">Add Books</h3>
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3 mt-3">
          <label for="bookname" class="form-label">Book:</label>
          <input name="bookname" type="text" class="form-control" required></input>
        </div>
        <div class="mb-3 mt-3">
          <label for="author" class="form-label">Author:</label>
          <input name="author" type="text" class="form-control" required></input>
        </div>
        <div class="mb-3 mt-3">
          <label for="description" class="form-label">Description:</label>
          <input name="description" type="text" class="form-control" required></input>
        </div>
        <div class="mb-3 mt-3">
          <label for="file" class="form-label">Attach book:</label>
          <input type="file" class="form-control" name="file" id="file" required>
        </div>
    <button type="submit" class="btn btn-primary">Add book</button>
  </form></div>

  <div class="col-lg-1"></div>
  <div class="col-lg-6" style="background-color:#c1c1c1;  height:500px;margin-top:5%;overflow-y:scroll">
  <?php 
        foreach($requests as $request){
        echo("<div style='background-color:#fff'>Requested by:".$request['user']."<br>Book: ".$request['book']."<br>Author: ".$request['author']."</div><hr><hr>");
        }
        ?>
  </div>
      </div>
      </div>
      </body>
</html>