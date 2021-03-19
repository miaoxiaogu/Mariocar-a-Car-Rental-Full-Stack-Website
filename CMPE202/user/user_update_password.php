<!doctype html>
<html lang="en">
  <head>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
    <script>
    var user_email = "";
    var user_id = "";
    $(document).ready(function() {
        console.log("ready");
        if (sessionStorage.getItem("log") != "user" || sessionStorage.getItem("email") == null) {
          document.getElementById("user_access").innerHTML = "<h4 class='pt-5' style='color:red; text-align:center;'>Only log in user have access!</h4>";
          return;
        }
        user_email = sessionStorage.getItem("email");
        
    });

    function logout() {
        sessionStorage.clear();
        window.location.href = "../home.html";
    }

    function back() {
        window.location.href = "myaccount.php";
    }
 
    function imageClick(url) {
        window.location = url;
    }

    function hint(element, id, text, status) {
        if (status == "success") {
          element.innerHTML = text;
          element.style.display = "block";
          $("#" + id).fadeOut(2000);
        } else if (status == "fail") {
          element.innerHTML = text;
          element.style.color = "red";
          element.style.display = "block";
          $("#" + id).fadeOut(2000);
        }
    }

    function ajax_call(endpoint, obj, element, id, redirect) {
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                window.location.href = redirect;
              } else {
                alert(data["message"]);
                hint(element, id, data["message"], "fail");
              }
            },
            error: function(e) {
              console.log(e);
              alert("Network Error!");
            }
        });   
    }

    function updatePassword() {
        var old_password = document.getElementById("old_password").value;
        var new_password = document.getElementById("new_password").value;
        var new_password_confirm = document.getElementById("new_password_confirm").value;

        var element = document.getElementById("modify_status");

        if (new_password != new_password_confirm) {
            hint(element, "modify_status", "Please confirm your password!", "fail");
            return;
        }
        var obj = {
            "email": user_email,
            "oldPass": old_password,
            "newPass": new_password,
        };
        console.log(JSON.stringify(obj));
        ajax_call("http://www.zhengyuduan.com/backend/database/users/changePass.php", obj, element, "modify_status", "myaccount.php");
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
    <div id="user_access">
    <div class="row px-5 pt-3">
        <button id="submit" type="submit" class="btn btn-secondary btn-light" onclick="back()">Back</button>
    </div>
    <div class="row">
        <div class="col-6 offset-3 pl-5">
                <div class="card col-12 my-5 pb-3">
                    <h5 class="card-title mb-4 mt-1">User Info</h5>
                    <div class="form-group">
                        <label>Old password</label>
                        <input id="old_password" type="password" class="form-control">
                    </div> 
                    <div class="form-group">
                        <label>New password</label>
                        <input id="new_password" type="password" class="form-control">
                    </div> 
                    <div class="form-group">
                        <label>Confirm new password</label>
                        <input id="new_password_confirm" type="password" class="form-control">
                    </div>
                    <button id="submit" type="submit" class="btn btn-secondary btn-block" onclick="updatePassword()">Update</button>
                    <p style="color:green; text-align:center; display:none;" id="modify_status">Fake text</p>
                </div>
        </div>
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
    </body>
</html>