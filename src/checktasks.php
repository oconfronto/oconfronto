<?php

declare(strict_types=1);

$taskaddprize = 0;

$gettasks = $db->execute("select * from `tasks` where `needlvl`<=?", [$player->level]);
if ($gettasks->recordcount() > 0) {
	while ($task = $gettasks->fetchrow()) {
		$checkcompleted = $db->execute("select * from `completed_tasks` where `player_id`=? and `task_id`=?", [$player->id, $task['id']]);
		if ($checkcompleted->recordcount() == 0) {
			if ($task['obj_type'] == 'monster' && $task['obj_extra'] > 0) {
				$checktaskkills = $db->GetOne("select `value` from `monster_tasks` where `player_id`=? and `task_id`=?", [$player->id, $task['id']]);
				if ($checktaskkills >= $task['obj_extra']) {
					$insert['player_id'] = $player->id;
					$insert['task_id'] = $task['id'];
					$insert['time'] = time();
					$query = $db->autoexecute('completed_tasks', $insert, 'INSERT');

					$mname = $db->GetOne("select `username` from `monsters` where `id`=?", [$task['obj_value']]);
					$tarefaconcluida = "Matar " . $task['obj_extra'] . "x o monstro " . $mname . ".";

					$db->execute("delete from `monster_tasks` where `player_id`=? and `task_id`=?", [$player->id, $task['id']]);

					$taskaddprize = 5;
				}
			} elseif ($task['obj_type'] == 'monster' && $task['obj_extra'] == 0) {
				if ($player->monsterkilled >= $task['obj_value']) {
					$insert['player_id'] = $player->id;
					$insert['task_id'] = $task['id'];
					$insert['time'] = time();
					$query = $db->autoexecute('completed_tasks', $insert, 'INSERT');

					$tarefaconcluida = "Matar " . $task['obj_value'] . " monstros.";
					$taskaddprize = 5;
				}
			} elseif ($task['obj_type'] == 'pvp' && $task['obj_extra'] == 0) {
				if ($player->kills >= $task['obj_value']) {
					$insert['player_id'] = $player->id;
					$insert['task_id'] = $task['id'];
					$insert['time'] = time();
					$query = $db->autoexecute('completed_tasks', $insert, 'INSERT');

					$tarefaconcluida = "Matar " . $task['obj_value'] . " usuários.";
					$taskaddprize = 5;
				}
			} elseif ($task['obj_type'] == 'level') {
				if ($player->level >= $task['obj_value']) {
					$insert['player_id'] = $player->id;
					$insert['task_id'] = $task['id'];
					$insert['time'] = time();
					$query = $db->autoexecute('completed_tasks', $insert, 'INSERT');

					$tarefaconcluida = "Alcançar o nível " . $task['obj_value'] . ".";
					$taskaddprize = 5;
				}
			}
		}

		if ($taskaddprize == 5) {
			if ($task['win_type'] == 'gold') {
				$db->execute("update `players` set `gold`=`gold`+? where `id`=?", [$task['win_value'], $player->id]);
				include(__DIR__ . "/templates/private_header.php");
				echo "Parabéns, voc&ecirc; completou a tarefa: <i>" . $tarefaconcluida . "</i><br/>";
				echo "Voc&ecirc; ganhou " . $task['win_value'] . ' moedas de ouro por este motivo.<br/><a href="home.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			if ($task['win_type'] == 'exp') {
				$addexp = $task['win_value'];
				$maxexp = maxExp($player->level);
				while ($addexp + $player->exp >= maxExp($player->level)) {

					$expofnewlvl = maxExp($player->level + 1);
					$db->execute("update `players` set `stat_points`=`stat_points`+3, `level`=`level`+1, `hp`=?, `maxhp`=?, `exp`=0, `magic_points`=`magic_points`+1 where `id`=?", [maxHp($db, $player->id, $player->level, $player->reino, $player->vip), maxHp($db, $player->id, $player->level, $player->reino, $player->vip), $player->id]);

					$usedexp = $maxexp - $player->exp;
					$player->exp = 0;
					$addexp -= $usedexp;
					$player->level += 1;
					$maxexp = maxExp($player->level);
				}

				$db->execute("update `players` set `exp`=`exp`+? where `id`=?", [$addexp, $player->id]);
				$db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", [maxMana($player->level, $player->extramana), maxMana($player->level, $player->extramana), $player->id]);
				$db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", [maxEnergy($player->level, $player->vip), $player->id]);
				include(__DIR__ . "/templates/private_header.php");
				echo "Parabéns, voc&ecirc; completou a tarefa: <i>" . $tarefaconcluida . "</i><br/>";
				echo "Voc&ecirc; ganhou " . $task['win_value'] . ' pontos de experi&ecirc;ncia por este motivo.<br/><a href="home.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			if ($task['win_type'] == 'item') {
				$insert['player_id'] = $player->id;
				$insert['item_id'] = $task['win_value'];
				$query = $db->autoexecute('items', $insert, 'INSERT');
				$itname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", [$task['win_value']]);
				include(__DIR__ . "/templates/private_header.php");
				echo "Parabéns, voc&ecirc; completou a tarefa: <i>" . $tarefaconcluida . "</i><br/>";
				echo "Voc&ecirc; ganhou um(a) " . $itname . ' por este motivo.<br/><a href="home.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}
		}
	}
}
