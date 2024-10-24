function set_xmlhttp() {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
          xmlhttp = false;
        }
      }
    }
}