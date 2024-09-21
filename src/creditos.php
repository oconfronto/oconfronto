<?php
	include("lib.php");
	define("PAGENAME", "Créditos");
	$player = check_user($secret_key, $db);
	include("templates/private_header.php");
?>
<b>As pessoas envolvidas na criação do site são:</b>
<br/><br/>
<b>Engine:</b> <i>ezRPG.</i><br />
<b>Códigos PHP:</b> <i>Zeggy, Jrotta, Adiel Araujo, Tshj, Treta, Die4me, Khashul, Cdoyle e outros membros do fórum do ezRPG Project.</i><br />
<b>Imagens:</b> <i>Wonderrow, Jrotta, Smurtz, CipSoft GmbH, Spyware, Snow, Lok, Yurizito, Paulistasks.</i><br />
<b>Icones:</b> <i>famfamfam.com</i><br />
<b>Itens:</b> <i>Lionblood, Galiant, Jrotta, Marcotonio, JuanDrake(Gawain), Rockonra, Adrianox, Pabloloko, Yurizito.</i><br />
<b>Agradeça pelos efeitos em javascript à:</b> <i>Treta e Jrotta.</i><br />
<?php
	include("templates/private_footer.php");
?>