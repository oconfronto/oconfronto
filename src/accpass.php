<?php
	declare(strict_types=1);

include(__DIR__ . "/lib.php");
	define("PAGENAME", "Alterar Senha");
	include(__DIR__ . "/templates/acc-header.php");

	$acc = check_acc($secret_key, $db);

$sucess1 = 0;
$sucess2 = 0;

if ($_POST['changepassword']) {
    //Check password
    if (!$_POST['password']) {
        $errmsg .= "Você precisa preencher todos os campos!";
        $error = 1;
    } elseif (!$_POST['password2']) {
        $errmsg .= "Você precisa preencher todos os campos!";
        $error = 1;
    } elseif (!$_POST['oldpassword']) {
        $errmsg .= "Você precisa preencher todos os campos!";
        $error = 1;
    } elseif ($acc->password != encodePassword($_POST['oldpassword'])) {
        $errmsg .= "Sua senha atual está incorreta!";
        $error = 1;
    } elseif ($_POST['password'] != $_POST['password2']) {
        $errmsg .= "Você não digitou as duas senhas corretamente!";
        $error = 1;
    } elseif (strlen($_POST['password']) < 4) {
        $errmsg .= "Sua senha deve ter mais que 3 caracteres.";
        $error = 1;
    }
    
    if ($error == 0) {
		$insert['player_id'] = $acc->id;
		$insert['msg'] = "Você alterou a senha de sua conta.";
		$insert['time'] = time();
		$query = $db->autoexecute('account_log', $insert, 'INSERT');

        $query = $db->execute("update `accounts` set `password`=? where `id`=?", array(encodePassword($_POST['password']), $acc->id));
        $msg .= "Senha alterada com sucesso.";
	$sucess1 = 1;
    }
}



echo '<span id="aviso-a">';
if ($errmsg != "") {
    echo $errmsg;
}

echo "</span>";

if ($sucess1 == 0){
?>
<br/><p>
<table width="90%" align="center">
<form method="POST" action="accpass.php">
<tr><td width="38%"><b>Senha atual</b>:</td><td width="62%"><input type="password" name="oldpassword" class="inp" size="20"/></td></tr>
<tr><td width="38%"><b>Nova senha</b>:</td><td width="62%"><input type="password" name="password" class="inp" size="20"/></td></tr>
<tr><td width="38%"><b>Digite novamente</b>:</td><td width="62%"><input type="password" name="password2" class="inp" size="20"/></td></tr>
</table>
<br/><center><button type="submit" name="changepassword" value="Atualizar" class="atualizar"></button></center>
</form>
<p />
<?php
}else{
echo "<br/><p><center><b>" . $msg . ' <a href="characters.php">Voltar</a>.</b></center></p><br/>';
}

	include(__DIR__ . "/templates/acc-footer.php");

?>
