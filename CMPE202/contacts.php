<!doctype html>
<html lang="en">
  <head>
    <script>
      function imageClick(url) {
        window.location = url;
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
        <a class="navbar-brand" href="home.html">
            <img src="./assets/car-icon.svg" width="25" height="25" class="d-inline-block align-top" alt="">
            Mario Car
        </a>
        
        <div class="collapse navbar-collapse ml-5" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="home.html">Home <span class="sr-only">(current)</span></a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="about.html">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contacts.php">Contacts</a>
            </li>
          </ul>
          <a class="nav-link" href="login.php" style="color:black"><img src="./assets/login.svg" width="20" height="20" class="mr-1">Login</a>
        </div>
      </nav>

    <div class="row justify-content-md-center" style="font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif">
      <p class="h4  ml-5 pt-1 text-muted">Contact Mario Car<br></p>
    </div>
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
    <div class="row justify-content-md-center" style="font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif">
      <p class="h6 ml-4 pt-1 text-muted" style="text-align: center;">Need more help? Contacts us!</p>
    </div>

    <?php $contacts = fopen("./assets/Contacts.txt","r") or die("Unable to open file!");
            $tel = fgets($contacts)."<br>";
            $time = fgets($contacts);
            $email = fgets($contacts);
            $i = 0;
            while(!feof($contacts)){
              $address[$i] = fgets($contacts);
              $i++;
            }
            fclose($contacts);
    ?>

    <div class="row" >
      <div class="col-4 offset-md-2 text-muted py-5" style="font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif">
        <div class="row my-3">
          <div class="col-2">
            <img src="./assets/icon-telephone.svg" width="50" height="50">
          </div>
           <div class="col-10 text-muted test-lg">
            <p>
            <?php 
            echo $tel;
            echo $time;
            ?>
            </p>
          </div>
        </div>

        <div class="row my-3">
          <div class="col-2">
            <img src="./assets/icon-email.svg" width="50" height="50">
          </div>
          <div class="col-10">
             <p>
            <?php 
            echo $email;
            ?>
            </p>
          </div>
        </div>

        <div class="row my-3">
          <div class="col-2">
            <img src="./assets/icon-address.svg" width="50" height="50">
          </div>
          <div class="col-10">
            <p>
              <?php 
              foreach($address as $x){
                echo $x."<br>";
              }
              ?>
            </p>
            <p>
              <?php 
              
              ?>
            </p>
          </div>
        </div>
      </div>

      <div class="col-4 py-5">
        <img src="./assets/contact.jpg" class="img-fluid mx-auto" alt="Responsive image">
      </div>
    </div>
    <style>
        hr{
          border: 0;
          height: 1px;
          background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
          }
     </style>
      <hr class="mt-5"/>
      <blockquote class="blockquote text-center" style="font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" >
        <footer class="blockquote-footer text-center">Copyright © 2020 Nordic House Ltd .  All Rights Reserved | Created by Qin Wang |SID : 013986752 </footer>
      </blockquote>
      

      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>