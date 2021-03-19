<!doctype html>
<html lang="en">
  <head>
    <script>
      function imageClick(url) {
        window.location = url;
      }

      function logout() {
        sessionStorage.clear();
        window.location.href = "../home.html";
      }
    </script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title> Mario Car </title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
        <a class="navbar-brand" href="../home.html">
            <img src="../assets/car-icon.svg" width="25" height="25" class="d-inline-block align-top" alt="">
            Mario Car
        </a>
        
        <div class="collapse navbar-collapse ml-5" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="../home.html">Home <span class="sr-only">(current)</span></a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="../about.html">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../contacts.php">Contacts</a>
            </li>
          </ul>
         <a class="nav-link" style="color:black" onclick="logout()"><img src="../assets/login.svg" width="20" height="20" class="mr-1">Log out</a>
        </div>
      </nav>


      <br>
      <br>


      <div class="card col-8 offset-md-2 my-5">
          <article class="card-body">
            <br>
            <br>
          <!-- <a href="" class="float-right btn btn-outline-secondary">Sign up</a> -->
            <h4 class="card-title mb-4 mt-1">No available vehicle near the location :( </h4>

          
            <br>
            <br>
            <br>
            
            <div class="form-group">
                <a href="myaccount.php" class="btn btn-secondary btn-block" >Return  To  Search  Again</a>
            </div> 
          </article>
      </div>

      <br>
      <br>

      <div class="d-flex bd-highlight" style = "margin:0px 100px 0px 120px;">
        <div class="col-6  col-sm-4" >
          <img src="./user_images/1.png" class="p-2 flex-fill bd-highlight" alt="Responsive image">
        </div>
        <div class="col-6 col-sm-4">
          <img src="./user_images/3.png" class="p-2 flex-fill bd-highlight" alt="Responsive image" >
        </div>
        <div class="col-6 col-sm-4">
          <img src="./user_images/2.png" class="p-2 flex-fill bd-highlight" alt="Responsive image" >
        </div>
      </div>
      <br>
      


      <blockquote class="blockquote text-center mt-5">
        <footer class="blockquote-footer">Mario car live in your local neighborhood, and in cities, campuses and airports across the globe.</footer>
      </blockquote>
      <style>
        hr{
          border: 0;
          height: 1px;
          background-image: linear-gradient(to right, rgba(70,70,70,0), rgba(70,70,70,0.75), rgba(70,70,70,0));
          -webkit-margin-before: 0.6em;
          -webkit-margin-after: 0.6em;
          -webkit-margin-start: auto;
          -webkit-margin-end: auto;
          }
     </style>
      <hr/>
      <blockquote class="mt-5 blockquote text-center" style="font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" >
        <footer class="blockquote-footer mt-5 text-center">Copyright © 2020 Mario Car Ltd .  All Rights Reserved | Created by Qin Wang, Yakun Feng, Chen Zhang, Zhengyu Duan</footer>
      </blockquote>
  </body>
</html>