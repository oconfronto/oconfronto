<?php
include("lib.php");
$player = check_user($secret_key, $db);

$query = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`!=90 and `player_id`=?", array($player->id));
if ($query->recordcount() == 0){
	header("Location: home.php");
}else{
	$get = $query->fetchrow();
}

if (($get['pending_status'] > 1) and ($get['pending_status'] < 90)){

	if (($_GET['act'] > 1) and ($_GET['act'] <= 90)){
        if (($_GET['act'] == 90) and (!$_GET['comfirm']))
        {
            define("PAGENAME", "Tutorial");
			include("templates/private_header.php");
			echo "<center>Você tem certeza que deseja pular o turorial? Ele levará menos de 5 minutos para ser concluído, irá mostrar as dicas básicas do jogo e ainda o ajudará a configurar seu personagem pela primeira vez.<br/><br/>";
			echo "<b><a href=\"start.php\">Clique aqui e continue com o tutorial.</a></b><br/><font size=\"1px\"><a href=\"start.php?act=90&comfirm=true\">Sair do tutorial.</a></font></center>";
			include("templates/private_footer.php");
			exit;
            exit; 
        } else {
            $db->execute("update `pending` set `pending_status`=? where `pending_id`=2 and `player_id`=?", array($_GET['act'], $player->id));
            header("Location: home.php");
            exit;
        }
	}

}

	if (($get['pending_status'] == 1) or ($player->reino == 0)){
		if ($_GET['reino'] == 1){
			$db->execute("update `players` set `reino`='1' where `id`=?", array($player->id));
			$db->execute("update `pending` set `pending_status`=2 where `pending_id`=2 and `player_id`=?", array($player->id));

			define("PAGENAME", "Reino Cathal");
			include("templates/private_header.php");
			echo "<center>Bem vindo ao reino de Cathal, voc&ecirc; fez uma s·bia escolha ao unir-se a nÛs.<br/><br/>";
			echo "<b><a href=\"start.php\">Clique aqui e continue com o tutorial.</a></b><br/><font size=\"1px\"><a href=\"start.php?act=90\">Pular tutorial.</a></font></center>";
			include("templates/private_footer.php");
			exit;

		}elseif ($_GET['reino'] == 2){
			$db->execute("update `players` set `reino`='2' where `id`=?", array($player->id));
			$db->execute("update `pending` set `pending_status`=2 where `pending_id`=2 and `player_id`=?", array($player->id));

			define("PAGENAME", "Reino Eroda");
			include("templates/private_header.php");
			echo "<center>Bem vindo ao reino de Eroda, voc&ecirc; fez uma s·bia escolha ao unir-se a nÛs.<br/><br/>";
			echo "<b><a href=\"start.php\">Clique aqui e continue com o tutorial.</a></b><br/><font size=\"1px\"><a href=\"start.php?act=90\">Pular tutorial.</a></font></center>";
			include("templates/private_footer.php");
			exit;

		}elseif ($_GET['reino'] == 3){
			$db->execute("update `players` set `reino`='3', `hp`=?, `maxhp`=? where `id`=?", array(maxHp($db, $player->id, ($player->level - 1), 3, $player->vip), maxHp($db, $player->id, ($player->level - 1), 3, $player->vip), $player->id));
			$db->execute("update `pending` set `pending_status`=2 where `pending_id`=2 and `player_id`=?", array($player->id));

			define("PAGENAME", "Reino Turkic");
			include("templates/private_header.php");
			echo "<center>Bem vindo ao reino de Turkic, voc&ecirc; fez uma s·bia escolha ao unir-se a nÛs.<br/><br/>";
			echo "<b><a href=\"start.php\">Clique aqui e continue com o tutorial.</a></b><br/><font size=\"1px\"><a href=\"start.php?act=90\">Pular tutorial.</a></font></center>";
			include("templates/private_footer.php");
			exit;
		}


		define("PAGENAME", "Escolha seu Reino");
		include("templates/private_header.php");
		echo "O mundo de O Confronto È dividido em 3 grandes reinos, Cathal, Eroda e Turkic.<br/>";
		echo "<font size=\"1px\">Cada reino possui caracterÌsticas diferentes. Escolha o seu reino com sabedoria, pois ele n„o poder· ser alterado.</font><br/>";
			echo "<ul>";
			echo "<li><b>Reino Cathal:</b> Itens e bebidas s„o 10% mais baratos; Magias requerem 5 pontos de mana a menos.</li>";
			echo "<li><b>Reino Eroda:</b> Permite voc&ecirc; caÁar e trabalhar por 1 hora extra.</li>";
			echo "<li><b>Reino Turkic:</b> BÙnus de vida de aproximadamente 8% aos seus membros.</li>";
			echo "</ul>";

		echo "<br/><table width=\"100%\"><tr>";
			echo "<td width=\"33%\">";
				$reinoa = $db->execute("select `id` from `players` where `reino`=1");
				$imperador = $db->GetOne("select `imperador` from `reinos` where `id`=1");
				echo showAlert("<b>Reino Cathal</b><br/><img src=\"images/reinoa.png\" width=\"82px\" height=\"82px\" border=\"0px\" alt=\"Cathal\"/><br/><br/><font size=\"1px\"><b>Membros:</b> " . $reinoa->recordcount() . "<br/><b>LÌder:</b> " . showName($imperador, $db, 'off', 'off') . "</font>", "white", "center", "start.php?reino=1");
			echo "</td>";
			echo "<td width=\"33%\">";
				$reinob = $db->execute("select `id` from `players` where `reino`=2");
				$imperador = $db->GetOne("select `imperador` from `reinos` where `id`=2");
				echo showAlert("<b>Reino Eroda</b><br/><img src=\"images/reinob.png\" width=\"82px\" height=\"82px\" border=\"0px\" alt=\"Eroda\"/><br/><br/><font size=\"1px\"><b>Membros:</b> " . $reinob->recordcount() . "<br/><b>LÌder:</b> " . showName($imperador, $db, 'off', 'off') . "</font>", "white", "center", "start.php?reino=2");
			echo "</td>";
			echo "<td width=\"34%\">";
				$reinoc = $db->execute("select `id` from `players` where `reino`=3");
				$imperador = $db->GetOne("select `imperador` from `reinos` where `id`=3");
				echo showAlert("<b>Reino Turkic</b><br/><img src=\"images/reinoc.png\" width=\"82px\" height=\"82px\" border=\"0px\" alt=\"Turkic\"/><br/><br/><font size=\"1px\"><b>Membros:</b> " . $reinoc->recordcount() . "<br/><b>LÌder:</b> " . showName($imperador, $db, 'off', 'off') . "</font>", "white", "center", "start.php?reino=3");
			echo "</td>";
		echo "</tr></table>";


		include("templates/private_footer.php");
		exit;
	}
	
	elseif ($get['pending_status'] == 2){
	
		define("PAGENAME", "Tutorial");
		include("templates/private_header.php");
		echo "Sempre existiu <u>muita rivalidade</u> entre os 3 reinos, mas como se isso n„o fosse suficiente, os mais diversos tipos de monstros rodeiam as florestas deste mundo.<br/><br/>";
		echo "Ao caÁar por <u>monstros</u> ou <u>matar personagens</u> de outros reinos voc&ecirc; ganha <u>pontos de experi&ecirc;ncia</u> e fica mais forte. Voc&ecirc; tambÈm poder· matar personagens do seu prÛprio reino, mas voc&ecirc; n„o quer uma queda na sua popularidade, quer?<br/><br/>";
		echo "<center><b><a href=\"start.php?act=3\">Clique aqui e continue com o tutorial.</a></b><br/><font size=\"1px\"><a href=\"start.php?act=90\">Pular tutorial.</a></font></center>";
		include("templates/private_footer.php");
		exit;
	}

	elseif ($get['pending_status'] == 3){
		header("Location: stat_points.php");
		exit;
	}

	elseif ($get['pending_status'] == 4){
		header("Location: inventory.php");
		exit;
	}

	elseif ($get['pending_status'] == 5){
		header("Location: home.php");
		exit;
	}

	elseif ($get['pending_status'] == 6){
		header("Location: monster.php");
		exit;
	}

	elseif ($get['pending_status'] == 7){
	
		define("PAGENAME", "Tutorial");
		include("templates/private_header.php");
		echo "Voc&ecirc; j· aprendeu a lutar, mas aqui tambÈm ser· nesces·rio conquistar os demais guerreiros. Amigos e aliados lhe trar„o benefÌcios durante sua jornada, por isso n„o deixe de visitar o <u>chat</u> e o <u>fÛrum</u>.<br/><br/>";
		echo "<font size=\"1px\">Com seus amigos voc&ecirc; poder·:</font><br/>";
			echo "<ul>";
			echo "<li>Criar <b>grupos de caÁa</b>, ganhando bÙnus de experi&ecirc;ncia.</li>";
			echo "<li>Participar de <b>cl„s</b> e ser temido na batalha contra jogadores.</li>";
			echo "<li><b>Trocar</b> itens raros com maior facilidade.</li>";
			echo "</ul>";

		echo "<center><b><a href=\"start.php?act=8\">Clique aqui e continue com o tutorial.</a></b></center>";
		include("templates/private_footer.php");
		exit;
	}

	elseif ($get['pending_status'] == 8){
	
		define("PAGENAME", "Tutorial");
		include("templates/private_header.php");

		$reino = $db->GetOne("select `nome` from `reinos` where `id`=?", array($player->reino));
		$imperador = $db->GetOne("select `imperador` from `reinos` where `id`=?", array($player->reino));
		$taxa = $db->GetOne("select `tax` from `reinos` where `id`=?", array($player->reino));

		echo "Voc&ecirc; faz parte do reino <b>" . $reino . "</b>, cujo imperador È " . showName($imperador, $db, 'off') . ". A cada 2 semanas uma <u>eleiÁ„o para imperador</u> È realizada, onde os usu·rios que possuem a maior mÈdia de horas online por dia podem se candidatar.<br/>";
		echo "Os <u>impostos que voc&ecirc; paga</u> diariamente (" . $taxa . "% do ouro que voc&ecirc; possui) vai para os cofres do reino. O imperador poder· investir este dinheiro em:<br/>";
			echo "<ul>";
			echo "<li>Aumento de sal·rios.</li>";
			echo "<li>Realizar eventos.</li>";
			echo "<li>DistribuiÁ„o de poÁıes entre jogadores do reino.</li>";
			echo "</ul>";

		echo "Este È o b·sico de tudo que voc&ecirc; deve saber, o resto a vida ir· lhe ensinar.<br/>";
		echo "Caso possua alguma d˙vida, n„o exite em perguntar aos veteranos no <u>fÛrum</u>.<br/><br/>";

		echo "<center><b><a href=\"start.php?act=90&comfirm=true\">Finalizar tutorial.</a></b></center>";
		include("templates/private_footer.php");
		exit;

	} else {
		header("Location: home.php");
		exit;
	}
?>