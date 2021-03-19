<!doctype html>
<html lang="en">
  <head>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
      function Price(data) {
          var trHTML = "<h4 class=\"mx-auto\" style = \"width:380px;\">You need to pay $";   
          trHTML += data["price"];
          trHTML += "&nbsp;for this trip</h4>";

          
          console.log(trHTML);
          $('#price').html(trHTML);
      }

    

      function sendPriceRequest() {
        var obj = {
          "orderId": sessionStorage.getItem("current_order_id"),
        }

        var endpoint = "http://www.zhengyuduan.com/backend/database/orders/getPrice.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              if (data["status"]) {
                Price(data);
              } else {
                alert(data["message"]);
              }
            },
            error: function(e) {
              alert("Network error");
            }
        });
      }

      function sendCommentRequest() {
        var obj = {
          "order_id": sessionStorage.getItem("current_order_id"),
          "condition": document.getElementById("car_state").value,
          "comments": document.getElementById("comments").value
        }
        console.log(obj);

        var endpoint = "http://www.zhengyuduan.com/backend/database/orders/feedBack.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                alert("succeed");
                window.location.href = "./myaccount.php";
              } else {
                  alert(data["message"]);
              }
            },
            error: function(e) {
                alert("Network error!");
            }
        });
      }
  

      let login_status = sessionStorage.getItem("log");
      if (login_status === 'user') {
        sendPriceRequest();
      }
      
      // test
      /*$(document).ready(function() {
        var fakeResponse = {
          "status": "success",
          "price": 90,
        }
        
        console.log(fakeResponse);
        Price(fakeResponse);
      });*/

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
            <!-- <li class="nav-item">
                <a class="nav-link" href="cars.php">Cars</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="../contacts.php">Contacts</a>
            </li>
          </ul>
          <a class="nav-link" style="color:black" onclick="logout()"><img src="../assets/login.svg" width="20" height="20" class="mr-1">Log out</a>
        </div>
      </nav>
    <div class="card col-8 offset-md-2 my-5">
        <article class="card-body">
        <!-- <a href="" class="float-right btn btn-outline-secondary">Sign up</a> -->
        <div id="price">
        </div>
        
        <br>
        </br>

          <div class="form-group">
              <button id="submit" type="submit" class="btn btn-secondary btn-block " onclick="window.location.href='./myaccount.php'"> Start My Next Trip! </button>
          </div> 

        <br>
        </br>

        <div>
            <label>Car Condition</label>
            <select id="car_state" class="form-control">
              <option value="" selected="selected">Select a condition</option>
                              <option value="Excellent">Excellent</option>
                              <option value="Good">Good</option>
                              <option value="Fair">Fair</option>
                              <option value="Poor">Poor</option>
                              <option value="Bad">Bad</option>
                             
                             
            </select>

            <br>
            </br>

            <label>Leave Your Comments</label>
            <input type="text" id="comments" style="font-size:10pt;height:100px;width:900px;"></input>
            <br>
            </br>
            
            <div class="form-group text-center">
              <button id="comment_submit" type="submit" class="btn btn-secondary" onclick="sendCommentRequest()"> Submit </button>
            </div>

        </article>
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
        <footer class="blockquote-footer mt-5 text-center">Copyright © 2020 Mario Car Ltd .  All Rights Reserved | Created by Qin Wang, Yakun Feng, Chen Zhang, Zhengyu Duan </footer>
      </blockquote>
  </body>
</html>