<?php
include("lib.php");
define("PAGENAME", "Ganhar Ouro");
$player = check_user($secret_key, $db);
include("templates/private_header.php");

echo "<fieldset>";
echo "<legend><b>Está precisando de ouro?</b></legend>";
echo "Que tal ganhar <b>" . $setting->earn . " moedas de ouro</b> por cada amigo que você convidar para o jogo?<br/><br/>";
echo "Ã simples, basta o seu amigo se registrar no jogo através do seu <b>Link de Referência</b>, e assim que ele atingir o nível " . $setting->activate_level . ", " . $setting->earn . " moedas de ouro serão adicionados na sua conta.";
echo "<br/><br/>";





echo "<b>Link de Referência:</b> <a href=\"" . $domain_url . "?r=" . $player->id . "\"" . $domain_url . "/?r=" . $player->id . "</a><br/>";
echo "<b>Amigos convidados:</b> " . $player->ref . "";
echo "</fieldset>";



if ($setting->event_convidados = true) {
	echo "<fieldset>";
	echo "<legend><b>Evento Convide Mais Amigos.</b></legend>";
	echo "<br/>Convide amigos para jogar o confronto, além de ganhar gold você terá chance de ganhar itens. Acumulando amigos convidados você terá chance de resgatar um item automaticamente.<p>";
	echo "Cada item abaixo para ser resgatado precisa ter uma quantidade de amigos convidados, após atingir a quantidade requerida o sistema irá automaticamente depositar o item na sua conta. Algumas recompensas você até poderá ganhar item e gold juntos!";
	echo "<br /><br />Lista de Prêmios:";

	$query2 = mysql_query("select * from ref_list_prem order by qt asc");
	while ($row = mysql_fetch_array($query2)) {
		$type_id = $row['item_id'];
		$query3 = mysql_query("select * from `blueprint_items` where `id` = $type_id ");
		while ($row2 = mysql_fetch_array($query3)) {

			if ($row2['type'] == "shield") {
				$itemtd = "Defesa";
			}
			if ($row2['type'] == "helmet") {
				$itemtd = "Defesa";
			}
			if ($row2['type'] == "legs") {
				$itemtd = "Defesa";
			}
			if ($row2['type'] == "boots") {
				$itemtd = "Defesa";
			}
			if ($row2['type'] == "weapon") {
				$itemtd = "Ataque";
			}


?>
			<p>
			<table id="table" align="left">
				<?php
				if (($row['bonus'] > 2) and ($row['bonus'] < 6)) {
					$colorbg = "itembg2";
				} elseif (($row['bonus'] > 5) and ($row['bonus'] < 9)) {
					$colorbg = "itembg3";
				} elseif ($row['bonus'] == 9) {
					$colorbg = "itembg4";
				} elseif ($row['bonus'] > 9) {
					$colorbg = "itembg5";
				} else {
					$colorbg = "itembg1";
				}
				?>
				<td class="<?php echo $colorbg; ?>">
					<div id="weapon" title="header=[<?php echo '' . $row2['name'] . ' +';
													echo '' . $row['bonus'] . ''; ?>] body=[<table width=100%>
	
	<td width=65%><font size=1px><?php echo '' . $itemtd . ': ' . $row2['effectiveness'] . ''; ?>
	<br/><b>Convidados: <?php echo $row['qt']; ?></b><br/><b>Gold Bônus: <?php echo $row['gold']; ?></b>
	</font></td>

	<td width=35%><font size=1px>+<font color=gray><?php echo rand(1, 5); ?> for</font></font>
	
	</br>
	<font size=1px>+<font color=green><?php echo rand(1, 5); ?> vit</font></font>
	</br>
	<font size=1px>+<font color=blue><?php echo rand(1, 5); ?> agi</font></font>
	</br>
	<font size=1px>+<font color=red><?php echo rand(1, 5); ?> res</font></font>
		
	</td>
	</table>]">
						<img src="images/itens/<?php echo $row2['img']; ?>" border="0">
					</div>
					<p>

			</table>



<?php
		}
	}
	echo "Obs: O prazo para recebimento do item esta vigente apenas no prazo de validade do evento, sendo assim após o termino do evento se seu amigo convidado não atingiu o nível requerido você estará sujeito a receber um item diferente dos presentes nesta lista.";
}
echo "</fieldset>";

echo "<br /><br />";

echo "<table width=\"100%\" border=\"0\">";
echo "<tr>";
echo "<td>";
echo "<img src=\"imprime.php?id=" . $player->id . "\" alt=\"Jogue o confronto! é de graça!\" border=\"0\">";
echo "</td>";
echo "<tbody><tr>";
echo "<td>";
echo "<textarea style=\"width: 720px; height: 30px;\">[URL=" . $domain_url . "/?r=" . $player->id . "][IMG]" . $domain_url . "/imprime.php?id=" . $player->id . "[/IMG][/URL]</textarea>";
echo "</td> ";
echo "</tr>";
echo "</tbody>";

echo "<tbody><tr>";
echo "<td>";
echo "<textarea style=\"width: 720px; height: 30px;\">[url=" . $domain_url . "/?r=" . $player->id . "][img=" . $domain_url . "/imprime.php?id=" . $player->id . "][/url]</textarea>";
echo "</td> ";
echo "</tr>";
echo "</tbody>";

echo "</table>";

?>

<br />
<img src="http://img121.imageshack.us/img121/3808/24gu1i8.png" alt="Jogue o confronto! é de graça!" width="360" height="21" border="0"></a>

<table border="0">
	<tr>

		<textarea style="width: 720px; height: 30px;">[URL=http://ocrpg.net/?r=<?php echo $player->id ?>][IMG]http://img121.imageshack.us/img121/3808/24gu1i8.png[/IMG][/URL]</textarea>


	</tr>
</table>
<table border="0">
	<tbody>
		<tr>
			<textarea style="width: 720px; height: 30px;">[url=http://ocrpg.net/?r=<?php echo $player->id ?>][http://img121.imageshack.us/img121/3808/24gu1i8.png][/url]</textarea>

		</tr>
	</tbody>
</table>

<br />
<center><b>Você também pode postar seu link no twitter <a href="http://button.topsy.com/retweet?title=Estou%20jogando%20oconfronto!%20Venha%20jogar%20tambem:&url=http%3A%2F%2Focrpg.com%2F?r=<?php echo $player->id ?>" target="blank">clicando aqui</a>,<br /> ou até mesmo promove-lo no orkut, <a href="http://promote.orkut.com/preview?nt=orkut.com&tt=O%20Confronto%20MMORPG%20Medieval&du=http://ocrpg.com/?r=<?php echo $player->id ?>&cn=O%20Confronto%20%C3%A9%20um%20jogo%20web-based%20medieval.%20Participe%20de%20torneios,%20batalhas,%20miss%C3%B5es%20e%20torne-se%20um%20grande%20guerreiro!%20Comece%20a%20jogar%20agora%20e%20fa%C3%A7a%20parte%20desta%20fam%C3%ADlia!&tn=http://img2.orkut.com/images/mittel/1224878329/73799681/ln.jpg" target="blank">clicando aqui</a>.</b></center>

<?php
include("templates/private_footer.php");
?>