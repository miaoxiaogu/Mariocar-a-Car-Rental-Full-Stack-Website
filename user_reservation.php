<!doctype html>
<html lang="en">
  <head>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
      var currentOrderId = -1;

      function renderReservationTable(data) {
        if (data["status"] == "success" &&
            !jQuery.isEmptyObject(data["data"])) {
          var reservationResponse = data["data"][0];

          var trHTML = '<tr>';
          trHTML += '<td>Pickup Time:&nbsp;</td>';
          trHTML += '<td></td>';
          trHTML += '<td>' + reservationResponse["REGIST_START_TIME"] + '</td>';
          trHTML += '</tr>';

          trHTML += '<tr>';
          trHTML += '<td>Dropoff Time:&nbsp;</td>';
          trHTML += '<td></td>';
          trHTML += '<td>' + reservationResponse["REGIST_END_TIME"] + '</td>';
          trHTML += '</tr>';

          trHTML += '<tr>';
          trHTML += '<td>Pickup Location:&nbsp;</td>';
          trHTML += '<td></td>';
          trHTML += '<td>' + reservationResponse["VEHICLE_PARK_LOCATION"] + '</td>';
          trHTML += '</tr>';

          trHTML += '<tr>';
          trHTML += '<td>Vehicle License Plate:&nbsp;</td>';
          trHTML += '<td></td>';
          trHTML += '<td>' + reservationResponse["VEHICLE_LICENSE_PLATE"] + '</td>';
          trHTML += '</tr>';

          trHTML += '<tr>';
          trHTML += '<td> &nbsp; </td>';
          trHTML += '</tr>';
          console.log(trHTML);
          $('#reservations_table').html(trHTML);

          var btnHtml = '<button id = \"btn1\"type=\"submit\" onclick=\"sendCancelRequest()\"> Cancel My Reservation </button>';
          btnHtml += '<button id = \"btn2\"type=\"submit\" onclick=\"sendPickUpRequest()\"> Pick Up Car </button>';
          btnHtml += '<button id = \"btn3\"type=\"submit\" onclick=\"sendDropOffRequest()\"> Drop Off Car </button>';

          $('#mybutton').html(btnHtml);
          

        }else{
          var reservationResponse = data["reservation"];

          var trHTML = '<tr>';
          trHTML += '<td>You Have No Reservation Now...</td>';
          trHTML += '</tr>';
          trHTML += '<tr>';
          trHTML += '<td> &nbsp; </td>';
          trHTML += '</tr>';
          console.log(trHTML);
          $('#reservations_table').html(trHTML);

          var btnHtml = '<button id = \"btn1\"type=\"submit\" onclick=\"window.location.href=\'./myaccount.php\'\"> Reserve A Car </button>';

          $('#mybutton').html(btnHtml);
        }
      }

      function renderHistoryTable(data) {
        if (data["status"] == "success" &&
            !jQuery.isEmptyObject(data["history"])) {
          var historyResponse = data["history"];

          var trHTML = '';
          $.each(historyResponse, function (i, item) {
            console.log(item)
            trHTML += '<tr><td>' + item.orderid + '</td><td>' + item.endtime + '</td><td>' + item.price + '</td></tr>';
          });

          $('#history_table').append(trHTML);
        } else{
          var historyResponse = data["history"];

          var trHTML = '<tr>';
          trHTML += '<td>You Have No History Record...</td>';
          trHTML += '</tr>';
          trHTML += '<tr>';
          trHTML += '<td> &nbsp; </td>';
          trHTML += '</tr>';
          console.log(trHTML);
          $('#history_table').html(trHTML);
        }
      }

      function sendReservationRequest() {
        var obj = {
          "email": sessionStorage.getItem("user_email"),
        }

        var endpoint = "http://www.zhengyuduan.com/backend/database/orders/getInProgress.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              if (data["success"]) {
                renderReservationTable(data);
                if (!jQuery.isEmptyObject(data["data"])) {
                   var currentOrder = data["data"][0];
                   currentOrderId = currentOrder["ORDER_ID"];
                   sessionStorage.setItem("current_order_id", currentOrderId.toString());
                }
              } else {
                alert("Reservation request: " + data["message"]);
              }
            },
            error: function(e) {
              alert("Can't retrieve reservations: network error");
            }
        });
      }

      function sendHistoryRequest() {
        var obj = {
          "email": sessionStorage.getItem("user_email"),
        }

        var endpoint = "http://www.zhengyuduan.com/backend/database/orders/getComplete.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              if (data["success"]) {
                renderHistoryTable(data);
              } else {
                alert("History request: " + data["message"]);
              }
            },
            error: function(e) {
              alert("Can't retrieve history: network error");
            }
        });
      }

      function sendDropOffRequest() {
        if (currentOrderId == -1) {
          alert("No Order");
          return;
        }

        var obj = {
          "order_id": currentOrderId.toString(),
          "comments": "",
          "condition": "",
        }

        var endpoint = "http://www.zhengyuduan.com/backend/database/orders/dropOff.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              if (data["status"] == "success") {
                window.location.href = "./dropoff_price.php";
              } else {
                alert(data["message"]);
              }
            },
            error: function(e) {
              alert("Network error");
            }
        });
      }

      function sendCancelRequest() {
        if (currentOrderId == -1) {
          alert("No Order");
          return;
        }

        var obj = {
          "order_id": currentOrderId,
        }

        var endpoint = "http://www.zhengyuduan.com/backend/database/orders/cancelOrder.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              if (data["status"] == "success") {
                window.location.href = "./cancel_fee.php";
              } else {
                alert(data["message"]);
              }
            },
            error: function(e) {
              alert("Network error");
            }
        });
      }

      function sendPickUpRequest() {
        if (currentOrderId == -1) {
          alert("No Order");
          return;
        }

        var obj = {
          "orderID": currentOrderId,
        }

        var endpoint = "http://www.zhengyuduan.com/backend/database/orders/pickUp.php";
        $.ajax({
            url: endpoint,
            type: "POST",
            data: JSON.stringify(obj),
            dataType: "json",
            contentType: 'application/json',
            success: function(data) {
              if (data["status"] == "success") {
                alert("Pick Up success");
              } else {
                alert(data["message"]);
              }
            },
            error: function(e) {
              alert("Network error");
            }
        });
      }

      let login_status = sessionStorage.getItem("log");
      if (login_status === 'user') {
        sendReservationRequest();
        sendHistoryRequest();
      }
      
      // tests
      /*$(document).ready(function() {
        var fakeResponse = {
          "status": "success",
          "data": [
              {
                  "ORDER_ID": "2",
                  "ORDER_CONFIRMED_TIME": "2020-05-04 05:19:00",
                  "REGIST_START_TIME": "2012-10-10 00:00:00",
                  "REGIST_END_TIME": "2012-11-10 00:00:00",
                  "ACTUAL_START_TIME": null,
                  "ACTUAL_END_TIME": null,
                  "CANCEL_TIME": null,
                  "ORDER_USER_ID": "1",
                  "VEHICLE_LICENSE_PLATE": "SF0001",
                  "VEHICLE_PARK_LOCATION": "1",
                  "PRICE": null,
                  "VEHICLE_CONDITION": null,
                  "COMMENTS": null,
                  "ORDER_STATUS": "In Progress"
              }
          ],
          "action": "Get Orders"
        }
        currentOrderId = 2;

        var fakeEmptyResponse = {
          "status": "success",
          "data": {}
        }

        var fakeErrorResponse = {
          "status": "error"
        }
        
        console.log(fakeResponse);
        renderReservationTable(fakeResponse);

        var fakeHistoryResponse = {
          "status":"success",
          "history":[{
            "orderid": "001",
            "endtime": "2020-10-10 15:00",
            "price": "35",
          }]
        }

        var fakeEmptyHistoryResponse = {
          "status":"success",
          "history":[]
        }
        console.log(fakeEmptyHistoryResponse);
        renderHistoryTable(fakeEmptyHistoryResponse)
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
        <h4 class="card-title mb-4 mt-1">Your Reservation:</h4>
          <table id="reservations_table">
          </table>

          <div id="mybutton" class="container">
          </div> 
        </article>
    </div>

    <div class="card col-8 offset-md-2 my-5">
    <br><h5 class="view_my_order_history" href = "history.html" name = "order_history" style = "marker-mid: 30pt">My History Orders</h5></br>
    <table id="history_table">    
      <tr>
        <th>Order ID</th>
        <th>End Time</th>
        <th>Price</th>
      </tr>
    </table>
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

          .container{

    width: 100%;
    float: left;
    text-align: center;
}
          .container button{
    padding: 12px 28px;
    color: white;
    border-radius: 4px;
    display: inline-block;
    background-color: #555555;
}
          #btn1{
    float:left;
}
          #btn3{
    float:right;
}

     </style>
      <hr class="mt-5"/>
      <blockquote class="mt-5 blockquote text-center" style="font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;" >
        <footer class="blockquote-footer mt-5 text-center">Copyright © 2020 Mario Car Ltd .  All Rights Reserved | Created by Qin Wang, Yakun Feng, Chen Zhang, Zhengyu Duan </footer>
      </blockquote>
  </body>
</html>