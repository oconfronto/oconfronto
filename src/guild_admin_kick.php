<?php

declare(strict_types=1);

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "Você não pode acessar esta página.";
    echo '<br/><a href="home.php">Voltar</a>.';
} else {

if (isset($_POST['username']) && ($_POST['submit'])) {
    
	$queryuser = $db->execute("select `id`, `username`, `guild` from `players` where `username`=?", [$_POST['username']]);

    if ($queryuser->recordcount() == 0) {
        $errmsg .= "Este usuário não existe!<p />";
        $error = 1;
    } elseif ($_POST['username'] == $guild['leader']) {
        $errmsg .= "Você não pode expulsar o lider do clã!<p />";
        $error = 1;
    } elseif ($_POST['username'] == $guild['vice']) {
        $errmsg .= "Você não pode expulsar o vice-lider do clã!<p />";
        $error = 1;
    } else {
      		$member = $queryuser->fetchrow();
   	   		if ($member['guild'] != $guild['id']) {
       			$errmsg .= "O usuário " . $member['username'] ." não faz parte do clã " . $guild['name'] ."!<p />";
       			$error = 1;
       			} else {
       			$query = $db->execute("update `guilds` set `members`=? where `id`=?", [$guild['members'] - 1, $guild['id']]);
       			$query1 = $db->execute("update `players` set `guild`=? where `username`=?", [NULL, $member['username']]);
       			$logmsg = "Você foi expulso do clã: ". $guild['name'] .".";
   				addlog($member['id'], $logmsg, $db);
       			$msg .= "Você expulsou " . $member['username'] . " do clã.<p />";
       		}
       	}
	}

?>

<fieldset>
<legend><b><?=$guild['name']?> :: Expulsar Membro</b></legend>
<form method="POST" action="guild_admin_kick.php">
<b>Usuário:</b> <?php $query = $db->execute("select `id`, `username` from `players` where `guild`=?", [$guild['id']]);
echo "<select name=\"username\"><option value=''>Selecione</option>";
while($result = $query->fetchrow()){
echo sprintf('<option value="%s">%s</option>', $result["username"], $result[\USERNAME]);
}

echo "</select>"; ?> <input type="submit" name="submit" value="Expulsar">
</form>
</fieldset>
<a href="guild_admin.php">Voltar</a>.

<center><p /><font color=green><?=$msg?></font><p /></center>
<center><p /><font color=red><?=$errmsg?></font><p /></center>

<?php
}

include(__DIR__ . "/templates/private_footer.php");
?>
