<?php
declare(strict_types=1);

if ($missao['quest_status'] == 1) {
    $db->execute("update `quests` set `quest_status`='2' where `id`=?", [$missao['id']]);
    $a = "<i>Meu nome  Hastakk, sou um treinador de guerreiros. Eu no costumo me apresentar assim, mas algo me diz que h algo muito especial em voc.</i>";
    $b = '<a href="tavern.php?p=quests&start='.$quest['id'].'">Continuar</a>';

} elseif ($missao['quest_status'] == 2) {
    if ($missao['pago'] == 't') {
        //define quantos usurios deve matar
        if ($missao['extra'] == null) {
            $db->execute("update `quests` set `extra`=? where `id`=?", [$player->kills + 15, $missao['id']]);
            $remaining = 15;
        } else {
            //define quantos usurios faltam ser mortos
            $remaining = ($missao['extra'] - $player->kills);
        }

        //verifica se j nao matou todos os usurios
        if ($remaining < 1)
        {
            $db->execute("update `quests` set `quest_status`='3' where `id`=?", [$missao['id']]);
            $a = "<i>Voc&ecirc; já matou todos os usuários nescesários.</i>";
            $b = '<a href="tavern.php?p=quests&start='.$quest['id'].'">Continuar</a>.';
        } else {
            $a = "<i>Grandes guerreiros precisam aprender a matar desde cedo, então minha missão à voc&ecirc; será simples. <b>Mate " . $remaining . " usuários</b>, volte aqui, e voc&ecirc; consiguirá os 3 níveis.</i>";
            $b = '<a href="home.php">Principal</a>';
        }
    } else {
        $a = "<i>Gostaria de começar seu treinamento por " . $quest['cost'] . " de ouro?<br>Se eu te treinar, voc&ecirc; poderá adiquirir até tr&ecirc;s níveis!</i>";
        $b = '<a href="tavern.php?p=quests&start='.$quest['id'].'&pay=true">Pagar</a>';
    }
} elseif ($missao['quest_status'] == 3) {
    //d o prmio
    $db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", [maxMana(($player->level + 2), $player->extramana), maxMana(($player->level + 2), $player->extramana), $player->id]);
    $db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", [maxEnergy(($player->level + 2), $player->vip), $player->id]);
    $db->execute("update `players` set `magic_points`=?, `stat_points`=?, `level`=?, `maxhp`=?, `exp`=0, `hp`=? where `id`=?", [$player->magic_points + 3, $player->stat_points + 9, $player->level + 3, maxHp($db, $player->id, ($player->level + 2), $player->reino, $player->vip), maxHp($db, $player->id, ($player->level + 2), $player->reino, $player->vip), $player->id]);

    //finaliza a quest
    $db->execute("update `quests` set `quest_status`='90' where `id`=?", [$missao['id']]);
    $a = "<i>Bom, espero que voc&ecirc; tenha aprendido a matar.<br><b>(Voc&ecirc; passou para o nível " . ($player->level+3) . ")</i>";
    $b = '<a href="tavern.php?p=quests">Voltar</a>';
}

/*
include("lib.php");
define("PAGENAME", "Missões");
$player = check_user($db);
include("checkbattle.php");

$calculo = ($player->level * $player->level);
$cost = ceil($calculo);



if ($player->level < 25)
{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Treinador</b></legend>\n";
	echo "<i>Seu nivel é muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}

if ($player->level > 35)
{
	$query = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=5", array($player->id));
	$query = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=6", array($player->id));
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
		echo "<a href=\"quest3.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "help":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Bom, esse é meu trabalho, treinar guerreiros. Gostaria de começar seu treinamento por " . $cost . " de ouro?<br>Se eu te treinar, voc&ecirc; poderá adiquirir até tr&ecirc;s níveis!</i><br><br>\n";
		echo "<a href=\"quest3.php?act=acept\">Aceito</a> | <a href=\"quest3.php?act=decline\">Recuso</a> | <a href=\"home.php\">Voltar</a>.";
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
		$verificationertz = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=5 and `quest_status`=1", array($player->id));
		if ($verificationertz->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu, contate o administrador.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
	}else{
	$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=5", array($player->kills + 12, $player->id));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Grandes guerreiros precisam aprender a matar desde cedo, então minha missão à voc&ecirc; será simples. <b>Mate 12 usuários</b> e voc&ecirc; consiguirá os 3 níveis.</i><br><br>\n";
		echo "<a href=\"quest3.php\">Continuar</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	}
	break;

	case "acept":
		$verifikcheck = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=5", array($player->id));
		if ($verifikcheck->recordcount() != 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "Voc&ecirc; já me pagou!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
			if ($player->gold - $cost < 0){
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Treinador</b></legend>\n";
			echo "<i>Voc&ecirc; não possui esta quantia de ouro!</i><br/><br/>\n";
			echo "<a href=\"home.php\">Voltar</a>.";
	        	echo "</fieldset>";
			include("templates/private_footer.php");
			exit;
		}else{
		$query = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold - $cost, $player->id));
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 5;
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Obrigado. vamos logo começar com o treinamento.</i><br><br>\n";
		echo "<a href=\"quest3.php\">Começar Treinamento</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
	break;

}

	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 5));
	$quest1 = $verificacao1->fetchrow();

	$verificac2 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 6));
	$quest2 = $verificac2->fetchrow();

	if ($verificacao1->recordcount() == 0 and $verificac2->recordcount() == 0)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Olá meu jovem. Porque me procura?</i><br/><br>\n";
		echo "<a href=\"quest3.php?act=who\">Quem é voc&ecirc;?</a> | <a href=\"quest3.php?act=help\">Preciso treinar</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}


	if ($quest1['quest_status'] == 1)
		{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=5", array($player->kills + 12, $player->id));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Grandes guerreiros precisam aprender a matar desde cedo, então minha missão à voc&ecirc; será simples. <b>Mate 12 usuários</b> e voc&ecirc; consiguirá os 3 níveis.</i><br><br>\n";
		echo "<a href=\"quest3.php\">Continuar</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}

	if ($quest1['quest_status'] > 1)
		{

		$remaining = ($quest1['quest_status'] - $player->kills);

		if ($remaining < 1){
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 6;
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		$query = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=5", array($player->id));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Voc&ecirc; já matou todos os usuários nescesários.</i><br><br>";
		echo "<a href=\"quest3.php\">Continuar</a>.";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Voc&ecirc; precisa matar <b>" . $remaining . " usuário(s)</b> para terminar seu treinamento.</i><br><br>";
		echo "<a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		}

	if ($quest2['quest_status'] == 1)
		{
		$newlvl = ($player->level+3);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=6", array(90, $player->id));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Bom, espero que voc&ecirc; tenha aprendido a matar.<br><b>(Voc&ecirc; passou para o nível " . $newlvl . ")</b></i><br><br>";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");

        $db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", array(maxMana(($player->level + 2), $player->extramana), maxMana(($player->level + 2), $player->extramana), $player->id));
        $db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", array(maxEnergy(($player->level + 2), $player->vip), $player->id));

		$query = $db->execute("update `players` set `magic_points`=?, `stat_points`=?, `level`=?, `maxhp`=?, `exp`=0, `hp`=? where `id`=?", array($player->magic_points + 3, $player->stat_points + 9, $player->level + 3, maxHp($db, $player->id, ($player->level + 2), $player->reino, $player->vip), maxHp($db, $player->id, ($player->level + 2), $player->reino, $player->vip), $player->id));
		exit;
		}

	if ($quest2['quest_status'] == 90)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
		echo "<i>Voc&ecirc; já fez esta missão.</i><br><br>";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
*/
?>
