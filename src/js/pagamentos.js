var nText = new Array()
nText[0] = "<b>Escolha uma profissão.</b><br><br><br>";
nText[1] = "<b>Pagamento por hora:</b> 375<br><b>Energia por hora:</b> 5<br/><b>Nivel de trabalho:</b> 1";
nText[2] = "<b>Pagamento por hora:</b> 700<br><b>Energia por hora:</b> 5<br/><b>Nivel de trabalho:</b> 9";
nText[3] = "<b>Pagamento por hora:</b> 1300<br><b>Energia por hora:</b> 10<br/><b>Nivel de trabalho:</b> 22";
nText[4] = "<b>Pagamento por hora:</b> 1800<br><b>Energia por hora:</b> 10<br/><b>Nivel de trabalho:</b> 35";
nText[5] = "<b>Pagamento por hora:</b> 2200<br><b>Energia por hora:</b> 15<br/><b>Nivel de trabalho:</b> 48";
nText[6] = "<b>Pagamento por hora:</b> 2800<br><b>Energia por hora:</b> 15<br/><b>Nivel de trabalho:</b> 65";
nText[7] = "<b>Pagamento por hora:</b> 3300<br><b>Energia por hora:</b> 20<br/><b>Nivel de trabalho:</b> 78";
nText[8] = "<b>Pagamento por hora:</b> 4000<br><b>Energia por hora:</b> 25<br/><b>Nivel de trabalho:</b> 90";
function swapText(isList){
txtIndex = isList.selectedIndex;
document.getElementById('textDiv').innerHTML = nText[txtIndex];
}