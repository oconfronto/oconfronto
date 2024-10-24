<!--

var intervalo;

function carregar() {
    set_xmlhttp();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4) {
            document.getElementById('usr').innerHTML = xmlhttp.responseText;
            intervalo = self.setInterval("atualizar()", 5000);
        }
    }
    xmlhttp.open('GET', 'engine.php', true);
    xmlhttp.send(null);
}

function atualizar() {
    intervalo = window.clearInterval(intervalo);
    carregar();
}
carregar();

-->