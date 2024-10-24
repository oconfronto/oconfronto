<?php
declare(strict_types=1);

ob_start(); // Inicia o buffer de saída
include(__DIR__ . "/lib.php");
define("PAGENAME", "Deleta Personagem");
$acc = check_acc($db);
include(__DIR__ . "/templates/acc-header.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if (isset($_POST['ddl_char']) && $_POST['ddl_char'] != 'none') {
        $nomedeusuari0 = $_POST['ddl_char'];
        $conf_delete = trim($_POST['conf_delete']);

        // Verifica se o campo de confirmação não está vazio
        if ($conf_delete === '' || $conf_delete === '0') {
            echo "<span id=\"aviso-a\">O campo de confirmação não pode estar vazio.</span>";
        } elseif ($conf_delete !== $nomedeusuari0) {
            echo "<span id=\"aviso-a\">A confirmação não corresponde ao personagem selecionado.</span>";
        } else {
            // Check if username has already been used
            $query = $db->execute("SELECT `id` FROM `players` WHERE `username`=?", [$nomedeusuari0]);
            if ($query->recordcount() > 0) {
                $row = $query->fetchrow(); // Obtém a linha como um array
                $playerID = $row['id']; // Acessa o ID
                $db->execute("DELETE FROM `players` WHERE `id`=?", [$playerID]);
                // $db->execute("DELETE FROM `friends` WHERE `fname`=?", array($nomedeusuari0));
            }

            // Redireciona após excluir o personagem
            header("Location: characters.php");
            exit;
        }
    } else {
        echo '<span id="aviso-a">Nenhum personagem selecionado.</span>';
    }
}

ob_end_flush(); // Envia o conteúdo do buffer e limpa
?>

<br />
<?php include(__DIR__ . "/box.php"); ?>
<form method="POST" action="deletechar.php">
    <table style="border-spacing: 10px 15px;width:95%;text-align:center">
        <tr>
            <td width="20%"><b>Character</b>:</td>
            <td width="72%">
                <select id="ddl_char" name="ddl_char" onchange="deleteMsg(this)" class="inp">
                    <?php
                    $querynumplayers = $db->execute("SELECT `username` FROM `players` WHERE `acc_id`=?", [$acc->id]);
                    if ($querynumplayers) {
                        echo '<option value="none" selected="selected">Selecione</option>';
                        foreach ($querynumplayers as $querynumplayer) {
                            echo  sprintf('<option value="%s">%s</option>', $querynumplayer['username'], $querynumplayer['username']);
                        }
                    } else {
                        echo '<option value="none" selected="selected">Selecione</option>';
                    }
                    ?>
                </select>
                <?php
                if ($erro2 == 1) {
                        echo '<span id="erro"></span>';
                    } elseif ($certo2 == 1) {
                        echo '<span id="certo"></span>';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 14px;font-weight: bold;text-align: center;padding: 15px;">
                <div id="txtDelete">
            </td>
        </tr>
        <tr id="tr_confirm" style="display:none">
            <td width="20%"><b>Confirmar</b>:</td>
            <td width="77%">
                <input autocomplete="off" type="text" id="conf_delete" name="conf_delete" class="inp" size="20"><span id="msgbox10">
            </td>
        </tr>
    </table>
    <br />
    <center>
        <button type="submit" name="register" value="Excluir Personagem" class="enviar" onclick="return confirm('Tem certeza que deseja excluir o personagem?');"></button>
    </center>
</form>
<?php include(__DIR__ . "/templates/acc-footer.php"); ?>
