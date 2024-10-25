<?php

declare(strict_types=1);

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Bug List");
$player = check_user($db);

include(__DIR__ . "/templates/private_header.php");


if ($player->gm_rank < 2) {
	echo "Você não tem permisão para acessar esta página!";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (isset($_GET['move'])) {
	$query = $db->execute("update `bugs` set `status`='Fixed' where `id`=?", [$_GET['move']]);
	echo "<b><center>Mensagem marcada como resolvida com sucesso.</center></b>";
}

if (isset($_GET['remove'])) {
	$query = $db->execute("update `bugs` set `status`='Pending' where `id`=?", [$_GET['remove']]);
	echo "<b><center>Mensagem marcada como não resolvida com sucesso.</center></b>";
}

if (isset($_GET['validate'])) {
	$query = $db->execute("update `players` set `validated`='1' where `username`=?", [$_GET['validate']]);
	echo "<b><center>A conta bancária do usuário " . $_GET['validate'] . " foi ativa.</center></b>";
}


if (isset($_GET['fixed'])) {

	$query = $db->execute("select * from `bugs` where status='Fixed'");

	while ($buglist = $query->fetchrow()) {
		$idstr = $buglist['id'] . "";
		$usernamestr = $buglist['username'] . "";
		$messagestr = $buglist['comment'] . "";
		$statusstr = "<font color=green>" . $buglist['status'] . "</font>";

		echo "<table>";
		echo sprintf('<tr><td><b>Username: </b>%s</td></tr>', $usernamestr);
		echo sprintf('<tr><td><b>Bug Report: </b>%s</td></tr>', $messagestr);
		echo sprintf('<tr><td><b>Status: </b>%s | <a href="bugslist.php?fixed=true&remove=', $statusstr) . $idstr . "\">Marcar como não resolvido</a></td></tr>";
		echo "</table><p />";
	}
}

if (isset($_GET['pending'])) {

	$query = $db->execute("select * from `bugs` where status='Pending'");

	while ($buglist = $query->fetchrow()) {
		$idstr = $buglist['id'] . "";
		$usernamestr = $buglist['username'] . "";
		$messagestr = $buglist['comment'] . "";
		$statusstr = "<font color=red>" . $buglist['status'] . "</font>";


		echo "<table>";
		echo sprintf('<tr><td><b>Usuário: </b>%s | <a href="bugslist.php?pending=true&validate=', $usernamestr) . $usernamestr . '">Ativar conta de ' . $usernamestr . "</a></td></tr>";
		echo sprintf('<tr><td><b>Mensagem: </b>%s</td></tr>', $messagestr);
		echo "<tr><td><form method=\"post\" action=\"mail.php?act=compose\">\n";
		echo '<input type="hidden" name="to" value="' . $usernamestr . "\" />\n";
		echo "<input type=\"hidden\" name=\"subject\" value=\"Resposta\"\" />\n";
		$reply = explode("\n", $messagestr);
		foreach ($reply as $key => $value) {
			$reply[$key] = ">>" . $value;
		}

		$reply = implode("\n", $reply);
		echo "<input type=\"hidden\" name=\"body\" value=\"\n\n\n" . $reply . "\" />\n";
		echo "<input type=\"submit\" value=\"Responder\" />\n";
		echo "</form></td></tr>\n";
		echo sprintf('<tr><td><b>Status: </b>%s | <a href="bugslist.php?pending=true&move=', $statusstr) . $idstr . '">Marcar como resolvido</a></td></tr>';
		echo "</table><p />";
	}
}

?>

<center>
	<form method="GET" action="bugslist.php">
		<input type="submit" name="fixed" value="Revolvidos">
		<input type="submit" name="pending" value="Não Resolvidos">
		<p /><b>Selecione quais mensagens você quer checar.</b>
		<p />
</center>

<?php include(__DIR__ . "/templates/private_footer.php")
?>