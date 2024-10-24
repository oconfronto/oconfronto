<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
include(__DIR__ . '/bbcode.php');
define("PAGENAME", "Frum");
$player = check_user($db);
include(__DIR__ . "/checkforum.php");

if (!$_GET['id'])
{
	header("Location: select_forum.php");
	exit;
}

include(__DIR__ . "/templates/private_header.php");
foreach($_GET as $key => $value) {
	$data[$key] = filtro($value);
}

$id = $data['id'];
$foruminfo = $db->execute("select * from `forum_question` where `id`=?", [$data['id']]);
if ($foruminfo->recordcount() != 1){
		echo "Este tpico no existe. <a href=\"select_forum.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

$rows = $foruminfo->fetchrow();
$id = $data['id'];

if (($rows['category'] == 'gangues' || $rows['category'] == 'trade') && $player->serv != $rows['serv']){
		echo "<fieldset><legend><b>Erro</b></legend>Voc no pode visualizar este tpico.<BR>";
		echo "<a href='select_forum.php'>Voltar</a></fieldset>";
            include(__DIR__ . "/templates/private_footer.php");
            exit;
}

 
if ($_GET['up'] || $_GET['down'])
{
	$jaupou = $db->execute("select * from `thumb` where `topic_id`=? and `player_id`=?", [$data['id'], $player->id]);
	if ($jaupou->recordcount() > 0){
		echo showAlert("Voc j votou neste tpico!", "red");
	} else {

		$insert['player_id'] = $player->id;
		$insert['topic_id'] = $data['id'];
		$db->autoexecute('thumb', $insert, 'INSERT');

		if ($_GET['up']) {
			$db->execute("update `forum_question` set `up`=`up`+1 where `id`=?", [$data['id']]);
		} elseif ($_GET['down']) {
			$db->execute("update `forum_question` set `down`=`down`+1 where `id`=?", [$data['id']]);
		}

		echo showAlert("Obrigado por votar!");
	}

}

if ($_POST['a_answer']) {
	$fecxhado = $db->GetOne("select `closed` from `forum_question` where `id`=?", [$id]);
	$foruminfo = $db->execute("select * from `forum_question` where `id`=?", [$id]);
	$categoryae = $db->GetOne("select `category` from `forum_question` where `id`=?", [$id]);
	$servae = $db->GetOne("select `serv` from `forum_question` where `id`=?", [$id]);
	$lastpost = $db->execute("select * from `forum_answer` where `a_user_id`=? and `a_datetime`>?", [$player->id, (time() - 20)]);

	if ($fecxhado['closed'] == 't') {
		echo showAlert("Este tpico est fechado.", "red");

	} elseif ($foruminfo->recordcount() != 1) {
		echo showAlert("Este tpico no existe.", "red");

	} elseif (($categoryae == 'gangues' || $categoryae == 'trade') && $player->serv != $servae){
		echo showAlert("Voc no pode postar aqui.", "red");
	
	} elseif ($lastpost->recordcount() != 0) {
		echo showAlert("No faa postagens seguidas.<br/>Aguarde 20 segundos para poder postar novamente.", "red");

	} else {
        $texto = strip_tags($_POST['a_answer']);
        $texto = nl2br($texto);

		$insert['question_id'] = $id;
		$insert['a_user_id'] = $player->id;
		$insert['a_answer'] = $texto;
		$insert['a_datetime'] = time();
		$db->autoexecute('forum_answer', $insert, 'INSERT');

		$total_answers = $db->execute("select `id` from `forum_answer` where `question_id`=?", [$id]);
		$page = ceil($total_answers->recordcount() / 5);
		if ($page < 1){
			$page = 1;
		}

		$db->execute("update `forum_question` set `last_post`=?, `reply`=`reply`+1 where `id`=?", [time(), $id]);
		$db->execute("update `players` set `posts`=`posts`+1 where `id`=?", [$player->id]);
		echo showAlert("Resposta enviada com sucesso.", "green");
	}
}

// get value of id that sent from address bar

if ($rows['category'] == 'gangues') {
$categoria = "Cls";
}elseif ($rows['category'] == 'trade') {
$categoria = "Compro/Vendo";
}elseif ($rows['category'] == 'noticias') {
$categoria = "Notcias";
}elseif ($rows['category'] == 'sugestoes') {
$categoria = "Sugestes";
}elseif ($rows['category'] == 'duvidas') {
$categoria = "Dúvidas";
}elseif ($rows['category'] == 'fan') {
$categoria = "Fanwork";
}elseif ($rows['category'] == 'off') {
$categoria = "Off-Topic";
}else{
$categoria = $rows['category'];
}

echo "<b><font size=\"1px\"><a href=\"select_forum.php\">Fruns</a> -> <a href=\"main_forum.php?cat=" . $rows['category'] . '">' . ucfirst($categoria) . '</a> -> <a href="view_topic.php?id=' . $rows['id'] . '">' . ucfirst(stripslashes($rows['topic'])) . "</a></font></b>";

$query = $db->execute("select `id`, `username`, `avatar`, `posts`, `ban`, `alerts`, `gm_rank`, `serv` from `players` where `id`=?", [$rows['user_id']]);
$topicouser = $query->fetchrow();
?>


<table width="100%" bgcolor="#f2e1ce">
  <tr>
    <td width="120px" bgcolor="#E1CBA4"><center><img src="static/<? echo $topicouser['avatar']; ?>" width="100px" height="100px" border="0"></center>
	<?php
	if($topicouser['gm_rank'] == 2){
		echo '<center><img src="static/images/designer.png" width="100px" height="21px" border="0"></center>';
	} elseif ($topicouser['gm_rank'] > 2 && $topicouser['gm_rank'] < 10){
		echo '<center><img src="static/images/mod.png" width="100px" height="21px" border="0"></center>';
	} elseif ($topicouser['gm_rank'] > 9){
		echo '<center><img src="static/images/admin.png" width="100px" height="21px" border="0"></center>';
	} elseif ($topicouser['alerts'] == 'forever' || $topicouser['alerts'] > 99){
		echo '<center><img src="static/images/banido.png" width="100px" height="21px" border="0"></center>';
	} else {
		echo '<center><img src="static/images/membro.png" width="100px" height="21px" border="0"></center>';
	}
	?>
<center><font size="1px"><b><?php echo showName($topicouser['id'], $db); ?></b><br/><b>Posts:</b> <?php echo $topicouser['posts']; ?>
<br/><?php
if ($topicouser['alerts'] != 0 && $topicouser['alerts'] < 100 && $topicouser['ban'] < time()) {
echo "<b>Alerta:</b> " . $topicouser['alerts'] . "%</br>";
}elseif ($topicouser['ban'] > time()){
echo "Banido</br>";
} elseif ($topicouser['alerts'] == 'forever' || $topicouser['alerts'] > 99) {
    echo "Banido do Frum</br>";
}
 
if ($player->gm_rank > 2) 
{
if ($player->gm_rank > 10) 
{
echo '<br/><a href="forum_ban.php?player=' . $topicouser['id'] . '">Banir</a><br/><a href="delete_all.php?player=' . $topicouser['id'] . '">Apagar todos Posts</a><br/>';
}else{
echo '<br/><a href="forum_ban.php?player=' . $topicouser['id'] . '">Banir</a><br/>';
}
}

?>
</font></center></td>
    <td><table width="95%" align="center">
          <tr>
            <th width="70%" bgcolor="#E1CBA4"><?php echo stripslashes($rows['topic']); ?></th>
            <th width="30%" bgcolor="#E1CBA4"><font size="1px"><center><?php echo date("m/d/y", $rows['postado']);
                echo " &#224;s ";
                echo date("G:i", $rows['postado']); ?></center><font></th>
          </tr>
        </table>

<div class=\"scroll\" style="width : 100%; overflow : auto; ">
<?php
if ($player->username == $topicouser['username'] && $player->gm_rank < 3)
{
echo '&nbsp;&nbsp;&nbsp;<font size="1px"><a href="edit_topic.php?topic=' . $rows['id'] . '">Editar</a> | <a href="move_topic.php?topic=' . $rows['id'] . '">Mover</a> | <a href="delete_topic.php?topic=' . $rows['id'] . '">Deletar</a></font><br/>';
}
elseif ($player->gm_rank > 2) 
{
echo '&nbsp;&nbsp;&nbsp;<font size="1px"><a href="edit_topic.php?topic=' . $rows['id'] . '">Editar</a> | <a href="forum_alert.php?topic=' . $rows['id'] . '">Alertar</a> | <a href="move_topic.php?topic=' . $rows['id'] . '">Mover</a> | <a href="delete_topic.php?topic=' . $rows['id'] . '">Deletar</a></font><br/>';
}

    $topiko = stripslashes($rows['detail']);
    echo (new bbcode())->parse($topiko);
?></div>
        </td>
  </tr>
</table>
<?php
	$alertado = $db->execute("select `msg` from `log_forum` where `type`=1 and `post`=? order by `time` desc limit 1", [$rows['id']]);
	if ($alertado->recordcount() > 0) {
		$alert = $alertado->fetchrow();
		echo showAlert('<font size="1px">' . $alert['msg'] . "</font>", "red");
	}
?>


<?php
if ($rows['vota'] == "t") {
$total = $rows['up'] + $rows['down'];
if ($total > 0){
$porcentoup = intval($rows['up'] / $total * 100);
$porcentodown = intval($rows['down'] / $total * 100);
}else{
$porcentoup = 0;
$porcentodown = 0;
}

echo '<b><font size="1px">De sua nota: <a href="view_topic.php?id=' . $data['id'] . '&up=true"><img src="static/images/thumb_up.png" border="0"></a>' . $porcentoup . '%&nbsp;&nbsp;&nbsp;<a href="view_topic.php?id=' . $data['id'] . '&down=true"><img src="static/images/thumb_down.png" border="0"></a>' . $porcentodown . "%</b> (" . $total . " Votos)</font>";
}

echo "<br/><br/>";

include(__DIR__ . "/pagination.class.php");

$items = 5;

if (isset($data['page']) && is_numeric($data['page'])) {
	$page = $data['page'];
} elseif (!$page) {
	$page = 1;
}

$limit = isset($page) && is_numeric($page) ? "limit ".(($page-1)*$items).(',' . $items) : 'limit ' . $items;

$sqlStr = sprintf('SELECT * FROM forum_answer WHERE question_id=%s order by a_datetime asc %s', $id, $limit);
$sqlStrAux = sprintf('SELECT count(*) as total FROM forum_answer WHERE question_id=%s order by a_datetime asc', $id);

$aux = $db->execute($sqlStrAux)->fetchrow();
$query = $db->execute($sqlStr);


if ($aux['total'] > 0) {

         $p = new pagination;
         $p->Items($aux['total']);
         $p->limit($items);
         $p->target("?id=" . $data['id'] . "");
         $p->currentPage($page);

         while($rows = $query->fetchrow()){
		$info = $db->execute("select `id`, `username`, `avatar`, `posts`, `ban`, `alerts`, `gm_rank`, `serv` from `players` where `id`=?", [$rows['a_user_id']]);
		$user = $info->fetchrow();

		echo '<table width="100%" bgcolor="#f2e1ce">';
		echo "<tr>";
		echo '<td width="120px" bgcolor="#E1CBA4"><center><img src="static/' . $user['avatar'] . '" width="100px" height="100px" border="0"></center>';

			if ($user['gm_rank'] == 2) {
				echo '<center><img src="static/images/designer.png" width="100px" height="21px" border="0"></center>';
			} elseif ($user['gm_rank'] > 2 && $user['gm_rank'] < 10){
				echo '<center><img src="static/images/mod.png" width="100px" height="21px" border="0"></center>';
			} elseif ($user['gm_rank'] > 9){
				echo '<center><img src="static/images/admin.png" width="100px" height="21px" border="0"></center>';
			} elseif ($user['alerts'] == 'forever' || $user['alerts'] > 99){
				echo '<center><img src="static/images/banido.png" width="100px" height="21px" border="0"></center>';
			} else {
				echo '<center><img src="static/images/membro.png" width="100px" height="21px" border="0"></center>';
			}


		echo '<center><font size="1px"><b>' . showName($user['id'], $db) . "</b><br/><b>Posts:</b> " . $user['posts'] . "";
		echo "<br/>";


			if ($user['alerts'] != 0 && $user['alerts'] < 100 && $user['ban'] < time()) {
				echo "<b>Alerta:</b> " . $user['alerts'] . "%</br>";
			}elseif ($user['ban'] > time()){
				echo "Banido</br>";
			} elseif ($user['alerts'] == 'forever' || $user['alerts'] > 99) {
       echo "Banido do Frum</br>";
   }

			if ($player->gm_rank > 2) {
				if ($player->gm_rank > 10) {
					echo '<br/><a href="forum_ban.php?player=' . $user['id'] . '">Banir</a><br/><a href="delete_all.php?player=' . $user['id'] . '">Apagar todos Posts</a><br/>';
				}else{
					echo '<br/><a href="forum_ban.php?player=' . $user['id'] . '">Banir</a><br/>';
				}
			}

		echo "</font></center></td>";
    		echo '<td bgcolor="#f2e1ce">';


			if ($player->username == $user['username']) {
				echo '<font size="1px"><a href="edit_answer.php?topic=' . $rows['question_id'] . "&a=" . $rows['id'] . '">Editar</a> | <a href="delete_answer.php?topic=' . $rows['question_id'] . "&a=" . $rows['id'] . '">Deletar</a></font><br/>';
			} elseif ($player->gm_rank > 2) {
				echo '<font size="1px"><a href="edit_answer.php?topic=' . $rows['question_id'] . "&a=" . $rows['id'] . '">Editar</a> | <a href="forum_alert.php?answer=' . $rows['id'] . '">Alertar</a> | <a href="delete_answer.php?topic=' . $rows['question_id'] . "&a=" . $rows['id'] . '">Deletar</a></font><br/>';
			}

		$resposta = stripslashes($rows['a_answer']);
        echo (new bbcode())->parse($resposta);

		echo "</td>";
        echo "</tr>";
             
		echo "</table>";
			$alertado = $db->execute("select `msg` from `log_forum` where `type`=2 and `post`=? order by `time` desc limit 1", [$rows['id']]);
			if ($alertado->recordcount() > 0) {
				$alert = $alertado->fetchrow();
				echo showAlert('<font size="1px">' . $alert['msg'] . "</font>", "red");
			}
   
		echo "<br/>";
	}

	$p->show();
}


$db->execute("update `forum_question` set `view`=`view`+1 where `id`=?", [$id]);

$fecxhado = $db->GetOne("select `closed` from `forum_question` where `id`=?", [$id]);
if ($fecxhado['closed'] != 't'){
?>
<BR><BR>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#f2e1ce">
<tr>
<form method="post" action="view_topic.php?id=<?php echo $id; ?>">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#f2e1ce">
<tr>
<td colspan="3" bgcolor="#E1CBA4"><strong>Responder</strong> </td>
</tr>
<tr>
<td><script>edToolbar('a_answer'); </script><textarea name="a_answer" rows="6" id="a_answer" class="ed"></textarea></td>
</tr>
<tr>
<td><input type="submit" name="submit" value="Enviar" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="javascript:window.open('example.html', '_blank','top=100, left=100, height=400, width=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');">Dicas de formatao</a></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
<?php
}else{
echo "<br/><center><b>Tpico fechado.</b></center>";
}

include(__DIR__ . "/templates/private_footer.php");
?>
