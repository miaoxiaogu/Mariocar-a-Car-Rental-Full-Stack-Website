<html lang="en">
  <head>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsdFN6_4vTIN0hE_umqTX0yN0Po-Zf-AI&callback=initialize"
    async defer></script>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
      }
    </style>
    <script>
        function imageClick(url) {
            window.location = url;
        }
      
        function logout() {
        sessionStorage.clear();
        window.location.href = "../home.html";
        }
        function submit() {
            var radios = document.getElementsByName('selection');
            var i = 0;
            for (var length = radios.length; i < length; i++) {
              if (radios[i].checked) {
                // do whatever you want with the checked radio
                //
                break;
                // only one radio can be logically checked, don't check the rest.
              }
            }
            // user not select a radio
            if (i == radios.length) {
                alert("Please Select a Car You Want");
            } else {
                var regist_start_time = sessionStorage.getItem("regist_start_time");
                var regist_end_time = sessionStorage.getItem("regist_end_time");
                var user_email = sessionStorage.getItem("email");
                var vehicle_list_str = sessionStorage.getItem("vehicle_list_str");
                var vehicle_list = JSON.parse(vehicle_list_str);
                var vehicle_id = vehicle_list[i]["VEHICLE_ID"];
//                 alert(vehicle_id);
                var obj = {
                    "regist_start_time": regist_start_time,
                    "regist_end_time": regist_end_time,
                    "user_email": user_email,
                    "vehicle_id": vehicle_id,
                }
                
                var json_string = JSON.stringify(obj);
                console.log(json_string);
             
                var endpoint = "http://www.zhengyuduan.com/backend/database/orders/addNew.php";
                $.ajax({
                    url: endpoint,
                    type: "POST",
                    data: JSON.stringify(obj),
                    dataType: "json",
                    contentType: 'application/json',
                    success: function(data) {
                      if (data["status"] == "success") {
                        //if success, turn to user_reservation page.
                        window.location.href = "user_reservation.php";
                      } else {
                          alert(data["message"]);
                      }
                    },
                    error: function(e) {
                        console.log(e);
                        alert("Network error!");
                    }
                });
                        
            }
        }
        function renderVehicleTable(vehicle_list) {
            console.log(vehicle_list.length);
            var trHTML = '<tr>';
            // trHTML +=  '<input type="radio" name="radios" id="radio2" />'
            for (i = 0; i < vehicle_list.length; i++) {
                var vehicle = vehicle_list[i];
                
                trHTML += '<tr>';
                trHTML += '<td rowspan="5" width="50">';
                trHTML += '<input type="radio" name="selection">';
                trHTML += '</td>';
                trHTML += '<td>Location Address:&nbsp;</td>';
                trHTML += '<td></td>';
                trHTML += '<td>' + vehicle["LOCATION_STREET"] + ',' + '&nbsp;' + 
                                     vehicle["LOCATION_CITY"] + ',' + '&nbsp;' +
                                     vehicle["LOCATION_STATE"] +',' + '&nbsp;' +
                                     vehicle["LOCATION_ZIPCODE"] + '</td>';
                trHTML += '</tr>';
                
                trHTML += '<tr>';
                trHTML += '<td>Type:&nbsp;</td>';
                trHTML += '<td></td>';
                trHTML += '<td>' + vehicle["TYPE_NAME"] + '</td>';
                trHTML += '</tr>';
                trHTML += '<tr>';
                trHTML += '<td>Brand:&nbsp;</td>';
                trHTML += '<td></td>';
                trHTML += '<td>' + vehicle["VEHICLE_BRAND"] + '</td>';
                trHTML += '</tr>';
                trHTML += '<tr>';
                trHTML += '<td>Model:&nbsp;</td>';
                trHTML += '<td></td>';
                trHTML += '<td>' + vehicle["VEHICLE_MODEL"] + '</td>';
                trHTML += '</tr>';
                trHTML += '<tr>';
                trHTML += '<td>Price:&nbsp;</td>';
                trHTML += '<td></td>';
                trHTML += '<td>' + "$" + vehicle["TYPE_RATE"] + "/ hr" + '</td>';
                trHTML += '</tr>';
                trHTML += '<tr>';
                 
                trHTML += '<td> &nbsp; </td>';
                trHTML += '</tr>';
                trHTML += '<td> &nbsp; </td>';
                trHTML += '</tr>';
                // console.log(trHTML);
              }
              $('#vehicle_table').html(trHTML);
                  var btnHtml = '<button id = \"btn1\"type=\"submit\" onclick=\"submit()\"> Cancel My Reservation </button>';
                  btnHtml += '<button id = \"btn2\"type=\"submit\" onclick=\"submit()\"> Pickup My Car </button>';
                  btnHtml += '<button id = \"btn3\"type=\"submit\" onclick=\"submit()\"> Dropoff My Car </button>';
                  $('#mybutton').html(btnHtml);
          }
      // render
      var geocoder;
      var map;
      var infoWindows = [];
      var prev_infowindow = false;
      function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(-34.397, 150.644);
        var mapOptions = {
          zoom: 12,
          center: latlng
        }
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
        var vehicle_list_str = sessionStorage.getItem("vehicle_list_str");
        var vehicle_list = JSON.parse(vehicle_list_str);
        // test
        /*var vehicle_list = [
            {
                "location_state": "CA",
                "location_city": "Santa",
                "location_zipcode": "95035",
                "location_street":"950 E Calaveras Blvd",
                "type_name": "Regular Car",
                "vehicle_id":"23424",
                "vehicle_brand":"haha",
                "vehicle_model":"aaaaaaaa",
                "type_rate":"4.16",
            },
        ];*/
        
        console.log(vehicle_list);
        renderVehicleTable(vehicle_list);
        var temp_address;
        for (i = 0; i < vehicle_list.length; i++) {
          temp_address = vehicle_list[i]["LOCATION_STREET"];
          temp_address += ", ";
          temp_address += vehicle_list[i]["LOCATION_CITY"];
          temp_address += ", ";
          temp_address += vehicle_list[i]["LOCATION_STATE"];
          temp_address += ", ";
          temp_address += vehicle_list[i]["LOCATION_ZIPCODE"];
          codeAddress(temp_address, i);
          var infoWindow = new google.maps.InfoWindow({
            content: temp_address
          });
          infoWindows.push(infoWindow);
        }
      }

      function codeAddress(address, index) {
        console.log(address);
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == 'OK') {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                label: String.fromCharCode(65 + index)
            });
            marker.addListener('click', function() {
              if(prev_infowindow) {
                 prev_infowindow.close();
              }
              prev_infowindow = infoWindows[index];
              infoWindows[index].open(map, marker);
            });
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
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
           <!--  </li>
            <li class="nav-item">
                <a class="nav-link" href="cars.php">Cars</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="../contacts.php">Contacts</a>
            </li>
          </ul>
          <a class="nav-link" style="color:black" onclick="logout()"><img src="../assets/login.svg" width="20" height="20" class="mr-1">Log out</a>
        </div>
      </nav>
    <div id="map"></div>
    <div class="card col-8 offset-md-2 my-5 table-responsive">
        <article class="card-body">
        <!-- <a href="" class="float-right btn btn-outline-secondary">Sign up</a> -->
        <h4 class="card-title mb-4 mt-1">Select a car you want:</h4>
          <table class="table-hover" id="vehicle_table">
          </table>
          
          <div class="form-group">
                    <button id="submit" type="submit" class="btn btn-secondary btn-lg" style = "width:300px;margin:0px 50px 0px 50px;"onclick="submit()"> Place My Order </button>
                    <a href="myaccount.php" class="btn btn-secondary btn-lg" style = "width:300px;margin:0px 50px 0px 50px;">Return To Search Again</a>
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
