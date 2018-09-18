<?php
	include("lib.php");
	define("PAGENAME", "Opções da conta");
	$acc = check_acc($secret_key, $db);

	include("templates/acc-header.php");
    echo "<span id=\"aviso-a\"></span>";
    
	echo "<br/><p>";
	echo "<center><a href=\"accpass.php\">Alterar senha desta conta.</a><br/></center>";
	echo "<center><a href=\"changemail.php\">Alterar email desta conta.</a><br/></center>";
	echo "<center><a href=\"editinfo.php\">Alterar configurações pessoais.</a><br/></center>";
    	echo "<center><a href=\"transferchar.php\">Transferir personagem para esta conta.</a><br/><br/></center>";

	echo "<center><font size=\"1px\"><a href=\"characters.php\"><b>Voltar</b></a> - <a href=\"#\" onclick=\"javascript:window.open('accountlog.php', '_blank','top=100, left=100, height=350, width=520, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Exibir logs da conta</a></font></center><br/></p>";

	include("templates/acc-footer.php");
?>