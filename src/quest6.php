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

	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 13));
	$verificacao2 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 14));

	if ($verificacao1->recordcount() > 0){
	$quest1 = $verificacao1->fetchrow();
	}

	if ($verificacao2->recordcount() > 0){
	$quest2 = $verificacao2->fetchrow();
	}

switch($_GET['act'])
{

	case "castle":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Já ouvi várias histórias suas através de meus mensageiros, você parece ser um forte e destemido guerreiro, qualidades que eu adimiro. E por isso lhe chamei aqui, para realizar algumas tarefas em meu nome, oque acha?<br/>Você seria recompensado generosamente.</i><br><br>\n";
		echo "<a href=\"quest6.php?act=aceptcastle\">Aceitar</a> / <a href=\"quest6.php?act=declinecastle\">Recusar</a>";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;


	case "declinecastle":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Tudo bem, poderia mandar lhe punirem caso recusace, mas tenho certeza de que você mudara de idéia.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "aceptcastle":
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
		$insert['item_id'] = 159;
		$query = $db->autoexecute('items', $insert, 'INSERT');

		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 13;   	  
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');

		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Ótimo, você fez a coisa certa ao aceitar. Vamos começar logo, preciso que alguem leve um pacote ao rei Rashar, é um pacote muito valioso, certifique-se que ele chegue em segurança.</i><br>";
		echo "<b>(você adiquiriu um pacote)</b><br><br>\n";
		echo "<a href=\"quest6.php?act=go\">Ir ao império de Rashar</a> / <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;

	case "go":
		$csadack = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=13 and (`quest_status`>100 or `quest_status`=90)", array($player->id));
		if ($csadack->recordcount() != 0){
		header("Location: home.php");
		}else{
		include("templates/private_header.php");

		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(time() + 36000, $player->id, 13));

		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você está a caminho do império de Rashar.</i><br>";
		echo "<i>Faltam 10 horas para você chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;

	case "entregar":
		if ($quest1['quest_status'] != 2){
		header("Location: home.php");
		}else{
		$vesetemobox = $db->execute("select * from `items` where `item_id`=159 and `player_id`=?", array($player->id));
		if ($vesetemobox->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Rashar</b></legend>\n";
		echo "<i>Que pacote? Você não tem nenhum pacote no seu inventário.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a> / <a href=\"quest6.php?act=abort\">Abandonar missão</a>";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}else{
		include("templates/private_header.php");
		$upxxdateeaaa = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(90, $player->id, 13));
		$deletaboxxe = $db->execute("delete from `items` where `item_id`=159 and `player_id`=? limit 1", array($player->id));

		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 14;   	  
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');

		echo "<fieldset><legend><b>Rashar</b></legend>\n";
		echo "<i>Vejo que Alexander está procurando novos guerreiros. Este pacote me é inútil, ele apenas está testando sua confiança. Vejo que você é honesto, pois este pacote possui grande valor comercial. Boa sorte guerreiro.</i><br><br>\n";
		echo "<a href=\"quest6.php?act=backalex\">Voltar à Alexander</a>";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
		}
	break;

	case "backalex":
	if (($quest1['quest_status'] == 90) and ($quest2['quest_status'] == 1)){

		$upxxdateeaz = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(time() + 36000, $player->id, 14));

		include("templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você está indo à Alexander.</i><br>";
		echo "<i>Faltam 10 horas para você chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");

		}else{
		header("Location: home.php");
		}
	break;


	case "finish":
	if (($quest1['quest_status'] == 90) and ($quest2['quest_status'] == 80)){

		$setnoventa = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(90, $player->id, 14));

		include("templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Ótimo, vamos continuar.</i><br><br>\n";
		echo "<a href=\"quest7.php\">Continuar</a> / <a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");

		}else{
		header("Location: home.php");
		}
	break;

	case "nofinish":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Não sei oque passa em sua cabeça, fazer parte da elite imperial é o sonho de todo guerreiro. Se mudar de idéia, sinta-se livre para voltar aqui.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "abort":
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(90, $player->id, 13));
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(89, $player->id, 14));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você abandonou a missão.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;
}
?>
<?php
	if ($verificacao1->recordcount() == 0)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Mensagem</b></legend>\n";
		echo "<i>" . $player->username . ", você atingiu altos níveis de batalha, e o Rei deseja falar com você pessoalmente.</i><br/><br>\n";
		echo "<a href=\"quest6.php?act=castle\">Ir ao Castelo</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}


	if ($quest1['quest_status'] == 1)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Agora que eu já lhe entreguei o pacote, vá ao império de Rashar.</i><br><br>";
		echo "<a href=\"quest6.php?act=go\">Ir ao império de Rashar</a> / <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] > 100)
		{
			if ($quest1['quest_status'] < time())
			{
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(2, $player->id, 13));
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você chegou no império de Rashar.</i><br><br>";
			echo "<a href=\"quest6.php\">Continuar</a>.";
	     	 	echo "</fieldset>";
			include("templates/private_footer.php");
			exit;
			}

		include("templates/private_header.php");
		$time = ($quest1['quest_status'] - time());
		$time_remaining = ceil($time / 60);
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você está a caminho do império de Rashar.</i><br>";
		echo "<i>Faltam $time_remaining minuto(s) para você chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 2)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Rashar</b></legend>\n";
		echo "<i>Olá " . $player->username . ", oque lhe traz aqui?</i><br><br>";
		echo "<a href=\"quest6.php?act=entregar\">Entregar pacote</a> / <a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if (($quest1['quest_status'] == 90) and ($quest2['quest_status'] == 1))
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você já entregou o pacote à Rashar, agora volte e fale com alexander.</i><br><br>";
		echo "<a href=\"quest6.php?act=backalex\">Voltar à Alexander</a>";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest2['quest_status'] > 100)
		{
			if ($quest2['quest_status'] < time())
			{
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(80, $player->id, 14));
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você chegou à Alexander.</i><br><br>";
			echo "<a href=\"quest6.php\">Continuar</a>.";
	     	 	echo "</fieldset>";
			include("templates/private_footer.php");
			exit;
			}

		include("templates/private_header.php");
		$time = ($quest2['quest_status'] - time());
		$time_remaining = ceil($time / 60);
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você está a caminho de Alexander.</i><br>";
		echo "<i>Faltam $time_remaining minuto(s) para você chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}


	if ($quest2['quest_status'] == 80)
		{
		include("templates/private_header.php");
			echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
			echo "<i>Olá " . $player->username . ", recebi uma mensagem de Rashar, ele recebeu o pacote.</i><br />";
			echo "<i>Vejo que você é um guerreiro honesto, e com o treinamento que estou disposto a oferecer, você poderá ingressar na elite imperial. Oque acha? Estará disposto a fazer alguns sacrificios?</i><br /><br />";
			echo "<a href=\"quest6.php?act=finish\">Sim</a> / <a href=\"quest6.php?act=nofinish\">Não</a>.";
	     	 	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest2['quest_status'] == 89)
	{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você abandonou esta missão.</i><br><br>";
		echo "<a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
	}

	if ($quest2['quest_status'] == 90)
	{
		header("Location: quest7.php");
	}

?>