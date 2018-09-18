<?php

include("lib.php");
define("PAGENAME", "GM");
$player = check_user($secret_key, $db);


if ($player->gm_rank < 75)
{
	include("templates/private_header.php");
	echo "VOcê não tem autoridade para acessar esta página.";
	include("templates/private_footer.php");
	exit;
}

$error = 0;
$noerror = 0;
$totalitem = 0;

include("templates/private_header.php");

//Check for user ID
if (!$_GET['from'])
{
	echo "<form method=\"get\" action=\"backitens.php\">Rastrear itens de: <input type=\"text\" name=\"from\"/><br/>";
	echo "Que provavelmente foram para: <input type=\"text\" name=\"to\"/><br/><br/><input type=\"submit\" value=\"Rastrear\"/>";
}
elseif (($_GET['from']) and (!$_POST['devolve']))
{
	if ($_GET['to']){
	$query = $db->execute("select * from `log_item` where `name1`=? and `name2`=? order by `time` desc", array($_GET['from'], $_GET['to']));
	}else{
	$query = $db->execute("select * from `log_item` where `name1`=? order by `time` desc", array($_GET['from']));
	}
	
	if ($query->recordcount() == 0)
	{
		echo "Nenhum resultado.<br/><br/><a href=\"hack.php\">Voltar</a> | <a href=\"backitens.php\">Procurar mais</a>";
		include("templates/private_footer.php");
		exit;
	}
	else
	{
		$to = $db->GetOne("select `id` from `players` where `username`=?", array($_GET['from']));

		echo "<form method=\"post\" action=\"backitens.php?from=" . $_GET['from'] . "\">";
		while($log = $query->fetchrow())
			{

				$queryver = $db->execute("select * from `items` where `id`=? and `player_id`!=?", array($log['itemid'], $to));
 				if ($queryver->recordcount() == 0)
				{
				echo "" . $log['value'] . " para " . $log['name2'] . " já devolvidada ou não encontrada.<br/>";
				}else{

		$valortempo = time() -  $log['time'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atrás.";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atrás.";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atrás.";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atrás.";
		}

			$totalitem = $totalitem + 1;
			echo "<input type=\"checkbox\" name=\"id[]\" value=\"" . $log['itemid'] . "\" /> " . $log['value'] . " para " . $log['name2'] . " " . $valortempo2 . " " . $auxiliar2 . "<br/>";
			}
			}
		if ($totalitem > 0){
		echo "<br/><input type=\"submit\" name=\"devolve\" value=\"Devolver Selecionados\" /></form>";
		}
	}
}
elseif (($_GET['from']) and ($_POST['devolve']))
{
	$to = $db->GetOne("select `id` from `players` where `username`=?", array($_GET['from']));

	foreach($_POST['id'] as $item)
	{
	$cancel = 0;
	$selitem = $db->execute("select items.item_id, items.player_id, items.mark, items.status, items.item_bonus, items.vit, blueprint_items.name, blueprint_items.type, blueprint_items.effectiveness from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=? and items.player_id!=?", array($item, $to));
	if ($selitem->recordcount() == 0)
	{
		$error = $error + 1;
		$cancel = 1;
	}else{
	$it = $selitem->fetchrow();
	}

	if ($cancel == 0){
	if ($it['status'] == 'equipped'){
				$menoshp = 0;
					if ($it['type'] == 'amulet'){
					$menoshp = (($it['effectiveness'] + ($it['item_bonus'] * 2) + $it['vit']) * 25);
					$query = $db->execute("update `players` set `hp`=`hp`-?, `maxhp`=`maxhp`-? where `id`=?", array($menoshp, $menoshp, $it['player_id']));
					}elseif ($it['type'] != 'amulet'){
					$menoshp = ($it['vit'] * 25);
					$query = $db->execute("update `players` set `hp`=`hp`-?, `maxhp`=`maxhp`-? where `id`=?", array($menoshp, $menoshp, $it['player_id']));
					}
			
	}

	$from = $db->GetOne("select `username` from `players` where `id`=?", array($it['player_id']));
	$mandaitens = $db->execute("update `items` set `player_id`=?, `status`='unequipped' where `id`=?", array($to, $item));
	$deshackeia = $db->execute("update `players` set `hack`='f' where `id`=?", array($to));

		$insert['player_id'] = $to;
		$insert['name1'] = $_GET['from'];
		$insert['name2'] = $from;
		$insert['action'] = "recuperou";
		$insert['value'] = "<b>" . $it['name'] . "</b>";
		$insert['itemid'] = $item;
		$insert['time'] = time();
		$query = $db->autoexecute('log_item', $insert, 'INSERT');

		$insert['player_id'] = $it['player_id'];
		$insert['name1'] = $from;
		$insert['name2'] = $_GET['from'];
		$insert['action'] = "devolveu";
		$insert['value'] = "<b>" . $it['name'] . "</b>";
		$insert['itemid'] = $item;
		$insert['time'] = time();
		$query = $db->autoexecute('log_item', $insert, 'INSERT');

		$noerror = $noerror + 1;
	}
	}

	echo "Erros: " . $error . "<br/>";
	echo "Sem Erros: " . $noerror . "<br/>";
	echo "Total: " . ($error + $noerror) . "<br/><br/><a href=\"hack.php\">Voltar</a> | <a href=\"backitens.php\">Procurar mais</a>";

}

include("templates/private_footer.php");
?>