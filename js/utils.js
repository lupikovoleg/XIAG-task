var app = app || {};

app.Utils = function() {
  var utils = {};
  
  utils.ajax = function(method, url) {
    var xhr = new XMLHttpRequest();

    if ("withCredentials" in xhr) {
      xhr.open(method, url, true);
    } else if (typeof XDomainRequest != "undefined") {
      xhr = new XDomainRequest();
      xhr.open(method, url);
    } else {
      xhr = null;
    }

    return xhr;
  };

  utils.escapeHtml = function(url) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };

    return url.replace(/[&<>"']/g, function(m) { 
      return map[m]; 
    });
  };

  utils.checkUrl = function(url) {    
    var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

    return regexp.test(url);
  };
  
  return utils;
}();