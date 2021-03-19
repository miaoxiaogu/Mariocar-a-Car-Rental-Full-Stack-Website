<!doctype html>
<html lang="en">
  <head>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
    <script>
    var user_id = "";
    var user_email = "";
    $(document).ready(function() {
        console.log("ready");
        if (sessionStorage.getItem("log") != "user" || sessionStorage.getItem("email") == null) {
          document.getElementById("user_access").innerHTML = "<h4 class='pt-5' style='color:red; text-align:center;'>Only log in user have access!</h4>";
          return;
        }
        user_email = sessionStorage.getItem("email");
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
                show_user_info(data);
                user_id = data["User"]["USER_ID"];
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

    function back() {
        window.location.href = "myaccount.php";
    }
 
    function imageClick(url) {
        window.location = url;
    }

    function show_user_info(data) {
        document.getElementById("first_name").value = data["User"]["USER_FIRST_NAME"];
        document.getElementById("last_name").value = data["User"]["USER_LAST_NAME"];
        document.getElementById("email").value = data["User"]["USER_EMAIL"];

        document.getElementById("driver_license_number").value = data["DriverLicense"]["DRIVER_LICENSE_NUMBER"];
        document.getElementById("driver_license_state").value = data["DriverLicense"]["ADDRESS_STATE"];
        document.getElementById("date_of_birth").value = data["DriverLicense"]["DRIVER_BIRTHDAY"];
        document.getElementById("drive_license_expire_date").value = data["DriverLicense"]["DRIVER_LICENCE_EXPIREDATE"];
        document.getElementById("address_street").value = data["DriverLicense"]["ADDRESS_STREET"];
        document.getElementById("address_state").value = data["DriverLicense"]["ADDRESS_STATE"];
        document.getElementById("address_city").value = data["DriverLicense"]["ADDRESS_CITY"];
        document.getElementById("address_zipcode").value = data["DriverLicense"]["ADDRESS_ZIPCODE"];

        document.getElementById("card_number").value = data["CreditCard"]["CARD_NUMBER"];
        document.getElementById("card_expire_date").value = data["CreditCard"]["CARD_EXPIREDATE"].substring(0, 7);
        document.getElementById("card_cvi").value = data["CreditCard"]["CVI"];
        document.getElementById("card_zipcode").value = data["CreditCard"]["ZIPCODE"];
        document.getElementById("billing_street").value = data["CreditCard"]["CARD_BILL_ADDRESS_STREET"];
        document.getElementById("billing_state").value = data["CreditCard"]["CARD_BILL_ADDRESS_STATE"];
        document.getElementById("billing_city").value = data["CreditCard"]["CARD_BILL_ADDRESS_CITY"];
        document.getElementById("billing_zipcode").value = data["CreditCard"]["CARD_BILL_ADDRESS_ZIPCODE"];
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

    function ajax_call(endpoint, obj, element, id) {
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

    function updateUserInfo() {
        var first_name = document.getElementById("first_name").value;
        var last_name = document.getElementById("last_name").value;
        var email = document.getElementById("email").value;
        var obj = {
            "email": user_email,
            "newFirst": first_name,
            "newLast": last_name,
        };
        console.log(JSON.stringify(obj));
        var element = document.getElementById("user_modify_status");
        ajax_call("http://www.zhengyuduan.com/backend/database/users/changeUserName.php", obj, element, "user_modify_status");
    }

    function updateDriverLicense() {
        var driver_license_number = document.getElementById("driver_license_number").value;
        var driver_license_state = document.getElementById("driver_license_state").value;
        var date_of_birth = document.getElementById("date_of_birth").value;
        var drive_license_expire_date = document.getElementById("drive_license_expire_date").value;
        var address_street = document.getElementById("address_street").value;
        var address_state = document.getElementById("address_state").value;
        var address_city = document.getElementById("address_city").value;
        var address_zipcode = document.getElementById("address_zipcode").value;
        var obj = {
            "email": user_email,
            "driver_license_number": driver_license_number,
            "date_of_birth": date_of_birth,
            "driver_license_state": driver_license_state,
            "drive_license_expire_date": drive_license_expire_date,
            "address_street": address_street,
            "address_state": address_state,
            "address_city": address_city,
            "address_zipcode": address_zipcode,
        };
        console.log(JSON.stringify(obj));
        var element = document.getElementById("driver_license_modify_status");
        ajax_call("http://www.zhengyuduan.com/backend/database/driverLicense/updateLicense.php", obj, element, "driver_license_modify_status");
    }

    function updateCreditCard() {
        var card_number = document.getElementById("card_number").value;
        var card_expire_date = document.getElementById("card_expire_date").value + "-00";
        var card_cvi = document.getElementById("card_cvi").value;
        var card_zipcode = document.getElementById("card_zipcode").value;
        var billing_street = document.getElementById("billing_street").value;
        var billing_state = document.getElementById("billing_state").value;
        var billing_city = document.getElementById("billing_city").value;
        var billing_zipcode = document.getElementById("billing_zipcode").value;
        var obj = {
            "email": user_email,
            "card_number": card_number,
            "card_expire_date": card_expire_date,
            "card_cvi": card_cvi,
            "card_zipcode" : card_zipcode,
            "billing_street" : billing_street,
            "billing_state" : billing_state,
            "billing_city" : billing_city,
            "billing_zipcode" : billing_zipcode,
        };
        console.log(JSON.stringify(obj));
        var element = document.getElementById("credit_card_modify_status");
        ajax_call("http://www.zhengyuduan.com/backend/database/creditCard/updateCard.php", obj, element, "credit_card_modify_status");
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
    <div id="user_access">
    <div class="row px-5 pt-3">
        <button id="submit" type="submit" class="btn btn-secondary btn-light" onclick="back()">Back</button>
    </div>
    <div class="row">
        <div class="col-4 pl-5">
                <div class="card col-12 my-5 pb-3">
                    <h5 class="card-title mb-4 mt-1">User Info</h5>
                    <div class="form-group">
                        <label>First Name</label>
                        <input id="first_name" name="first_name" class="form-control" placeholder="First Name">
                    </div> 
                    <div class="form-group">
                        <label>Last Name</label>
                        <input id="last_name" name="last_name" class="form-control" placeholder="Last Name">
                    </div> 
                    <div class="form-group">
                        <label>Email Address</label>
                        <input id="email" name="email" class="form-control" placeholder="Email Address" disabled>
                    </div>
                    <button id="submit" type="submit" class="btn btn-secondary btn-block" onclick="updateUserInfo()">Update</button>
                    <p style="color:green; text-align:center; display:none;" id="user_modify_status">Fake text</p>
                </div>
        </div>
        <div class="col-4 pl-5">
                <div class="card col-12 my-5 pb-3">
                    <h5 class="card-title mb-4 mt-1">Driver License Info</h5>
                        <div class="form-group">
                        <label>Driver License Number</label>
                        <input id="driver_license_number" name="driver_license_number" class="form-control" placeholder="Driver License Number">
                    </div>
                    <div class="form-group">
                        <label>Driver License State</label>
                        <select id="driver_license_state" name="driver_license_state" class="form-control">
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
                        <label>Date of Birth</label>
                        <input id="date_of_birth" name="drive_license_expire_date" class="form-control" type="date">
                    </div>
                    <div class="form-group">
                        <label>Driver License Expire Date</label>
                        <input id="drive_license_expire_date" name="drive_license_expire_date" class="form-control" type="date">
                    </div>
                    <div class="form-group">
                        <label>Street Address</label>
                        <input id="address_street" name="address_street" class="form-control" placeholder="Street Address">
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input id="address_city" name="address_city" class="form-control" placeholder="City">
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <select id="address_state" name="address_state" class="form-control">
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
                        <label>Zipcode</label>
                        <input id="address_zipcode" name="address_zipcode" class="form-control" placeholder="Address Zipcode">
                    </div>
                    <button id="submit" type="submit" class="btn btn-secondary btn-block" onclick="updateDriverLicense()">Update</button>
                    <p style="color:green; text-align:center; display:none;" id="driver_license_modify_status">Fake text</p>                    
                </div>
        </div>
        <div class="col-4 px-5">
                <div class="card col-12 my-5 pb-3">
                    <h5 class="card-title mb-4 mt-1">Credit Card Info</h5>
                    <div class="form-group">
                        <label>Card Number</label>
                        <input id="card_number" name="card_number" class="form-control" placeholder="Card Number">
                    </div> 
                    <div class="form-group">
                        <label>CVI</label>
                        <input id="card_cvi" name="card_cvi" class="form-control" placeholder="Card CVI">
                    </div> 
                    <div class="form-group">
                        <label>Expire Date</label>
                        <input id="card_expire_date" name="card_expire_date" class="form-control" type="month">
                    </div> 
                    <div class="form-group">
                        <label>Zipcode</label>
                        <input id="card_zipcode" name="card_zipcode" class="form-control" placeholder="Card Zipcode">
                    </div>
                    <div class="form-group billing_info">
                        <label>Street Address</label>
                        <input id="billing_street" name="billing_street" class="form-control" placeholder="Street Address">
                    </div>
                    <div class="form-group billing_info">
                        <label>City</label>
                        <input id="billing_city" name="billing_city" class="form-control" placeholder="City">
                    </div>
                    <div class="form-group billing_info">
                        <label>State</label>
                        <select id="billing_state" name="billing_state" class="form-control">
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
                    <div class="form-group billing_info">
                        <label>Zipcode</label>
                        <input id="billing_zipcode" name="billing_zipcode" class="form-control" placeholder="Address Zipcode">
                    </div>
                    <button id="submit" type="submit" class="btn btn-secondary btn-block" onclick="updateCreditCard()">Update</button>
                    <p style="color:green; text-align:center; display:none;" id="credit_card_modify_status">Fake text</p>
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
        <footer class="blockquote-footer mt-5 text-center">Copyright © 2020 Nordic House Ltd .  All Rights Reserved | Created by Qin Wang, Yakun Feng, Chen Zhang, Zhengyu Duan </footer>
      </blockquote>
    </body>
</html>
