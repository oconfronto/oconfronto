document.write('<div id="loading"><br><br>Please wait...</div>');

// Created by: Simon Willison | http://simon.incutio.com/
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}