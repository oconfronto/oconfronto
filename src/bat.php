<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Batalhar");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");
include(__DIR__ . "/checktax.php");

include(__DIR__ . "/templates/private_header.php");

if ($player->stat_points > 0 && $player->level < 40) {
	echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">Antes de batalhar, utilize seus <b>' . $player->stat_points . "</b> pontos de status disponíveis, assim você pode ficar mais forte! <a href=\"stat_points.php\">Clique aqui para utilizá-los!</a></div>";
}

$query = $db->execute("select * from `items` where `player_id`=? and `status`='equipped'", [$player->id]);
if ($query->recordcount() < 2 && $player->level > 4 && $player->level < 25) {
	echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Já está na hora de você comprar seus próprios itens. <a href=\"shop.php\">Clique aqui e visite o ferreiro</a>.</div>";
}



echo "<center><i>Existem diversas formas de se obter experiência. Escolha uma delas abaixo.</i></center><br/>";

echo "<a href=\"battle.php\" style=\"text-decoration: none;\"><div class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\">";
echo "<br/>";
echo '<center><span style="font-size:21px; font-weight:bold; font-family:Arial;">Batalhar</font></center>';
echo '<div align="right">Contra jogadores</div>';
echo "</div></a>";


echo "<a href=\"monster.php\" style=\"text-decoration: none;\"><div class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\">";
if ($player->level < 55) {
	echo "<font size=\"1px\">Recomendado para seu nível.</font>";
} else {
	echo "<br/>";
}

echo '<center><span style="font-size:21px; font-weight:bold; font-family:Arial;">Batalhar</font></center>';
echo '<div align="right">Contra monstros</div>';
echo "</div></a>";

echo "<a href=\"hunt.php\" style=\"text-decoration: none;\"><div class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\">";
echo "<br/>";
echo "<center><span style=\"font-size:21px; font-weight:bold; font-family:Arial;\">Caçar</font></center>";
echo "<div align=\"right\">Caçar monstros</div>";
echo "</div></a>";

echo "<a href=\"duel.php\" style=\"text-decoration: none;\"><div class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\">";
echo "<br/>";
echo '<center><span style="font-size:21px; font-weight:bold; font-family:Arial;">Duelar</font></center>';
echo '<div align="right">Contra jogadores</div>';
echo "</div></a>";

include(__DIR__ . "/templates/private_footer.php");
