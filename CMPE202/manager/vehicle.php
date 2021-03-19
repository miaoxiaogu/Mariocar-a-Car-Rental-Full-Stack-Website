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
      var all_locations = [];
      var all_types = [];
      $(document).ready(function() {
          if (sessionStorage.getItem("log") != "manager") {
            document.getElementById("manager_access").innerHTML = "<h4 class='pt-5' style='color:red; text-align:center;'>Only manager have access!</h4>";
            return;
          }
          brand_selector = document.getElementById("brand");
          model_selector = document.getElementById("model");
          year_selector = document.getElementById("year");
          for (var brand in vehicle_list) {
            brand_selector.options[brand_selector.options.length] = new Option(brand, brand);
          }

          brand_selector.onchange = function() {
            model_selector.length = 1; 
            year_selector.length = 1;
            if (this.selectedIndex < 1) {
              return;
            }
            for (var model in vehicle_list[this.value]) {
              model_selector.options[model_selector.options.length] = new Option(model, model);
            }
          }

          model_selector.onchange = function() {
            year_selector.length = 1; 
            if (this.selectedIndex < 1) {
              return;
            }
            var years = vehicle_list[brand_selector.value][this.value];
            for (var i = 0; i < years.length; i++) {
              year_selector.options[year_selector.options.length] = new Option(years[i], years[i]);
            }
          } 
        
        var endpoint = "http://www.zhengyuduan.com/backend/database/types/getAllTypes.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                all_types = data["data"]; 
                type_selector = document.getElementById("type");
                for (var i=0; i<all_types.length; i++) {
                  type_selector.options[type_selector.options.length] = new Option(all_types[i]["TYPE_NAME"], all_types[i]["TYPE_NAME"]);
                }
              }
            },
            error: function(e) {
              console.log(e);
              alert("Network Error!");
            }
        }); 
        
        endpoint = "http://www.zhengyuduan.com/backend/database/location/getAllLocations.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                all_locations = data["data"];
                location_selector = document.getElementById("location");
                show_location_selector = document.getElementById("show_location");
                for (var i=0; i<all_locations.length; i++) {
                  location_selector.options[location_selector.options.length] = new Option(all_locations[i]["LOCATION_NAME"], all_locations[i]["LOCATION_ID"]);
                  show_location_selector.options[show_location_selector.options.length] = new Option(all_locations[i]["LOCATION_NAME"], all_locations[i]["LOCATION_ID"]);
                }
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

      function show_vehicle(data) {
        var content = document.getElementById("search_result");
        content.style.display = "block";
        document.getElementById("show_plate").value = data["LICENSE_PLATE"];
        document.getElementById("show_type").value = data["VEHICLE_TYPE"];
        document.getElementById("show_condition").value = data["VEHICLE_CONDITION"];
        document.getElementById("show_brand").value = data["VEHICLE_BRAND"];
        document.getElementById("show_model").value = data["VEHICLE_MODEL"];
        document.getElementById("show_year").value = data["VEHICLE_YEAR"];
        document.getElementById("show_mileage").value = data["VEHICLE_CURRENT_MILEAGE"];
        document.getElementById("show_registration").value = data["VEHICLE_REGISTRATION_TAG"];
        document.getElementById("show_location").value = data["VEHICLE_LOCATION"];
        document.getElementById("show_last_service_time").value = data["LAST_SERVICED_TIME"];
        document.getElementById("show_vehicle_status").value = data["VEHICLE_STATUS"];
      }

      function add() {
        console.log("add");
        var plate = document.getElementById("plate").value;
        var type = document.getElementById("type").value;
        var condition = document.getElementById("condition").value;
        var brand = document.getElementById("brand").value;
        var model = document.getElementById("model").value;
        var year = document.getElementById("year").value;
        var mileage = document.getElementById("mileage").value;
        var registration = document.getElementById("registration").value;
        var location = document.getElementById("location").value;

        var status = document.getElementById("add_status");

        obj = {
          "vehiclePlate": plate,
          "vehicleType": type, 
          "vehicleCondition": condition,
          "vehicleBrand": brand,
          "vehicleModel": model,
          "vehicleMile": mileage,
          "vehicleYear": year,
          "vehicleLocation": location,
          "vehicleRegistration": registration,
        }
        console.log(JSON.stringify(obj));

        var endpoint = "http://www.zhengyuduan.com/backend/database/vehicles/addNew.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                console.log("success");
                hint(status, "add_status", "Successfully add a vehicle!", "success");
              } else {
                console.log("fail");
                hint(status, "add_status", data["message"], "fail");
              }
            },
            error: function(e) {
              console.log(e);
              alert("Network Error!");
            }
        });
      }

      function search() {
        console.log("search");
        var plate = document.getElementById("search_plate").value;
        var registration = document.getElementById("search_registration").value;

        var status = document.getElementById("search_status");

        obj = {
          "vehiclePlate": plate,
          "vehicleRegistration": registration
        }
        var endpoint = "http://www.zhengyuduan.com/backend/database/vehicles/getVehicleData.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                var vehicle = data["data"];
                hint(status, "search_status", "Successfully retrieve a vehicle!", "success");
                show_vehicle(vehicle);
                
              } else {
                hint(status, "search_status", data["message"], "fail");
              }
            },
            error: function(e) {
              console.log(e);
              alert("Network Error!");
            }
        });
      }

      function modify(operation) {
        console.log("modify");
        var plate = document.getElementById("show_plate").value;
        var type = document.getElementById("show_type").value;
        var condition = document.getElementById("show_condition").value;
        var brand = document.getElementById("show_brand").value;
        var model = document.getElementById("show_model").value;
        var year = document.getElementById("show_year").value;
        var mileage = document.getElementById("show_mileage").value;
        var registration = document.getElementById("show_registration").value;
        var location = document.getElementById("show_location").value;
        var last_service_time = document.getElementById("show_last_service_time").value;
        var vehicle_status = document.getElementById("show_vehicle_status").value;

        var status = document.getElementById("modify_status");

        obj = {
          "vehiclePlate": plate,
          "vehicleType": type, 
          "vehicleCondition": condition,
          "vehicleBrand": brand,
          "vehicleModel": model,
          "vehicleMile": mileage,
          "vehicleYear": year,
          "vehicleLocation": location,
          "vehicleRegistration": registration,
          "lastServicedTime": last_service_time,
          "vehicleStatus": vehicle_status,
        }
        console.log(JSON.stringify(obj));

        var endpoint = "";
        if (operation == "modify") {
          endpoint = "http://www.zhengyuduan.com/backend/database/vehicles/updateVehicle.php";
        } else if(operation == "delete") {
          endpoint = "http://www.zhengyuduan.com/backend/database/vehicles/deleteVehicle.php"
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
                hint(status, "modify_status", "Successfully " + operation + " a vehicle!", "success");
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
              <a class="nav-link" href="../home.html">Home <span class="sr-only">(current)</span></a>
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
                      <b>Vehicles</b>
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
          <h4 style="text-align:center;">Add a vehicle</h4>
          <div class="container mt-5">
            <div class="row">
              <div class="col">
                  <div class="form-group">
                    <label>PLATE</label>
                    <input id="plate" class="form-control" placeholder="License Plate">
                  </div>
              </div>
              <div class="col">
                  <div class="form-group">
                    <label>Type</label>
                    <!-- TODO -->
                    <select id="type" class="form-control">
                        <option value="" selected="selected">Select type</option>
                    </select>
                  </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Condition</label>
                    <select id="condition" class="form-control">
                        <option value="Excellent" >Excellent</option>
                        <option value="Good" selected="selected">Good</option>
                        <option value="Fair">Fair</option>
                        <option value="Poor">Poor</option>
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label>Brand</label>
                    <select id="brand" class="form-control">
                      <option value="" selected="selected">Select brand</option>
                    </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Model</label>
                    <select id="model" class="form-control">
                      <option value="" selected="selected">Select model</option>
                    </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Year</label>
                    <select id="year" class="form-control">
                      <option value="" selected="selected">Select year</option>
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label>Mileage</label>
                  <input id="mileage" class="form-control" placeholder="Mileage">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Registration Tag</label>
                  <input id="registration" class="form-control" placeholder="Registration Tag">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Location</label>
                    <select id="location" class="form-control">
                      <option value="" selected="selected">Select location</option>
                    </select>
                </div>
              </div>
            </div>
            <button id="add" type="submit" class="col-2 offset-5 btn btn-success" onclick="add()">Add</button>
            <p style="color:green; text-align:center; display:none;" id="add_status">Fake text</p>
          </div>

          <hr class="my-5"/>

          <h4 style="text-align:center;">Search a vehicle</h4>
          <div class="container mt-5">
            <div class="row">
              <div class="col">
                  <div class="form-group">
                    <label>PLATE</label>
                    <input id="search_plate" class="form-control" placeholder="License Plate">
                  </div>
              </div>
              <div class="col">
                  <div class="form-group">
                    <label>Registration Tag</label>
                    <input id="search_registration" class="form-control" placeholder="Registration Tag">
                  </div>
              </div>
              <div class="col">
              </div>
            </div>
            <button id="search" type="submit" class="col-2 offset-5 btn btn-success" onclick="search()">Search</button>
            <p style="color:green; text-align:center; display:none;" id="search_status">Fake text</p>
          </div>

          <div id="search_result" class="container mt-5" style="display:none;">
            <div class="row">
              <div class="col">
                  <div class="form-group">
                    <label>PLATE</label>
                    <input id="show_plate" class="form-control">
                  </div>
              </div>
              <div class="col">
                  <div class="form-group">
                    <label>Type</label>
                    <input id="show_type" class="form-control" disabled>
                  </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Condition</label>
                    <select id="show_condition" class="form-control">
                      <option value="Excelent">Excelent</option>
                      <option value="Good">Good</option>
                      <option value="Fair">Fair</option>
                      <option value="Poor">Poor</option>
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label>Brand</label>
                  <input id="show_brand" class="form-control" disabled>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Model</label>
                  <input id="show_model" class="form-control" disabled>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Year</label>
                  <input id="show_year" class="form-control" disabled>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label>Mileage</label>
                  <input id="show_mileage" class="form-control">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Registration Tag</label>
                  <input id="show_registration" class="form-control" disabled>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Location</label>
                    <select id="show_location" class="form-control">
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label>Last Service Time</label>
                  <input id="show_last_service_time" type="date" class="form-control">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Vehicle Status</label>
                  <input id="show_vehicle_status" class="form-control" disabled>
                </div>
              </div>
              <div class="col">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <button id="modify" type="submit" class="col-4 offset-3 btn btn-success" onclick="modify('modify')">Modify</button>
              </div>
              <div class="col">
                <button id="delete" type="submit" class="col-4 offset-3 btn btn-danger" onclick="modify('delete')">Delete</button>
              </div>
            </div>
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