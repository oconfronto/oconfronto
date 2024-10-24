<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;
$username = ($_POST['username']);

$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");
//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "Você não pode acessar esta página. <a href=\"home.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

//Guild Leader Admin check
if ($guild['msgs'] > 3) {
    echo "Seu clã já enviou mensagens demais hoje.<br>Máximo de 3 mensagens por dia. <a href=\"home.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

if ($_POST['submit']) {
	if (!$_POST['subject']) {
    		$errmsg .= "<font color=red>Você precisa adicionar um titulo para sua mensagem.</font>";
    		$error = 1;
	}
 
	if (!$_POST['body']) {
    		$errmsg .= "<font color=red>Você precisa escrever uma mensagem.</font>";
    		$error = 1;
	}
 
	if (strlen($_POST['body']) > 5000) {
    		$errmsg .= "<font color=red>Sua mensagem deve ter menos que 5000 caracteres.</font>";
    		$error = 1;
	}


		if ($error == 0){
				$mensagem = "<div style='width:100%; background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px' align='center'><font size=1><b>Esta mensagem foi enviada para todos os membros do clã: " . $guild['name'] . ".</b></font></div><br/>" . $_POST['body'] . "";

				$database = $db->execute("select `id` from `players` where `guild`=?", array($guild['id']));
  					while($member = $database->fetchrow()) {
					$query = $db->execute("insert into `mail` (`to`, `from`, `body`, `subject`, `time`) values (?, ?, ?, ?, ?)", array($member['id'], $player->id, $mensagem, $_POST['subject'], time()));
					}
       
			$query = $db->execute("update `guilds` set `msgs`=? where `id`=?", array($guild['msgs'] + 1, $player->guild));
			$errmsg .= "Mensagem enviada com sucesso.";
			}
	}


?>

<fieldset>
<legend><b><?=$guild['name']?> :: Enviar mensagem</b></legend>
<form method="POST" action="guild_admin_msg.php">
<table width="100%" border="0">
<tr><td width="20%"><b>Para:</b></td><td width="80%">Membros do Clã <?=$guild['name']?></td></tr>
<tr><td width="20%"><b>Assunto:</b></td><td width="80%"><input type="text" name="subject"/></td></tr>
<tr><td width="20%"><b>Mensagem:</b></td><td width="80%"><textarea name="body" rows="15" cols="50"></textarea></td></tr>
<tr><td></td><td><input type="submit" value="Enviar" name="submit" /></td></tr>
</table>
</form>
<br><?=$errmsg?>
</fieldset>
<a href="guild_admin.php">Voltar</a>.

<?php
include(__DIR__ . "/templates/private_footer.php");
?>
