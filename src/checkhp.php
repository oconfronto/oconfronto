<?php
if ($player->ban > time()){
$newlast = (time() - 210);
$query = $db->execute("update `players` set `last_active`=? where `id`=?", array($newlast, $player->id));
session_unset();
session_destroy();

echo "Você foi banido. As vezes usuários são banidos automaticamente por algum erro em suas contas. Se você acha que foi banido injustamente, ou se tiver algum erro para reportar, crie outra conta e entre em contato com o [GOD]. Assim seu banimento poderá ser removido.";  
  
include("templates/private_footer.php");
exit;
}


if ($player->hp <= 0 and $player->deadtime > time())
{
	$db->execute("delete from `bixos` where `player_id`=?", array($player->id));

	$time = ceil($player->deadtime - time());
	$time_remaining = ceil($time / 60);	
	if (!$_GET['nolayout']){ include("templates/private_header.php"); }
	echo "<fieldset>";

  echo "<legend><b>Você está morto!</b></legend>\n";
  
	echo "<center>Você irá ressucitar em " . $time_remaining . " minuto(s).</center>";
    
    echo "<br/><b><div id=\"counter\" align=\"center\"></div></b><br/>";
    echo "<script type=\"text/javascript\">";
    echo "javascript_countdown.init(" . ceil($player->deadtime - time()) . ", 'counter');";
    echo "</script>";
    
	echo "<center>Ou você poderá ir ao <a href=\"hospt.php\">hospital</a> ou até mesmo usar uma poção de vida.</center>";
	echo "</fieldset>";

echo "<br><br>";

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
echo "<table width=\"100%\"><tr><td><table width=\"80px\"><tr><td><div title=\"header=[Health Potion] body=[Recupera até 5 mil de vida.]\"><img src=\"static/images/itens/healthpotion.gif\"></div></td><td><b>x" . $numerodepocoes . "</b>";
if ($numerodepocoes > 0){
$item = $query->fetchrow();
echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item['id'] . "\">Usar</a>";
}
echo "</td></tr></table></td>";
echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Big Health Potion] body=[Recupera até 10 mil de vida.]\"><img src=\"static/images/itens/bighealthpotion.gif\"></div></td><td><b>x" . $numerodepocoes3 . "</b>";
if ($numerodepocoes3 > 0){
$item3 = $query3->fetchrow();
echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item3['id'] . "\">Usar</a>";
}
echo "</td></tr></table></td>";
echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Mana Potion] body=[Recupera toda sua mana.]\"><img src=\"static/images/itens/manapotion.gif\"></div></td><td><b>x" . $numerodepocoes4 . "</b>";
if ($numerodepocoes4 > 0){
$item4 = $query4->fetchrow();
echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item4['id'] . "\">Usar</a>";
}
echo "</td></tr></table></td>";
echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Energy Potion] body=[Recupera até 50 de energia.]\"><img src=\"static/images/itens/energypotion.gif\"></div></td><td><b>x" . $numerodepocoes2 . "</b>";
if ($numerodepocoes2 > 0){
$item2 = $query2->fetchrow();
echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item2['id'] . "\">Usar</a>";
}
echo "</td></tr></table></td><td><a href=\"hospt.php?act=sell\">Vender Poções</a></td></tr></table>";
echo "</fieldset>";


	if (!$_GET['nolayout']){ include("templates/private_footer.php"); }
	exit;
}

?>