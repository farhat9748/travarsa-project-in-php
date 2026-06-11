<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>


<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">About</a>
        </li>

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Services</a>
        </li>

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Contact</a>
        </li>
        
        
        
      </ul>
      <?php
      if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
          echo '<a href="/travarsa/pages/logout.php" class="btn btn-sm btn-danger">Logout</a>';
      } else {
          echo '<a class="btn btn-sm btn-success m-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Login</a>  <a class="btn btn-sm m-2 btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>';
      }
        ?>
    </div>
  </div>
</nav>





