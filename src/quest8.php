<?php
include("lib.php");
define("PAGENAME", "Missões");
$player = check_user($secret_key, $db);
include("checkbattle.php");

if ($player->level < 300)
{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Seu nivel é muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}

	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 17));

	if ($verificacao1->recordcount() > 0){
	$quest1 = $verificacao1->fetchrow();
	}


$verificacao3 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`=?", array($player->id, 15, 90));
if ($verificacao3->recordcount() < 1){
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você precisa completar outra missão primeiro.</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}

switch($_GET['act'])
{

	case "question":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Você já ouviu falar em um oddin orb? Ele é um orb raríssimo, que cai em qualquer monstro morto por usuários de nível 75 ou mais. Um membro da elite imperial deve ter experiência em procurar itens diversos, e por isso quero que me traga <u>dois oddin orbs</u>.</i><br><br>\n";
		echo "<a href=\"quest8.php?act=acept\">Aceitar a Missão</a> / <a href=\"quest8.php?act=decline\">Recusar</a>";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;


	case "decline":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Vai abandonar a elite imperial agora? Sei que voltará em breve.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "acept":
		if ($verificacao1->recordcount() > 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Você já aceitou esta missão.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		include("templates/private_header.php");

		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 17;   	  
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');

		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Ótimo, volte aqui quando possuir os dois orbs.</i><br /><br />";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;
}
?>
<?php
	if ($verificacao1->recordcount() == 0)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>" . $player->username . ", se você realmente estiver interessado em fazer parte da elite imperial, precisará procurar por alguns itens para mim.</i><br/><br>\n";
		echo "<a href=\"quest8.php?act=question\">Quais Itens?</a> / <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 1)
		{
			$contaorbs = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", array($player->id, 156));

			if ($contaorbs->recordcount() >= 2)
			{
			$deletaorbs = $db->execute("delete from `items` where `player_id`=? and `item_id`=? LIMIT 2", array($player->id, 156));
			$updatestatus = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(80, $player->id, 17));
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você entregou os dois orbs para Alexander.</i><br><br>";
			echo "<a href=\"quest8.php\">Continuar</a>.";
	     	 	echo "</fieldset>";
			include("templates/private_footer.php");
			exit;
			}
			else
			{
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
			echo "<i>Você ainda não possui os dois oddin orbs que solicitei.</i><br><br>";
			echo "<a href=\"home.php\">Página Principal</a>.";
	     	 	echo "</fieldset>";
			include("templates/private_footer.php");
			exit;
			}

		}

	if ($quest1['quest_status'] == 80)
	{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(90, $player->id, 17));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Fiquei impressionado quando você me entregou os orbs. Geralmente os guerreiros demoram muito mais tempo para reuni-los. Isso me prova que você é um ótimo guerreiro, e acho que já podemos passar para o teste final.</i><br><br>";
		echo "<a href=\"quest9.php\">Continuar</a> / <a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
	}

	if ($quest1['quest_status'] == 90)
	{
		header("Location: quest9.php");
	}

?>