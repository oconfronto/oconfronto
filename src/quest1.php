<?php
include("lib.php");
define("PAGENAME", "Missões");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");

if ($player->promoted == f)
{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Você precisa ter uma vocação superior para fazer esta missão!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo '</fieldset>';
	include("templates/private_footer.php");
	exit;
}

if ($player->level < 100)
{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Seu nivel é muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}

switch($_GET['act'])
{
	case "warrior":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Além da força, iteligência e coragem, um grande guerreiro precisa de ótimos itens. Vejo que você tem ótimos itens, mas está faltando uma coisa.</i><br>\n";
		echo "<a href=\"quest1.php?act=what\">Oquê?</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "what":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você já ouviu falar no jeweled ring? Ele é capas de aumentar seu ataque, sua defesa e sua resistência.</i><br>\n";
		echo "<i>Eu posso te ajudar a conseguir este precioso anel, irei te dizer tudo que é nescesário se você me pagar uma pequena quantia de <b>120000 moedas de ouro</b>.</i><br>\n";
		echo "<a href=\"quest1.php?act=pay\">Eu pago!</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "pay":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você aceita pagar <b>120000 moedas de ouro</b> para saber tudo que precisa?</i><br>\n";
		echo "<a href=\"quest1.php?act=confirmpay\">Sim</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "raderon":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você tem certeza disso? Raderon é muito forte!</i><br>\n";
		echo "<a href=\"raderon.php\">Sim</a> | <a href=\"quest1.php\">Não</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "who":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Minha história é muito longa, eu já fui um grande guerreiro e agora ajudo as pessoas que querem seguir meu caminho.</i><br>\n";
		echo "<a href=\"quest1.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "confirmpay":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 2));
	if ($verificacao->recordcount() == 0)
		{
		if ($player->gold - 120000 < 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você não possui esta quantia de ouro!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold - 120000, $player->id));
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 2;
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Pronto, agora podemos continuar com as missões.</i><br>\n";
		echo "<a href=\"quest1.php\">Continuar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
		}else{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "Você já me pagou esta taixa!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
	break;

	case "continue1":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 2));
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 1){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", array($player->id, 112));
		if ($selectfirstitem->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você não possui um Jeweled Crystal.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(2, $player->id, 2));
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(112, $player->id, 1));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Obrigado, agora podemos passar para a próxima missão.</i><br>\n";
		echo "<a href=\"quest1.php\">Continuar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;

	case "continue2":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 2));
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 2){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", array($player->id, 112));
		if ($selectfirstitem->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você não possui um Jeweled Crystal.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(3, $player->id, 2));
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(112, $player->id, 1));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Obrigado, agora podemos passar para a próxima missão.</i><br>\n";
		echo "<a href=\"quest1.php\">Continuar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;

	case "continue3":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 2));
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 3){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", array($player->id, 112));
		if ($selectfirstitem->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você não possui um Jeweled Crystal.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(4, $player->id, 2));
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(112, $player->id, 1));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Obrigado, agora podemos passar para a próxima missão.</i><br>\n";
		echo "<a href=\"quest1.php\">Continuar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;


	case "titanium":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 2));
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 5){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", array($player->id, 111));
		if ($selectfirstitem->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você não possui uma Titanium Wheel.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(90, $player->id, 2));
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(111, $player->id, 1));

			$insert['player_id'] = $player->id;
			$insert['item_id'] = 163;
			$db->autoexecute('items', $insert, 'INSERT');
			$ringid = $db->Insert_ID();
			$db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", array(20, 20, 20, 20, $ringid));

		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Pronto, ai está seu Jeweled Ring.</i><br>\n";
		echo "(Acesse seu inventário para equipa-lo.)<br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;

}
?>
<?php
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 2));
	$quest = $verificacao->fetchrow();

	if ($verificacao->recordcount() == 0)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>A muito tempo ninguém procura por mim. Oquê lhe traz aqui?</i><br/>\n";
		echo "<a href=\"quest1.php?act=who\">Quem é você?</a> | <a href=\"quest1.php?act=warrior\">Quero me tornar um grande guerreiro</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 1)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Para criar o anel, são nescesários três <b>Jeweled Crystals</b>. Você pode obtelos matando Dragões de Pedra ou comprando no mercado.</i><br/>\n";
		echo "<i>Quando conseguir o primeiro jeweled crystal volte aqui.</i><br/>\n";
		echo "<a href=\"quest1.php?act=continue1\">Já possuo o jeweled crystal</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 2)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você já me entegou um <b>jeweled crystal</b>, preciso de mais dois. Você pode obtelos matando Dragões de Pedra ou comprando no mercado.</i><br/>\n";
		echo "<i>Quando conseguir o segundo jeweled crystal volte aqui.</i><br/>\n";
		echo "<a href=\"quest1.php?act=continue2\">Já possuo o jeweled crystal</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 3)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você já me entegou dois <b>jeweled crystals</b>, preciso de mais um. Você pode obtelo matando Dragões de Pedra ou comprando no mercado.</i><br/>\n";
		echo "<i>Quando conseguir o terceiro jeweled crystal volte aqui.</i><br/>\n";
		echo "<a href=\"quest1.php?act=continue3\">Já possuo o jeweled crystal</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 4)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Agora que possuo todos os cristais nescesários só preciso de uma peça para montar o anel, uma titanium wheel. A única maneira de obtela é matando Raderon, um poderoso guerreiro.</i><br/><br/>\n";
		echo "<a href=\"quest1.php?act=raderon\">Quero lutar contra Raderon</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 5)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Nossa! Você conseguiu mesmo vencer raderon?!</i><br/>\n";
		echo "<i>Vamos acabar logo com isso, me entregue a titanium wheel e eu criarei o anel.</i><br/>\n";
		echo "<a href=\"quest1.php?act=titanium\">Entregar a titanium wheel</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 90)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você já fez esta missão!</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

?>