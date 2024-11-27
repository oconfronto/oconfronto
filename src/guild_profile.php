<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Clã");
$player = check_user($db);
include(__DIR__ . "/bbcode.php");
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$totalgold = 0;
$totalbattles = 0;
$totalmonsters = 0;
$totallevel = 0;
$totaldeaths = 0;

//Check for user ID
if (!($_GET['id'] ?? null)) {
	header("Location: guild_listing.php");
} else {
	//Populates $guild variable
	$query = $db->execute("select * from `guilds` where `id`=?", [$_GET['id'] ?? null]);
	if ($query->recordcount() == 0) {
		header("Location: guild_listing.php");
	} else {
		$guild = $query->fetchrow();
	}
}

if ($player->guild == ($guild['id'] ?? null) && !($_GET['redirect'] ?? null)) {
	header("Location: guild_home.php");
}


include(__DIR__ . "/templates/private_header.php");


echo '<ul class="tabs">';
echo '<li><a href="#tab1">' . $guild['name'] . "</a></li>";
echo '<li><a href="#tab2">Membros</a></li>';
echo '<li><a href="#tab3">Aliados</a></li>';
echo '<li><a href="#tab4">Inimigos</a></li>';
echo "<li><a href=\"#tab5\">Estatásticas</a></li>";
echo "</ul>";


echo '<div class="tab_container">';
echo '<div id="tab1" class="tab_content">';
?>

<table width="100%">
	<tr>
		<td width="100%">
			<table width="100%">
				<tr>
					<td width="25%">
						<center><img src="<?php echo $guild['img'] ?? null; ?>" width="120px" height="120px" border="1"></center>
					</td>
					<td width="75%">
						<table width="100%">
							<tr>
								<td width="20%"><b>Lider:</b></td>
								<td width="35%"><a href="profile.php?id=<?php echo $guild['leader'] ?? null; ?>"><?php echo $guild['leader'] ?? null; ?></a></td>

								<td width="20%"><b>Membros:</b></td>
								<td width="25%"><?php echo $guild['members'] ?? null; ?></td>
							</tr>

							<td width="20%"><b>Vice-Lider:</b></td>
							<td width="35%">
								<?php
								if (($guild['vice'] ?? null) == NULL || ($guild['vice'] ?? null) == '') {
									echo "Ninguém";
								} else {
									echo '<a href="profile.php?id=' . $guild['vice'] . '">' . $guild['vice'] . "</a></td>";
								}
								?>

							<td width="20%"><b>Tesouro:</b></td>
							<td width="25%"><?php echo $guild['gold'] ?? null; ?></td>
				</tr>


				<tr>
					<td width="20%"><b>Reino:</b></td>
					<td width="35%">
						<?php
						if (($guild['reino'] ?? null) == 1) {
							echo "Cathal";
						} elseif (($guild['reino'] ?? null) == 2) {
							echo "Eroda";
						} elseif (($guild['reino'] ?? null) == 3) {
							echo "Turkic";
						} else {
							echo "Nenhum";
						}

						$contvitoria = $db->execute("select `id` from `pwar` where ((`status`='g' and `guild_id`=?) or (`status`='e' and `enemy_id`=?))", [$guild['id'] ?? null, $guild['id'] ?? null]);
						?>
					</td>

					<td width="20%"><b>Vitórias:</b></td>
					<td width="25%"><?php echo $contvitoria->recordcount(); ?></td>
				</tr>

				<tr>
					<td width="20%"><b>Fundada:</b></td>
					<td width="35%">
						<?php

						$mes = date("M", $guild['registered']);
						$mes_ano["Jan"] = "Janeiro";
						$mes_ano["Feb"] = "Fevereiro";
						$mes_ano["Mar"] = "Março";
						$mes_ano["Apr"] = "Abril";
						$mes_ano["May"] = "Maio";
						$mes_ano["Jun"] = "Junho";
						$mes_ano["Jul"] = "Julho";
						$mes_ano["Aug"] = "Agosto";
						$mes_ano["Sep"] = "Setembro";
						$mes_ano["Oct"] = "Outubro";
						$mes_ano["Nov"] = "Novembro";
						$mes_ano["Dec"] = "Dezembro";

						echo "" . date("d", $guild['registered']) . " de " . $mes_ano[$mes] . " de " . date("Y", $guild['registered']) . "";

						$contderrota = $db->execute("select `id` from `pwar` where ((`status`='e' and `guild_id`=?) or (`status`='g' and `enemy_id`=?))", [$guild['id'] ?? null, $guild['id'] ?? null]);
						?>
					</td>

					<td width="20%"><b>Derrotas:</b></td>
					<td width="25%"><?php echo $contderrota->recordcount(); ?></td>
				</tr>
			</table>

		</td>
	</tr>
</table>
</td>
</tr>
<tr>
	<td width="100%">
		<?php
		$bbcode = new bbcode();

		echo "<p> ";
		if (($guild['blurb'] ?? null) == NULL || ($guild['blurb'] ?? null) == '') {
			echo "Sem descrição.";
		} else {
			$descrikon = stripslashes((string) ($guild['blurb'] ?? null));
			$descrikon = $bbcode->parse($descrikon);
			echo textLimit($descrikon, 5000, 105);
		}

		echo "</p>";
		?>
	</td>
</tr>
</table>
<?php
echo "</div>";
echo '<div id="tab2" class="tab_content">';
?>
<table width="100%" border="0">
	<tr>
		<th width="30%"><b>Usuário</b></td>
		<th width="15%"><b>nível</b></td>
		<th width="25%"><b>Vocação</b></td>
		<th width="15%"><b>Status</b></td>
		<th width="20%"><b>Opções</b></td>
	</tr>
	<?php
	//Select all members ordered by level (highest first, members table also doubles as rankings table)
	$query = $db->execute("select `id`, `username`, `level`, `voc`, `promoted`, `gold`, `bank`, `hp`, `kills`, `monsterkilled`, `deaths` from `players` where `guild`=? order by `level` desc", [$guild['id'] ?? null]);

	$bool = 1;
	while ($member = $query->fetchrow()) {
		echo '<tr class="row' . $bool . "\">\n";
		echo '<td><a href="profile.php?id=' . $member['username'] . '">';
		echo (($member['username'] ?? null) == $player->username) ? "<b>" : "";
		echo $member['username'] ?? null;
		echo (($member['username'] ?? null) == $player->username) ? "</b>" : "";
		echo "</a></td>\n";
		echo "<td>" . $member['level'] . "</td>\n";

		echo "<td>";
		if (($member['voc'] ?? null) == 'archer' && ($member['promoted'] ?? null) == 'f') {
			echo "Caçador";
		} elseif (($member['voc'] ?? null) == 'knight' && ($member['promoted'] ?? null) == 'f') {
			echo "Espadachim";
		} elseif (($member['voc'] ?? null) == 'mage' && ($member['promoted'] ?? null) == 'f') {
			echo "Bruxo";
		} elseif (($member['voc'] ?? null) == 'archer' && (($member['promoted'] ?? null) == 't' || ($member['promoted'] ?? null) == 's' || ($member['promoted'] ?? null) == 'r')) {
			echo "Arqueiro";
		} elseif (($member['voc'] ?? null) == 'knight' && (($member['promoted'] ?? null) == 't' || ($member['promoted'] ?? null) == 's' || ($member['promoted'] ?? null) == 'r')) {
			echo "Guerreiro";
		} elseif (($member['voc'] ?? null) == 'mage' && (($member['promoted'] ?? null) == 't' || ($member['promoted'] ?? null) == 's' || ($member['promoted'] ?? null) == 'r')) {
			echo "Mago";
		} elseif (($member['voc'] ?? null) == 'archer' && ($member['promoted'] ?? null) == 'p') {
			echo "Arqueiro Royal";
		} elseif (($member['voc'] ?? null) == 'knight' && ($member['promoted'] ?? null) == 'p') {
			echo "Cavaleiro";
		} elseif (($member['voc'] ?? null) == 'mage' && ($member['promoted'] ?? null) == 'p') {
			echo "Arquimago";
		}

		echo "</td>\n";


		echo "<td>";
		if (($member['hp'] ?? null) < 1) {
			echo '<font color="red">Morto</font>';
		} else {
			echo '<font color="green">Vivo</font>';
		}

		echo "</td>\n";
		echo '<td><font size="1"><a href="mail.php?act=compose&to=' . $member['username'] . '">Mensagem</a><br/><a href="battle.php?act=attack&username=' . $member['username'] . "\">Lutar</a></font></td>\n";
		echo "</tr>\n";

		$totalgold += ($member['gold'] + $member['bank'])  / $guild['members']; //Add to total gold
		$totalbattles += $member['kills'] / $guild['members']; //Add to total battles
		$totalmonsters += $member['monsterkilled'] / $guild['members']; //Add to total monsters
		$totallevel += $member['level'] / $guild['members']; //Add to total level
		$totaldeaths += $member['deaths'] / $guild['members']; //Add to total deaths
		$bool = ($bool == 1) ? 2 : 1;
	}
	?>
</table>
<?php
echo "</div>";
echo '<div id="tab3" class="tab_content">';

echo "<br/>";
$alyquery = $db->execute("select `aled_na` from `guild_aliance` where `guild_na`=?", [$guild['id'] ?? null]);
if ($alyquery->recordcount() < 1) {
	echo "<center><b>O clã " . $guild['name'] . " não tem alianças.</b></center><br/>";
} else {
	while ($aly = $alyquery->fetchrow()) {
		$allyname = $db->GetOne("select `name` from `guilds` where `id`=?", [$aly['aled_na'] ?? null]);
		echo "<center><b>O clã " . $guild['name'] . " possui alianças com o clã <a href=\"guild_profile.php?id=" . $aly['aled_na'] . '">' . $allyname . "</a>.</b></center><br/>";
	}
}

echo "</div>";
echo '<div id="tab4" class="tab_content">';

$enyquery = $db->execute("select `enemy_na` from `guild_enemy` where `guild_na`=?", [$guild['id'] ?? null]);
if ($enyquery->recordcount() < 1) {
	echo "<br/><center><b>O clã " . $guild['name'] . " não tem inimigos.</b></center><br/>";
} else {
	while ($eny = $enyquery->fetchrow()) {
		$ennyname = $db->GetOne("select `name` from `guilds` where `id`=?", [$eny['enemy_na'] ?? null]);
		echo "<br/><center><b>O clã " . $guild['name'] . " é inimigo do clã <a href=\"guild_profile.php?id=" . $eny['enemy_na'] . '">' . $ennyname . "</a>.</b></center><br/>";
	}
}


echo "</div>";
echo '<div id="tab5" class="tab_content">';

echo '<table width="100%" border="0">';
echo "<tr class=\"row1\"><td><b>Média de nível:</b></td><td>" . ceil($totallevel) . "</td></tr>";
echo "<tr class=\"row2\"><td><b>Média de ouro:</b></td><td>" . ceil($totalgold) . "</td></tr>";
echo "<tr class=\"row1\"><td><b>Média de usuários mortos:</b></td><td>" . ceil($totalbattles) . "</td></tr>";
echo "<tr class=\"row2\"><td><b>Média de monstros mortos:</b></td><td>" . ceil($totalmonsters) . "</td></tr>";
echo "</table>";
echo "</div>";
echo "</div>";

include(__DIR__ . "/templates/private_footer.php");
?>