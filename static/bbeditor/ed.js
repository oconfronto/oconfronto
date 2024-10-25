/*****************************************/
// Name: Javascript Textarea BBCode Markup Editor
// Version: 1.3
// Author: Balakrishnan
// Last Modified Date: 25/jan/2009
// License: Free
// URL: http://www.corpocrat.com
/******************************************/

var textarea;
var content;
document.write(
  '<link href="bbeditor/styles.css" rel="stylesheet" type="text/css">'
);

function edToolbar(obj) {
  document.write('<div class="toolbar">');
  document.write(
    '<img class="button" src="static/bbeditor/images/bold.png" name="btnItalic" onClick="doAddTags(\'[b]\',\'[/b]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/italic.png" name="btnItalic" onClick="doAddTags(\'[i]\',\'[/i]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/underline.png" name="btnUnderline" onClick="doAddTags(\'[u]\',\'[/u]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/strike.png" name="btnStrike" onClick="doAddTags(\'[s]\',\'[/s]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/link.png" name="btnLink" onClick="doURL(\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/picture.png" name="btnPicture" onClick="doImage(\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/film_add.png" name="btnYou" onClick="doYou(\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/color.png" name="btnColor" onClick="doColor(\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/ordered.png" name="btnOrder" onClick="doAddTags(\'[order]\',\'[/order]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/unordered.png" name="btnList" onClick="doAddTags(\'[list]\',\'[/list]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/left.png" name="btnLeft" onClick="doAddTags(\'[left]\',\'[/left]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/center.png" name="btnCenter" onClick="doAddTags(\'[center]\',\'[/center]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/right.png" name="btnRight" onClick="doAddTags(\'[right]\',\'[/right]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/small.png" name="btnSmall" onClick="doAddTags(\'[small]\',\'[/small]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/big.png" name="btnBig" onClick="doAddTags(\'[big]\',\'[/big]\',\'' +
      obj +
      "')\">"
  );
  document.write(
    '<img class="button" src="static/bbeditor/images/quote.png" name="btnQuote" onClick="doAddTags(\'[quote]\',\'[/quote]\',\'' +
      obj +
      "')\">"
  );
  document.write("</div>");
  //document.write("<textarea id=\""+ obj +"\" name = \"" + obj + "\" cols=\"" + width + "\" rows=\"" + height + "\"></textarea>");
}

function doImage(obj) {
  textarea = document.getElementById(obj);
  var url = prompt("Digite o endere�o da imagem:", "http://");
  var scrollTop = textarea.scrollTop;
  var scrollLeft = textarea.scrollLeft;

  if (document.selection) {
    textarea.focus();
    var sel = document.selection.createRange();
    sel.text = "[img]" + url + "[/img]";
  } else {
    var len = textarea.value.length;
    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;

    var sel = textarea.value.substring(start, end);
    //alert(sel);
    var rep = "[img]" + url + "[/img]";
    textarea.value =
      textarea.value.substring(0, start) +
      rep +
      textarea.value.substring(end, len);

    textarea.scrollTop = scrollTop;
    textarea.scrollLeft = scrollLeft;
  }
}

function doYou(obj) {
  textarea = document.getElementById(obj);
  var you = prompt("Digite o ID do video:", "Exemplo: 8bG5HMKVyhQ");
  var scrollTop = textarea.scrollTop;
  var scrollLeft = textarea.scrollLeft;

  if (document.selection) {
    textarea.focus();
    var sel = document.selection.createRange();

    sel.text = "[youtube]" + you + "[/youtube]";

    //alert(sel.text);
  } else {
    var len = textarea.value.length;
    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;

    var sel = textarea.value.substring(start, end);

    var rep = "[youtube]" + you + "[/youtube]";
    //alert(sel);

    textarea.value =
      textarea.value.substring(0, start) +
      rep +
      textarea.value.substring(end, len);

    textarea.scrollTop = scrollTop;
    textarea.scrollLeft = scrollLeft;
  }
}

function doURL(obj) {
  textarea = document.getElementById(obj);
  var url = prompt("Digite o endere�o:", "http://");
  var scrollTop = textarea.scrollTop;
  var scrollLeft = textarea.scrollLeft;

  if (document.selection) {
    textarea.focus();
    var sel = document.selection.createRange();

    if (sel.text == "") {
      sel.text = "[url]" + url + "[/url]";
    } else {
      sel.text = "[url=" + url + "]" + sel.text + "[/url]";
    }

    //alert(sel.text);
  } else {
    var len = textarea.value.length;
    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;

    var sel = textarea.value.substring(start, end);

    if (sel == "") {
      var rep = "[url]" + url + "[/url]";
    } else {
      var rep = "[url=" + url + "]" + sel + "[/url]";
    }
    //alert(sel);

    textarea.value =
      textarea.value.substring(0, start) +
      rep +
      textarea.value.substring(end, len);

    textarea.scrollTop = scrollTop;
    textarea.scrollLeft = scrollLeft;
  }
}

function doColor(obj) {
  textarea = document.getElementById(obj);
  var cor = prompt("Escreva uma cor: (em ingl�s)", "black");
  var scrollTop = textarea.scrollTop;
  var scrollLeft = textarea.scrollLeft;

  if (document.selection) {
    textarea.focus();
    var sel = document.selection.createRange();

    if (sel.text == "") {
      sel.text = "[color=" + cor + "][/color]";
    } else {
      sel.text = "[color=" + cor + "]" + sel.text + "[/color]";
    }

    //alert(sel.text);
  } else {
    var len = textarea.value.length;
    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;

    var sel = textarea.value.substring(start, end);

    if (sel == "") {
      var rep = "[color=" + cor + "][/color]";
    } else {
      var rep = "[color=" + cor + "]" + sel + "[/color]";
    }
    //alert(sel);

    textarea.value =
      textarea.value.substring(0, start) +
      rep +
      textarea.value.substring(end, len);

    textarea.scrollTop = scrollTop;
    textarea.scrollLeft = scrollLeft;
  }
}

function doAddTags(tag1, tag2, obj) {
  textarea = document.getElementById(obj);
  // Code for IE
  if (document.selection) {
    textarea.focus();
    var sel = document.selection.createRange();
    //alert(sel.text);
    sel.text = tag1 + sel.text + tag2;
  } else {
    // Code for Mozilla Firefox
    var len = textarea.value.length;
    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;

    var scrollTop = textarea.scrollTop;
    var scrollLeft = textarea.scrollLeft;

    var sel = textarea.value.substring(start, end);
    //alert(sel);
    var rep = tag1 + sel + tag2;
    textarea.value =
      textarea.value.substring(0, start) +
      rep +
      textarea.value.substring(end, len);

    textarea.scrollTop = scrollTop;
    textarea.scrollLeft = scrollLeft;
  }
}
