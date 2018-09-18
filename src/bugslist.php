<?php

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include("lib.php");
define("PAGENAME", "Bug List");
$player = check_user($secret_key, $db);

include("templates/private_header.php");


if ($player->gm_rank < 2)
{
	echo "Você não tem permisão para acessar esta página!";
	include("templates/private_footer.php");
	exit;
}

if (isset($_GET['move'])) {	
	$query = $db->execute("update `bugs` set `status`='Fixed' where `id`=?", array($_GET['move']));	    
	echo "<b><center>Mensagem marcada como resolvida com sucesso.</center></b>";
}

if (isset($_GET['remove'])) {	
	$query = $db->execute("update `bugs` set `status`='Pending' where `id`=?", array($_GET['remove']));	    
	echo "<b><center>Mensagem marcada como não resolvida com sucesso.</center></b>";
}

if (isset($_GET['validate'])) {	
	$query = $db->execute("update `players` set `validated`='1' where `username`=?", array($_GET['validate']));	    
	echo "<b><center>A conta bancária do usuário " . $_GET['validate'] . " foi ativa.</center></b>";
}


if (isset($_GET['fixed'])) {
	
	$query = $db->execute("select * from `bugs` where status='Fixed'");	
	
	while ($buglist= $query->fetchrow()) {
	$idstr = $buglist['id'] . "";
	$usernamestr = $buglist['username'] . "";
    $messagestr = $buglist['comment'] . "";
    $statusstr = "<font color=green>" . $buglist['status'] . "</font>";

    echo "<table>";
    echo "<tr><td><b>Username: </b>$usernamestr</td></tr>";
    echo "<tr><td><b>Bug Report: </b>$messagestr</td></tr>";
    echo "<tr><td><b>Status: </b>$statusstr | <a href=\"bugslist.php?fixed=true&remove=" . $idstr . "\">Marcar como não resolvido</a></td></tr>";
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
    echo "<tr><td><b>Usuário: </b>$usernamestr | <a href=\"bugslist.php?pending=true&validate=" . $usernamestr . "\">Ativar conta de " . $usernamestr . "</a></td></tr>";
    echo "<tr><td><b>Mensagem: </b>$messagestr</td></tr>";
			echo "<tr><td><form method=\"post\" action=\"mail.php?act=compose\">\n";
			echo "<input type=\"hidden\" name=\"to\" value=\"" . $usernamestr . "\" />\n";
			echo "<input type=\"hidden\" name=\"subject\" value=\"Resposta\"\" />\n";
			$reply = explode("\n", $messagestr);
			foreach($reply as $key=>$value)
			{
				$reply[$key] = ">>" . $value;
			}
			$reply = implode("\n", $reply);
			echo "<input type=\"hidden\" name=\"body\" value=\"\n\n\n" . $reply . "\" />\n";
			echo "<input type=\"submit\" value=\"Responder\" />\n";
			echo "</form></td></tr>\n";
    echo "<tr><td><b>Status: </b>$statusstr | <a href=\"bugslist.php?pending=true&move=" . $idstr . "\">Marcar como resolvido</a></td></tr>";
    echo "</table><p />";
	}


}

?>

<center>
<form method="GET" action="bugslist.php">
<input type="submit" name="fixed" value="Revolvidos">
<input type="submit" name="pending" value="Não Resolvidos">
<p /><b>Selecione quais mensagens você quer checar.</b><p />
</center>

<?php include("templates/private_footer.php")
?>