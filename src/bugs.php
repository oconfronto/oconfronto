<?php
declare(strict_types=1);

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*         Modified by Booher        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Contato");
$player = check_user($db);

if ($_POST['comment'] && $_POST['submit']) {  


		$insert['to'] = 1;
		$insert['from'] = $player->id;
		$insert['body'] = $_POST['comment'];
		$insert['body'] = htmlentities((string) $_POST['comment'], ENT_QUOTES);
		$insert['subject'] = "contato";
		$insert['time'] = time();
		$query = $db->execute("insert into `mail` (`to`, `from`, `body`, `subject`, `time`) values (?, ?, ?, ?, ?)", [$insert['to'], $insert['from'], $insert['body'], $insert['subject'], $insert['time']]);
    
    include(__DIR__ . "/templates/private_header.php");
    echo "<p /><center>Obrigado, em breve enviaremos uma resposta.<p />";
    echo '<a href="home.php">Principal</a></center><p />';
    include(__DIR__ . "/templates/private_footer.php");
}

include(__DIR__ . "/templates/private_header.php");
?>

<fieldset>
<p />
<legend><b>Contato</b></legend>
<i>Precisa entrar em contato com a administração? Então está no lugar certo!</i>
<form method="POST" action="bugs.php">
<textarea cols="60" rows="4" name="comment"></textarea><p />
<input type="submit" name="submit" value="Enviar Mensagem">
</form>
</fieldset>

<?php include(__DIR__ . "/templates/private_footer.php");
?>
