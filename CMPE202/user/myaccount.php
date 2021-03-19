<!doctype html>
<html lang="en">
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>

      $(document).ready(function() {
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
                type_selector = document.getElementById("type_name");
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

        var user_email = sessionStorage.getItem("email");
        var obj = {
            "email": user_email,
        }
        var endpoint = "http://www.zhengyuduan.com/backend/database/users/getAllData.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                member_endtime = data["User"]["MEMBER_ENDTIME"];
                document.getElementById("member_endtime").innerHTML = "Your membership is valid until: " + member_endtime;
              } else {
                alert(data["message"]);
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


      function renew_membership() {
        var user_email = sessionStorage.getItem("email");
        var obj_email = {
            "email":user_email,
        }
        
        var json_email = JSON.stringify(obj_email);
        console.log(json_email);
        var endpoint = "http://www.zhengyuduan.com/backend/database/users/renewMembership.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj_email),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                alert(data["message"]);
                window.location.reload();
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


      function terminate_membership() {
        var user_email = sessionStorage.getItem("email");
        var obj_email = {
            "email":user_email,
        }
        
        var json_email = JSON.stringify(obj_email);
        console.log(json_email);
        var endpoint = "http://www.zhengyuduan.com/backend/database/users/terminateMembership.php ";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj_email),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                 alert("Your membership has been terminated :(");
                 window.location.reload();
//                 logout();
                
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





      function modifytimeformat(s) {
        s = s.replace("T", " ") + ":00";
        return s;
      }


      function submit() {
        var regist_start_time = modifytimeformat(document.getElementById("regist_start_time").value);
        var regist_end_time = modifytimeformat(document.getElementById("regist_end_time").value);
        var location_state = document.getElementById("location_state").value;
        var location_city = document.getElementById("location_city").value;
        var type_name = document.getElementById("type_name").value;



        if (regist_start_time == ":00" || regist_end_time == ":00" ) {
            alert("Please Input Time Info!");
            return;
        } else if ((location_state == "" || location_city == "" )&& type_name == "") {
            alert("Please Input Full Location Info Or Cartype Info!");
            return;
        }
        
        var search_type;
        if (location_state == "" && location_city == ""){
            search_type = "type"; 
        }else if(type_name == ""){
            search_type = "location";
        }else{
            search_type = "both";
        }
       

        var obj = {
            "location_state": location_state,
            "location_city": location_city,
            "type_name": type_name,
            "search_type":search_type,
        }
        
        var json_string = JSON.stringify(obj);
        console.log(json_string);
     
        var endpoint = "http://www.zhengyuduan.com/backend/database/vehicles/search.php ";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              if (data["status"] == "success") {
                var vehicle_list = data["vehicle_list"];
                if (!Array.isArray(vehicle_list) || !vehicle_list.length) {
                  // link to no available vehicle page
                  window.location.href = "noavailable.php";

                }  else {
                    var vehicle_list_str = JSON.stringify(vehicle_list);
                    sessionStorage.setItem("vehicle_list_str", vehicle_list_str);
                    sessionStorage.setItem("regist_start_time", regist_start_time);
                    sessionStorage.setItem("regist_end_time", regist_end_time);





                    console.log(vehicle_list_str);


                    window.location.href = "listofavailable.php";
                }
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


      <div class="card col-6 offset-md-3 my-5">
        <article class="card-body">

          <!-- <form action="" method=""> -->
  
                   <div class="form-group">
                        <h5 class="card-title mb-4 mt-1"for="regist_start_time">Pick-Up Time</h5>
                        <!-- <label>Drop-Off Time</label> -->
                        <input id="regist_start_time" name="pick_up_time" class="form-control" placeholder="Pick-Up Time" type = "datetime-local">
                    </div>

                    <div class="form-group">
                        <h5 class="card-title mb-4 mt-1"for="regist_end_time">Drop-Off Time</h5>
                        <!-- <label>Drop-Off Time</label> -->
                        <input id="regist_end_time" name="drop_off_time" class="form-control" placeholder="Drop-Off Time" type = "datetime-local">
                    </div>

                    <br>


                    <div class="form-group">

                      <h5 class="card-title mb-4 mt-1">Pick-Up Location</h5>
                        <label>State</label>
                        <select id="location_state" name="location_state" class="form-control">
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
                    <div class="form-group">
                        <label>City</label>
                        <input id="location_city" name="location_city" class="form-control" placeholder="City">
                    </div>
                    
                    <br>
                    <div class="form-group">
                        <h5 class="card-title mb-4 mt-1">Car Type</h5>
                        <!-- <label>Drop-Off Time</label> -->
                        <select id="type_name" name="car_type" class="form-control">
                         
                          <option value="" selected="selected">Select type</option>

                            <!-- <option value="" selected="selected">Select a Car Type</option>
                            <option value="two-compartment">Regular Car</option>
                            <option value="minivan">Mini Van</option> -->
                        </select>
                    </div> 



                    <div class="form-group">
                        <button id="submit" type="submit" class="btn btn-secondary btn-block" onclick="submit()"> Search For A Car </button>
                    </div>  




                    <div >
                        <label>*&nbsp Discount avalible for drivers over 25 !</label><br>
                        <label>*&nbsp Rate will go down if you book for longer time !</label>

                    </div>

                    </div>
                    <br>
                </div>


                                                                        
            <!-- </form> -->
        </article>
    </div> 
     <br>
    <br>

    <div style = "margin:auto; text-align:center;">
        <h5 class="card-title mb-4 mt-1" id="member_endtime"></h5>
    </div>
    <br>
    <br>


    <div style = "margin:auto; text-align:center;">
            <a href="user_reservation.php" class="btn btn-secondary btn-sm" style = "width:180px;margin:10px;">My Reservation</a>
            <a href="user_modify_info.php" class="btn btn-secondary btn-sm" style = "width:180px;margin:10px;">Manage Account</a>
            <a href="user_update_password.php" class="btn btn-secondary btn-sm" style = "width:180px;margin:10px;">Change Password</a>
            <button id="renew_membership" type="submit" class="btn btn-secondary btn-sm" onclick="renew_membership()" style = "width:200px;margin:10px;"> Renew My Membership </button>

            <button id="terminate_membership" type="submit" class="btn btn-secondary btn-sm" onclick="terminate_membership()" style = "width:200px;margin:10px;"> Terminate My Membership </button>


            <!-- <a href="welcome.html" class="btn btn-secondary btn-sm" style = "width:200px;margin:0px 50px ;">Reopen My Membership</a> -->
            <!-- <a href="../home.html" class="btn btn-secondary btn-sm" style = "width:200px;margin:10px;">Terminate My Membership</a> -->
    </div>
    <br>
    <br>
    <br>
    <br>
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
