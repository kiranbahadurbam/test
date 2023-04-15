<?php
  require_once('logincheck.php');
  if (!$loggedin){
    header("Location: /login.php");
    die();
  }
  $query = "SELECT * from booksdb";
  $books= array();
 
    if(isset($_GET['search']) && trim($_GET['search']!="") ){
      $search = $_GET["search"];
      $query = "SELECT book, author, bookdescription, target_file FROM booksdb WHERE author LIKE '%$search%' OR book LIKE '%$search%' OR bookdescription LIKE '%$search%'";
    }
  
  $res= mysqli_query($link, $query);
  while($row=mysqli_fetch_array($res)) {
    array_push($books,$row);
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
    
    <div style="background-color:#c1c1c1; height:100%; margin:5% 0%">
      <h1 style="text-align:center">Books</h1>
      
<form name="search" id="booksearch" enctype="multipart/form-data" method="get">
<div class="mb-3 mt-3" align="center">
              <label for="search" class="form-label">Search:</label>
              <input type="text" name="search" class="form-control" style="width: 20%" required></input><br>
              <button type="submit" class="btn btn-primary">Search</button>
</div>
              
      </form>
<br><br>
      <?php 
        foreach($books as $book){
        echo("<div class='col-md-3' style='background-color:#c1c1c1; text-align:center'><img width='50%' src='/images/book.png'><br><h4>".$book['book']."</h4>By: ".$book['author']."</div>");
        }
        ?>
    </div>
    
    </div></div>
</body>
</HTML>
