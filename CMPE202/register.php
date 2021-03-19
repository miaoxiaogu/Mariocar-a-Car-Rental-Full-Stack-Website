<!doctype html>
<html lang="en">
  <head>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
    <script>
    $(document).ready(function() {
        var endpoint = "http://www.zhengyuduan.com/backend/database/systemInfo/getData.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                membership_fee = data["data"]["MEMBERSHIP_FEE"];
                document.getElementById("fee").innerHTML = "Memebership fee: $" + membership_fee;
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
 
    function imageClick(url) {
        window.location = url;
    }

    function isSameAsAddress() {
        var arr = document.getElementsByClassName("billing_info");
        for (var i=0; i<arr.length; i++) {
            var e = arr[i];
            if (e.style.display == "none") {
                e.style.display = "block";
            } else {
                e.style.display = "none";
            }
        }
    }

    function memebershipFee(value) {
        document.getElementById("memebership_fee").innerHTML("Memebership fee: $" + value);
    }

    function submit() {
        var first_name = document.getElementById("first_name").value;
        var last_name = document.getElementById("last_name").value;
        var password = document.getElementById("password").value;
        var password_confirm = document.getElementById("password_confirm").value;
        var email = document.getElementById("email").value;
        var driver_license_number = document.getElementById("driver_license_number").value;
        var driver_license_state = document.getElementById("driver_license_state").value;
        var date_of_birth = document.getElementById("date_of_birth").value;
        var drive_license_expire_date = document.getElementById("drive_license_expire_date").value;
        var card_number = document.getElementById("card_number").value;
        var card_expire_date = document.getElementById("card_expire_date").value;
        var card_cvi = document.getElementById("card_cvi").value;
        var card_zipcode = document.getElementById("card_zipcode").value;

        var address_street = document.getElementById("address_street").value;
        var address_state = document.getElementById("address_state").value;
        var address_city = document.getElementById("address_city").value;
        var address_zipcode = document.getElementById("address_zipcode").value;
        var billing_street = document.getElementById("billing_street").value;
        var billing_state = document.getElementById("billing_state").value;
        var billing_city = document.getElementById("billing_city").value;
        var billing_zipcode = document.getElementById("billing_zipcode").value;
        var is_same = document.getElementById("is_same").checked;

        if (first_name == "" || last_name == "" || password == "" || email == "") {
            alert("Please Input User Info!");
            return;
        } else if (driver_license_number == "" || driver_license_state == "" || date_of_birth == "" || drive_license_expire_date == "") {
            alert("Please Input Driver License Info!");
            return;
        } else if (card_number == "" || card_expire_date == "" || card_cvi == "" || card_zipcode == "") {
            alert("Please Input Credit Card Info!");
            return;
        } else if (password != password_confirm) {
            alert("Password is not the same!");
            return;
        }

        var obj = {
            "first_name": first_name,
            "last_name": last_name,
            "password": password,
            "email": email,
            "driver_license_number": driver_license_number,
            "driver_license_state": driver_license_state,
            "date_of_birth": date_of_birth,
            "drive_license_expire_date": drive_license_expire_date,
            "card_number": card_number,
            "card_expire_date": card_expire_date,
            "card_cvi": card_cvi,
            "card_zipcode": card_zipcode,
            "address_street": address_street,
            "address_state": address_state,
            "address_city": address_city,
            "address_zipcode": address_zipcode,
        };
        if (is_same) {
            obj["billing_street"] = address_street;
            obj["billing_state"] = address_state;
            obj["billing_city"] = address_city;
            obj["billing_zipcode"] = address_zipcode;
        } else {
            obj["billing_street"] = billing_street;
            obj["billing_state"] = billing_state;
            obj["billing_city"] = billing_city;
            obj["billing_zipcode"] = billing_zipcode;
        }
        var json_string = JSON.stringify(obj);
        console.log(json_string);

        var endpoint = "http://www.zhengyuduan.com/backend/database/users/signUp.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              console.log(data);
              if (data["status"] == "success") {
                sessionStorage.setItem("log", "user");
                sessionStorage.setItem("email", email);
                window.location.href = "welcome.html";
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

    <div class="card col-6 offset-md-3 my-5">
        <article class="card-body">
        <h4 class="card-title mb-4 mt-1">Membership Sign Up</h4>
        <h4 class="card-title mb-4 mt-1" id = "fee"></h4>
            <!-- <form action="" method=""> -->
                <div class="card col-12 my-5">
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
                        <label>Password</label>
                        <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                    </div> 
                    <div class="form-group">
                        <label>Confirm password</label>
                        <input id="password_confirm" name="password_confirm" type="password" class="form-control" placeholder="Password">
                    </div> 
                    <div class="form-group">
                        <label>Email Address</label>
                        <input id="email" name="email" class="form-control" placeholder="Email Address">
                    </div>
                </div>

                <div class="card col-12 my-5">
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
                </div>

                <div class="card col-12 my-5">
                    <h5 class="card-title mb-4 mt-1">Address Info</h5>
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
                </div>

                <div class="card col-12 my-5">
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
                </div>

                <div class="card col-12 my-5">
                    <h5 class="card-title mb-4 mt-1">Billing Address</h5>
                    <div class="input-group my-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <input id="is_same" type="checkbox" aria-label="Radio button for following text input" onclick="isSameAsAddress()">
                            </div>
                        </div>
                        <input type="text" class="form-control" aria-label="Text input with radio button" placeholder="Same as address information" disabled>
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
                </div>
                
                <div class="form-group">
                    <button id="submit" type="submit" class="btn btn-secondary btn-block" onclick="submit()"> Sign Up </button>
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
    </body>
</html>