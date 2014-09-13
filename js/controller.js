'use strict';

var app = app || {};

app.mainCtrl = function() {
  var proto = {},
      priv = {};
  
  proto.init = function() {
    console.log('Hello! XIAG test task ultra super core system are loaded! :P');
    
    priv.watchForm();
  };
  
  priv.watchForm = function() {
    var $form = document.querySelector('form');
    
    $form.addEventListener("submit", function(e) {
      priv.proccessForm();
      
      e.preventDefault();
    });
  };
  
  priv.proccessForm = function() {
    var $url_input = document.querySelector("[name='url']").value,
        req = corsRequest("POST", "/index.php?get=url&method=add");
    
    if (req) {
      req.onload = function() {
        var response = JSON.parse(req.responseText);
        if(response.status === "ok") {
          document.getElementById("result").innerHTML = response.shorturl;
        } else {
          document.getElementById("result").innerHTML = "Error";
        }
      };
      
      req.send($url_input);
    }
  };
  
  return proto;
}();