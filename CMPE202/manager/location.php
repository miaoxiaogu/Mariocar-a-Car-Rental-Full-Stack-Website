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
        var endpoint = "http://www.zhengyuduan.com/backend/database/location/getAllLocations.php";
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
            content += "<td style=width:20%;'><input id='name_" + data[i]["LOCATION_ID"]+ "' value='" + data[i]["LOCATION_NAME"]+ "'></td>";
            content += "<td style=width:20%;'>" + data[i]["LOCATION_STREET"] + "</td>";
            content += "<td style=width:10%;'>" + data[i]["LOCATION_CITY"] + "</td>";
            content += "<td style=width:5%;'>" + data[i]["LOCATION_STATE"] + "</td>";
            content += "<td style=width:5%;'>" + data[i]["LOCATION_ZIPCODE"] + "</td>";
            content += "<td style=width:20%;'><input type='number' id='capacity_" + data[i]["LOCATION_ID"] + "' value='" + data[i]["LOCATION_CAPACITY"] + "'></td>";
            content += "<td style=width:10%;'><button class='btn-success' onclick='modify(\"" + data[i]["LOCATION_ID"] + "\", \"modify\")'>Modify</button></td>";
            content += "<td style=width:10%;'><button class='btn-danger' onclick='modify(\"" + data[i]["LOCATION_ID"] + "\", \"delete\")'>Delete</button></td>";
            content += "</tr>";
        }
        body.innerHTML = content;
      }

      function modify(id, operation) {
        console.log("modify");
        var location_name = document.getElementById("name_" + id).value;
        var location_capacity = document.getElementById("capacity_" + id).value;

        var obj = {
            "locationID": id,
            "newName": location_name,
            "newCapacity": location_capacity
        }
        var status = document.getElementById("modify_status");
        var endpoint = "";
        if (operation == "modify") {
            endpoint = "http://www.zhengyuduan.com//backend/database/location/updateLocation.php";
        } else if (operation == "delete") {
            endpoint = "http://www.zhengyuduan.com/backend/database/location/deleteLocation.php";
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

      function add() {
        var location_name = document.getElementById("location_name").value;
        var location_street = document.getElementById("location_street").value;
        var location_city = document.getElementById("location_city").value;
        var location_state = document.getElementById("location_state").value;
        var location_zipcode = document.getElementById("location_zipcode").value;
        var location_capacity = document.getElementById("location_capacity").value;

        var status = document.getElementById("add_status");
        var obj = {
            "name": location_name,
            "locationStreet": location_street,
            "locationCity": location_city,
            "locationState": location_state,
            "locationZip": location_zipcode,
            "capacity": location_capacity,
        }
        var endpoint = "http://www.zhengyuduan.com/backend/database/location/addNew.php";
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
                hint(status, "add_status", data["message"], "fail");
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
                      <b>Locations</b>
                  </a>
                </li>
                <li class="nav-item my-3">
                  <a class="nav-link" href="price.php">
                    Prices & Discounts
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
          <h4 style="text-align:center;">All locations</h4>
          <div class="container mt-5">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th style="width:20%;">Name</th>
                        <th style="width:20%;">Street</th>
                        <th style="width:10%;">City</th>
                        <th style="width:5%;">State</th>
                        <th style="width:5%;">Zipcode</th>
                        <th style="width:20%;">Capacity</th>
                        <th style="width:10%;">Modify</th>
                        <th style="width:10%;">Delete</th>
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

          <hr class="my-5"/>

          <h4 style="text-align:center;">Add a location</h4>
          <div class="container mt-5">
            <div class="row">
              <div class="col">
                  <div class="form-group">
                    <label>Location name</label>
                    <input id="location_name" class="form-control" placeholder="Location name">
                  </div>
              </div>
              <div class="col">
                  <div class="form-group">
                    <label>Location street</label>
                    <input id="location_street" class="form-control" placeholder="Location street">
                  </div>
              </div>
              <div class="col">
                 <div class="form-group">
                    <label>Location city</label>
                    <input id="location_city" class="form-control" placeholder="Location city">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                  <div class="form-group">
                    <label>Location state</label>
                    <select id="location_state" class="form-control">
                            <option value="" selected="selected">Select a State</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District Of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                    </select>
                  </div>
              </div>
              <div class="col">
                  <div class="form-group">
                    <label>Location zipcode</label>
                    <input id="location_zipcode" class="form-control" placeholder="Location zipcode">
                  </div>
              </div>
              <div class="col">
                 <div class="form-group">
                    <label>Location capacity</label>
                    <input id="location_capacity" type="number" class="form-control" placeholder="Location capacity">
                  </div>
              </div>
            </div>
            <button id="add" type="submit" class="col-2 offset-5 btn btn-success" onclick="add()">Add</button>
            <p style="color:green; text-align:center; display:none;" id="add_status">Fake text</p>
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