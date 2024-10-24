<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($secret_key, $db);

if ($_GET['msg']) {
	$msg = strip_tags($_GET['msg']);
	if ($msg != NULL && strlen($msg) < 240){

		if ($_GET['guild'] == 'true') {
			if ($player->guild != NULL || $player->guild > 0) {
				$insert['player_id'] = $player->id;
				$insert['guild'] = $player->guild;
				$insert['msg'] = $msg;				
				$insert['time'] = time();
				$db->autoexecute('user_chat', $insert, 'INSERT');
			}
		} else {

			$check = $db->execute("select * from `pending` where `pending_id`=31 and `player_id`=?", array($player->id));
			if ($check->recordcount() == 0){
				$insert['player_id'] = $player->id;
				$insert['msg'] = $msg;
				$insert['reino'] = 0;
				$insert['guild'] = 0;
				$insert['time'] = time();
				$db->autoexecute('user_chat', $insert, 'INSERT');
			}else{
				$user = $check->fetchrow();

				if ($user['pending_status'] == 'reino') {
        $insert['player_id'] = $player->id;
        $insert['reino'] = $player->reino;
        $insert['msg'] = $msg;
        $insert['time'] = time();
        $db->autoexecute('user_chat', $insert, 'INSERT');
    } elseif ($player->guild != NULL || $player->guild > 0) {
        $insert['player_id'] = $player->id;
        $insert['guild'] = $player->guild;
        $insert['msg'] = $msg;
        $insert['time'] = time();
        $db->autoexecute('user_chat', $insert, 'INSERT');
    }
			}
		}
	}
}
?>
