<?php
    if ($_GET['voltar'] == true){
        include("lib.php");
		header("Content-Type: text/html; charset=utf-8",true);
	}
	$player = check_user($secret_key, $db);
    
    $pbonusfor = 0;
    $pbonusvit = 0;
    $pbonusagi = 0;
    $pbonusres = 0;
	$countstats = $db->query("select `for`, `vit`, `agi`, `res` from `items` where `player_id`=? and `status`='equipped'", array($player->id));
	while($count = $countstats->fetchrow())
	{
		$pbonusfor += $count['for'];
		$pbonusvit += $count['vit'];
		$pbonusagi += $count['agi'];
		$pbonusres += $count['res'];
	}
    
    $totalstats = ($player->vitality + $player->agility + $player->resistance + $player->strength + $pbonusfor + $pbonusvit + $pbonusagi + $pbonusres);
    echo "<center><div title=\"header=[Força (" . round((($player->strength + $pbonusfor) / $totalstats) * 100) . "%)] body=[" . $player->strength . " +" . $pbonusfor . " pontos de forÁa.]\"><img src=\"images/for.png\"><img src=\"bargen.php?for\">"; if ($player->stat_points > 0){ echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('stat_points.php?for=1&vit=0&agi=0&res=0&add=Home', 'skills')\"><img src=\"images/addstat.png\" border=\"0px\"></a>"; }else{ echo "<img src=\"images/none.png\" border=\"0px\">"; } echo "</div></center>";
    echo "<center><div title=\"header=[Vitalidade (" . round((($player->vitality + $pbonusvit) / $totalstats) * 100) . "%)] body=[" . $player->vitality . " +" . $pbonusvit . " pontos de vitalidade.]\"><img src=\"images/vit.png\"><img src=\"bargen.php?vit\">"; if ($player->stat_points > 0){ echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('stat_points.php?for=0&vit=1&agi=0&res=0&add=Home', 'skills')\"><img src=\"images/addstat.png\" border=\"0px\"></a>"; }else{ echo "<img src=\"images/none.png\" border=\"0px\">"; } echo "</div></center>";
    echo "<center><div title=\"header=[Agilidade (" . round((($player->agility + $pbonusagi) / $totalstats) * 100) . "%)] body=[" . $player->agility . " +" . $pbonusagi . " pontos de agilidade.]\"><img src=\"images/agi.png\"><img src=\"bargen.php?agi\">"; if ($player->stat_points > 0){ echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('stat_points.php?for=0&vit=0&agi=1&res=0&add=Home', 'skills')\"><img src=\"images/addstat.png\" border=\"0px\"></a>"; }else{ echo "<img src=\"images/none.png\" border=\"0px\">"; } echo "</div></center>";
    echo "<center><div title=\"header=[ResistÍncia (" . round((($player->resistance + $pbonusres) / $totalstats) * 100) . "%)] body=[" . $player->resistance . " +" . $pbonusres . " pontos de resistÍncia.]\"><img src=\"images/res.png\"><img src=\"bargen.php?res\">"; if ($player->stat_points > 0){ echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('stat_points.php?for=0&vit=0&agi=0&res=1&add=Home', 'skills')\"><img src=\"images/addstat.png\" border=\"0px\"></a>"; }else{ echo "<img src=\"images/none.png\" border=\"0px\">"; } echo "</div></center>";
    echo "<center><font size=\"1px\"><b>Pontos de status:</b> " . $player->stat_points . "</font></center>";

?>