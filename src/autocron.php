<?php
declare(strict_types=1);

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
	$atualizacron = $db->execute("update `cron` set `value`=? where `name`=?", [$now, "reset_last"]);

	if ($atualizacron) {
	$timedif = ($diff / 60);
	$addhp = (70 * $timedif);
	$addenergy = (20 * $timedif);
	$addmana = (70 * $timedif);
	$sql = sprintf('update `players` set hp = IF((hp + %s)>maxhp, maxhp, (hp + %s)), mana = IF((mana + %s)>maxmana, maxmana, (mana + %s)) where hp > 0', $addhp, $addhp, $addmana, $addmana);
	$sql2 = sprintf('update `players` set energy = IF((energy + %s)>maxenergy, maxenergy, (energy + %s))', $addenergy, $addenergy);
	$result=mysql_query($sql);
	$result=mysql_query($sql2);
	}
}

$diff = ($now - $cron['interest_last']);

if($diff >= $cron['interest_time'])
{
	$db->execute("update `players` set `died`=0");
	$db->execute("update `players` set `bank`=`bank`+(`bank` / 100)* ? where `bank`+`gold` < ?", [$setting->bank_interest_rate, $setting->bank_limit]);
	$db->execute("update `players` set `alerts`=`alerts`-1 where `alerts`>0 and `alerts`<999");
	$db->execute("update `guilds` set `msgs`=0");

	$db->execute("update `cron` set `value`=? where `name`=?", [$now, "interest_last"]);

	$friendlogs = $db->execute("select `username`, `level`, `last_level` from `players` where `level`>`last_level`");
	while($flog = $friendlogs->fetchrow())
	{
		$db->execute("update `players` set `last_level`=`level` where `username`=?", [$flog['username']]);

		$upo = ceil($flog['level'] - $flog['last_level']);
		$plural = $upo == 1 ? "nível" : "níveis";

		$insert['fname'] = $flog['username'];
		$insert['log'] = 'Seu(a) amigo(a) <a href="profile.php?id=' . $flog['username'] . '">' . $flog['username'] . "</a> avançou " . $upo . " " . $plural . " nas últimas 24 horas.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');
	}
 
	$db->execute("update `players` set `last_level`=`level`");
	$db->execute("update `settings` set `value`=0 where `name`='wanteds'");
}

$diff = ($now - $cron['tuesday_next']);
if($diff >= 0 && $setting->lottery_1 == 'f')
{
	$next = strtotime("next Tuesday");
	$db->execute("update `cron` set `value`=? where `name`=?", [$next, "tuesday_next"]);

		$day = random_int (2, 3);
		$hour = ["10:00:00", "12:00:00", "14:00:00", "16:00:00", "18:00:00", "20:00:00"];
		$hour = $hour[random_int(0, (count($hour) - 1))];
		$lottotime = strtotime("+" . $day . " day " . $hour . "");

		$win = ["140-2500", "132-2500", "5000000-500", "173-2000", "175-2500", "172-2000", "174-1500"];
		$win = $win[random_int(0, (count($win) - 1))];
		$win = explode("-", $win);

		while ($setting->win_id_1 == $win[0]){
			$win = ["140-2500", "132-2500", "5000000-500", "173-2000", "175-2500", "172-2000", "174-1500"];
			$win = $win[random_int(0, (count($win) - 1))];
			$win = explode("-", $win);
		}

	$db->execute("update `settings` set `value`=? where `name`=?", [$lottotime, \END_LOTTO_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$win[0], \WIN_ID_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$win[1], \LOTTERY_PRICE_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \LOTTERY_1]);
}

$diff = ($now - $cron['friday_next']);
if($diff >= 0)
{
	$next = strtotime("next Friday");
	$db->execute("update `cron` set `value`=? where `name`=?", [$next, "friday_next"]);

		$day = random_int (1, 2);
		$hour = ["14:00:00", "16:00:00", "18:00:00", "20:00:00"];
		$hour = $hour[random_int(0, (count($hour) - 1))];
		$tourtime = strtotime("+" . $day . " day " . $hour . "");

		while ($setting->end_tour_1_1 == $tourtime){
			$day = random_int (1, 2);
			$hour = ["14:00:00", "16:00:00", "18:00:00", "20:00:00"];
			$hour = $hour[random_int(0, (count($hour) - 1))];
			$tourtime = strtotime("+" . $day . " day " . $hour . "");
		}

	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_1_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_2_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_3_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_4_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_5_1]);

	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_1_2]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_2_2]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_3_2]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_4_2]);
	$db->execute("update `settings` set `value`=? where `name`=?", [$tourtime, \END_TOUR_5_2]);

	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_1_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_2_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_3_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_4_1]);
	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_5_1]);

	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_1_2]);
	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_2_2]);
	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_3_2]);
	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_4_2]);
	$db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_5_2]);
}

$diff = ($now - $cron['oneweek_last']);
if($diff >= $cron['oneweek_time'])
{
	$db->execute("update `players` set `totalbet`=0");
	$db->execute("update `cron` set `value`=? where `name`=?", [$now, "oneweek_last"]);
}

$cura = $db->execute("update `players` set `hp`=`maxhp`, `mana`=`maxmana`+`extramana`, `deadtime`=0 where `hp`<1 and `deadtime`<?", [time()]);

$tempoip = ceil(time() - 1800);
$deletaip = $db->execute("delete from `ip` where `time`<?", [$tempoip]);

$umasemana = ceil(time() - 604800);
$db->execute("delete from `mail` where `time`<?", [$umasemana]);
$db->execute("delete from `user_log` where `time`<?", [$umasemana]);
$db->execute("delete from `log_battle` where `time`<?", [$umasemana]);
$db->execute("delete from `logbat` where `time`<?", [$umasemana]);
$db->execute("delete from `revenge` where `time`<?", [$umasemana]);
$db->execute("delete from `work` where `started`<?", [$umasemana]);
$db->execute("delete from `log_friends` where `time`<?", [$umasemana]);

$duassemana = ceil(time() - 1209600);
$db->execute("delete from `log_gold` where `time`<?", [$duassemana]);
$db->execute("delete from `log_item` where `time`<?", [$duassemana]);
$db->execute("delete from `account_log` where `time`<?", [$duassemana]);
$db->execute("delete from `log_forum` where `time`<?", [$duassemana]);

$updategeralwork = $db->execute("select * from `work` where `status`='t' and (`start`+(`worktime`*3600))<?", [time()]);

while($newwork = $updategeralwork->fetchrow())
{
	$db->execute("update `work` set `status`='f' where `id`=?", [$newwork['id']]);
	$db->execute("update `players` set `gold`=`gold`+?, `energy`=`energy`/? where `id`=?", [($newwork['gold'] * $newwork['worktime']), $newwork['worktime'], $newwork['player_id']]);
    		$worklog = "Seu trabalho como " . $newwork['worktype'] . " terminou! Voc&ecirc; recebeu <b>" . ($newwork['gold'] * $newwork['worktime']) . " moedas de ouro</b>.";
		addlog($newwork['player_id'], $worklog, $db);
}

$updategeralhunt = $db->execute("select * from `hunt` where `status`='t' and (`start`+(`hunttime`*3600))<?", [time()]);
while($newhunt = $updategeralhunt->fetchrow())
{
	$db->execute("update `hunt` set `status`='f' where `id`=?", [$newhunt['id']]);

	$automlevel = $db->GetOne("select `level` from `monsters` where `id`=?", [$newhunt['hunttype']]);
	$automname = $db->GetOne("select `username` from `monsters` where `id`=?", [$newhunt['hunttype']]);

        //Seleciona o nível do player.
        $autoplevel = $db->GetOne("select `level` from `players` where `id`=?", [$newhunt['player_id']]);

        //Seleciona a experi&ecirc;ncia atual do player
        $autopexp = $db->GetOne("select `exp` from `players` where `id`=?", [$newhunt['player_id']]);

        //QUANTIDADE DE EXP QUE DEVE SER ADICIONADA AO PLAYER
        $autommtexp = $db->GetOne("select `mtexp` from `monsters` where `id`=?", [$newhunt['hunttype']]);
        $expdomonstro = ceil((($autommtexp) * 20) * $newhunt['hunttime']);

        while($expdomonstro + $autopexp >= maxExp($autoplevel)) {

                        //Atualiza player...
                        $query = $db->execute("update `players` set `stat_points`=`stat_points`+3, `level`=`level`+1, `hp`=`maxhp`+30, `maxhp`=`maxhp`+30, `exp`=0, `magic_points`=`magic_points`+1, `monsterkilled`=`monsterkilled`+20 where `id`=?", [$newhunt['player_id']]);

		//atualiza variaveis
		$usedexp = maxExp($autoplevel) - $autopexp;
      		$autopexp = 0;

		$expdomonstro -= $usedexp;
		$autoplevel += 1;
        }

	$db->execute("update `players` set `exp`=`exp`+? where `id`=?", [$expdomonstro, $newhunt['player_id']]);

        			$autopextramp = $db->GetOne("select `extramana` from `players` where `id`=?", [$newhunt['player_id']]);

				$dividecinco = ($autoplevel / 5);
				$dividecinco = floor($dividecinco);
				$ganha = 100 + ($dividecinco * 15) + $autopextramp;
				$db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", [$ganha, $ganha, $newhunt['player_id']]);

				$fdividevinte = ($autoplevel / 20);
				$fdividevinte = floor($fdividevinte);
				$fganha = 100 + ($fdividevinte * 10);
				$db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", [$fganha, $newhunt['player_id']]);
	

			$expwin1 = $automlevel * 6;
			$expwin2 = (($autoplevel - $automlevel) > 0)?$expwin1 - (($autoplevel - $automlevel) * 3):$expwin1 + (($autoplevel - $automlevel) * 3);
			$expwin2 = ($expwin2 <= 0)?1:$expwin2;
			$expwin3 = round(0.5 * $expwin2);
			$expwin = random_int($expwin3, $expwin2);
			$goldwin = round(0.9 * $expwin);
			if ($setting->eventoouro > time()){
				$goldwin = round($goldwin * 2);
			}

			$autohuntgold = ceil(($goldwin * 7) * $newhunt['hunttime']);


	$db->execute("update `players` set `gold`=`gold`+?, `energy`=`energy`/?, `hp`=`hp`/? where `id`=?", [$autohuntgold, ceil (($newhunt['hunttime'] + 1) * 1.2), ceil(($newhunt['hunttime'] + 2) / 2.5), $newhunt['player_id']]);

  $huntlog = "Sua caça(" . $automname . ") terminou! Voc&ecirc; recebeu <b>" . ceil((($autommtexp) * 20) * $newhunt['hunttime']) . " pontos de experi&ecirc;ncia</b> e <b>" . $autohuntgold . " moedas de ouro</b>.";
	addlog($newhunt['player_id'], $huntlog, $db);
}
    
    $db->execute("delete from `login_tries` where `time`<?", [time() - 1800]);

?>
