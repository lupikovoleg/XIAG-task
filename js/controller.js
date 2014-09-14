var app = app || {};

app.mainCtrl = function() {
  var pub = {},
      watchForm = {};
  
  pub.init = function() {
    console.log("Hello! XIAG test task ultra super core system are loaded! :P");
    
    watchForm.init();
  };
  
  watchForm.init = function() {
    var elem = document.querySelector("form");
    
    elem.addEventListener("submit", function(e) {
      watchForm.proccessForm();
      
      e.preventDefault();
    });
  };

  watchForm.proccessForm = function() {
    var data = document.querySelector("[name='url']").value,
        xhr = app.Utils.ajax("POST", "/index.php?get=url&method=add");
    
    if (xhr) {
      xhr.onload = function() {
        var response = JSON.parse(xhr.responseText);
        
        if (response.status === "ok") {
          document.getElementById("result").innerHTML = response.shorturl;
        } else {
          document.getElementById("result").innerHTML = "Empty URL";
        }
      };
      
      if (data.length > 0) {
        if (app.Utils.checkUrl(data)) {
          xhr.send(app.Utils.escapeHtml(data));
        } else {
          document.getElementById("result").innerHTML = "It isn't a valid URL!";
        }
      } else {
        document.getElementById("result").innerHTML = "Empty URL";
      }
    }
  };
  
  return pub;
}();
