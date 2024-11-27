<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Alterar Email");
$acc = check_acc($db);

include(__DIR__ . "/templates/acc-header.php");

if (($_GET['act'] ?? null) == "cancel") {
    $query = $db->execute("delete from `pending` where `pending_id`=1 and `player_id`=?", [$acc->id]);
    echo '<span id="aviso-a"></span>';
    echo "<br/><p><center>A solicitação para mudança de e-mail foi removida. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
    include(__DIR__ . "/templates/acc-footer.php");
    exit;
}

if ($_POST['submit'] ?? null) {
    if (!($_POST['senhadaconta'] ?? null)) {
        $errmsg .= "Você precisa preencher todos os campos.";
        $error = 1;
    } elseif (!($_POST['emaill'] ?? null)) {
        $errmsg .= "Você precisa preencher todos os campos.";
        $error = 1;
    } elseif (!($_POST['emaill2'] ?? null)) {
        $errmsg .= "Você precisa preencher todos os campos.";
        $error = 1;
    } elseif (encodePassword($_POST['senhadaconta']) != $acc->password) {
        $errmsg .= "Sua senha antiga está incorreta.";
        $error = 1;
    } elseif (($_POST['emaill'] ?? null) != ($_POST['emaill2'] ?? null)) {
        $errmsg .= "Você não digitou os dois e-mails corretamente!";
        $error = 1;
    } elseif (strlen((string) ($_POST['emaill'] ?? null)) < 3) {
        $errmsg .= "O seu endereço de e-mail deve conter mais de 5 caracteres.";
        $error = 1;
    } elseif (strlen((string) ($_POST['emaill'] ?? null)) > 200) {
        $errmsg .= "O seu endereço de e-mail deve conter menos de 200 caracteres.";
        $error = 1;
    } elseif (preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", (string) ($_POST['emaill'] ?? null)) === 0 || preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", (string) ($_POST['emaill'] ?? null)) === false) {
        $errmsg .= "O formato do seu e-mail é inválido!";
        $error = 1;
    } else {
        $query = $db->execute("select `id` from `accounts` where `email`=?", [$_POST['emaill'] ?? null]);
        $query2 = $db->execute("select * from `pending` where `pending_id`=1 and `player_id`=?", [$acc->id]);
        $query3 = $db->execute("select * from `pending` where `pending_id`=1 and `pending_status`=?", [$_POST['emaill'] ?? null]);
        if ($query->recordcount() > 0) {
            $errmsg .= "Este e-mail já está em uso.";
            $error = 1;
        } elseif ($query2->recordcount() > 0) {
            $errmsg .= "Você já enviou uma solicitação de mudança de e-mail.";
            $error = 1;
        } elseif ($query3->recordcount() > 0) {
            $errmsg .= "Este e-mail já está em uso.";
            $error = 1;
        }
    }

    if ($error == 0) {
        $insert['player_id'] = $acc->id;
        $insert['pending_id'] = 1;
        $insert['pending_status'] = $_POST['emaill'];
        $insert['pending_time'] = (time() + 1296000);
        $query = $db->autoexecute('pending', $insert, 'INSERT');

        echo '<span id="aviso-a"></span>';
        echo "<br/><p><center>Seu e-mail será alterado para: " . $_POST['emaill'] . ".<br/>Aguarde 14 dias para que a mudança seja efetuada. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
        include(__DIR__ . "/templates/acc-footer.php");
        exit;
    }
}

echo '<span id="aviso-a">';
if ($errmsg != "") {
    echo $errmsg;
}

echo "</span>";

echo '<br/><center><font size="1px"><b>Email Atual:</b> ' . $acc->email . ".</font></center>";
?>

<p>
<form method="POST" action="changemail.php">
    <table width="90%" align="center">
        <tr>
            <td width="38%"><b>Senha da conta</b>:</td>
            <td width="62%"><input type="password" name="senhadaconta" value="<?= $_POST['senhadaconta'] ?? null; ?>" class="inp" size="20" /></td>
        </tr>
        <tr>
            <td width="38%"><b>Novo email</b>:</td>
            <td width="62%"><input type="text" name="emaill" value="<?= $_POST['emaill'] ?? null; ?>" class="inp" size="20" /></td>
        </tr>
        <tr>
            <td width="38%"><b>Repita o email</b>:</td>
            <td width="62%"><input type="text" name="emaill2" value="<?= $_POST['emaill2'] ?? null; ?>" class="inp" size="20" /></td>
        </tr>
    </table>
    <br />
    <center><button type="submit" name="submit" value="Atualizar" class="atualizar"></button></center>
</form>
</p>

<?php include(__DIR__ . "/templates/acc-footer.php");
?>