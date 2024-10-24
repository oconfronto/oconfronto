<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Grupos de Caça");
$player = check_user($db);


if (!$_GET['id']) {
	header("Location: home.php");
} else {

	$query = $db->execute("select * from `groups` where `id`=? and `player_id`=?", [$_GET['id'], $player->id]);
	if ($query->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
    		echo "Você não pertence a este grupo de caça.<br/>";
		echo '<a href="home.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

 $group = $query->fetchrow();
 $leadername = $db->GetOne("select `username` from `players` where `id`=?", [$_GET['id']]);


if ($_GET['confirm'] && $_GET['id']) {

	include(__DIR__ . "/templates/private_header.php");

			if ($player->id == $group['id']){

				$log1 = $db->execute("select `player_id` from `groups` where `id`=? and `player_id`!=?", [$_GET['id'], $player->id]);
				while($p1 = $log1->fetchrow())
				{
    				$logmsg1 = '<a href="profile.php?id='. $player->username .'">' . $player->username . "</a> desfez seu grupo de caça.";
				addlog($p1['player_id'], $logmsg1, $db);
				}

			$query = $db->execute("delete from `groups` where `id`=?", [$_GET['id']]);
			$query = $db->execute("delete from `group_invite` where `group_id`=?", [$_GET['id']]);
			}else{

				$log1 = $db->execute("select `player_id` from `groups` where `id`=? and `player_id`!=?", [$_GET['id'], $player->id]);
				while($p1 = $log1->fetchrow())
				{
    				$logmsg1 = '<a href="profile.php?id='. $player->username .'">' . $player->username . "</a> não faz mais parte do grupo de caça.";
				addlog($p1['player_id'], $logmsg1, $db);
				}

			$query = $db->execute("delete from `groups` where `id`=? and `player_id`=?", [$_GET['id'], $player->id]);
			$query = $db->execute("delete from `group_invite` where `group_id`=? and `invited_id`=?", [$_GET['id'], $player->id]);
			}

		echo "Você abandonou seu grupo de caça.<br/>";
		echo '<a href="home.php">Voltar</a>.';

	include(__DIR__ . "/templates/private_footer.php");
	exit;


}

include(__DIR__ . "/templates/private_header.php");
echo "Tem certeza que deseja abandonar seu grupo de caça?<br/>";
if ($player->id == $group['id']){
		echo "(Você é o lider do grupo, se o abandonar ele deixará de existir).<br/>";
		}

echo '<a href="group_leave.php?id=' . $_GET['id'] . '&confirm=t">Sim</a> | <a href="friendlist.php">Voltar</a>.';
include(__DIR__ . "/templates/private_footer.php");
exit;
}

?>
