<!doctype html>
<html lang="en">
  <head>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="../js_resource/constant.js"></script>
    <script>
      $(document).ready(function() {
        if (sessionStorage.getItem("log") != "manager") {
          document.getElementById("manager_access").innerHTML = "<h4 class='pt-5' style='color:red; text-align:center;'>Only manager have access!</h4>";
          return;
        }
        var timepoint2 = document.getElementById("timepoint2");
        var timepoint3 = document.getElementById("timepoint3");
        
        var status = document.getElementById("modify_status");
        var endpoint = "http://www.zhengyuduan.com/backend/database/systemInfo/getData.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                show_prices(data["data"]);
              } else {
                hint(status, "modify_status", data["message"], "fail");
              }
            },
            error: function(e) {
              console.log(e);
              alert("Network Error!");
            }
        });    
      });

      function logout() {
        sessionStorage.clear();
        window.location.href = "../home.html";
      }

      function my_type(str) {
        var timepoint2 = document.getElementById("timepoint2");
        var timepoint3 = document.getElementById("timepoint3");
        if (str == "timepoint2") {
            timepoint3.value = timepoint2.value;
        } else if (str == "timepoint3") {
            timepoint2.value = timepoint3.value;
        }
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

      function show_prices(data) {
        document.getElementById("membership_fee").value = data["MEMBERSHIP_FEE"];
        document.getElementById("timepoint1").value = data["TIMEPOINT_ONE"];
        document.getElementById("discount1").value = data["TIME_RANGE_DISCOUNT_ONE"];
        document.getElementById("timepoint2").value = data["TIMEPOINT_TWO"];
        document.getElementById("timepoint3").value = data["TIMEPOINT_TWO"];
        document.getElementById("discount2").value = data["TIME_RANGE_DISCOUNT_TWO"];
        document.getElementById("discount3").value = data["TIME_RANGE_DISCOUNT_THREE"];
        document.getElementById("over_25").value = data["DISCOUNT_OVER_25"];
      }

      function modify() {
        console.log("modify");
        var membership_fee = document.getElementById("membership_fee").value;
        var timepoint1 = document.getElementById("timepoint1").value;
        var discount1 = document.getElementById("discount1").value;
        var timepoint2 = document.getElementById("timepoint2").value;
        var discount2 = document.getElementById("discount2").value;
        var discount3 = document.getElementById("discount3").value;
        var over_25 = document.getElementById("over_25").value;

        var obj = {
            "newMemberFee": membership_fee,
            "newTimePoint1": timepoint1,
            "newDiscount1": discount1,
            "newTimePoint2": timepoint2,
            "newDiscount2": discount2,
            "newDiscount3": discount3,
            "newOverAge": over_25,
        }
        var status = document.getElementById("modify_status");

        var endpoint = "http://www.zhengyuduan.com/backend/database/systemInfo/update.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                window.location.reload();
              } else {
                hint(status, "modify_status", data["message"], "fail");
              }
            },
            error: function(e) {
              console.log(e);
              alert("Network Error!");
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
        <a class="navbar-brand" href="../home.html">
            <img src="../assets/car-icon.svg" width="25" height="25" class="d-inline-block align-top" alt="">
            Mario Car
        </a>
        <div class="collapse navbar-collapse ml-5" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="../home.html">Home <span class="sr-only"></span></a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="../about.html">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../contacts.php">Contacts</a>
            </li>
          </ul>
          <a class="nav-link" onclick="logout()" style="color:black"><img src="../assets/login.svg" width="20" height="20" class="mr-1">Log out</a>
        </div>
    </nav>
      <div class="row">
        <div class="bg-light px-2" style="width:12%">
          <nav class="d-none ml-3 d-md-block bg-light sidebar">
            <!-- <div class="sidebar-sticky"> -->
              <ul class="navbar-nav ">
                <li class="nav-item  my-3">
                  <a class="nav-link" href="vehicle.php">
                      Vehicles
                  </a>
                </li>
                <li class="nav-item my-3">
                  <a class="nav-link" href="vehicle_type.php">
                      Vehicles Types
                  </a>
                </li>
                <li class="nav-item my-3">
                  <a class="nav-link" href="location.php">
                    Locations
                  </a>
                </li>
                <li class="nav-item my-3">
                  <a class="nav-link" href="price.php">
                    <b>Prices & Discounts</b>
                  </a>
                </li>
                <li class="nav-item my-3">
                  <a class="nav-link" href="users.php">
                    Users
                  </a>
                </li>
                <li class="nav-item my-3">
                  <a class="nav-link" href="orders.php">
                    History order
                  </a>
                </li>
              </ul>
            <!-- </div> -->
          </nav>
        </div>

        <div id="manager_access" class="py-3" style="width:88%">
          <h4 style="text-align:center;">Prices and Discounts</h4>
          <div class="container mt-5">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Membership fee</label>
                        <input id="membership_fee" class="form-control" type="number">
                    </div>
                </div>
                <div class="col"></div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Rent hour less than</label>
                        <input id="timepoint1" class="form-control" type="number">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Rent discount</label>
                        <input id="discount1" class="form-control" type="number">
                    </div>
                </div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Rent hour less than</label>
                        <input id="timepoint2" class="form-control" type="number" oninput="my_type('timepoint2')">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Rent discount</label>
                        <input id="discount2" class="form-control" type="number">
                    </div>
                </div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Rent hour more than</label>
                        <input id="timepoint3" class="form-control" type="number" oninput="my_type('timepoint3')">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Rent discount</label>
                        <input id="discount3" class="form-control" type="number">
                    </div>
                </div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Discount age over 25</label>
                        <input id="over_25" class="form-control" type="number">
                    </div>
                </div>
                <div class="col"></div>
                <div class="col"></div>
            </div>
            <button id="modify" type="submit" class="col-2 offset-5 btn btn-success" onclick="modify()">Modify</button>
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
      <hr/>
      <blockquote class="mt-5 blockquote text-center" style="font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" >
        <footer class="blockquote-footer mt-5 text-center">Copyright © 2020 Mario Car Ltd .  All Rights Reserved | Created by Qin Wang, Yakun Feng, Chen Zhang, Zhengyu Duan</footer>
      </blockquote> 
    </body>
</html>