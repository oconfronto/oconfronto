<?php
include("lib.php");
define("PAGENAME", "Administraç„o do Cl„");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

$error = 0;

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", array($player->guild));

if ($query->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $query->fetchrow();
}

include("templates/private_header.php");
?>
<script type="text/javascript" src="bbeditor/ed.js"></script>
<?php
//Guild Leader Admin check
if (($player->username != $guild['leader']) and ($player->username != $guild['vice'])) {
	echo "<fieldset>";
	echo "<legend><b>Acesso Negado</b></legend>";
	echo "<p />Voc  n„o pode acessar esta página.<br/><br/>";
	echo "<a href=\"home.php\">Principal</a>";
	echo "</fieldset>";
include("templates/private_footer.php");
exit;
} else {

    //If price set then update query
    if (isset($_POST['price']) && ($_POST['submit'])) {
        if (($_POST['price']) < 0) {
            $msg1 .= "<font color=\"red\">O preÁo para entrar no cl„ deve ser 0 ou mais.</font><p />";
            $error = 1;
        } else if ($_POST['price'] == $guild['price']) {
            $error = 1;
        } else if ($_POST['price'] > 999999) {
            $msg1 .= "<font color=\"red\">O preÁo maximo È de 999999!</font><p />";
            $error = 1;
        } else if (!is_numeric($_POST['price'])) {
            $msg1 .= "<font color=\"red\">Este valor n„o È valido.</font><p />";
            $error = 1;
        } else {
            $query = $db->execute("update `guilds` set `price`=? where `id`=?", array($_POST['price'], $guild['id']));
            $msg1 .= "Voc trocou o preÁo para entrar no seu cl„.<p />";
        }
    }
    //Imagem by jrotta
    if (isset($_POST['img']) && ($_POST['submit'])) {
        if (strlen($_POST['img']) < 12) {
            $msg2 .= "<font color=\"red\">O endereço da imagem deve ser maior que 12 caracteres!</font><p />";
            $error = 1;
        }
        elseif (@getimagesize($_POST['img'])) {
            $msg2 .= "<font color=\"red\">O endereço da imagem n„o È valido!</font><p />";
            $error = 1;
        } else {
            $query = $db->execute("update `guilds` set `img`=? where `id`=?", array($_POST['img'], $guild['id']));
            $msg2 .= "VocÍ trocou a imagem do seu cl„.<p />";
        }
    }
    //If motd set then update query
    if (isset($_POST['motd']) && ($_POST['submit'])) {
        if (strlen($_POST['motd']) < 3) {
            $msg3 .= "<font color=\"red\">A mensagem do seu cl„ deve conter de 3 ‡ 220 caracteres!</font><p />";
            $error = 1;
        } else if ($_POST['motd'] == $guild['motd']) {
            $error = 1;
        } else if (strlen($_POST['motd']) > 220) {
            $msg3 .= "<font color=\"red\">A mensagem do seu cl„ deve conter de 3 ‡ 220 caracteres!</font><p />";
            $error = 1;
        } else {
            $query = $db->execute("update `guilds` set `motd`=? where `id`=?", array($_POST['motd'], $guild['id']));
            $msg3 .= "Você trocou a mensagem do seu cl„.<p />";
        }
    }
    //If blurb set then update query
    if (isset($_POST['blurb']) && ($_POST['submit'])) {
        if (strlen($_POST['blurb']) < 50) {
            $msg4 .= "<font color=\"red\">A descriç„o deve ser maior que 50 caracteres!</font><p />";
            $error = 1;
        } else if ($_POST['blurb'] == $guild['blurb']) {
            $error = 1;
        } else if (strlen($_POST['blurb']) > 5000) {
            $msg4 .= "<font color=\"red\">A descriç„o deve ser menor que 5000 caracteres!</font><p />";
            $error = 1;
        } else {
            $tirahtmldades=strip_tags($_POST['blurb']);
            $texto=nl2br($tirahtmldades);


            $query = $db->execute("update `guilds` set `blurb`=? where `id`=?", array($texto, $guild['id']));
            $msg4 .= "VocÍ trocou a descriÁ„o do seu cl„.<p />";
        }
    }
    
}

echo "<table width=\"100%\">";
echo "<tr>";

echo "<td width=\"25%\">";
echo "<table width=\"95%\" align=\"center\"><tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Membros</b></td></tr>";
	echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_invite.php'\">Convidar usu·rio</td></tr>";
	echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_kick.php'\">Expulsar usu·rio</td></tr>";
	echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_msg.php'\">Enviar mensagems</td></tr></table>";
echo "</td>";
echo "<td width=\"25%\">";
echo "<table width=\"95%\" align=\"center\"><tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Alianças</b></td></tr>";
	echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_aliado.php'\">Cl„s aliados</td></tr>";
	echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_enemy.php'\">Cl„s inimigos</td></tr></table>";
echo "</td>";
echo "<td width=\"25%\">";
echo "<table width=\"95%\" align=\"center\"><tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Cargos</b></td></tr>";
	if ($player->username == $guild['leader']){
		echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_leadership.php'\">Liderança</td></tr>";
	}
	echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_vice.php'\">Vice-Liderança</td></tr></table>";
echo "</td>";
echo "<td width=\"25%\">";
echo "<table width=\"95%\" align=\"center\"><tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Outros</b></td></tr>";
	echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_treasury.php'\">Tesouro</td></tr>";
	echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_upgrade.php'\">Optimizar cl„</td></tr>";
	if ($player->username == $guild['leader']){
		echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_disband.php'\">Desfazer cl„</td></tr>";
	}
	echo "</table>";
echo "</td>";

echo "</tr>";
echo "</table>";
?>

<p /><p />
<fieldset>
<legend><b>Pagamento do Cl„</b></legend>
<?php
		$valortempo = $guild['pagopor'] - time();
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s)";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s)";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s)";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s)";
		}
?>
<center><b>Cl„ pago por:</b> <?=$valortempo2;?> <?=$auxiliar2;?>. <a href="guild_admin_pay.php">Pagar mais</a>.<br>Este cl„ ser· deletado se o tempo acabar e você n„o pagar mais.</center>
</fieldset>

<p /><p />

<fieldset>
<legend><b>Editar perfil</b></legend>
<p />
<table width="100%">
<form method="POST" action="guild_admin.php">
<tr><td width="25%"><b>Preço para entrar</b>:</td><td><input type="text" name="price" value="<?php
if (!$_POST['price']){
echo $guild['price'];
}else{
echo $_POST['price'];
}
?>" size="10"/><br/><?=$msg1;?></td></tr>
<tr><td width="25%"><b>Imagem</b>:</td><td><input type="text" name="img" value="<?php
if (!$_POST['img']){
echo $guild['img'];
}else{
echo $_POST['img'];
}
?>" size="40"/><br/><?=$msg2;?></td></tr>
<tr><td width="25%"><b>Mensagem</b>:</td><td><input type="text" name="motd" size="40" value="<?php
if (!$_POST['motd']){
echo $guild['motd'];
}else{
echo $_POST['motd'];
}
?>"/><br/><?=$msg3;?></td></tr>
<tr><td width="25%"><b>DescriÁ„o</b>:</td><td>
<?php
if (!$_POST['blurb']){
$textoreferencia = $guild['blurb'];
}else{
$textoreferencia = $_POST['blurb'];
}
?>
<script>
function contador(id_campo,id_alvo,qt_max){
	var texto_campo = document.getElementById(id_campo);

	if(texto_campo.value.length >= qt_max){
		texto_campo.value = texto_campo.value.substring(0,qt_max);
		document.getElementById(id_alvo).innerHTML = '<span style="color:#FF0000;font-weight:bold;">'+texto_campo.value.length+'</span>';

	}else{
		document.getElementById(id_alvo).innerHTML = texto_campo.value.length;
	}
}
</script>
<script>edToolbar('blurb'); </script><textarea onkeyup="contador(this.id,'alvo',5000);"rows="12" name="blurb" id="blurb" class="ed"><?php
$quebras = Array( '<br />', '<br>', '<br/>' );
echo str_replace($quebras, "", $textoreferencia);
?></textarea><font size="1"><br/>O seu texto contem <span id="alvo">5000</span> caracteres. (M·ximo 5000)</font> <?=$msg4;?></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Atualizar"  id="link" class="neg"></td></tr>
</table>
</form>
</fieldset>
<a href="guild_home.php">Voltar</a>.
<?php
include("templates/private_footer.php");
?>
