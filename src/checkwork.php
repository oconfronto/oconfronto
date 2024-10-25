<?php

declare(strict_types=1);

if ($player->ban > time()) {
	$newlast = (time() - 210);
	$query = $db->execute("update `players` set `last_active`=? where `id`=?", [$newlast, $player->id]);
	session_unset();
	session_destroy();
	echo "Você foi banido. As vezes usuários são banidos automaticamente por algum erro em suas contas. Se você acha que foi banido injustamente, ou se tiver algum erro para reportar, crie outra conta e entre em contato com o [ADM]. Assim seu banimento poderá ser removido.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$checkworkkee = $db->execute("select * from `work` where `player_id`=? and `status`='t'", [$player->id]);
if ($checkworkkee->recordcount() > 0) {
	$trab = $checkworkkee->fetchrow();

	$timeleftforwork = (($trab['start'] + ($trab['worktime'] * 3600)) - time());
	$time_remaining = ceil($timeleftforwork / 60);
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Trabalho</b></legend>";
	echo "<center>Você está trabalhando como <b>" . $trab['worktype'] . "</b>. Tempo Restante: <b>" . $time_remaining . " minuto(s)</b>.</center>";

	echo '<br/><b><div id="counter" align="center"></div></b><br/>';
	echo '<div id="LEAVE" align="center"><a href="work.php?act=cancel">Abandonar o Trabalho</a></div>';
	echo '<script type="text/javascript">';
	echo "javascript_countdown.init(" . $timeleftforwork . ", 'counter');";
	echo "</script>";

	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}



$checkhuntee = $db->execute("select * from `hunt` where `player_id`=? and `status`='t'", [$player->id]);
if ($checkhuntee->recordcount() > 0) {
	$hunt = $checkhuntee->fetchrow();

	$timeleftforhunt = (($hunt['start'] + ($hunt['hunttime'] * 3600)) - time());
	$time_remaining = ceil($timeleftforhunt / 60);
	$huntmonstername = $db->GetOne("select `username` from `monsters` where `id`=?", [$hunt['hunttype']]);

	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Caça</b></legend>";
	echo "<center>Você está caçando: <b>" . $huntmonstername . "</b>. Tempo Restante: <b>" . $time_remaining . " minuto(s)</b>.</center>";
?>
	<br /><b>
		<div id="COUNTER" align="center"></div>
	</b><br />
	<div id="LEAVE" align="center"><a href="hunt.php?act=cancel">Parar de Caçar</a></div>
	<script type="text/javascript">
		//<![CDATA[[
		<!--
		var OpenTimeCOUNTER = '';
		var TargetCOUNTER = document.getElementById('COUNTER');
		var TargetLEAVE = document.getElementById('LEAVE');
		var SecondsCOUNTER = <?php echo $timeleftforhunt; ?>;
		var TargetTimeCOUNTER = new Date();
		var TimeBeginnCOUNTER = TargetTimeCOUNTER.getTime();
		var TimeEndCOUNTER = TimeBeginnCOUNTER + (SecondsCOUNTER * 1000);
		TargetTimeCOUNTER.setTime(TimeEndCOUNTER);
		var DayCOUNTER = TargetTimeCOUNTER.getDate();
		var MonthCOUNTER = TargetTimeCOUNTER.getMonth() + 1;
		var YearCOUNTER = TargetTimeCOUNTER.getYear();
		if (YearCOUNTER < 999) YearCOUNTER += 1900;
		var hCOUNTER = TargetTimeCOUNTER.getHours();
		var mCOUNTER = TargetTimeCOUNTER.getMinutes();
		var sCOUNTER = TargetTimeCOUNTER.getSeconds();
		var fdayCOUNTER = ((DayCOUNTER < 10) ? "0" : "");
		var fmonthCOUNTER = ((MonthCOUNTER < 10) ? ".0" : ".");
		var fhCOUNTER = ((hCOUNTER < 10) ? "0" : "");
		var fmCOUNTER = ((mCOUNTER < 10) ? ":0" : ":");
		var fsCOUNTER = ((sCOUNTER < 10) ? ":0" : ":");
		var EndDateCOUNTER = fdayCOUNTER + DayCOUNTER + fmonthCOUNTER + MonthCOUNTER + "." + YearCOUNTER;
		var EndTimeCOUNTER = fhCOUNTER + hCOUNTER + fmCOUNTER + mCOUNTER + fsCOUNTER + sCOUNTER;

		var counterthing = window.setTimeout("CountDownCOUNTER()", 1000);

		function CountDownCOUNTER() {
			var CurrentDateCOUNTER = new Date();
			var CurrentTimeCOUNTER = CurrentDateCOUNTER.getTime();
			OpenTimeCOUNTER = Math.floor((TargetTimeCOUNTER - CurrentTimeCOUNTER) / 1000);
			var sCOUNTER = OpenTimeCOUNTER % 60;
			var mCOUNTER = ((OpenTimeCOUNTER - sCOUNTER) / 60) % 60;
			var hCOUNTER = ((OpenTimeCOUNTER - sCOUNTER - mCOUNTER * 60) / (60 * 60));
			var fhCOUNTER = ((hCOUNTER < 10) ? "0" : "");
			var fmCOUNTER = ((mCOUNTER < 10) ? ":0" : ":");
			var fsCOUNTER = ((sCOUNTER < 10) ? ":0" : ":");
			var TimeCOUNTER = fhCOUNTER + hCOUNTER + fmCOUNTER + mCOUNTER + fsCOUNTER + sCOUNTER;
			var OutputStringCOUNTER = TimeCOUNTER;

			if (OpenTimeCOUNTER <= 0) {
				OutputStringCOUNTER = '<a href="hunt.php">Atualizar<\/a>';
				OutputStringLEAVE = '';
				TargetLEAVE.innerHTML = OutputStringLEAVE;
				window.clearTimeout(counterthing);
			}
			TargetCOUNTER.innerHTML = OutputStringCOUNTER;
			counterthing = window.setTimeout("CountDownCOUNTER()", 1000);
		}
		//-->
		//]]>
	</script>

<?php
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

?>