<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($query->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $query->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");
?>
<script type="text/javascript" src="static/bbeditor/ed.js"></script>
<?php
//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "<fieldset>";
    echo "<legend><b>Acesso Negado</b></legend>";
    echo "<p />Você não pode acessar esta página.<br/><br/>";
    echo '<a href="home.php">Principal</a>';
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

//If price set then update query
if (isset($_POST['price']) && ($_POST['submit'])) {
    if (($_POST['price']) < 0) {
        $msg1 .= "<font color=\"red\">O preço para entrar no clã deve ser 0 ou mais.</font><p />";
        $error = 1;
    } elseif ($_POST['price'] == $guild['price']) {
        $error = 1;
    } elseif ($_POST['price'] > 999999) {
        $msg1 .= "<font color=\"red\">O preço maximo é de 999999!</font><p />";
        $error = 1;
    } elseif (!is_numeric($_POST['price'])) {
        $msg1 .= "<font color=\"red\">Este valor não é valido.</font><p />";
        $error = 1;
    } else {
        $query = $db->execute("update `guilds` set `price`=? where `id`=?", [$_POST['price'], $guild['id']]);
        $msg1 .= "Voc trocou o preço para entrar no seu clã.<p />";
    }
}

//Imagem by jrotta
// if (isset($_POST['img']) && ($_POST['submit'])) {
//     if (strlen($_POST['img']) < 12) {
//         $msg2 .= "<font color=\"red\">O endereço da imagem deve ser maior que 12 caracteres!</font><p />";
//         $error = 1;
//     } elseif (@getimagesize($_POST['img'])) {
//         $msg2 .= "<font color=\"red\">O endereço da imagem não é valido!</font><p />";
//         $error = 1;
//     } else {
//         $query = $db->execute("update `guilds` set `img`=? where `id`=?", array($_POST['img'], $guild['id']));
//         $msg2 .= "Você trocou a imagem do seu clã.<p />";
//     }
// }
if ($_POST['upload']) {
    if (!$_POST['guild_admin']) {
        $errmsg .= "Por favor preencha todos os campos!";
        $error = 1;
    } elseif ($_POST['guild_admin'] && (@GetImageSize($_POST['guild_admin']) === [] || @GetImageSize($_POST['guild_admin']) === false)) {
        $errmsg .= "O endereço desta imagem não é válido!";
        $error = 1;
    }

    if ($error == 0) {
        $avat = $_POST['guild_admin'] ?: "default_guild.png";
        $query = $db->execute("update `guilds` set `img`=? where `id`=?", [$avat, $guild['id']]);
        $msg .= "Você alterou seu avatar com sucesso!";
        // Espera 1.5 segundos antes de atualizar a página
        //  echo "<p><font color='green'>$msg</font></p>";
        echo showAlert("<b>" . $msg . "</b>", "green");
        echo '<meta http-equiv="refresh" content="1.3">';
        exit;
    }

    // Espera 1.5 segundos antes de atualizar a página
    //  echo "<p><font color='green'>$msg</font></p>";
    echo showAlert("<b>" . $errmsg . "</b>", "red");
    echo '<meta http-equiv="refresh" content="1.3">';
    exit;
}

// if ($_GET['success'] == 'true') {
//     echo showAlert("Você trocou a imagem do seu clã com sucesso!", "green");
// } elseif ($_GET['msg']) {
//     echo showAlert("<b>Erro:</b> A imagem enviada não é suportada.<br/>Verifique se a imagem enviada atende todos os requisitos listados abaixo do formulário de envio.", "red", "left");
// }
//If motd set then update query
if (isset($_POST['motd']) && ($_POST['submit'])) {
    if (strlen((string) $_POST['motd']) < 3) {
        $msg3 .= "<font color=\"red\">A mensagem do seu clã deve conter de 3 é 220 caracteres!</font><p />";
        $error = 1;
    } elseif ($_POST['motd'] == $guild['motd']) {
        $error = 1;
    } elseif (strlen((string) $_POST['motd']) > 220) {
        $msg3 .= "<font color=\"red\">A mensagem do seu clã deve conter de 3 é 220 caracteres!</font><p />";
        $error = 1;
    } else {
        $query = $db->execute("update `guilds` set `motd`=? where `id`=?", [$_POST['motd'], $guild['id']]);
        $msg3 .= "Você trocou a mensagem do seu clã.<p />";
    }
}

//If blurb set then update query
if (isset($_POST['blurb']) && ($_POST['submit'])) {
    if (strlen((string) $_POST['blurb']) < 50) {
        $msg4 .= "<font color=\"red\">A descrição deve ser maior que 50 caracteres!</font><p />";
        $error = 1;
    } elseif ($_POST['blurb'] == $guild['blurb']) {
        $error = 1;
    } elseif (strlen((string) $_POST['blurb']) > 5000) {
        $msg4 .= "<font color=\"red\">A descrição deve ser menor que 5000 caracteres!</font><p />";
        $error = 1;
    } else {
        $tirahtmldades = strip_tags((string) $_POST['blurb']);
        $texto = nl2br($tirahtmldades);


        $query = $db->execute("update `guilds` set `blurb`=? where `id`=?", [$texto, $guild['id']]);
        $msg4 .= "Você trocou a descrição do seu clã.<p />";
    }
}

echo '<table width="100%">';
echo "<tr>";

echo '<td width="25%">';
echo '<table width="95%" align="center"><tr><td align="center" bgcolor="#E1CBA4"><b>Membros</b></td></tr>';
echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_invite.php'\">Convidar usuário</td></tr>";
echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_kick.php'\">Expulsar usuário</td></tr>";
echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_msg.php'\">Enviar mensagens</td></tr></table>";
echo "</td>";
echo '<td width="25%">';
echo "<table width=\"95%\" align=\"center\"><tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Alianças</b></td></tr>";
echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_aliado.php'\">Clãs aliados</td></tr>";
echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_enemy.php'\">Clãs inimigos</td></tr></table>";
echo "</td>";
echo '<td width="25%">';
echo '<table width="95%" align="center"><tr><td align="center" bgcolor="#E1CBA4"><b>Cargos</b></td></tr>';
if ($player->username == $guild['leader']) {
    echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_leadership.php'\">Liderança</td></tr>";
}

echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_vice.php'\">Vice-Liderança</td></tr></table>";
echo "</td>";
echo '<td width="25%">';
echo '<table width="95%" align="center"><tr><td align="center" bgcolor="#E1CBA4"><b>Outros</b></td></tr>';
echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_treasury.php'\">Tesouro</td></tr>";
echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_upgrade.php'\">Optimizar clã</td></tr>";
if ($player->username == $guild['leader']) {
    echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" onclick=\"window.location.href='guild_admin_disband.php'\">Desfazer clã</td></tr>";
}

echo "</table>";
echo "</td>";

echo "</tr>";
echo "</table>";
?>

<p />
<p />
<fieldset>
    <legend><b>Pagamento do Clã</b></legend>
    <?php
    $valortempo = $guild['pagopor'] - time();
    if ($valortempo < 60) {
        $valortempo2 = $valortempo;
        $auxiliar2 = "segundo(s)";
    } elseif ($valortempo < 3600) {
        $valortempo2 = floor($valortempo / 60);
        $auxiliar2 = "minuto(s)";
    } elseif ($valortempo < 86400) {
        $valortempo2 = floor($valortempo / 3600);
        $auxiliar2 = "hora(s)";
    } elseif ($valortempo > 86400) {
        $valortempo2 = floor($valortempo / 86400);
        $auxiliar2 = "dia(s)";
    }
    ?>
    <center><b>Clã pago por:</b> <?= $valortempo2; ?> <?= $auxiliar2; ?>. <a href="guild_admin_pay.php">Pagar
            mais</a>.<br>Este clã será deletado se o tempo acabar e você não pagar mais.</center>
</fieldset>
<br />
<fieldset>
    <legend><b>Editar perfil</b></legend>
    <table width="100%">
        <thead>
            <tr>
                <div style="text-align: center;"><img src="<?php $dire = ($guild['img'] == "default_guild.png") ? "static/" . $guild['img'] : $guild['img'];
                                                            echo $dire ?>" width="120px" height="120px"
                        alt="<?php echo $guild['name'] ?>" border="1px"></div>
            </tr>
        </thead>
        <tbody>
            <tr>
                <form action="guild_admin.php" method="POST">
                    <td width="25%"><b>Enviar avatar:</b><br /></td>
                    <td>
                        <input type="text" name="guild_admin" value="<?= $guild['img'] ?>" size="45" />
                        <input type="submit" name="upload" value="Enviar" />
                    </td>
                    <!-- <td><input type="file" name="foto" size="30"><input style="margin-left: 5px;" type="submit" name="upload" value="Enviar"><img style="margin-left: 5px;" src="static/images/help.gif" title="header=[Atenção!!!] body=[<font size='1px'>Envie uma imagem para ser utilizada como imagem do clã. A imagem deve ter formato jpg, jpeg, png, bmp ou gif. O tamanho da imagem não deve ultrapassar 1 MB. A resolução máxima permitida é de 1400x1024.</font>]"></td> -->
                </form>
            </tr>
            <form method="POST" action="guild_admin.php">
                <tr>
                    <td width="25%"><b>Preço para entrar</b>:</td>
                    <td><input type="text" name="price" value="<?php
                                                                if (!$_POST['price']) {
                                                                    echo $guild['price'];
                                                                } else {
                                                                    echo $_POST['price'];
                                                                }

                                                                ?>" size="10" /><br /><?= $msg1; ?></td>
                </tr>
                <tr>
                    <td width="25%"><b>Mensagem</b>:</td>
                    <td><input type="text" name="motd" size="40" value="<?php
                                                                        if (!$_POST['motd']) {
                                                                            echo $guild['motd'];
                                                                        } else {
                                                                            echo $_POST['motd'];
                                                                        }

                                                                        ?>" /><br /><?= $msg3; ?></td>
                </tr>
                <tr>
                    <td width="25%"><b>Descrição</b>:</td>
                    <td>
                        <?php
                        $textoreferencia = $_POST['blurb'] ?: $guild['blurb'];
                        ?>
                        <script>
                            function contador(id_campo, id_alvo, qt_max) {
                                var texto_campo = document.getElementById(id_campo);

                                if (texto_campo.value.length >= qt_max) {
                                    texto_campo.value = texto_campo.value.substring(0, qt_max);
                                    document.getElementById(id_alvo).innerHTML = '<span style="color:#FF0000;font-weight:bold;">' + texto_campo.value.length + '</span>';

                                } else {
                                    document.getElementById(id_alvo).innerHTML = texto_campo.value.length;
                                }
                            }
                        </script>
                        <script>
                            edToolbar('blurb');
                        </script><textarea onkeyup="contador(this.id,'alvo',5000);" rows="12"
                            name="blurb" id="blurb" class="ed"><?php
                                                                $quebras = ['<br />', '<br>', '<br/>'];
                                                                echo str_replace($quebras, "", $textoreferencia);
                                                                ?></textarea>
                        <font size="1"><br />O seu texto contem <span id="alvo">5000</span> caracteres. (Máximo 5000)</font>
                        <?= $msg4; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="submit" value="Atualizar" id="link"
                            class="neg"></td>
                </tr>
            </form>
        </tbody>
    </table>
</fieldset>
<a href="guild_home.php">Voltar</a>.
<?php
include(__DIR__ . "/templates/private_footer.php");
?>