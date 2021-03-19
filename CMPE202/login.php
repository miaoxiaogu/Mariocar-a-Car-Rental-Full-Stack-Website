<!doctype html>
<html lang="en">
  <head>
    <script>
      function imageClick(url) {
        window.location = url;
      }

      function submit() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var obj = {
          "email": email,
          "password": password,
        }
        console.log(obj);

        var endpoint = "http://www.zhengyuduan.com/backend/database/users/login.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                if (data["role"] == "user") {
                  sessionStorage.setItem("log", data["role"]);
                  sessionStorage.setItem("email", email);
                  window.location.href = "user/myaccount.php";
                } else if (data["role"] == "manager") {
                  sessionStorage.setItem("log", data["role"]);
                  sessionStorage.setItem("email", email);
                  window.location.href = "manager/vehicle.php";
                }
              } else {
                alert(data["message"]);
              }
            },
            error: function(error) {
              console.log(error);
              alert("Network error!");
            }
        });
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
    <div class="card col-4 offset-md-4 my-5">
        <article class="card-body">
        <!-- <a href="" class="float-right btn btn-outline-secondary">Sign up</a> -->
        <h4 class="card-title mb-4 mt-1">Sign in</h4>
            <!-- <form action="" method="post"> -->
                <div class="form-group">
                    <label>Your Email</label>
                    <input id="email" name="email" class="form-control" placeholder="Email">
                </div> 
                <div class="form-group">
                    <!-- <a class="float-right" href="#">Forgot?</a> -->
                    <label>Your password</label>
                    <input id="password" type="password" name="password" class="form-control" placeholder="Password" type="password">
                </div> 
                <div class="form-group">
                    <button id="submit" type="submit" class="btn btn-secondary btn-block" onclick="submit()"> Login </button>
                </div>                                                          
            <!-- </form> -->
        </article>
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
      <hr class="mt-5"/>
      <blockquote class="mt-5 blockquote text-center" style="font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" >
        <footer class="blockquote-footer mt-5 text-center">Copyright © 2020 Nordic House Ltd .  All Rights Reserved | Created by Qin Wang |SID : 013986752 </footer>
      </blockquote>

      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>