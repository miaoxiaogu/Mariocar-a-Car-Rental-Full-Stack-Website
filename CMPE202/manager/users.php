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
        
        var status = document.getElementById("modify_status");
        var endpoint = "http://www.zhengyuduan.com/backend/database/users/getAllUsers.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                show_table(data["data"]);
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

      function show_table(data) {
        var body = document.getElementById("table_body");
        var len = data.length;
        var content = "";
        for (var i=0; i<len; i++) {
            content += "<tr>";
            content += "<td style=width:10%;'>" + data[i]["USER_FIRST_NAME"] + "</td>";
            content += "<td style=width:10%;'>" + data[i]["USER_LAST_NAME"] + "</td>";
            content += "<td style=width:10%;'>" + data[i]["USER_EMAIL"] + "</td>";
            content += "<td style=width:5%;'>" + data[i]["MEMBERSHIP_STATUE"] + "</td>";
            if (data[i]["MEMBER_STARTTIME"] == null) {
                content += "<td style=width:10%;'>N/A</td>";
                content += "<td style=width:10%;'>N/A</td>";
            } else {
                content += "<td style=width:10%;'>" + data[i]["MEMBER_STARTTIME"] + "</td>";
                content += "<td style=width:10%;'>" + data[i]["MEMBER_ENDTIME"] + "</td>";
            }
            content += "<td style=width:10%;'><button class='btn-secondary' onclick='modify(\"" + data[i]["USER_EMAIL"] + "\", \"terminate\")'>Terminate</button></td>";
            content += "<td style=width:5%;'><button class='btn-danger' onclick='modify(\"" + data[i]["USER_EMAIL"] + "\", \"delete\")'>Delete</button></td>";
            content += "</tr>";
        }
        body.innerHTML = content;
      }

      function modify(email, operation) {
        console.log("modify");

        var obj = {
            "email": email,
        }
        var status = document.getElementById("modify_status");
        var endpoint = "";
        if (operation == "terminate") {
            endpoint = "http://www.zhengyuduan.com/backend/database/users/terminateMembership.php";
        } else if (operation == "delete") {
            endpoint = "http://www.zhengyuduan.com/backend/database/users/deleteUser.php";
        }
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
                    Prices & Discounts
                  </a>
                </li>
                <li class="nav-item my-3">
                  <a class="nav-link" href="users.php">
                    <b>Users</b>
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
          <h4 style="text-align:center;">All users</h4>
          <div class="container mt-5">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th style="width:10%;">First name</th>
                        <th style="width:10%;">Last name</th>
                        <th style="width:10%;">Email</th>
                        <th style="width:5%;">Membership</th>
                        <th style="width:10%;">Membership starttime</th>
                        <th style="width:10%;">Membership endtime</th>
                        <th style="width:10%;">Terminate Membership</th>
                        <th style="width:5%;">Delete</th>
                    </tr>
                </thead>
            </table>
            <div style="height:250px; overflow:auto;">
                <table class="table table-bordered">
                    <tbody id="table_body">
                    </tbody>
                </table>
            </div>
            </table>
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
        <footer class="blockquote-footer mt-5 text-center">Copyright ?? 2020 Mario Car Ltd .  All Rights Reserved | Created by Qin Wang, Yakun Feng, Chen Zhang, Zhengyu Duan</footer>
      </blockquote> 
    </body>
</html>