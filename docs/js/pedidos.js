//notification
function notification() {
    // Let's check if the browser supports notifications
    if (!("Notification" in window)) {
      alert("This browser does not support desktop notification");
    }

    // Let's chec that the user agreed on the permition
    else if (Notification.permission === "granted") {
      // If it's okay let's create a notification
      var notification = new Notification("Cliente quer contrata-lo");
    }

    // Otherwise we nweeed to ask for permition
    else if (Notification.permission !== "denied") {
      Notification.requestPermission().then(function (permission) {
        // If the user accepts, let's create a notification
        if (permission === "granted") {
          var notification = new Notification("Cliente gostaria de estabelecer negocio consigo!");
        }
      });
    }

    if (notification) {
        notification.onclick = function(event) {
            event.preventDefault();
            window.open('../html/pedidos.html')
        }
    }

  }

var express = require('express');
var mysql = require('mysql');
var app = express();
//connection to db
var connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'mydb',
});

//when its connected it does....
connection.connect(function(error){
  //callback
  if(!error) {
    console.log('Error');
  }else {
    console.log('Connected');
  }
});

app.get('/', function(req, resp) {
  //about mysql
  connection.query("SELECT * FROM profissional", function(error,row,fields){
    if (!error) {
      console.log('ERROR on query');
    } else {
      console.log('sucessful');
    }
  });
});


  function showDiv(divId, element)
  {
      var x;

      if (element.value != 0) {
        x = 'block';
      }
      else {
        x ='none'
      }
      document.getElementById(divId).style.display = x;
  }


app.listen(1337)
