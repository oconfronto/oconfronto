<?php
include("lib.php");
define("PAGENAME", "Perfil");
$player = check_user($secret_key, $db);

//Check for user ID
if (!$_GET['id'])
{
	header("Location: members.php");
    exit;
}
else
{
	$buscaprofile = $db->execute("select * from `players` where `reino`!='0' and `username`=?", array($_GET['id']));
	if ($buscaprofile->recordcount() == 0)
	{
		header("Location: members.php?error=true");
        exit;
	}
	else
	{
		$profile = $buscaprofile->fetchrow();
	}
}

include("bbcode.php");
include("templates/private_header.php");

if ($profile['ban'] > time()) {
	echo "<fieldset>";
	echo "<legend><b>" . $profile['username'] . "</b></legend>";
	echo "O usuário " . $profile['username'] . " foi banido.<br/>";
		$time = ($profile['ban'] - time());
		$time_remaining = ceil($time / 86400);
	if ($time_remaining > 100){
	echo "Este usuário foi banido permanentemente.";
	}else{
	echo "Faltam " . $time_remaining . " dia(s) para o banimento terminar.";
	}
	echo "</fieldset>";
	echo "<br/><br/>";
	echo "<fieldset>";
	echo "<legend><b>Comentários da administração</b></legend>";
	$admincomments = $db->execute("select `msg` from `bans` where `player_id`=?", array($profile['id']));
 	if ($admincomments->recordcount() == 0) {
	echo "Sem comentários da administração.";
	}else{
	$mensagemdoamn = $admincomments->fetchrow();
	echo $mensagemdoamn['msg'];
	}
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}

if ($profile['gm_rank'] > 9) {
echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
echo "<center><b>O usuário " . $profile['username'] . " Ã um dos administradores do jogo.</b></center>";
echo "</div>";
}elseif ($profile['gm_rank'] > 2){
echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
echo "<center><b>Este usuário é um moderador do jogo.</b></center>";
echo "</div>";
} elseif ($profile['serv'] != $player->serv){
echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
echo "<center><b>Este usuário pertence a outro servidor.</b></center>";
echo "</div>";
}

echo "<ul class=\"tabs\">";
	echo "<li><a href=\"#tab1\">" . $profile['username'] . "</a></li>";
	echo "<li><a href=\"#tab2\">Comentários</a></li>";
	echo "<li><a href=\"#tab3\">Medalhas</a></li>";
	echo "<li><a href=\"#tab4\">Amigos</a></li>";
	echo "<li><a href=\"#tab5\">Estatísticas</a></li>";
echo "</ul>";

echo "<div class=\"tab_container\">";
	echo "<div id=\"tab1\" class=\"tab_content\">";

	echo "<table width=\"100%\">";
	echo "<tr><th>";

	echo "<table width=\"120px\" height=\"120px\" align=\"center\"><tr><td>";
	echo "<div style=\"position: relative;\">";
	echo "<img src=\"" . $profile['avatar'] . "\" width=\"120px\" height=\"120px\" style=\"position: absolute; top: 1; left: 1;\" alt=\"" . $profile['username'] . "\" border=\"1\">";
	$checkranknosite = $db->execute("select `time` from `user_online` where `player_id`=?", array($profile['id']));
	if ($checkranknosite->recordcount() > 0) {
	echo "<a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ","_",$profile['username']) . "')\"><img src=\"images/online2.png\" width=\"120px\" height=\"120px\" style=\"position: absolute; top: 1; left: 1;\" alt=\"" . $profile['username'] . "\" border=\"1px\"></a>";
	}
	echo "</div>";
	echo "</td></tr></table>";

	echo "</th><td width=\"70%\">";
	echo "<br/><table width=\"100%\">";
	echo "<tr>";
	echo "<td width=\"50%\"><b>Usuário:</b></td>";
	echo "<td width=\"50%\">" . $profile['username'] . " (<a href=\"mail.php?act=compose&to=" . $profile['username'] . "\">Mensagem</a>)</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Nível:</b></td>";
	echo "<td>" . $profile['level'] . "</td>";
	echo "</tr>";

	if ($profile['gm_rank'] < 10){
	echo "<tr><td><b>Ranking:</b></td>";
	echo "<td>";
		$sql = "select id from players where gm_rank<10 and serv=" . $profile['serv'] . " order by level desc, exp desc";
		$dados = mysql_query($sql);
		$i = 1;
		while($linha = mysql_fetch_array($dados))
		{
       		 	if($linha['id'] == $profile['id'])
		        echo "$i";
       			$i++;
		}
		echo "Âº";
	echo "</td></tr>";
	}

	echo "<tr><td><b>Vocação:</b></td>";
	echo "<td>";

		if ($profile['voc'] == 'archer' and $profile['promoted'] == 'f'){
		echo "Caçador";
		} else if ($profile['voc'] == 'knight' and $profile['promoted'] == 'f'){
		echo "Espadachim";
		} else if ($profile['voc'] == 'mage' and $profile['promoted'] == 'f'){
		echo "Bruxo";
		} else if (($profile['voc'] == 'archer') and ($profile['promoted'] == 't' or $profile['promoted'] == 's' or $profile['promoted'] == 'r')){
		echo "Arqueiro";
		} else if (($profile['voc'] == 'knight') and ($profile['promoted'] == 't' or $profile['promoted'] == 's' or $profile['promoted'] == 'r')){
		echo "Guerreiro";
		} else if (($profile['voc'] == 'mage') and ($profile['promoted'] == 't' or $profile['promoted'] == 's' or $profile['promoted'] == 'r')){
		echo "Mago";
		} else if ($profile['voc'] == 'archer' and $profile['promoted'] == 'p'){
		echo "Arqueiro Royal";
		} else if ($profile['voc'] == 'knight' and $profile['promoted'] == 'p'){
		echo "Cavaleiro";
		} else if ($profile['voc'] == 'mage' and $profile['promoted'] == 'p'){
		echo "Arquimago";
		}
	echo "</td></tr>";

	echo "<tr><td><b>Reino:</b></td>";
	echo "<td>";

		if ($profile['reino'] == 1){
			echo "Cathal";
		} else if ($profile['reino'] == 2){
			echo "Eroda";
		} else if ($profile['reino'] == 3){
			echo "Turkic";
		} else {
			echo "Nenhum";
                }
	echo "</td></tr>";

	echo "<tr><td><b>Clã:</b></td>";
	echo "<td>";

		if ($profile['guild'] == NULL or $profile['guild'] == '')
		{
		echo "[Nenhum]";
		}
		else
		{
		$profilenomecla = $db->GetOne("select `name` from `guilds` where `id`=?", array($profile['guild']));
		echo "<b>[</b><a href=\"guild_profile.php?id=" . $profile['guild'] . "\">" . $profilenomecla . "</a><b>]</b>";
		}
	echo "</td></tr>";

    echo "<tr><td><b>Vip:</b></td>";
	echo "<td>";
    if ($profile['vip']>time()){ echo "Sim"; }else{ echo "Não"; }
	echo "</font></td>";
	echo "</tr>";
    
	echo "<tr><td><b>Status:</b></td>";
	echo "<td><font color=\"";
		if ($profile['hp']==0){ echo "red\">Morto"; }else{ echo "green\">Vivo"; }
	echo "</font></font></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Cadastrado:</b></td>";

		$mes = date("M", $profile['registered']);
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

	echo "<td>" . date("d", $profile['registered']) . " de " . $mes_ano[$mes] . " de " . date("Y, g:i A", $profile['registered']) . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Idade no jogo:</b></td>";

		$diff = time() - $profile['registered'];
		$age = intval(($diff / 3600) / 24);

	echo "<td>" . $age . " dia(s)</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>última atividade:</b></td>";

		$valortempo = time() -  $profile['last_active'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atrás";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atrás";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atrás";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atrás";
		}

	echo "<td>" . $valortempo2 . " " . $auxiliar2 . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Tempo Online:</b></td>";

		if ($profile['uptime'] < 60){
			$uptime = ceil($profile['uptime']);
			$tempo = "" . $uptime . " segundo(s)";
		}else if($profile['uptime'] < 3600){
			$uptime = ceil($profile['uptime'] / 60);
			$tempo = "" . $uptime . " minuto(s)";
		}else if($profile['uptime'] < 86400){
			$uptime = floor($profile['uptime'] / 3600);
			$extra = ceil(($profile['uptime'] - ($uptime * 3600)) / 60);
			$tempo = "" . $uptime . " hora(s) e " . $extra . " minuto(s)";
		}else if($profile['uptime'] > 86400){
			$uptime = floor($profile['uptime'] / 86400);
			$extra = ceil(($profile['uptime'] - ($uptime * 86400)) / 3600);
			$tempo = "" . $uptime . " dia(s) e " . $extra . " hora(s)";
		}

	echo "<td>" . $tempo . "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</td></tr></table>";

	echo "<br /><br />";

	echo "<center>";
	if ($player->gm_rank < 50){
        if ($profile['id'] != $player->id) {
		echo "<a href=\"battle.php?act=attack&username=" . $profile['username'] . "\">Lutar contra " . $profile['username'] . "</a> | <a href=\"friendlist.php?add=".$profile['username']."\">+ Amigo</a>";
        }
	}else{
		echo "<a href=\"gm/edit_member.php?id=" . $profile['id'] . "\">Editar</a> | <a href=\"gm/ban_member.php?act=ban&id=" . $profile['id'] . "\">Banir</a>";
	}

	if (($player->gm_rank > 2) and ($profile['username'] != 0) and ($profile['username'] > 99)){
		echo " | <a href=\"forum_unban.php?player=" . $profile['id'] . "\">Desbanir do Fórum</a>";
	}
	echo "</center>";


echo "</div>";
echo "<div id=\"tab2\" class=\"tab_content\">";
?>
<table width="95%">
<?php
	$procuramengperfil = $db->execute("select `perfil` from `profile` where `player_id`=?", array($profile['id']));
	if ($procuramengperfil->recordcount() == 0)
	{
		$mencomentario = "Sem comentários.";
	}
	else
	{
		$comentdocara = $procuramengperfil->fetchrow();
		$mencomentario = stripslashes($comentdocara['perfil']);
	}
?>
<tr><td width="15%"><b>Nome real:</b></td><td><?php
$nname = $db->GetOne("select `name` from `accounts` where `id`=?", array($profile['acc_id']));

if ($nname != NULL)
{
echo $nname;
}else{
echo "Não Informado";
}
?></td></tr>
<tr><td width="15%"><b>Sexo:</b></td><td><?php
$sex = $db->GetOne("select `sex` from `accounts` where `id`=?", array($profile['acc_id']));

if ($sex == 'm')
{
echo "Masculino";
}elseif ($sex == 'f'){
echo "Feminino";
}else{
echo "Não Informado";
}
?></td></tr>
<tr><td width="15%"><b>Email:</b></td><td><?php

		$checkshowmmaiele = $db->execute("select * from `other` where `value`=? and `player_id`=?", array(showmail, $profile['acc_id']));
		if ($checkshowmmaiele->recordcount() > 0) {
		$profilemail = $db->GetOne("select `email` from `accounts` where `id`=?", array($profile['acc_id']));
		echo $profilemail;
		}else{
		echo "Email Oculto";
		}
?></td></tr>
<tr><td width="15%"><b>Comentários:</b></td><td><?php echo bbcode::parse($mencomentario); ?></td></tr>
</table>
<?php

echo "</div>";
echo "<div id=\"tab3\" class=\"tab_content\">";

			$medalha = $db->execute("select * from `medalhas` where `player_id`=? order by `medalha` asc, `type` desc", array($profile['id']));
			if ($medalha->recordcount() == 0) {
				echo "<br/><center><b>" . $profile['username'] . " não tem medalhas.</b></center><br/>";
			}else{
			$bronze = $db->execute("select * from `medalhas` where `player_id`=? and `type`='1'", array($profile['id']));
			$prata = $db->execute("select * from `medalhas` where `player_id`=? and `type`='2'", array($profile['id']));
			$ouro = $db->execute("select * from `medalhas` where `player_id`=? and `type`='3'", array($profile['id']));

			echo "<p><table width=\"100%\"><tr>";
				echo "<th width=\"33%\" align=\"right\"><img src=\"images/itens/prata.png\"> X " . $prata->recordcount() . "</th>";
				echo "<th width=\"34%\" align=\"center\"><img src=\"images/itens/medalha.gif\"> X " . $ouro->recordcount() . "</th>";
				echo "<th width=\"33%\" align=\"left\"><img src=\"images/itens/bronze.png\"> X " . $bronze->recordcount() . "</th>";
			echo "</tr></table></p>";

			echo "<table>";

				while($meda = $medalha->fetchrow())
				{
					echo "<tr><td>";
					if ($meda['type'] == '1') {
						echo "<img src=\"images/itens/bronze.png\">";
					} elseif ($meda['type'] == '2') {
						echo "<img src=\"images/itens/prata.png\">";
					} else {
						echo "<img src=\"images/itens/medalha.gif\">";
					}
				
					echo "</td><td><b>" . $meda['medalha'] . ":</b> " . $meda['motivo'] . "</td></tr>";
				}

			echo "</table>";
			}

echo "</div>";
echo "<div id=\"tab4\" class=\"tab_content\">";

			$querwwq = $db->execute("select `fname` from `friends` where `uid`=? order by `fname` desc", array($profile['id']));
			if ($querwwq->recordcount() == 0)
			{
				echo "<br/><center><b>" . $profile['username'] . " não tem amigos.</b></center><br/>";
			}else{
				echo "<table width=\"95%\" border=\"0\">";
				echo "<tr>";
				echo "<th width=\"60%\"><b>Usuário</b></td>";
				echo "<th width=\"40%\"><b>Opções</b></td>";
				echo "</tr>";

			while($friend = $querwwq->fetchrow())
			{
				echo "<tr>\n";
				echo "<td><a href=\"profile.php?id=" . $friend['fname'] . "\">" . $friend['fname'] . "</a></td>\n";
				echo "<td><a href=\"mail.php?act=compose&to=" . $friend['fname'] . "\">Mensagem</a> | <a href=\"battle.php?act=attack&username=" . $friend['fname'] . "\">Lutar</a> | <a href=\"friendlist.php?add=" . $friend['fname'] . "\">+ Amigo</a></td>\n";
				echo "</tr>\n";
			}
				echo "</table>";
			}

echo "</div>";
echo "<div id=\"tab5\" class=\"tab_content\">";

		echo "<b>Usuários assassinados:</b> " . $profile['kills'] . "";
		echo "<br/><b>Monstros mortos:</b> " . $profile['monsterkilled'] . "";
		echo "<br/><b>Monstros mortos em grupo:</b> " . $profile['groupmonsterkilled'] . "";
		echo "<br/><b>Mortes:</b> " . $profile['deaths'] . "";
		echo "<br/><br/><b>Pontuação total:</b> " . ceil(($profile['kills']*6) + ($profile['monsterkilled']/3) + ($profile['groupmonsterkilled']/12) - ($profile['deaths']*35)) . "";

echo "</div>";
echo "</div><br/>";

	include("templates/private_footer.php");
?>