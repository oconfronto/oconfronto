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
			echo "<center>Bem vindo ao reino de Cathal, voc&ecirc; fez uma sábia escolha ao unir-se a nós.<br/><br/>";
			echo "<b><a href=\"start.php\">Clique aqui e continue com o tutorial.</a></b><br/><font size=\"1px\"><a href=\"start.php?act=90\">Pular tutorial.</a></font></center>";
			include("templates/private_footer.php");
			exit;

		}elseif ($_GET['reino'] == 2){
			$db->execute("update `players` set `reino`='2' where `id`=?", array($player->id));
			$db->execute("update `pending` set `pending_status`=2 where `pending_id`=2 and `player_id`=?", array($player->id));

			define("PAGENAME", "Reino Eroda");
			include("templates/private_header.php");
			echo "<center>Bem vindo ao reino de Eroda, voc&ecirc; fez uma sábia escolha ao unir-se a nós.<br/><br/>";
			echo "<b><a href=\"start.php\">Clique aqui e continue com o tutorial.</a></b><br/><font size=\"1px\"><a href=\"start.php?act=90\">Pular tutorial.</a></font></center>";
			include("templates/private_footer.php");
			exit;

		}elseif ($_GET['reino'] == 3){
			$db->execute("update `players` set `reino`='3', `hp`=?, `maxhp`=? where `id`=?", array(maxHp($db, $player->id, ($player->level - 1), 3, $player->vip), maxHp($db, $player->id, ($player->level - 1), 3, $player->vip), $player->id));
			$db->execute("update `pending` set `pending_status`=2 where `pending_id`=2 and `player_id`=?", array($player->id));

			define("PAGENAME", "Reino Turkic");
			include("templates/private_header.php");
			echo "<center>Bem vindo ao reino de Turkic, voc&ecirc; fez uma sábia escolha ao unir-se a nós.<br/><br/>";
			echo "<b><a href=\"start.php\">Clique aqui e continue com o tutorial.</a></b><br/><font size=\"1px\"><a href=\"start.php?act=90\">Pular tutorial.</a></font></center>";
			include("templates/private_footer.php");
			exit;
		}


		define("PAGENAME", "Escolha seu Reino");
		include("templates/private_header.php");
		echo "O mundo de O Confronto é dividido em 3 grandes reinos, Cathal, Eroda e Turkic.<br/>";
		echo "<font size=\"1px\">Cada reino possui características diferentes. Escolha o seu reino com sabedoria, pois ele não poderá ser alterado.</font><br/>";
			echo "<ul>";
			echo "<li><b>Reino Cathal:</b> Itens e bebidas são 10% mais baratos; Magias requerem 5 pontos de mana a menos.</li>";
			echo "<li><b>Reino Eroda:</b> Permite voc&ecirc; caçar e trabalhar por 1 hora extra.</li>";
			echo "<li><b>Reino Turkic:</b> Bônus de vida de aproximadamente 8% aos seus membros.</li>";
			echo "</ul>";

		echo "<br/><table width=\"100%\"><tr>";
			echo "<td width=\"33%\">";
				$reinoa = $db->execute("select `id` from `players` where `reino`=1");
				$imperador = $db->GetOne("select `imperador` from `reinos` where `id`=1");
				echo showAlert("<b>Reino Cathal</b><br/><img src=\"images/reinoa.png\" width=\"82px\" height=\"82px\" border=\"0px\" alt=\"Cathal\"/><br/><br/><font size=\"1px\"><b>Membros:</b> " . $reinoa->recordcount() . "<br/><b>Líder:</b> " . showName($imperador, $db, 'off', 'off') . "</font>", "white", "center", "start.php?reino=1");
			echo "</td>";
			echo "<td width=\"33%\">";
				$reinob = $db->execute("select `id` from `players` where `reino`=2");
				$imperador = $db->GetOne("select `imperador` from `reinos` where `id`=2");
				echo showAlert("<b>Reino Eroda</b><br/><img src=\"images/reinob.png\" width=\"82px\" height=\"82px\" border=\"0px\" alt=\"Eroda\"/><br/><br/><font size=\"1px\"><b>Membros:</b> " . $reinob->recordcount() . "<br/><b>Líder:</b> " . showName($imperador, $db, 'off', 'off') . "</font>", "white", "center", "start.php?reino=2");
			echo "</td>";
			echo "<td width=\"34%\">";
				$reinoc = $db->execute("select `id` from `players` where `reino`=3");
				$imperador = $db->GetOne("select `imperador` from `reinos` where `id`=3");
				echo showAlert("<b>Reino Turkic</b><br/><img src=\"images/reinoc.png\" width=\"82px\" height=\"82px\" border=\"0px\" alt=\"Turkic\"/><br/><br/><font size=\"1px\"><b>Membros:</b> " . $reinoc->recordcount() . "<br/><b>Líder:</b> " . showName($imperador, $db, 'off', 'off') . "</font>", "white", "center", "start.php?reino=3");
			echo "</td>";
		echo "</tr></table>";


		include("templates/private_footer.php");
		exit;
	}
	
	elseif ($get['pending_status'] == 2){
	
		define("PAGENAME", "Tutorial");
		include("templates/private_header.php");
		echo "Sempre existiu <u>muita rivalidade</u> entre os 3 reinos, mas como se isso não fosse suficiente, os mais diversos tipos de monstros rodeiam as florestas deste mundo.<br/><br/>";
		echo "Ao caçar por <u>monstros</u> ou <u>matar personagens</u> de outros reinos voc&ecirc; ganha <u>pontos de experi&ecirc;ncia</u> e fica mais forte. Voc&ecirc; também poderá matar personagens do seu próprio reino, mas voc&ecirc; não quer uma queda na sua popularidade, quer?<br/><br/>";
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
		echo "Voc&ecirc; já aprendeu a lutar, mas aqui também será nescesário conquistar os demais guerreiros. Amigos e aliados lhe trarão benefícios durante sua jornada, por isso não deixe de visitar o <u>chat</u> e o <u>fórum</u>.<br/><br/>";
		echo "<font size=\"1px\">Com seus amigos voc&ecirc; poderá:</font><br/>";
			echo "<ul>";
			echo "<li>Criar <b>grupos de caça</b>, ganhando bônus de experi&ecirc;ncia.</li>";
			echo "<li>Participar de <b>clãs</b> e ser temido na batalha contra jogadores.</li>";
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

		echo "Voc&ecirc; faz parte do reino <b>" . $reino . "</b>, cujo imperador é " . showName($imperador, $db, 'off') . ". A cada 2 semanas uma <u>eleição para imperador</u> é realizada, onde os usuários que possuem a maior média de horas online por dia podem se candidatar.<br/>";
		echo "Os <u>impostos que voc&ecirc; paga</u> diariamente (" . $taxa . "% do ouro que voc&ecirc; possui) vai para os cofres do reino. O imperador poderá investir este dinheiro em:<br/>";
			echo "<ul>";
			echo "<li>Aumento de salários.</li>";
			echo "<li>Realizar eventos.</li>";
			echo "<li>Distribuição de poções entre jogadores do reino.</li>";
			echo "</ul>";

		echo "Este é o básico de tudo que voc&ecirc; deve saber, o resto a vida irá lhe ensinar.<br/>";
		echo "Caso possua alguma dúvida, não exite em perguntar aos veteranos no <u>fórum</u>.<br/><br/>";

		echo "<center><b><a href=\"start.php?act=90&comfirm=true\">Finalizar tutorial.</a></b></center>";
		include("templates/private_footer.php");
		exit;

	} else {
		header("Location: home.php");
		exit;
	}
?>