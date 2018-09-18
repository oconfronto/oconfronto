<?php

include("lib.php");
define("PAGENAME", "Missões");
$player = check_user($secret_key, $db);
include("checkbattle.php");

$calculo = ceil(($player->level * $player->level) * 1.5);
$cost = ceil($calculo);



if ($player->level < 145)
{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Treinador</b></legend>\n";
	echo "<i>Seu nivel é muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}

if ($player->level > 155)
{
	$query = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=8", array($player->id));
	$query = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=9", array($player->id));
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Treinador</b></legend>\n";
	echo "<i>Seu nivel é muito alto!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}


switch($_GET['act'])
{

	case "who":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Eu treino guerreiros, ganho a vida assim.</i><br><br>\n";
		echo "<a href=\"quest5.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "help":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Bom, esse é meu trabalho, treinar guerreiros. Gostaria de começar seu treinamento por " . $cost . " de ouro?<br>Se eu te treinar, você poderá adiquirir até três níveis!</i><br><br>\n";
		echo "<a href=\"quest5.php?act=acept\">Aceito</a> | <a href=\"quest5.php?act=decline\">Recuso</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "decline":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Tudo bem, a escolha é sua.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "begin":
		$verificationertz = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=8 and `quest_status`=1", array($player->id));
		if ($verificationertz->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu, contate o administrador.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
	}else{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=8", array($player->monsterkilled + $player->groupmonsterkilled + 200, $player->id));
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=9", array($player->kills + 30, $player->id));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Você é mesmo um ótimo guerreiro como eu ouvi dizer por ai?<br/><b>Mate 200 monstros</b> e <b>30 usuários</b> e eu te darei os 3 níveis.</i><br><br>\n";
		echo "<a href=\"quest5.php\">Continuar</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	}
	break;

	case "acept":
		$verifikcheck = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=8", array($player->id));
		if ($verifikcheck->recordcount() != 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "Você já me pagou!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
			if ($player->gold - $cost < 0){
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Treinador</b></legend>\n";
			echo "<i>Você não possui esta quantia de ouro!</i><br/><br/>\n";
			echo "<a href=\"home.php\">Voltar</a>.";
	        	echo "</fieldset>";
			include("templates/private_footer.php");
			exit;
		}else{
		$query = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold - $cost, $player->id));
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 8;
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 9;
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Obrigado. vamos logo começar com o treinamento.</i><br><br>\n";
		echo "<a href=\"quest5.php\">Começar Treinamento</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
	break;

}
?>
<?php
	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 8));
	$quest1 = $verificacao1->fetchrow();

	$verificac2 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 9));
	$quest2 = $verificac2->fetchrow();

	if ($verificacao1->recordcount() == 0 and $verificac2->recordcount() == 0)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Olá meu jovem. Porque me procura?</i><br/><br>\n";
		echo "<a href=\"quest5.php?act=who\">Quem é você?</a> | <a href=\"quest5.php?act=help\">Preciso treinar</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}


	if ($quest1['quest_status'] == 1)
		{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=8", array($player->monsterkilled + $player->groupmonsterkilled + 200, $player->id));
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=9", array($player->kills + 30, $player->id));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Você é mesmo um ótimo guerreiro como eu ouvi dizer por ai?<br/><b>Mate 200 monstros</b> e <b>30 usuários</b> e eu te darei os 3 níveis.</i><br><br>\n";
		echo "<a href=\"quest5.php\">Continuar</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}

	if ($quest1['quest_status'] > 170)
		{

		$remaining = ($quest1['quest_status'] - $player->monsterkilled - $player->groupmonsterkilled);
		$remaining2 = ($quest2['quest_status'] - $player->kills);

		if ($remaining < 0){
		$remaining = 0;
		}

		if ($remaining2 < 0){
		$remaining2 = 0;
		}
		
		if (($remaining < 1) and ($remaining2 < 1)){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=8", array(90, $player->id));
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=9", array(2, $player->id));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Você já matou o suficiente.</i><br><br>";
		echo "<a href=\"quest5.php\">Continuar</a>.";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Você precisa matar <b>" . $remaining . " monstro(s)</b> e <b>" . $remaining2 . " usuário(s)</b> para terminar seu treinamento.</i><br><br>";
		echo "<a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		}

	if ($quest2['quest_status'] == 2)
		{

        $db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", array(maxMana(($player->level + 2), $player->extramana), maxMana(($player->level + 2), $player->extramana), $player->id));
        $db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", array(maxEnergy(($player->level + 2), $player->vip), $player->id));
        $db->execute("update `players` set `stat_points`=`stat_points`+9, `level`=`level`+3, `magic_points`=`magic_points`+3, `hp`=?, `maxhp`=?, `exp`=0 where `id`=?", array(maxHp($db, $player->id, ($player->level + 2), $player->reino, $player->vip), maxHp($db, $player->id, ($player->level + 2), $player->reino, $player->vip), $player->id));

		$newlvl = ($player->level+3);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=9", array(90, $player->id));
		include("templates/private_header.php");
            echo "<fieldset><legend><b>Treinador</b></legend>\n";
            echo "<i>Bom, parece mesmo que você é um ótimo guerreiro.<br><b>(Você passou para o nível " . $newlvl . ")</b></i><br><br>";
            echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest2['quest_status'] == 90)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
		echo "<i>Você já fez esta missão.</i><br><br>";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}



?>