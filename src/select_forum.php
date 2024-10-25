<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Fórum");
$player = check_user($db);

include(__DIR__ . "/checkforum.php");
include(__DIR__ . "/templates/private_header.php");

$dtopictempo = ceil(time() - 10368000);
$oldtopicselect = $db->execute("select `id`, `user_id` from `forum_question` where `last_post`<?", [$dtopictempo]);
while ($dtopic = $oldtopicselect->fetchrow()) {

	$removeposts = $db->execute("select `a_user_id` from `forum_answer` where `question_id`=?", [$dtopic['id']]);
	while ($player = $removeposts->fetchrow()) {
		$query = $db->execute("update `players` set `posts`=`posts`-1 where `id`=?", [$player['a_user_id']]);
	}

	$query = $db->execute("update `players` set `posts`=`posts`-1 where `id`=?", [$dtopic['user_id']]);

	$real = $db->execute("delete from `forum_question` where `id`=?", [$dtopic['id']]);
	$real = $db->execute("delete from `forum_answer` where `question_id`=?", [$dtopic['id']]);
	$real = $db->execute("delete from `thumb` where `topic_id`=?", [$dtopic['id']]);
}
?>

<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
		<td width="70%" align="center" bgcolor="#E1CBA4"><strong>Categoria</strong></td>
		<td width="15%" align="center" bgcolor="#E1CBA4"><strong>Tópicos</strong></td>
		<td width="15%" align="center" bgcolor="#E1CBA4"><strong>Respostas</strong></td>
	</tr>



	<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><a href="main_forum.php?cat=noticias">Notícias</a></b><br />
			<font size="1">Esteja informado sobre os acontecimentos no jogo.</font>
		</td>
		<?php
		$totalreply = 0;
		$cate1 = $db->execute("select `reply` from `forum_question` where `category`=?", ["noticias"]);
		while ($selecate1 = $cate1->fetchrow()) {
			$totalreply += $selecate1['reply'];
		}

		$topicos1 = $cate1->recordcount();

		?>
		<td><b>
				<center><?= $topicos1 ?></center>
			</b></td>
		<td><b>
				<center><?= $totalreply ?></center>
			</b></td>
	</tr>

	<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><a href="main_forum.php?cat=reino">Reino</a></b><br />
			<font size="1">Participe das discussões promovidas pelo seu imperador.</font>
		</td>
		<?php
		$totalreply2 = 0;
		$cate2 = $db->execute("select `reply` from `forum_question` where `category`=? and `reino`=?", ["reino", $player->reino]);
		while ($selecate2 = $cate2->fetchrow()) {
			$totalreply2 += $selecate2['reply'];
		}

		$topicos2 = $cate2->recordcount();

		?>
		<td><b>
				<center><?= $topicos2 ?></center>
			</b></td>
		<td><b>
				<center><?= $totalreply2 ?></center>
			</b></td>
	</tr>

	<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><a href="main_forum.php?cat=sugestoes">Sugestões</a></b><br />
			<font size="1">Poste aqui suas idéias para tornar o jogo melhor.</font>
		</td>
		<?php
		$totalreply3 = 0;
		$cate3 = $db->execute("select `reply` from `forum_question` where `category`=?", ["sugestoes"]);
		while ($selecate3 = $cate3->fetchrow()) {
			$totalreply3 += $selecate3['reply'];
		}

		$topicos3 = $cate3->recordcount();

		?>
		<td><b>
				<center><?= $topicos3 ?></center>
			</b></td>
		<td><b>
				<center><?= $totalreply3 ?></center>
			</b></td>
	</tr>

	<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><a href="main_forum.php?cat=gangues">Clãs</a></b><br />
			<font size="1">Reuna membros ou encontre um clã através deste fórum.</font>
		</td>
		<?php
		$totalreply4 = 0;
		$cate4 = $db->execute("select `reply` from `forum_question` where `category`=? and `serv`=?", ["gangues", $player->serv]);
		while ($selecate4 = $cate4->fetchrow()) {
			$totalreply4 += $selecate4['reply'];
		}

		$topicos4 = $cate4->recordcount();

		?>
		<td><b>
				<center><?= $topicos4 ?></center>
			</b></td>
		<td><b>
				<center><?= $totalreply4 ?></center>
			</b></td>
	</tr>

	<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><a href="main_forum.php?cat=trade">Compro/Vendo</a></b><br />
			<font size="1">Venda seus itens em desuso e faça um dinheiro extra.</font>
		</td>
		<?php
		$totalreply5 = 0;
		$cate5 = $db->execute("select `reply` from `forum_question` where `category`=? and `serv`=?", ["trade", $player->serv]);
		while ($selecate5 = $cate5->fetchrow()) {
			$totalreply5 += $selecate5['reply'];
		}

		$topicos5 = $cate5->recordcount();

		?>
		<td><b>
				<center><?= $topicos5 ?></center>
			</b></td>
		<td><b>
				<center><?= $totalreply5 ?></center>
			</b></td>
	</tr>

	<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><a href="main_forum.php?cat=duvidas">Dúvidas</a></b><br />
			<font size="1">Esclareça suas dúvidas sobre o jogo aqui.</font>
		</td>
		<?php
		$totalreply6 = 0;
		$cate6 = $db->execute("select `reply` from `forum_question` where `category`=?", ["duvidas"]);
		while ($selecate6 = $cate6->fetchrow()) {
			$totalreply6 += $selecate6['reply'];
		}

		$topicos6 = $cate6->recordcount();

		?>
		<td><b>
				<center><?= $topicos6 ?></center>
			</b></td>
		<td><b>
				<center><?= $totalreply6 ?></center>
			</b></td>
	</tr>

	<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><a href="main_forum.php?cat=fan">Fanwork</a></b><br />
			<font size="1">Trabalhos realizados pelos fans do jogo.</font>
		</td>
		<?php
		$totalreply7 = 0;
		$cate7 = $db->execute("select `reply` from `forum_question` where `category`=?", ["fan"]);
		while ($selecate7 = $cate7->fetchrow()) {
			$totalreply7 += $selecate7['reply'];
		}

		$topicos7 = $cate7->recordcount();

		?>
		<td><b>
				<center><?= $topicos7 ?></center>
			</b></td>
		<td><b>
				<center><?= $totalreply7 ?></center>
			</b></td>
	</tr>


	<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><a href="main_forum.php?cat=outros">Outros</a></b><br />
			<font size="1">Tópicos sobre o jogo que não se encaixam nas categorias acima.</font>
		</td>
		<?php
		$totalreply8 = 0;
		$cate8 = $db->execute("select `reply` from `forum_question` where `category`=?", ["outros"]);
		while ($selecate8 = $cate8->fetchrow()) {
			$totalreply8 += $selecate8['reply'];
		}

		$topicos8 = $cate8->recordcount();

		?>
		<td><b>
				<center><?= $topicos8 ?></center>
			</b></td>
		<td><b>
				<center><?= $totalreply8 ?></center>
			</b></td>
	</tr>

	<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><a href="main_forum.php?cat=off">Off-Topic</a></b><br />
			<font size="1">Assuntos gerais sem relação ao jogo.</font>
		</td>
		<?php
		$totalreply9 = 0;
		$cate9 = $db->execute("select `reply` from `forum_question` where `category`=?", ["off"]);
		while ($selecate9 = $cate9->fetchrow()) {
			$totalreply9 += $selecate9['reply'];
		}

		$topicos9 = $cate9->recordcount();

		?>
		<td><b>
				<center><?= $topicos9 ?></center>
			</b></td>
		<td><b>
				<center><?= $totalreply9 ?></center>
			</b></td>
	</tr>

</table>

<br />
<br />

<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
		<td width="100%" align="center" bgcolor="#E1CBA4"><strong>últimas ações dos moderadores</strong></td>
	</tr>
	<?php
	$querymod = $db->execute("select * from `log_forum` where `type`=0 order by time desc limit 5");
	if ($querymod->recordcount() == 0) {
		echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><font size=\"1\">Nenhum registro encontrado.</font></td></tr>";
	} else {
		while ($mods = $querymod->fetchrow()) {
			echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><font size=\"1\">" . $mods['msg'] . "</font></td></tr>";
		}
	}
	?>
</table>


<?php
include(__DIR__ . "/templates/private_footer.php");
?>