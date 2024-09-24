<?php
$fiun = $db->execute("select * from `cron`");

if(!$fiun){
    die("Error executing query: " . $db->ErrorMsg());
}

while($row = $fiun->fetchrow())
{
	$cron[$row['name']] = $row['value'];
}

date_default_timezone_set('UTC');
$now = time();

$diff = ($now - $cron['reset_last']);
if($diff >= 60)
{
	$atualizacron = $db->execute("update `cron` set `value`=? where `name`=?", array($now, "reset_last"));

	if ($atualizacron) {
	$timedif = ($diff / 60);
	$addhp = (35 * $timedif);
	$addenergy = (20 * $timedif);
	$addmana = (35 * $timedif);
	$sql = "update `players` set hp = IF((hp + $addhp)>maxhp, maxhp, (hp + $addhp)), mana = IF((mana + $addmana)>maxmana, maxmana, (mana + $addmana)) where hp > 0";
	$sql2 = "update `players` set energy = IF((energy + $addenergy)>maxenergy, maxenergy, (energy + $addenergy))";
	$result=mysql_query($sql);
	$result=mysql_query($sql2);
	}
}

$diff = ($now - $cron['interest_last']);

if($diff >= $cron['interest_time'])
{
	$db->execute("update `players` set `died`=0");
	$db->execute("update `players` set `bank`=`bank`+(`bank` / 100)* ? where `bank`+`gold` < ?", array($setting->bank_interest_rate, $setting->bank_limit));
	$db->execute("update `players` set `alerts`=`alerts`-1 where `alerts`>0 and `alerts`<999");
	$db->execute("update `guilds` set `msgs`=0");

	$db->execute("update `cron` set `value`=? where `name`=?", array($now, "interest_last"));

	$friendlogs = $db->execute("select `username`, `level`, `last_level` from `players` where `level`>`last_level`");
	while($flog = $friendlogs->fetchrow())
	{
		$db->execute("update `players` set `last_level`=`level` where `username`=?", array($flog['username']));

		$upo = ceil($flog['level'] - $flog['last_level']);
		if ($upo == 1){

      $plural = "nível";
		}else{
		$plural = "níveis";
		}

		$insert['fname'] = $flog['username'];
		$insert['log'] = "Seu(a) amigo(a) <a href=\"profile.php?id=" . $flog['username'] . "\">" . $flog['username'] . "</a> avançou " . $upo . " " . $plural . " nas últimas 24 horas.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');
	}
	$db->execute("update `players` set `last_level`=`level`");
	$db->execute("update `settings` set `value`=0 where `name`='wanteds'");
}

$diff = ($now - $cron['tuesday_next']);
if(($diff >= 0) and ($setting->lottery_1 == 'f'))
{
	$next = strtotime("next Tuesday");
	$db->execute("update `cron` set `value`=? where `name`=?", array($next, "tuesday_next"));

		$day = rand (2, 3);
		$hour = array("10:00:00", "12:00:00", "14:00:00", "16:00:00", "18:00:00", "20:00:00");
		$hour = $hour[rand(0, (count($hour) - 1))];
		$lottotime = strtotime("+" . $day . " day " . $hour . "");

		$win = array("140-2500", "132-2500", "5000000-500", "173-2000", "175-2500", "172-2000", "174-1500");
		$win = $win[rand(0, (count($win) - 1))];
		$win = explode("-", $win);

		while ($setting->win_id_1 == $win[0]){
			$win = array("140-2500", "132-2500", "5000000-500", "173-2000", "175-2500", "172-2000", "174-1500");
			$win = $win[rand(0, (count($win) - 1))];
			$win = explode("-", $win);
		}

	$db->execute("update `settings` set `value`=? where `name`=?", array($lottotime, end_lotto_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array($win[0], win_id_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array($win[1], lottery_price_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array(t, lottery_1));
}

$diff = ($now - $cron['friday_next']);
if($diff >= 0)
{
	$next = strtotime("next Friday");
	$db->execute("update `cron` set `value`=? where `name`=?", array($next, "friday_next"));

		$day = rand (1, 2);
		$hour = array("14:00:00", "16:00:00", "18:00:00", "20:00:00");
		$hour = $hour[rand(0, (count($hour) - 1))];
		$tourtime = strtotime("+" . $day . " day " . $hour . "");

		while ($setting->end_tour_1_1 == $tourtime){
			$day = rand (1, 2);
			$hour = array("14:00:00", "16:00:00", "18:00:00", "20:00:00");
			$hour = $hour[rand(0, (count($hour) - 1))];
			$tourtime = strtotime("+" . $day . " day " . $hour . "");
		}

	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_1_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_2_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_3_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_4_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_5_1));

	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_1_2));
	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_2_2));
	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_3_2));
	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_4_2));
	$db->execute("update `settings` set `value`=? where `name`=?", array($tourtime, end_tour_5_2));

	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_1_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_2_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_3_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_4_1));
	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_5_1));

	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_1_2));
	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_2_2));
	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_3_2));
	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_4_2));
	$db->execute("update `settings` set `value`=? where `name`=?", array(t, tournament_5_2));
}

$diff = ($now - $cron['oneweek_last']);
if($diff >= $cron['oneweek_time'])
{
	$db->execute("update `players` set `totalbet`=0");
	$db->execute("update `cron` set `value`=? where `name`=?", array($now, "oneweek_last"));
}

$cura = $db->execute("update `players` set `hp`=`maxhp`, `mana`=`maxmana`+`extramana`, `deadtime`=0 where `hp`<1 and `deadtime`<?", array(time()));

$tempoip = ceil(time() - 1800);
$deletaip = $db->execute("delete from `ip` where `time`<?", array($tempoip));

$umasemana = ceil(time() - 604800);
$db->execute("delete from `mail` where `time`<?", array($umasemana));
$db->execute("delete from `user_log` where `time`<?", array($umasemana));
$db->execute("delete from `log_battle` where `time`<?", array($umasemana));
$db->execute("delete from `logbat` where `time`<?", array($umasemana));
$db->execute("delete from `revenge` where `time`<?", array($umasemana));
$db->execute("delete from `work` where `started`<?", array($umasemana));
$db->execute("delete from `log_friends` where `time`<?", array($umasemana));

$duassemana = ceil(time() - 1209600);
$db->execute("delete from `log_gold` where `time`<?", array($duassemana));
$db->execute("delete from `log_item` where `time`<?", array($duassemana));
$db->execute("delete from `account_log` where `time`<?", array($duassemana));
$db->execute("delete from `log_forum` where `time`<?", array($duassemana));

$updategeralwork = $db->execute("select * from `work` where `status`='t' and (`start`+(`worktime`*3600))<?", array(time()));

while($newwork = $updategeralwork->fetchrow())
{
	$db->execute("update `work` set `status`='f' where `id`=?", array($newwork['id']));
	$db->execute("update `players` set `gold`=`gold`+?, `energy`=`energy`/? where `id`=?", array(($newwork['gold'] * $newwork['worktime']), $newwork['worktime'], $newwork['player_id']));
    		$worklog = "Seu trabalho como " . $newwork['worktype'] . " terminou! Voc&ecirc; recebeu <b>" . ($newwork['gold'] * $newwork['worktime']) . " moedas de ouro</b>.";
		addlog($newwork['player_id'], $worklog, $db);
}

$updategeralhunt = $db->execute("select * from `hunt` where `status`='t' and (`start`+(`hunttime`*3600))<?", array(time()));
while($newhunt = $updategeralhunt->fetchrow())
{
	$db->execute("update `hunt` set `status`='f' where `id`=?", array($newhunt['id']));

	$automlevel = $db->GetOne("select `level` from `monsters` where `id`=?", array($newhunt['hunttype']));
	$automname = $db->GetOne("select `username` from `monsters` where `id`=?", array($newhunt['hunttype']));

        //Seleciona o nível do player.
        $autoplevel = $db->GetOne("select `level` from `players` where `id`=?", array($newhunt['player_id']));

        //Seleciona a experi&ecirc;ncia atual do player
        $autopexp = $db->GetOne("select `exp` from `players` where `id`=?", array($newhunt['player_id']));

        //QUANTIDADE DE EXP QUE DEVE SER ADICIONADA AO PLAYER
        $autommtexp = $db->GetOne("select `mtexp` from `monsters` where `id`=?", array($newhunt['hunttype']));
        $expdomonstro = ceil((($autommtexp) * 20) * $newhunt['hunttime']);

        while($expdomonstro + $autopexp >= maxExp($autoplevel)) {

                        //Atualiza player...
                        $query = $db->execute("update `players` set `stat_points`=`stat_points`+3, `level`=`level`+1, `hp`=`maxhp`+30, `maxhp`=`maxhp`+30, `exp`=0, `magic_points`=`magic_points`+1, `monsterkilled`=`monsterkilled`+20 where `id`=?", array($newhunt['player_id']));

		//atualiza variaveis
		$usedexp = maxExp($autoplevel) - $autopexp;
      		$autopexp = 0;

		$expdomonstro = $expdomonstro - $usedexp;
		$autoplevel = $autoplevel + 1;
        }

	$db->execute("update `players` set `exp`=`exp`+? where `id`=?", array($expdomonstro, $newhunt['player_id']));

        			$autopextramp = $db->GetOne("select `extramana` from `players` where `id`=?", array($newhunt['player_id']));

				$dividecinco = ($autoplevel / 5);
				$dividecinco = floor($dividecinco);
				$ganha = 100 + ($dividecinco * 15) + $autopextramp;
				$db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", array($ganha, $ganha, $newhunt['player_id']));

				$fdividevinte = ($autoplevel / 20);
				$fdividevinte = floor($fdividevinte);
				$fganha = 100 + ($fdividevinte * 10);
				$db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", array($fganha, $newhunt['player_id']));
	

			$expwin1 = $automlevel * 6;
			$expwin2 = (($autoplevel - $automlevel) > 0)?$expwin1 - (($autoplevel - $automlevel) * 3):$expwin1 + (($autoplevel - $automlevel) * 3);
			$expwin2 = ($expwin2 <= 0)?1:$expwin2;
			$expwin3 = round(0.5 * $expwin2);
			$expwin = rand($expwin3, $expwin2);
			$goldwin = round(0.9 * $expwin);
			if ($setting->eventoouro > time()){
				$goldwin = round($goldwin * 2);
			}

			$autohuntgold = ceil(($goldwin * 7) * $newhunt['hunttime']);


	$db->execute("update `players` set `gold`=`gold`+?, `energy`=`energy`/?, `hp`=`hp`/? where `id`=?", array($autohuntgold, ceil (($newhunt['hunttime'] + 1) * 1.2), ceil(($newhunt['hunttime'] + 2) / 2.5), $newhunt['player_id']));

  $huntlog = "Sua caça(" . $automname . ") terminou! Voc&ecirc; recebeu <b>" . ceil((($autommtexp) * 20) * $newhunt['hunttime']) . " pontos de experi&ecirc;ncia</b> e <b>" . $autohuntgold . " moedas de ouro</b>.";
	addlog($newhunt['player_id'], $huntlog, $db);
}
    
    $db->execute("delete from `login_tries` where `time`<?", array(time() - 1800));

?>
