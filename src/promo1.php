<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Missões");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

if ($player->level < 240) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Seu nivel é muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($player->voc == "knight") {
	if ($_GET['act'] == "pay") {
		$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
		if ($verificacao->recordcount() == 0) {
			if ($player->gold - 2000000 < 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
				echo "<i>Você não possui esta quantia de ouro!</i><br/><br/>\n";
				echo '<a href="home.php">Voltar</a>.';
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

   $query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 2000000, $player->id]);
   $insert['player_id'] = $player->id;
   $insert['quest_id'] = 12;
   $insert['quest_status'] = 1;
   $query = $db->autoexecute('quests', $insert, 'INSERT');
   include(__DIR__ . "/templates/private_header.php");
   echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
   echo "<i>Muito obrigado, agora vamos continuar.</i><br>\n";
   echo '<a href="promo1.php">Continuar</a>.';
   echo "</fieldset>";
   include(__DIR__ . "/templates/private_footer.php");
   exit;
		}

  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
  echo "Você já me pagou!</i><br/><br/>\n";
  echo '<a href="home.php">Voltar</a>.';
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
	}

	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
	$quest = $verificacao->fetchrow();

	if ($verificacao->recordcount() == 0) {
     if ($_GET['next'] == 1) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
         echo "<i>É sobre uma guerreiro incrível, porém não me lembro muito bem. Acho que 2 milhões ajudarão a refrescar minha memória.</i><br/>\n";
         echo '<a href="promo1.php?next=2">Eu pago</a> | <a href="promo1.php?next=3">Nunca!</a>.';
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 2) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
         echo "<i>Deseja pagar 2000000 de ouro para ouvir mais sore este guerreiro?</i><br/>\n";
         echo "<a href=\"promo1.php?act=pay\">Sim</a> | <a href=\"promo1.php?next=3\">Não</a>.";
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 3) {
   			include(__DIR__ . "/templates/private_header.php");
   			echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
   			echo "<i>Tudo bem, talvez mais tarde.</i><br/>\n";
   			echo '<a href="home.php">Voltar</a>.';
   			echo "</fieldset>";
   			include(__DIR__ . "/templates/private_footer.php");
   			exit;
   		}

     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Olá " . $player->username . "! Ouvi muito sobre você na cidade, e tambem ouvi alguns boatos que você vai querer ouvir.</i><br/>\n";
     echo "<a href=\"promo1.php?next=1\">Que Boatos?</a> | <a href=\"promo1.php?next=3\">Não estou interessado</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }

	if ($quest['quest_status'] == 1) {
     if ($_GET['next'] == 1) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
         echo "<i>Seu nome é Friden o destemido guerreiro banhado pelo sangue do dragão!<br/>Derrote Friden e obtenha sua espada que também foi banhada pelo sangue do dragão, e você poderá se tornar um grandioso Guerreiro, porém devo avisar-lhe que não é uma tarefa fácil. Friden tem muitos admiradores e amigos, cuidado para não encontralos em sua jornada.</i><br/>\n";
         echo "<a href=\"promo1.php?next=2\">Desejo começar minha jornada</a> | <a href=\"promo1.php?next=3\">Não estou preparado</a>.";
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 2) {
         $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 12]);
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
         echo "<i>Adimiro sua corragem guerreiro, boa sorte em sua jornada.</i><br/>\n";
         echo '<a href="promo1.php">Continuar</a>.';
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 3) {
   			include(__DIR__ . "/templates/private_header.php");
   			echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
   			echo "<i>Tudo bem, treine um pouco mais e mais tarde você poderá buscar por Friden.</i><br/>\n";
   			echo '<a href="home.php">Voltar</a>.';
   			echo "</fieldset>";
   			include(__DIR__ . "/templates/private_footer.php");
   			exit;
   		}

     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Dizem por ai que existe um homem que todos julgam ser imortal.  Arranque sua cabeça, perfure seus pulmões, corte-o ao meio nada o matará... A não ser, que perfurem seu coração, sua grande franqueza.</i><br/>\n";
     echo '<a href="promo1.php?next=1">Conte-me Mais!</a> | <a href="promo1.php?next=3">Voltar</a>.';
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }

	if ($quest['quest_status'] == 2) {
		if ($_GET['next'] == 3) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você conseguiu despistar Alexia, mas deverá enfrentá-la mais tarde.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Missão</b></legend>\n";
  echo "<i>Conforme você procurava por Friden, encontrou Alexia, uma de seus seguidores.</i><br/>\n";
  echo '<a href="bquest.php">Lutar contra Alexia</a> | <a href="promo1.php?next=3">Fugir</a>.';
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
	}

	if ($quest['quest_status'] == 3 || $quest['quest_status'] == 4) {
		if ($_GET['next'] == 1) {
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [4, $player->id, 12]);
			header("Location: bquest.php");
		} elseif ($_GET['next'] == 3) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você conseguiu despistar Ramthysts, mas deverá enfrentá-lo mais tarde.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		} else {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você derrotou Alexia, e continuou sua jornada, até encontrar Ramthysts, o poderoso guerreiro manipulador de fogo!</i><br/>\n";
			echo '<a href="promo1.php?next=1">Lutar contra Ramthysts</a> | <a href="promo1.php?next=3">Fugir</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}
	}

	if ($quest['quest_status'] == 5 || $quest['quest_status'] == 6) {
		if ($_GET['next'] == 1) {
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [6, $player->id, 12]);
			header("Location: bquest.php");
		} elseif ($_GET['next'] == 3) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você conseguiu despistar Friden, mas deverá enfrentá-lo mais tarde.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		} else {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você derrotou Ramthysts, e logo após encontrou Friden, que cabara de sair de uma luta, o sangue do guerreiro derrotado por Friden escorria em sua face.</i><br/>\n";
			echo '<a href="promo1.php?next=1">Lutar contra Friden</a> | <a href="promo1.php?next=3">Fugir</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}
	}


	if ($quest['quest_status'] == 7) {
		$query = $db->execute("update `players` set `promoted`=? where `id`=?", ['p', $player->id]);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 12]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Ao segurar a espada de Friden você sente seus músculos enrijecerem e seu corpo fortalecido. Você acaba de se tornar um Cavaleiro!</i><br/><br/>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($quest['quest_status'] == 90) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
		echo "<i>Você já terminou esta missão!</i><br/>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}
} elseif ($player->voc == "mage") {

	if ($_GET['act'] == "pay") {
		$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
		if ($verificacao->recordcount() == 0) {
			if ($player->gold - 2000000 < 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Missão</b></legend>\n";
				echo "<i>Você não possui esta quantia de ouro!</i><br/><br/>\n";
				echo '<a href="home.php">Voltar</a>.';
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

   $query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 2000000, $player->id]);
   $insert['player_id'] = $player->id;
   $insert['quest_id'] = 12;
   $insert['quest_status'] = 1;
   $query = $db->autoexecute('quests', $insert, 'INSERT');
   include(__DIR__ . "/templates/private_header.php");
   echo "<fieldset><legend><b>Missão</b></legend>\n";
   echo "<i>Você se inscreveu no torneio e agora podemos continuar.</i><br>\n";
   echo '<a href="promo1.php">Continuar</a>.';
   echo "</fieldset>";
   include(__DIR__ . "/templates/private_footer.php");
   exit;
		}

  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Missão</b></legend>\n";
  echo "Você já me pagou!</i><br/><br/>\n";
  echo '<a href="home.php">Voltar</a>.';
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
	}
 
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
	$quest = $verificacao->fetchrow();

	if ($verificacao->recordcount() == 0) {
     if ($_GET['next'] == 1) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Missão</b></legend>\n";
         echo "<i>Você segue e encontra um gigantesco coliseu, e ao redor vê vários dos mais famosos magos da região. Este torneio não será nada fácil. Você realmente deseja participar?</i><br/>\n";
         echo "<a href=\"promo1.php?next=2\">Sim</a> | <a href=\"promo1.php?next=3\">Não</a>.";
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 2) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Missão</b></legend>\n";
         echo "<i>Você precisará pagar 2000000 de ouro para participar do torneio. Acredite, valerá a pena!</i><br/>\n";
         echo "<a href=\"promo1.php?act=pay\">Eu pago</a> | <a href=\"promo1.php?next=3\">Não</a>.";
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 3) {
   			include(__DIR__ . "/templates/private_header.php");
   			echo "<fieldset><legend><b>Missão</b></legend>\n";
   			echo "<i>Tudo bem, talvez mais tarde.</i><br/>\n";
   			echo '<a href="home.php">Voltar</a>.';
   			echo "</fieldset>";
   			include(__DIR__ . "/templates/private_footer.php");
   			exit;
   		}

     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Missão</b></legend>\n";
     echo "<i>Você sente algo queimar sua pele durante o sono, e ao acordar se repara com uma marca talhada em seu peito em forma de um dragão. Você sabia que a hora chegava e teria de enfrentar seu destino!";
     echo "<br/>Durante toda sua vida você se preparava que estas marcas aparecessem. Está na hora de você participar do torneio dos magos, onde você deverá provar que você domina seus feitiços e sua mente.</i><br/>\n";
     echo '<a href="promo1.php?next=2">Continuar</a> | <a href="promo1.php?next=3">Voltar</a>.';
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }

	if ($quest['quest_status'] == 1) {
     if ($_GET['next'] == 1) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Missão</b></legend>\n";
         echo "<i>Seu nome é Friden o destemido guerreiro banhado pelo sangue do dragão!<br/>Derrote Friden e obtenha sua espada que também foi banhada pelo sangue do dragão, e você poderá se tornar um grandioso Guerreiro, porém devo avisar-lhe que não é uma tarefa fácil. Friden tem muitos admiradores e amigos, cuidado para não encontralos em sua jornada.</i><br/>\n";
         echo "<a href=\"promo1.php?next=2\">Desejo começar minha jornada</a> | <a href=\"promo1.php?next=3\">Não estou preparado</a>.";
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 2) {
         $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 12]);
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Missão</b></legend>\n";
         echo "<i>Adimiro sua corragem guerreiro, boa sorte em sua jornada.</i><br/>\n";
         echo '<a href="promo1.php">Continuar</a>.';
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 3) {
   			include(__DIR__ . "/templates/private_header.php");
   			echo "<fieldset><legend><b>Missão</b></legend>\n";
   			echo "<i>Tudo bem, treine um pouco mais e volte mais tarde.</i><br/>\n";
   			echo '<a href="home.php">Voltar</a>.';
   			echo "</fieldset>";
   			include(__DIR__ . "/templates/private_footer.php");
   			exit;
   		}

     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Missão</b></legend>\n";
     echo "<i>Dizem por ai que existe um homem que todos julgam ser imortal.  Arranque sua cabeça, perfure seus pulmões, corte-o ao meio nada o matará... A não ser, que perfurem seu coração, sua grande franqueza.</i><br/>\n";
     echo '<a href="promo1.php?next=1">Conte-me Mais!</a> | <a href="promo1.php?next=3">Voltar</a>.';
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }

	if ($quest['quest_status'] == 2) {
     if ($_GET['next'] == 1) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Missão</b></legend>\n";
         echo "<i>Seu primerio oponente é Detros. Este mago é famoso pela sua incrível velocidade que tem ao lançar feitiços.</i><br/>\n";
         echo "<a href=\"bquest.php\">Lutar contra Detros</a> | <a href=\"promo1.php?next=3\">Não estou preparado</a>.";
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 3) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Missão</b></legend>\n";
         echo "<i>Você abandonou o coliseu e acabou deixando uma má impressão entre os magos.</i><br/>\n";
         echo '<a href="home.php">Voltar</a>.';
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Torneio</b></legend>\n";
     echo "<i>O torneio se baseia em três etapas em que você terá de derrotar 3 guerreiros e o último jovem em pé será o que domina seus poderes!</i><br/>\n";
     echo '<a href="bquest.php">Continuar</a> | <a href="promo1.php?next=3">Fugir</a>.';
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }

	if ($quest['quest_status'] == 3 || $quest['quest_status'] == 4) {
		if ($_GET['next'] == 1) {
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [4, $player->id, 12]);
			header("Location: bquest.php");
		} elseif ($_GET['next'] == 3) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você abandonou o coliseu e acabou deixando uma má impressão entre os magos.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		} else {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você derrotou Detros, mas é bom se recuperar logo, pois agora você terá de lutar com Azura, uma poderosa feiticeira.</i><br/>\n";
			echo '<a href="promo1.php?next=1">Lutar contra Azura</a> | <a href="promo1.php?next=3">Fugir</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}
	}

	if ($quest['quest_status'] == 5 || $quest['quest_status'] == 6) {
		if ($_GET['next'] == 1) {
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [6, $player->id, 12]);
			header("Location: bquest.php");
		} elseif ($_GET['next'] == 3) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você abandonou o coliseu e acabou deixando uma má impressão entre os magos.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		} else {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Incrível! Você derrotou Azura. Muitos pelo colizeu não acreditavam que isso aconteceria!<br/>Você avançou para a etapa final do torneio, e agora terá de enfrentar Draconos, um mago com poderes incríveis.</i><br/>\n";
			echo '<a href="promo1.php?next=1">Lutar contra Draconos</a> | <a href="promo1.php?next=3">Fugir</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}
	}


	if ($quest['quest_status'] == 7) {
		$query = $db->execute("update `players` set `promoted`=? where `id`=?", ['p', $player->id]);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 12]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você derrotou o destemido Draconos! Nunca um forasteiro havia conseguido tamanha façanha.<br/><b>Você foi promovido para um Arquimago, e agora possui a Draconia Staff!</b></i><br/><br/>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($quest['quest_status'] == 90) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você já terminou esta missão!</i><br/>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}
} elseif ($player->voc == "archer") {
	if ($_GET['act'] == "pay") {
		$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
		if ($verificacao->recordcount() == 0) {
			if ($player->gold - 2000000 < 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
				echo "<i>Você não possui esta quantia de ouro!</i><br/><br/>\n";
				echo '<a href="home.php">Voltar</a>.';
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

   $query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 2000000, $player->id]);
   $insert['player_id'] = $player->id;
   $insert['quest_id'] = 12;
   $insert['quest_status'] = 1;
   $query = $db->autoexecute('quests', $insert, 'INSERT');
   include(__DIR__ . "/templates/private_header.php");
   echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
   echo "<i>Muito obrigado, agora vamos continuar.</i><br>\n";
   echo '<a href="promo1.php">Continuar</a>.';
   echo "</fieldset>";
   include(__DIR__ . "/templates/private_footer.php");
   exit;
		}

  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
  echo "Você já me pagou!</i><br/><br/>\n";
  echo '<a href="home.php">Voltar</a>.';
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
	}

	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
	$quest = $verificacao->fetchrow();

	if ($verificacao->recordcount() == 0) {
     if ($_GET['next'] == 1) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
         echo "<i>É sobre o famoso arqueiro Baltazar, porém não me lembro muito bem. Acho que 2 milhões ajudarão a refrescar minha memória.</i><br/>\n";
         echo '<a href="promo1.php?next=2">Eu pago</a> | <a href="promo1.php?next=3">Nunca!</a>.';
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 2) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
         echo "<i>Deseja pagar 2000000 de ouro para ouvir mais sore Baltazar?</i><br/>\n";
         echo "<a href=\"promo1.php?act=pay\">Sim</a> | <a href=\"promo1.php?next=3\">Não</a>.";
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 3) {
   			include(__DIR__ . "/templates/private_header.php");
   			echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
   			echo "<i>Tudo bem, talvez mais tarde.</i><br/>\n";
   			echo '<a href="home.php">Voltar</a>.';
   			echo "</fieldset>";
   			include(__DIR__ . "/templates/private_footer.php");
   			exit;
   		}

     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Olá " . $player->username . "! Ouvi muito sobre você na cidade, e tambem ouvi alguns boatos que você vai querer ouvir.</i><br/>\n";
     echo "<a href=\"promo1.php?next=1\">Que Boatos?</a> | <a href=\"promo1.php?next=3\">Não estou interessado</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }

	if ($quest['quest_status'] == 1) {
     if ($_GET['next'] == 1) {
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
         echo "<i>O arco de Baltazar foi banhado pelo sangue de demônios, é a arma perfeita.<br/>Porém, os demônios estão sempre próximos a Baltazar, você terá de enfrenta-los em sua jornada.</i><br/>\n";
         echo "<a href=\"promo1.php?next=2\">Desejo começar minha jornada</a> | <a href=\"promo1.php?next=3\">Não estou preparado</a>.";
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 2) {
         $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 12]);
         include(__DIR__ . "/templates/private_header.php");
         echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
         echo "<i>Adimiro sua corragem guerreiro, boa sorte em sua jornada.</i><br/>\n";
         echo '<a href="promo1.php">Continuar</a>.';
         echo "</fieldset>";
         include(__DIR__ . "/templates/private_footer.php");
         exit;
     }

     if ($_GET['next'] == 3) {
   			include(__DIR__ . "/templates/private_header.php");
   			echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
   			echo "<i>Tudo bem, treine um pouco mais e mais tarde você poderá buscar por Baltazar, mas não demore demais, ou ele poderá acabar indo embora.</i><br/>\n";
   			echo '<a href="home.php">Voltar</a>.';
   			echo "</fieldset>";
   			include(__DIR__ . "/templates/private_footer.php");
   			exit;
   		}

     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Dizem por que Baltazar está morando em uma caverna ao norte da cidade. E esta provavelmete será sua única chance de roubar seu poderoso arco.</i><br/>\n";
     echo '<a href="promo1.php?next=1">Conte-me Mais!</a> | <a href="promo1.php?next=3">Voltar</a>.';
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }

	if ($quest['quest_status'] == 2) {
		if ($_GET['next'] == 3) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você fugiu, mas sabe que deverá enfrentar o demônio se quiser continuar.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Missão</b></legend>\n";
  echo "<i>Você seguiu ao norte da cidade como Thomas mencionou, em busca da caverna. Finalmente a encontra, mas avista um demônio em sua entrada.</i><br/>\n";
  echo "<a href=\"bquest.php\">Lutar contra o Demônio</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
	}

	if ($quest['quest_status'] == 3 || $quest['quest_status'] == 4) {
		if ($_GET['next'] == 1) {
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [4, $player->id, 12]);
			header("Location: bquest.php");
		} elseif ($_GET['next'] == 3) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você fugiu, mas sabe que deverá enfrentar o demônio se quiser continuar.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		} else {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você matou o demônio, mas com o barulho que você fez, vê outro demônio se aproxima. Oque você fará?</i><br/>\n";
			echo "<a href=\"promo1.php?next=1\">Lutar contra o Demônio</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}
	}

	if ($quest['quest_status'] == 5 || $quest['quest_status'] == 6) {
		if ($_GET['next'] == 1) {
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [6, $player->id, 12]);
			header("Location: bquest.php");
		} elseif ($_GET['next'] == 3) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você conseguiu despistar Baltazar, mas deverá enfrentá-lo mais tarde.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		} else {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você derrotou o segundo demônio, e ao entrar um pouco mais na caverna vê Baltazar furioso. Oque você fará?</i><br/>\n";
			echo '<a href="promo1.php?next=1">Lutar contra Baltazar</a> | <a href="promo1.php?next=3">Fugir</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}
	}


	if ($quest['quest_status'] == 7) {
		$query = $db->execute("update `players` set `promoted`=? where `id`=?", ['p', $player->id]);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 12]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Ao tocar no arco de Baltazar você sente seus músculos enrijecerem e seu corpo fortalecido. Você acaba de se tornar um Arqueiro Royal!</i><br/><br/>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($quest['quest_status'] == 90) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
		echo "<i>Você já terminou esta missão!</i><br/>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}
}
