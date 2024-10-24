<?php
declare(strict_types=1);

$newday = $db->GetOne("select `value` from `cron` where `name`='tax_last'");
$newtime = $db->GetOne("select `value` from `cron` where `name`='tax_time'");


if((time() - $newday) >= $newtime)
{	

	$totalreinoa = 0;
	$totalreinob = 0;
	$totalreinoc = 0;
	
	$db->execute("update `cron` set `value`=? where `name`=?", [time(), "tax_last"]);

	$impostos = $db->execute("select `id`, `bank`, `reino` from `players` where `reino`!=0");	

	while($player = $impostos->fetchrow())
	{
		$taxa = $db->GetOne("select `tax` from `reinos` where `id`=?", [$player['reino']]);
		$taxa = floor($taxa * (0.1 * $player['bank']));

		if ($taxa > 0){

			$db->execute("update `players` set `bank`=`bank`-? where `id`=?", [$taxa, $player['id']]);
			$db->execute("update `reinos` set `ouro`=`ouro`+? where `id`=?", [$taxa, $player['reino']]);

			$msg = "Você pagou " . $taxa . " moedas de ouro em impostos para o reino.";
			addlog($player['id'], $msg, $db);

			if ($player['reino'] == 1){
				$totalreinoa += $taxa;
			} elseif ($player['reino'] == 2){
				$totalreinob += $taxa;
			} else {
				$totalreinoc += $taxa;
			}
		}
	}

	$insert['reino'] = 1;
	$insert['log'] = "O reino arrecadou " . $totalreinoa . " em impostos hoje.";
	$insert['time'] = time();
	$db->autoexecute('log_reino', $insert, 'INSERT');

	$insert['reino'] = 2;
	$insert['log'] = "O reino arrecadou " . $totalreinob . " em impostos hoje.";
	$insert['time'] = time();
	$db->autoexecute('log_reino', $insert, 'INSERT');

	$insert['reino'] = 3;
	$insert['log'] = "O reino arrecadou " . $totalreinoc . " em impostos hoje.";
	$insert['time'] = time();
	$db->autoexecute('log_reino', $insert, 'INSERT');
}

$player = check_user($db);
?>
