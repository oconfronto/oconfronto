<?php
include("lib.php");
define("PAGENAME", "Batalhar");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");
$valor547 = $player->level/1.25;
$diflvl = ceil ($valor547);

switch($_GET['act'])
{
	case "attack":
		if (!$_GET['username']) //No username entered
		{
			header("Location: battle.php");
			break;
		}
		
		//Otherwise, get player data:
		$query = $db->execute("select * from `players` where `username`=?", array($_GET['username']));
		if ($query->recordcount() == 0) //Player doesn't exist
		{
			include("templates/private_header.php");
			echo "Este usuário não existe! <a href=\"battle.php\"/>Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}
		
		$enemy1 = $query->fetchrow(); //Get player info
		foreach($enemy1 as $key=>$value)
		{
			$enemy->$key = $value;
		}
		
		if ($enemy->serv != $player->serv)
		{
			include("templates/private_header.php");
			echo "Este usuário não pertence ao mesmo servidor que você! <a href=\"battle.php\"/>Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}

		if ($enemy->reino == 0)
		{
			include("templates/private_header.php");
			echo "Você não pode atacar este usuário! <a href=\"battle.php\"/>Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}
		
		if ($enemy->level < 20)
		{
			include("templates/private_header.php");
			echo "Level desde usuário é menor que 20 <a href=\"battle.php\"/>Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}
		if ($player->level <= 20)
		{
			include("templates/private_header.php");
			echo "Só é permitido PVP para players de level 20+ <a href=\"battle.php\"/>Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}
		//Otherwise, check if player has any health
		if ($enemy->hp <= 0)
		{
			include("templates/private_header.php");
			echo "Este usuário está morto! <a href=\"battle.php\"/>Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}

		if ($player->level < 100){
		$mytier = 1;
		} elseif (($player->level > 99) and ($player->level < 200)){
		$mytier = 2;
		} elseif (($player->level > 199) and ($player->level < 300)){
		$mytier = 3;
		} elseif (($player->level > 299) and ($player->level < 400)){
		$mytier = 4;
		} elseif (($player->level > 399) and ($player->level < 1000)){
		$mytier = 5;
		}

		if ($enemy->level < 100){
		$enytier = 1;
		} elseif (($enemy->level > 99) and ($enemy->level < 200)){
		$enytier = 2;
		} elseif (($enemy->level > 199) and ($enemy->level < 300)){
		$enytier = 3;
		} elseif (($enemy->level > 299) and ($enemy->level < 400)){
		$enytier = 4;
		} elseif (($enemy->level > 399) and ($enemy->level < 1000)){
		$enytier = 5;
		}

		$enytourstatus = "tournament_" . $enytier . "_" . $player->serv . "";


		//Checa se o usuario jah foi morto demais
		if ($enemy->died >= 3)
		{
			if ($enemy->tour == 'f'){
			include("templates/private_header.php");
			echo "Este usuário já morreu demais hoje! <a href=\"battle.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
			}

			if (($enemy->tour == 't') and (($setting->$enytourstatus != 'y') or ((($setting->$enytourstatus == 'y') and ($mytier != $enytier)) or (($setting->$enytourstatus == 'y') and ($enemy->killed > 0))))){
			include("templates/private_header.php");
			echo "Este usuário já morreu demais hoje! <a href=\"battle.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
			}
		}


		//Checa se o usuario jah foi morto recentemente
		$recentlyattacked = $db->execute("select * from `attacked` where `time`>? and `player_id`=? and `attacker_id`=?", array(time() - 1200, $enemy->id, $player->id));
		if ($recentlyattacked->recordcount() > 0)
		{
			if ($enemy->tour == 'f'){
			include("templates/private_header.php");
			echo "Você já atacou este usuário nos últimos 20 minutos.<br/>Aguarde alguns minutos para poder ataca-lo novamente. <a href=\"battle.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
			}

			if (($enemy->tour == 't') and (($setting->$enytourstatus != 'y') or ((($setting->$enytourstatus == 'y') and ($mytier != $enytier)) or (($setting->$enytourstatus == 'y') and ($enemy->killed > 0))))){
			include("templates/private_header.php");
			echo "Você já atacou este usuário nos últimos 20 minutos.<br/>Aguarde alguns minutos para poder ataca-lo novamente. <a href=\"battle.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
			}
		}

		//Checa se o usuario tah banido
		if ($enemy->ban > time())
		{
			include("templates/private_header.php");
			echo "Este usuário está banido! <a href=\"battle.php\"/>Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}


		$checkenyrowk = $db->GetOne("select `status` from `work` where `player_id`=? order by `start` DESC", array($enemy->id));
		$checkenyhunt = $db->GetOne("select `status` from `hunt` where `player_id`=? order by `start` DESC", array($enemy->id));
		if ((($checkenyrowk == t) or ($checkenyhunt == t)) and ($enemy->tour == 'f'))
		{
			include("templates/private_header.php");
			echo "Você não encontrou o usuário " . $enemy->username . "! Ele deve estar trabalhando ou caçando. <a href=\"battle.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}

		$checarevenge = $db->execute("select * from `revenge` where `player_id`=? and `enemy_id`=?", array($player->id, $enemy->id));

		//checa os niveis
		if (($player->level > $enemy->level*1.25) and ($enemy->tour == 't') and (($enemy->killed > 0) or ($mytier != $enytier)) and ($checarevenge->recordcount() < 1))
		{
			include("templates/private_header.php");
			echo "A diferença de nível entre os dois usuários é muito grande!<br>";
			echo "<b>Você pode atacar usuários de nível $diflvl ou mais.</b> <a href=\"battle.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}


		//checa os niveis
		if (($player->level > $enemy->level*1.25) and ($enemy->tour == 'f') and ($checarevenge->recordcount() < 1))
		{
			include("templates/private_header.php");
			echo "A diferença de nível entre os dois usuários é muito grande!<br>";
			echo "<b>Você pode atacar usuários de nível $diflvl ou mais.</b> <a href=\"battle.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}


		if (($setting->$enytourstatus == 'y') and ($enemy->tour == 't') and ($player->tour == 'f'))
		{
			include("templates/private_header.php");
			echo "O usuário " . $enemy->username . " está participando de um torneio agora.<br/>Você não está no torneio portanto não pode mata-lo.";
			include("templates/private_footer.php");
			break;
		}

		if (($setting->$enytourstatus == 'y') and ($enemy->tour == 't') and ((($player->tour == 't') and ($player->killed > 0)) or ($mytier != $enytier)))
		{
			include("templates/private_header.php");
			if ($mytier != $enytier){
			echo "O usuário " . $enemy->username . " está participando de outra categoria do torneio.<br/>Você não está na mesma categoria portanto não pode mata-lo.";
			}else{
			echo "Você não pode matar " . $enemy->username . " pois ele está participando de um torneio agora.<br/>Você foi desclasificado do torneio portanto não pode mata-lo.<br>";
			}
			include("templates/private_footer.php");
			break;
		}
		
		//Player cannot attack anymore
		if ($player->energy < 10)
		{
			include("templates/private_header.php");
			echo "<fieldset>";
			echo "<legend><b>Você está sem energia</b></legend>\n";
			echo "Você está exausto. A cada minuto que se passa você adquire <b>10 pontos de energia</b>.<br/><br/>";
			echo "<div id=\"counter\" align=\"center\"></div><br/>";

			$gettime = $db->GetOne("select `value` from `cron` where `name`='reset_last'");

            echo "<script type=\"text/javascript\">";
            echo "javascript_countdown.init(" . ceil($gettime - (time() - 60)) . ", 'counter');";
            echo "</script>";
            
			echo "</fieldset><br/>";

			$query = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=136 and `mark`='f' order by rand()", array($player->id));
			$numerodepocoes = $query->recordcount();

			$query2 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=137 and `mark`='f' order by rand()", array($player->id));
			$numerodepocoes2 = $query2->recordcount();

			$query3 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=148 and `mark`='f' order by rand()", array($player->id));
			$numerodepocoes3 = $query3->recordcount();

			$query4 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=150 and `mark`='f' order by rand()", array($player->id));
			$numerodepocoes4 = $query4->recordcount();

			echo "<fieldset>";
			echo "<legend><b>Poções</b></legend>";
			echo "<table width=\"100%\"><tr><td><table width=\"80px\"><tr><td><div title=\"header=[Health Potion] body=[Recupera até 5 mil de vida.]\"><img src=\"images/itens/healthpotion.gif\"></div></td><td><b>x" . $numerodepocoes . "</b>";
			if ($numerodepocoes > 0){
			$item = $query->fetchrow();
			echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item['id'] . "\">Usar</a>";
			}
			echo "</td></tr></table></td>";
			echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Big Health Potion] body=[Recupera até 10 mil de vida.]\"><img src=\"images/itens/bighealthpotion.gif\"></div></td><td><b>x" . $numerodepocoes3 . "</b>";
			if ($numerodepocoes3 > 0){
			$item3 = $query3->fetchrow();
			echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item3['id'] . "\">Usar</a>";
			}
			echo "</td></tr></table></td>";
			echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Mana Potion] body=[Recupera até 500 de mana.]\"><img src=\"images/itens/manapotion.gif\"></div></td><td><b>x" . $numerodepocoes4 . "</b>";
			if ($numerodepocoes4 > 0){
			$item4 = $query4->fetchrow();
			echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item4['id'] . "\">Usar</a>";
			}
			echo "</td></tr></table></td>";
			echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Energy Potion] body=[Recupera até 50 de energia.]\"><img src=\"images/itens/energypotion.gif\"></div></td><td><b>x" . $numerodepocoes2 . "</b>";
			if ($numerodepocoes2 > 0){
			$item2 = $query2->fetchrow();
			echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item2['id'] . "\">Usar</a>";
			}
			echo "</td></tr></table></td><td><font size=\"1\"><a href=\"hospt.php?act=sell\">Vender Poções</a><br/><a href=\"inventory.php?transpotion=true\">Transferir Poções</a></font></td></tr></table>";
			echo "</fieldset>";

			echo "<a href=\"monster.php\">Voltar</a>";

			include("templates/private_footer.php");
			break;
		}


		//Checa se vc jah foi morto demais
		if ($player->died >= 3)
		{
			include("templates/private_header.php");
			echo "Você morreu 3x hoje e ficou imune de ataques dos outros jogadores.<br/>";
			echo "Se você quiser atacar alguém, você perderá sua imunidade! <a href=\"nobless.php\"/>Remover imunidade</a>.";
			include("templates/private_footer.php");
			break;
		}

		//Player In Same Guild
		if (($enemy->guild == $player->guild) and ($player->guild != NULL) and (!$_GET['comfirm']))
		{
			include("templates/private_header.php");
			echo "Este usuário é membro do mesmo clã que você.<br/>Tem certeza que deseja ataca-lo?<br/><br/><a href=\"battle.php?act=attack&username=" . $enemy->username . "&comfirm=true\">Atacar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"battle.php\">Voltar</a>";
			include("templates/private_footer.php");
			break;
		}

		//Player In Same Guild
		if (($enemy->guild != NULL) and ($player->guild != NULL) and (!$_GET['comfirm']))
		{
			$ganguesaliadas = $db->execute("select `id` from `guild_aliance` where `guild_na`=? and `aled_na`=?", array($player->guild, $enemy->guild));
			if ($ganguesaliadas->recordcount() > 0){
			include("templates/private_header.php");
			echo "Este usuário é membro do clã " . $enemy->guild . ", um clã aliado do seu clã.<br/>Tem certeza que deseja ataca-lo?<br/><br/><a href=\"battle.php?act=attack&username=" . $enemy->username . "&comfirm=true\">Atacar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"battle.php\">Voltar</a>";
			include("templates/private_footer.php");
			break;
			}
		}

		//Player is friend
		$checkfriendname = $db->execute("select * from `friends` where `fname`=? and `uid`=?", array($enemy->username, $player->id));
		if (($checkfriendname->recordcount() > 0) and (!$_GET['comfirm'])){
			include("templates/private_header.php");
			echo "Este usuário é seu amigo.<br/>Tem certeza que deseja ataca-lo?<br/><br/><a href=\"battle.php?act=attack&username=" . $enemy->username . "&comfirm=true\">Atacar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"battle.php\">Voltar</a>";
			include("templates/private_footer.php");
			break;
		}

		
		if ($enemy->username == $player->username)
		{
			include("templates/private_header.php");
			echo "Você não pode atacar você mesmo! <a href=\"battle.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}

		if ($enemy->gm_rank > 9)
		{
			include("templates/private_header.php");
			echo "Este usuário é um administrador, você não pode ataca-lo! <a href=\"battle.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}
		
		//Get enemy's bonuses from equipment
		$query = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", array($enemy->id));
  		$enemy->atkbonus = ($query->recordcount() == 1)?$query->fetchrow():0;
		$query50 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", array($enemy->id));
		$enemy->defbonus1 = ($query50->recordcount() == 1)?$query50->fetchrow():0;
		$query51 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", array($enemy->id));
		$enemy->defbonus2 = ($query51->recordcount() == 1)?$query51->fetchrow():0;
		$query52 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", array($enemy->id));
		$enemy->defbonus3 = ($query52->recordcount() == 1)?$query52->fetchrow():0;
		$query54 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='shield' and items.status='equipped'", array($enemy->id));
		$enemy->defbonus5 = ($query54->recordcount() == 1)?$query54->fetchrow():0;
		$query55 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", array($enemy->id));
		$enemy->agibonus6 = ($query55->recordcount() == 1)?$query55->fetchrow():0;

		$enybonusfor = 0;
		$enybonusagi = 0;
		$enybonusres = 0;
			$countstats = $db->query("select `for`, `vit`, `agi`, `res` from `items` where `player_id`=? and `status`='equipped'", array($enemy->id));
			while($count = $countstats->fetchrow())
			{
				$enybonusfor += $count['for'];
				$enybonusagi += $count['agi'];
				$enybonusres += $count['res'];
			}

			$everificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", array($enemy->id, time()));
				if ($everificpotion->recordcount() > 0){
					$selct = $everificpotion->fetchrow();
					$getpotion = $db->execute("select * from `for_use` where `item_id`=?", array($selct['item_id']));
						$potbonus = $getpotion->fetchrow();
						$enemy->strength = ceil($enemy->strength + (($enemy->strength / 100) * ($potbonus['for'])));
						$enemy->vitality = ceil($enemy->vitality + (($enemy->vitality / 100) * ($potbonus['vit'])));
						$enemy->agility = ceil($enemy->agility + (($enemy->agility / 100) * ($potbonus['agi'])));
						$enemy->resistance = ceil($enemy->resistance + (($enemy->resistance / 100) * ($potbonus['res'])));
				}
		

		//Get player's bonuses from equipment
		$query = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", array($player->id));
		$player->atkbonus = ($query->recordcount() == 1)?$query->fetchrow():0;
		$query50 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", array($player->id));
		$player->defbonus1 = ($query50->recordcount() == 1)?$query50->fetchrow():0;
		$query51 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", array($player->id));
		$player->defbonus2 = ($query51->recordcount() == 1)?$query51->fetchrow():0;
		$query52 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", array($player->id));
		$player->defbonus3 = ($query52->recordcount() == 1)?$query52->fetchrow():0;
		$query54 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='shield' and items.status='equipped'", array($player->id));
		$player->defbonus5 = ($query54->recordcount() == 1)?$query54->fetchrow():0;
		$query55 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", array($player->id));
		$player->agibonus6 = ($query55->recordcount() == 1)?$query55->fetchrow():0;

		$pbonusfor = 0;
		$pbonusagi = 0;
		$pbonusres = 0;
			$countstats = $db->query("select `for`, `vit`, `agi`, `res` from `items` where `player_id`=? and `status`='equipped'", array($player->id));
			while($count = $countstats->fetchrow())
			{
				$pbonusfor += $count['for'];
				$pbonusagi += $count['agi'];
				$pbonusres += $count['res'];
			}

			$pverificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", array($player->id, time()));
				if ($pverificpotion->recordcount() > 0){
					$selct = $pverificpotion->fetchrow();
					$getpotion = $db->execute("select * from `for_use` where `item_id`=?", array($selct['item_id']));
						$potbonus = $getpotion->fetchrow();
						$player->strength = ceil($player->strength + (($player->strength / 100) * ($potbonus['for'])));
						$player->vitality = ceil($player->vitality + (($player->vitality / 100) * ($potbonus['vit'])));
						$player->agility = ceil($player->agility + (($player->agility / 100) * ($potbonus['agi'])));
						$player->resistance = ceil($player->resistance + (($player->resistance / 100) * ($potbonus['res'])));
				}


	$checamagiastatus = $db->execute("select * from `magias` where `magia_id`=5 and `player_id`=?", array($player->id));
		if ($player->voc == 'archer'){
			if ($checamagiastatus->recordcount() > 0){
			$varataque = 0.31;
			$vardefesa = 0.15;
			$vardivide = 0.14;
			}else{
			$varataque = 0.29;
			$vardefesa = 0.14;
			$vardivide = 0.13;
			}
		}
		else if ($player->voc == 'mage'){
			if ($checamagiastatus->recordcount() > 0){
			$varataque = 0.265;
			$vardefesa = 0.15;
			$vardivide = 0.14;
			}else{
			$varataque = 0.245;
			$vardefesa = 0.14;
			$vardivide = 0.13;
			}
		}
		else if ($player->voc == 'knight'){
			if ($checamagiastatus->recordcount() > 0){
			$varataque = 0.22;
			$vardefesa = 0.17;
			$vardivide = 0.15;
			}else{
			$varataque = 0.20;
			$vardefesa = 0.16;
			$vardivide = 0.14;
			}
		}


			if ($player->promoted == 'f') {
				$multipleatk = 1 + ($varataque * 1.6);
				$multipledef = 1 + ($vardefesa * 1.6);
				$divideres = 2.3 - ($vardivide * 1.6);
			}elseif ($player->promoted == 't') {
				$multipleatk = 1 + ($varataque * 2.4);
				$multipledef = 1 + ($vardefesa * 2.4);
				$divideres = 2.3 - ($vardivide * 2.4);
			}elseif ($player->promoted == 'p') {
				$multipleatk = 1 + ($varataque * 3.2);
				$multipledef = 1 + ($vardefesa * 3.2);
				$divideres = 2.3 - ($vardivide * 3.2);
			}else{
				echo "um erro foi encontrado em seu personagem, contate o administrador.";
				exit;
			}

	$enychecamagiastatus = $db->execute("select * from `magias` where `magia_id`=5 and `player_id`=?", array($enemy->id));
		if ($enemy->voc == 'archer'){
			if ($enychecamagiastatus->recordcount() > 0){
			$varataque = 0.31;
			$vardefesa = 0.13;
			$vardivide = 0.13;
			}else{
			$varataque = 0.29;
			$vardefesa = 0.12;
			$vardivide = 0.12;
			}
		}
		else if ($enemy->voc == 'mage'){
			if ($enychecamagiastatus->recordcount() > 0){
			$varataque = 0.265;
			$vardefesa = 0.15;
			$vardivide = 0.14;
			}else{
			$varataque = 0.245;
			$vardefesa = 0.14;
			$vardivide = 0.13;
			}
		}
		else if ($enemy->voc == 'knight'){
			if ($enychecamagiastatus->recordcount() > 0){
			$varataque = 0.22;
			$vardefesa = 0.17;
			$vardivide = 0.15;
			}else{
			$varataque = 0.20;
			$vardefesa = 0.16;
			$vardivide = 0.14;
			}
		}


			if ($enemy->promoted == 'f') {
				$enymultipleatk = 1 + ($varataque * 1.6);
				$enymultipledef = 1 + ($vardefesa * 1.6);
				$enydivideres = 2.3 - ($vardivide * 1.6);
			}elseif ($enemy->promoted == 't') {
				$enymultipleatk = 1 + ($varataque * 2.4);
				$enymultipledef = 1 + ($vardefesa * 2.4);
				$enydivideres = 2.3 - ($vardivide * 2.4);
			}elseif ($enemy->promoted == 'p') {
				$enymultipleatk = 1 + ($varataque * 3.2);
				$enymultipledef = 1 + ($vardefesa * 3.2);
				$enydivideres = 2.3 - ($vardivide * 3.2);
			}else{
				echo "um erro foi encontrado em seu personagem, contate o administrador.";
				exit;
			}

		//Calculate some variables that will be used
		$forcadoplayer = ceil(($player->strength + $player->atkbonus['effectiveness'] + ($player->atkbonus['item_bonus'] * 2)  + $pbonusfor) * $multipleatk);
		$agilidadedoplayer = ceil($player->agility + $player->agibonus6['effectiveness'] + ($player->agibonus6['item_bonus'] * 2) + $pbonusagi);
		$resistenciadoplayer = ceil(($player->resistance + ($player->defbonus1['effectiveness'] + $player->defbonus2['effectiveness'] + $player->defbonus3['effectiveness'] + $player->defbonus5['effectiveness']) + (($player->defbonus1['item_bonus'] * 2) + ($player->defbonus2['item_bonus'] * 2) + ($player->defbonus3['item_bonus'] * 2) + ($player->defbonus5['item_bonus'] * 2)) + $pbonusres) * $multipledef);

		$forcadoenemy = ceil(($enemy->strength + $enemy->atkbonus['effectiveness'] + ($enemy->atkbonus['item_bonus'] * 2) + $enybonusfor) * $enymultipleatk);
		$agilidadedoenemy = ceil($enemy->agility + $enemy->agibonus6['effectiveness'] + ($enemy->agibonus6['item_bonus'] * 2) + $enybonusagi);
		$resistenciadoenemy = ceil(($enemy->resistance + ($enemy->defbonus1['effectiveness'] + $enemy->defbonus2['effectiveness'] + $enemy->defbonus3['effectiveness'] + $enemy->defbonus5['effectiveness']) + (($enemy->defbonus1['item_bonus'] * 2) + ($enemy->defbonus2['item_bonus'] * 2) + ($enemy->defbonus3['item_bonus'] * 2) + ($enemy->defbonus5['item_bonus'] * 2)) + $enybonusres) * $enymultipledef);

		$enemy->strdiff = (($forcadoenemy - $forcadoplayer) > 0)?($forcadoenemy - $forcadoplayer):0;
		$enemy->resdiff = (($resistenciadoenemy - ($resistenciadoplayer * 1.5)) > 0)?($resistenciadoenemy - $resistenciadoplayer):0;
		$enemy->agidiff = (($agilidadedoenemy - $agilidadedoplayer) > 0)?($agilidadedoenemy - $agilidadedoplayer):0;
		$enemy->leveldiff = (($enemy->level - $player->level) > 0)?($enemy->level - $player->level):0;
		$player->strdiff = (($forcadoplayer - $forcadoenemy) > 0)?($forcadoplayer - $forcadoenemy):0;
		$player->resdiff = (($resistenciadoplayer - $resistenciadoenemy) > 0)?($resistenciadoplayer - $resistenciadoenemy):0;
       		$player->agidiff = (($agilidadedoplayer - $agilidadedoenemy) > 0)?($agilidadedoplayer - $agilidadedoenemy):0;
		$player->leveldiff = (($player->level - $enemy->level) > 0)?($player->level - $enemy->level):0;
		$totalstr = $forcadoenemy + $forcadoplayer;
		$totalres = $resistenciadoenemy + $resistenciadoplayer;
		$totalagi = $agilidadedoenemy + $agilidadedoplayer;
		$totallevel = $enemy->level + $player->level;

		
		//Calculate the damage to be dealt by each player (dependent on strength and agility)
		$enemy->maxdmg = ceil($forcadoenemy - ($resistenciadoplayer / $divideres));
		$enemy->maxdmg = $enemy->maxdmg - intval($enemy->maxdmg * ($player->leveldiff / $totallevel));
		$enemy->maxdmg = ($enemy->maxdmg <= 2)?2:$enemy->maxdmg; //Set 2 as the minimum damage
		$enemy->mindmg = (($enemy->maxdmg - 4) < 1)?1:($enemy->maxdmg - 4); //Set a minimum damage range of maxdmg-4
  		$player->maxdmg = ceil($forcadoplayer - ($resistenciadoenemy / $enydivideres));
		$player->maxdmg = $player->maxdmg - intval($player->maxdmg * ($enemy->leveldiff / $totallevel));
		$player->maxdmg = ($player->maxdmg <= 2)?2:$player->maxdmg; //Set 2 as the minimum damage
		$player->mindmg = (($player->maxdmg - 4) < 1)?1:($player->maxdmg - 4); //Set a minimum damage range of maxdmg-4
		
		//Calculate battle 'combos' - how many times in a row a player can attack (dependent on agility)
		$enemy->combo = ceil($agilidadedoenemy / $agilidadedoplayer);
		$enemy->combo = ($enemy->combo > 3)?3:$enemy->combo;
		$player->combo = ceil($agilidadedoplayer / $agilidadedoenemy);
		$player->combo = ($player->combo > 3)?3:$player->combo;
		
		//Calculate the chance to miss opposing player
		$enemy->miss = intval(($player->agidiff / $totalagi) * 100);
		$enemy->miss = ($enemy->miss > 20)?20:$enemy->miss; //Maximum miss chance of 20% (possible to change in admin panel?)
		$enemy->miss = ($enemy->miss <= 8)?8:$enemy->miss; //Minimum miss chance of 5%
		$player->miss = intval(($enemy->agidiff / $totalagi) * 100);
		$player->miss = ($player->miss > 20)?20:$player->miss; //Maximum miss chance of 20%
		$player->miss = ($player->miss <= 8)?8:$player->miss; //Minimum miss chance of 5%
		
		
		$battlerounds = $setting->pvp_battle_rounds; //Maximum number of rounds/turns in the battle. Changed in admin panel?
		
		$depoput = ""; //Output message
		$output = ""; //Output message

		//While somebody is still alive, battle!
		while ($enemy->hp > 0 && $player->hp > 0 && $battlerounds > 0)
		{
			$attacking = ($especagi >= $especagieny)?$player:$enemy;
			$defending = ($especagi >= $especagieny)?$enemy:$player;
			
			for($i = 0;$i < $attacking->combo;$i++)
			{
				//Chance to miss?
				$misschance = intval(rand(0, 100));
				if ($misschance <= $attacking->miss)
				{
					$output .= $attacking->username . " tentou atacar " . $defending->username . " mas errou!<br />";
				}
				else
				{
					$damage = rand($attacking->mindmg, $attacking->maxdmg); //Calculate random damage				
					$defending->hp -= $damage;
					$output .= ($player->username == $defending->username)?"<font color=\"red\">":"<font color=\"green\">";
					$output .= $attacking->username . " atacou " . $defending->username . " e tirou " . $damage . " de vida! (";
					$output .= ($defending->hp > 0)?$defending->hp . " de vida":"Morto";
					$output .= ")<br />";
					$output .= "</font>";

					//Check if anybody is dead
					if ($defending->hp <= 0)
					{
						$player = ($especagi >= $especagieny)?$attacking:$defending;
						$enemy = ($especagi >= $especagieny)?$defending:$attacking;
						break 2; //Break out of the for and while loop, but not the switch structure
					}
				}
				$battlerounds--;
				if ($battlerounds <= 0)
				{
					break 2; //Break out of for and while loop, battle is over!
				}
			}
			
			for($i = 0;$i < $defending->combo;$i++)
			{
				//Chance to miss?
				$misschance = intval(rand(0, 100));
				if ($misschance <= $defending->miss)
				{
					$output .= $defending->username . " tentou atacar " . $attacking->username . " mas errou!<br />";
				}
				else
				{
					$damage = rand($defending->mindmg, $defending->maxdmg); //Calculate random damage
					$attacking->hp -= $damage;
					$output .= ($player->username == $defending->username)?"<font color=\"green\">":"<font color=\"red\">";
					$output .= $defending->username . " atacou " . $attacking->username . " e tirou " . $damage . " de vida! (";
					$output .= ($attacking->hp > 0)?$attacking->hp . " de Vida":"Morto";
					$output .= ")<br />";
					$output .= "</font>";

					//Check if anybody is dead
					if ($attacking->hp <= 0)
					{
						$player = ($especagi >= $especagieny)?$attacking:$defending;
						$enemy = ($especagi >= $especagieny)?$defending:$attacking;
						break 2; //Break out of the for and while loop, but not the switch structure
					}
				}
				$battlerounds--;
				if ($battlerounds <= 0)
				{
					break 2; //Break out of for and while loop, battle is over!
				}
			}
			
			$player = ($especagi >= $especagieny)?$attacking:$defending;
			$enemy = ($especagi >= $especagieny)?$defending:$attacking;
		}


		if ($player->hp <= 0)
		{
			//Calculate losses
			$exploss1 = $player->level * 6;
			$exploss2 = (($player->level - $enemy->level) > 0)?($enemy->level - $player->level) * 4:0;
			$exploss = $exploss1 + $exploss2;
			$goldloss = intval(0.35 * $player->gold);
			$goldloss = intval(rand(1, $goldloss));
			if ($goldloss < 1){
				$goldloss = 0;
			}
			
			$depoput .= "<br/><div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><b><u>Você foi assassinado por " . $enemy->username . "!</u></b></div>";
			$depoput .= "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Você perdeu <b>" . number_format($exploss) . "</b> de EXP e <b>" . number_format($goldloss) . "</b> de ouro.</div>";
			$exploss3 = (($player->exp - $exploss) <= 0)?$player->exp:$exploss;
			$goldloss2 = (($player->gold - $goldloss) <= 0)?$player->gold:$goldloss;
			//Update player (the loser)

			if (($setting->$enytourstatus == 'y') and ($player->tour == 't') and ($enemy->tour == 't') and ($player->killed == 0) and ($enemy->killed == 0) and ($mytier == $enytier)){
			$tourlose = time();
			$logmsg = "Você morreu e foi desclassificado do torneio.";
			addlog($player->id, $logmsg, $db);
			}else{
			$tourlose = $player->killed;
			}

			$checkwanted = $db->execute("select * from `wanted` where `player_id`=?", array($player->id));
			if ($checkwanted->recordcount() > 0) {
				$db->execute("delete from `wanted` where `player_id`=?", array($player->id));
			}

			$query = $db->execute("update `players` set `energy`=?, `exp`=?, `gold`=?, `deaths`=?, `killed`=?, `died`=?, `hp`=0, `deadtime`=? where `id`=?", array($player->energy - 10, $player->exp - $exploss3, $player->gold - $goldloss2, $player->deaths + 1, $tourlose, $player->died + 1, time() + $setting->dead_time, $player->id));
			
			//Update enemy (the winner)
			if ($exploss + $enemy->exp < maxExp($enemy->level))
			{
				$query = $db->execute("update `players` set `exp`=?, `gold`=?, `kills`=?, `hp`=? where `id`=?", array($enemy->exp + $exploss, $enemy->gold + $goldloss, $enemy->kills + 1, $enemy->hp, $enemy->id));
				//Add log message for winner

				$logmsg3 = "Você foi atacado por <a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> mas venceu!<br />\nVocê ganhou " . number_format($exploss) . " de EXP e " . number_format($goldloss) . " de ouro.";
				$insert['player_id'] = $enemy->id;
				$insert['msg'] = $logmsg3;
				$insert['time'] = time();
				$query = $db->autoexecute('logbat', $insert, 'INSERT');
			}
			else //Defender has gained a level! =)
			{
				$db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", array(maxMana($enemy->level, $enemy->extramana), maxMana($enemy->level, $enemy->extramana), $enemy->id));
				$db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", array(maxEnergy($enemy->level, $enemy->vip), $enemy->id));
                
                $db->execute("update `players` set `magic_points`=`magic_points`+1, `stat_points`=`stat_points`+1, `level`=`level`+1, `exp`=?, `gold`=?, `kills`=`kills`+1, `hp`=?, `maxhp`=? where `id`=?", array(($enemy->exp + $exploss) - maxExp($enemy->level), $enemy->gold + $goldloss, maxHp($db, $enemy->id, $enemy->level, $enemy->reino, $enemy->vip), maxHp($db, $enemy->id, $enemy->level, $enemy->reino, $enemy->vip), $enemy->id));
				//Add log message for winner

				$logmsg4 = "Você foi atacado por <a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> mas venceu!<br />\nVocê ganhou um nível e " . number_format($goldloss) . " de ouro.";
				$insert['player_id'] = $enemy->id;
				$insert['msg'] = $logmsg4;
				$insert['time'] = time();
				$query = $db->autoexecute('logbat', $insert, 'INSERT');
			}

		}
		else if ($enemy->hp <= 0)
		{
			//Calculate losses
			$expwin1 = $enemy->level * 8;
			$expwin2 = (($player->level - $enemy->level) > 0)?$expwin1 - (($player->level - $enemy->level) * 3):$expwin1 + (($player->level - $enemy->level) * 3);
			$expwin2 = ($expwin2 <= 0)?1:$expwin2;
			$expwin3 = round(0.9 * $expwin2);
			$expwin = ceil(rand($expwin3, $expwin2));
			$goldwin = ceil(0.35 * $enemy->gold);
			$goldwin = intval(rand(1, $goldwin));
			if ($goldwin < 1){
			$goldwin = 0;
			}
			$depoput .= "<br/><div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><b><u>Você matou " . $enemy->username . "!</u></b></div>";

		$checkwanted = $db->execute("select * from `wanted` where `player_id`=?", array($player->id));
		if ($checkwanted->recordcount() < 1) {
			$insert['player_id'] = $player->id;
			$db->autoexecute('wanted', $insert, 'INSERT');
		}else{
			$db->execute("update `wanted` set `kills`=`kills`+1 where `player_id`=?", array($player->id));
		}

		$checkaward = $db->execute("select * from `wanted` where `player_id`=?", array($enemy->id));
		if ($checkaward->recordcount() == 1) {
			$kills = $db->GetOne("select `kills` from `wanted` where `player_id`=?", array($enemy->id));
			$awardgold = ceil($kills * $enemy->level);

			$db->execute("delete from `wanted` where `player_id`=?", array($enemy->id));
			$db->execute("update `players` set `gold`=`gold`+? where `id`=?", array($awardgold, $player->id));
			$depoput .= "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center>O usuário que você matou era um procurado!<br/>Você ganhou uma recompensa de " . number_format($awardgold) . " moedas de ouro.</center></div>";
		}


			$depoput .= "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Você ganhou <b>" . number_format($expwin) . "</b> de EXP e <b>" . number_format($goldwin) . "</b> de ouro.</div>";
			
			if ($expwin + $player->exp >= maxExp($player->level)) //Player gained a level!
			{
				//Update player, gained a level
				$depoput .= "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><u><b>Você passou de nível!</b></u></div>";
				$newexp = $expwin + $player->exp - maxExp($player->level);

				$db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", array(maxMana($player->level, $player->extramana), maxMana($player->level, $player->extramana), $player->id));
				$db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", array(maxEnergy($player->level, $player->vip), $player->id));

				$query = $db->execute("update `players` set `magic_points`=`magic_points`+1, `stat_points`=`stat_points`+3, `level`=`level`+1, `hp`=?, `maxhp`=?, `exp`=?, `gold`=?, `energy`=? where `id`=?", array(maxHp($db, $player->id, $player->level, $player->reino, $player->vip), maxHp($db, $player->id, $player->level, $player->reino, $player->vip), $newexp, $player->gold + $goldwin + $awardgold, $player->energy - 10, $player->id));
			}
			else
			{
				//Update player
				$query = $db->execute("update `players` set `exp`=?, `gold`=?, `hp`=?, `energy`=? where `id`=?", array($player->exp + $expwin, $player->gold + $goldwin + $awardgold, $player->hp, $player->energy - 10, $player->id));
			}

			if ($player->reino == $enemy->reino) {
				$db->execute("update `players` set `akills`=`akills`+1 where `id`=?", array($player->id));
			} else {
				$db->execute("update `players` set `kills`=`kills`+1 where `id`=?", array($player->id));
			}

		$heal = $player->maxhp - $player->hp;

			if ($heal > 0){
			if($player->level < 36){
			$cost = ceil($heal * 1);
			}
			else if (($player->level > 35) and ($player->level < 90)){
			$cost = ceil($heal * 1.45);
			}else{
			$cost = ceil($heal * 1.8);
			}
			$depoput .= "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><a href=\"hospt.php?act=heal\">Clique aqui</a> para recuperar toda sua vida por <b>" . number_format($cost) . "</b> de ouro.</div>";
			}

			
			//Add log message
		if ($player->level*1.25 < $enemy->level){
			$insert['player_id'] = $enemy->id;
			$insert['enemy_id'] = $player->id;
			$insert['time'] = time();
			$addrevenge = $db->autoexecute('revenge', $insert, 'INSERT');
		}


		$insert['player_id'] = $enemy->id;
		$insert['enemy_id'] = $player->id;
		$insert['time'] = time();
		$addrevenge = $db->autoexecute('revenge', $insert, 'INSERT');

			$logmsg5 = "Você foi atacado por <a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> e foi derrotado... <a href=\"battle.php?act=attack&username=" . $player->username . "&comfirm=true\">Clique aqui</a> para se vingar.";
			$insert['player_id'] = $enemy->id;
			$insert['msg'] = $logmsg5;
			$insert['time'] = time();
			$query = $db->autoexecute('logbat', $insert, 'INSERT');
			//Update enemy (who was defeated)

			if (($setting->$enytourstatus == 'y') and ($player->tour == 't') and ($enemy->tour == 't') and ($player->killed == 0) and ($enemy->killed == 0) and ($mytier == $enytier)){
			$tourlose = time();
			$logmsg = "Você morreu e foi desclassificado do torneio.";
			addlog($enemy->id, $logmsg, $db);
			}else{
			$tourlose = $enemy->killed;
			}

			$query = $db->execute("update `players` set `gold`=?, `hp`=0, `deaths`=?, `killed`=?, `died`=?, `deadtime`=? where `id`=?", array($enemy->gold + 1 - $goldwin, $enemy->deaths + 1, $tourlose, $enemy->died + 1, time() + $setting->dead_time, $enemy->id));
			

		}
		else
		{
			$depoput .= "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><b>Os dois estáo cançados. Ninguém venceu.</center></b></div>";
		}

		
		if (($checarevenge->recordcount() > 0) and ($player->level > $enemy->level*1.25)){
			if ($enemy->tour != 't'){
			$deleterevenge = $db->execute("delete from `revenge` where `player_id`=? and `enemy_id`=? limit ?", array($player->id, $enemy->id, 1));
			}elseif ($enemy->killed != 0){
			$deleterevenge = $db->execute("delete from `revenge` where `player_id`=? and `enemy_id`=? limit ?", array($player->id, $enemy->id, 1));
			}
		}

			$insert['player_id'] = $enemy->id;
			$insert['time'] = time();
			$insert['attacker_id'] = $player->id;
			$query = $db->autoexecute('attacked', $insert, 'INSERT');
			if (!$query){
				echo "errrooo";
				exit;
			}
		
		$player = check_user($secret_key, $db); //Get new stats
		include("templates/private_header.php");

			$verificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", array($player->id, time()));
				if ($verificpotion->recordcount() > 0){
					$selct = $verificpotion->fetchrow();
					$valortempo = $selct['time'] - time();
					if ($valortempo < 60){
						$valortempo = $valortempo;
						$auxiliar = "segundo(s)";
					}else if($valortempo < 3600){
						$valortempo = ceil($valortempo / 60);
						$auxiliar = "minuto(s)";
					}else if($valortempo < 86400){
						$valortempo = ceil($valortempo / 3600);
						$auxiliar = "hora(s)";
					}

					$potname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", array($selct['item_id']));
					$potdesc = $db->GetOne("select `description` from `blueprint_items` where `id`=?", array($selct['item_id']));
					echo "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><b>" . $potname . ":</b> " . $valortempo . " " . $auxiliar . " restante(s).<br/>" . $potdesc . "</center></div>";
				}

		echo "<div id=\"logdebatalha\" class=\"scroll\" style=\"background-color:#FFFDE0; overflow: auto; height:270px; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
		echo $output;
		echo "</div>";
		echo $depoput;
		echo "<a href=\"battle.php\">Voltar</a>.";
		include("templates/private_footer.php");
		break;
	
	case "search":
		//Check in case somebody entered 0
		$_GET['fromlevel'] = ($_GET['fromlevel'] == 0)?"":$_GET['fromlevel'];
		$_GET['tolevel'] = ($_GET['tolevel'] == 0)?"":$_GET['tolevel'];
		
		//Construct query
		$search = "select `id`, `username`, `level`, `voc`, `promoted` from `players` where `id`!= " . $player->id . " and `hp`>0 and `died`<3 and `reino`!='0' and ";

		$search .= ($_GET['fromlevel'] != "")?"`level` >= " . $_GET['fromlevel'] . " and ":"";
		$search .= ($_GET['tolevel'] != "")?"`level` <= " . $_GET['tolevel'] . " and ":"";

		if ($_GET['username'] != NULL){
			$search .= "`username` LIKE  '%" . $_GET['username'] . "%' and ";
		}

		if ($_GET['voc'] == "1"){
		$search .= "`voc` = 'archer' ";
		} elseif ($_GET['voc'] == "2"){
		$search .= "`voc` = 'knight' ";
		} elseif ($_GET['voc'] == "3"){ 
		$search .= "`voc` = 'mage' ";
		} else {
		$search .= "`voc` != '' ";
		}

		if ($_GET['promo'] == 't'){
		$search .= "and `promoted` = 't' or `promoted` = 's' or `promoted` = 'r' ";
		}elseif ($_GET['promo'] == 'p'){
		$search .= "and `promoted` = 'p' ";
		}

		if ($_GET['reino'] == '1'){
		$search .= "and `reino` = '1' ";
		} elseif ($_GET['reino'] == '2'){
		$search .= "and `reino` = '2' ";
		} elseif ($_GET['reino'] == '3'){
		$search .= "and `reino` = '3' ";
		}

		if ($player->serv == 1){
		$search .= "and `serv`=1 ";
		}elseif ($player->serv == 2){
		$search .= "and `serv`=2 ";
		}

		$search .= "ORDER BY RAND() LIMIT 20";
		
		include("templates/private_header.php");
	
		//Display search form again

			$verificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", array($player->id, time()));
				if ($verificpotion->recordcount() > 0){
					$selct = $verificpotion->fetchrow();
					$valortempo = $selct['time'] - time();
					if ($valortempo < 60){
						$valortempo = $valortempo;
						$auxiliar = "segundo(s)";
					}else if($valortempo < 3600){
						$valortempo = ceil($valortempo / 60);
						$auxiliar = "minuto(s)";
					}else if($valortempo < 86400){
						$valortempo = ceil($valortempo / 3600);
						$auxiliar = "hora(s)";
					}

					$potname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", array($selct['item_id']));
					$potdesc = $db->GetOne("select `description` from `blueprint_items` where `id`=?", array($selct['item_id']));
					echo "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><b>" . $potname . ":</b> " . $valortempo . " " . $auxiliar . " restante(s).<br/>" . $potdesc . "</center></div>";
				}

		echo "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><i>Você pode atacar usuários de nível " . $diflvl . " ou mais.</i></center></div>\n";
		echo "<fieldset>\n";
		echo "<legend><b>Procurar por alguém</b></legend>\n";
		echo "<form method=\"get\" action=\"battle.php\">\n<input type=\"hidden\" name=\"act\" value=\"search\" />\n";
		echo "<table width=\"100%\">\n";
		echo "<tr>\n<td width=\"35%\">Nome:</td>\n<td width=\"65%\"><input type=\"text\" name=\"username\" size=\"16\" value=\"" . stripslashes($_GET['username']) . "\"/></td>\n</tr>\n";
		echo "<tr>\n<td width=\"35%\">Nível:</td>\n<td width=\"65%\"><input type=\"text\" name=\"fromlevel\" size=\"4\" value=\"" . stripslashes($_GET['fromlevel']) . "\" /> á <input type=\"text\" name=\"tolevel\" size=\"4\" value=\"" . stripslashes($_GET['tolevel']) . "\" /></td>\n</tr>\n";

		echo "<tr>\n<td width=\"35%\">Reino:</td>\n<td width=\"65%\"><select name=\"reino\">\n<option value=\"0\"";
		echo (($_GET['reino'] == 0) or (!$_GET['reino']))?" selected=\"selected\"":"";
		echo ">Selecione</option><option value=\"1\"";
		echo ($_GET['reino'] == 1)?" selected=\"selected\"":"";
		echo ">Cathal</option>\n<option value=\"2\"";
		echo ($_GET['reino'] == 2)?" selected=\"selected\"":"";
		echo ">Eroda</option>\n<option value=\"3\"";
		echo ($_GET['reino'] == 3)?" selected=\"selected\"":"";
		echo ">Turkic</option>\n</select></td>\n</tr>\n";

		echo "<tr>\n<td width=\"35%\">Vocação:</td>\n<td width=\"65%\"><select name=\"voc\">\n<option value=\"0\"";
		echo ($_GET['voc'] == 0)?" selected=\"selected\"":"";
		echo ">Selecione</option><option value=\"1\"";
		echo ($_GET['voc'] == 1)?" selected=\"selected\"":"";
		echo ">Arqueiro</option>\n<option value=\"2\"";
		echo ($_GET['voc'] == 2)?" selected=\"selected\"":"";
		echo ">Guerreiro</option>\n<option value=\"3\"";
		echo ($_GET['voc'] == 3)?" selected=\"selected\"":"";
		echo ">Mago</option></select> ";

		echo "<select name=\"promo\">\n<option value=\"any\"";
		echo (($_GET['promo'] == 'any') or (!$_GET['promo']))?" selected=\"selected\"":"";
		echo ">Selecione</option><option value=\"t\"";
		echo ($_GET['promo'] == 't')?" selected=\"selected\"":"";
		echo ">Vocação Superior</option>\n<option value=\"p\"";
		echo ($_GET['promo'] == 'p')?" selected=\"selected\"":"";
		echo ">Vocação Suprema</option>\n</select></td>\n</tr>\n";

		echo "<tr><td></td><td><br /><input type=\"submit\" value=\"Procurar\" /></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n</fieldset>\n";
		if ((!$_GET['reino']) or ($_GET['reino'] == $player->reino)){
			if ($player->reino == 1){
				$reinno = "Cathal";
			} else if ($player->reino == 2){
				$reinno = "Eroda";
			} else if ($player->reino == 3){
				$reinno = "Turkic";
			}

			echo "<center><i>Atacar usuários do seu próprio reino, " . $reinno . ", pode lhe gerar pontos negativos.</i></center>";
		}
		echo "<br /><br />";

		echo "<table width=\"100%\">\n";
		echo "<tr><th width=\"35%\">Usuário</th><th width=\"15%\">Nível</th><th width=\20%\">Vocação</th><th width=\"30%\">Batalha</a></th></tr>\n";
		$query = $db->execute($search); //Search!
		if ($query->recordcount() > 0) //Check if any players were found
		{
			$bool = 1;
			while ($result = $query->fetchrow())
			{
				$checkquerywork = $db->GetOne("select `status` from `work` where `player_id`=? order by `start` DESC", array($result['id']));
				if ($checkquerywork != t) {
				echo "<tr class=\"row" . $bool . "\">\n";
				echo "<td width=\"35%\"><a href=\"profile.php?id=" . $result['username'] . "\">" . $result['username'] . "</a></td>\n";
				echo "<td width=\"15%\">" . $result['level'] . "</td>\n";
				echo "<td width=\"20%\">";
if ($result['voc'] == 'archer' and $result['promoted'] == 'f'){
echo "Caçador";
} else if ($result['voc'] == 'knight' and $result['promoted'] == 'f'){
echo "Espadachim";
} else if ($result['voc'] == 'mage' and $result['promoted'] == 'f'){
echo "Bruxo";
} else if (($result['voc'] == 'archer') and ($result['promoted'] == 't' or $result['promoted'] == 's' or $result['promoted'] == 'r')){
echo "Arqueiro";
} else if (($result['voc'] == 'knight') and ($result['promoted'] == 't' or $result['promoted'] == 's' or $result['promoted'] == 'r')){
echo "Guerreiro";
} else if (($result['voc'] == 'mage') and ($result['promoted'] == 't' or $result['promoted'] == 's' or $result['promoted'] == 'r')){
echo "Mago";
} else if ($result['voc'] == 'archer' and $result['promoted'] == 'p'){
echo "Besteiro";
} else if ($result['voc'] == 'knight' and $result['promoted'] == 'p'){
echo "Cavaleiro";
} else if ($result['voc'] == 'mage' and $result['promoted'] == 'p'){
echo "Arquimago";
}
 				echo "</td>\n";
 				echo "<td width=\"30%\"><a href=\"battle.php?act=attack&username=" . $result['username'] . "\">Atacar</a></td>\n";
				echo "</tr>\n";
				$bool = ($bool==1)?2:1;
				}
			}
		}
		else //Display error message
		{
			echo "<tr>\n";
			echo "<td colspan=\"3\">Nenhum usuário encontrado.</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
		include("templates/private_footer.php");
		break;
	
	default:
		include("templates/private_header.php");

			$verificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", array($player->id, time()));
				if ($verificpotion->recordcount() > 0){
					$selct = $verificpotion->fetchrow();
					$valortempo = $selct['time'] - time();
					if ($valortempo < 60){
						$valortempo = $valortempo;
						$auxiliar = "segundo(s)";
					}else if($valortempo < 3600){
						$valortempo = ceil($valortempo / 60);
						$auxiliar = "minuto(s)";
					}else if($valortempo < 86400){
						$valortempo = ceil($valortempo / 3600);
						$auxiliar = "hora(s)";
					}

					$potname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", array($selct['item_id']));
					$potdesc = $db->GetOne("select `description` from `blueprint_items` where `id`=?", array($selct['item_id']));
					echo "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><b>" . $potname . ":</b> " . $valortempo . " " . $auxiliar . " restante(s).<br/>" . $potdesc . "</center></div>";
				}

if (($player->stat_points > 0) and ($player->level < 15))
{
	echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Antes de batalhar, utilize seus <b>" . $player->stat_points . "</b> pontos de status disponíveis, assim você fica mais forte! <a href=\"stat_points.php\">Clique aqui para utiliza-los!</a></div>";
}

$query = $db->execute("select * from `items` where `player_id`=? and `status`='equipped'", array($player->id));
if (($query->recordcount() < 2) and ($player->level > 4) and ($player->level < 20))
{
	echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">JÁ está na hora de você comprar seus própios itens. <a href=\"shop.php\">Clique aqui e visite o ferreiro</a>.</div>";
}
		
		//The default battle page, giving choice of whether to search for players or to target one
		echo "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><i>Você pode atacar usuários de nível " . $diflvl . " ou mais.</i></center></div>\n";
		echo "<fieldset>\n";
		echo "<legend><b>Procurar por alguém</b></legend>\n";
		echo "<form method=\"get\" action=\"battle.php\">\n<input type=\"hidden\" name=\"act\" value=\"search\" />\n";
		echo "<table width=\"100%\">\n";
		echo "<tr>\n<td width=\"35%\">Nome:</td>\n<td width=\"65%\"><input type=\"text\" name=\"username\" size=\"16\"/></td>\n</tr>\n";
		echo "<tr>\n<td width=\"35%\">Nível:</td>\n<td width=\"65%\"><input type=\"text\" name=\"fromlevel\" size=\"4\" value=\"" . $diflvl . "\" /> á <input type=\"text\" name=\"tolevel\" size=\"4\" /></td>\n</tr>\n";

		echo "<tr>\n<td width=\"35%\">Reino:</td>\n<td width=\"65%\"><select name=\"reino\">";
		echo "<option value=\"0\">Selecione</option>\n";
		echo "<option value=\"1\">Cathal</option>\n";
		echo "<option value=\"2\">Eroda</option>\n";
		echo "<option value=\"3\">Turkic</option>\n";
		echo "</select></td>\n</tr>\n";

		echo "<tr>\n<td width=\"35%\">Vocação:</td>\n<td width=\"65%\"><select name=\"voc\">\n<option value=\"0\"selected=\"selected\">Selecione</option>\n<option value=\"1\">Arqueiro</option>\n<option value=\"2\">Cavaleiro</option>\n<option value=\"3\">Mago</option>\n</select> <select name=\"promo\">\n";
		echo "<option value=\"any\" selected=\"selected\">Selecione</option>";
		echo "<option value=\"t\">Vocação Superior</option>\n";
		echo "<option value=\"p\">Vocação Suprema</option>\n";
		echo "</select>";
		echo "</td>\n</tr>\n";

		echo "<tr><td></td><td><br /><input type=\"submit\" value=\"Procurar\" /></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n</fieldset>\n";
		if ((!$_GET['reino']) or ($_GET['reino'] == $player->reino)){
			if ($player->reino == 1){
				$reinno = "Cathal";
			} else if ($player->reino == 2){
				$reinno = "Eroda";
			} else if ($player->reino == 3){
				$reinno = "Turkic";
			}

			echo "<center><i>Atacar usuários do seu próprio reino, " . $reinno . ", pode lhe gerar pontos negativos.</i></center>";
		}
		echo "<br /><br />\n";

		echo "<fieldset>\n";
		echo "<legend><b>Procurados</b></legend>\n";

		$wantedsleft = ceil(10 - $setting->wanteds);
		if ($wantedsleft < 0){
		$wantedsleft = 0;
		}
		$serchwanted = $db->execute("select * from `wanted` where `kills`>9 order by `kills` desc limit " . $wantedsleft . "");
        
        echo "<table width=\"100%\" border=\"0\">";
		if ($serchwanted->recordcount() > 0){
			echo "<tr>";
				echo "<th width=\"33%\"><b>Usuário</b></td>";
				echo "<th width=\"10%\"><b>Nível</b></td>";
				echo "<th width=\"26%\"><b>Assassinatos/Mortes</b></td>";
				echo "<th width=\"16%\"><b>Recompensa</b></td>";
				echo "<th width=\"15%\"><b>Opções</b></td>";
			echo "</tr>";

			$bool = 1;
			while ($pw = $serchwanted->fetchrow()){
				$wantedinfo = $db->execute("select * from `players` where `id`=?", array($pw['player_id']));
				while($wanted = $wantedinfo->fetchrow())
				{
					echo "<tr class=\"row" . $bool . "\">";
						echo "<td width=\"33%\"><a href=\"profile.php?id=" . $wanted['username'] . "\">" . $wanted['username'] . "</a></td>";
						echo "<td width=\"10%\">" . $wanted['level'] . "</td>";
						echo "<td width=\"26%\">" . $pw['kills'] . "/1</td>";
						echo "<td width=\"16%\">" . ceil($wanted['level'] * $pw['kills']) . "</td>";
						echo "<td width=\"15%\"><a href=\"battle.php?act=attack&username=" . $wanted['username'] . "\">Atacar</a></td>";
					echo "</tr>";
					$bool = ($bool==1)?2:1;
				}
			}
		}else{
			echo "<tr>";
				echo "<td width=\"100%\"><br/><b><center>Nenhum usuário encontrado.</center></b><br/></td>";
			echo "</tr>";
		}

		echo "</table>";
		echo "</fieldset>\n";
		include("templates/private_footer.php");
		break;
}
?>