<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($secret_key, $db);

if ($_GET['type'] != 96 && $_GET['type'] < 98 && $_GET['type'] > 0) {
	$db->execute("update `bixos` set `type`=? where `hp`>0 and `player_id`=?", array($_GET['type'], $player->id));

} elseif ($_GET['type'] == 96) {
	$db->execute("update `bixos` set `type`=? where `hp`>0 and `player_id`=?", array($_GET['type'], $player->id));
	header("Location: monster.php?act=attack");
	exit;

} elseif ($_GET['alterar']){
	$modefastbattle = $db->execute("select * from `other` where `value`=? and `player_id`=?", array('fastbattle', $player->id));
	if ($modefastbattle->recordcount() < 1) {
		$insert['player_id'] = $player->id;
		$insert['value'] = fastbattle;
		$db->autoexecute('other', $insert, 'INSERT');

		$enemyid = $db->GetOne("select `id` from `bixos` where `hp`>0 and `player_id`=?", array($player->id));
		if ($enemyid) {
			$db->execute("update `bixos` set `type`=95 where `id`=? and `player_id`=?", array($enemyid, $player->id));
		}

	}else{
		$db->execute("delete from `other` where `value`=? and `player_id`=?", array('fastbattle', $player->id));
	}

	header("Location: monster.php?act=attack");
	exit;
    
} elseif ($_GET['descarregar']){
    
    if ($_GET['times']) {
        $vezes = floor($_GET['times']);
        if ($vezes > 1 && $player->energy >= ($vezes * 10))
        {
            $enemyid = $db->GetOne("select `id` from `bixos` where `hp`>0 and `player_id`=?", array($player->id));
            if ($enemyid) {
                $monsterhp = $db->getone("select `hp` from `monsters` where `id`=?", array($enemyid));
                $db->execute("update `bixos` set `type`=95, `hp`=`hp`+?, `mul`=? where `id`=? and `player_id`=?", array(($monsterhp * ($vezes - 1)), $vezes, $enemyid, $player->id));
            }
        }
     }
     
     header("Location: monster.php?act=attack");
     exit;
}
?>
