<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Alterar Senha");
$acc = check_acc($secret_key, $db);
$player = check_user($secret_key, $db);

$sucess2 = 0;
$error2 = 0;

include(__DIR__ . "/templates/private_header.php");


if ($player->transpass != f){
if ($_POST['changetrans']) {
    //Check trans
    if (!$_POST['trans']) {
        $errmsg2 .= "Você precisa preencher todos os campos!";
        $error2 = 1;
    } elseif (!$_POST['trans2']) {
        $errmsg2 .= "Você precisa preencher todos os campos!";
        $error2 = 1;
    } elseif (!$_POST['pass2']) {
        $errmsg2 .= "Você precisa preencher todos os campos!";
        $error2 = 1;
    } elseif (!$_POST['oldtrans']) {
        $errmsg2 .= "Você precisa preencher todos os campos!";
        $error2 = 1;
    } elseif ($player->transpass != $_POST['oldtrans']) {
        $errmsg2 .= "Sua senha de transferência atual está incorreta!";
        $error2 = 1;
    } elseif ($acc->password == encodePassword($_POST['trans'])) {
        $errmsg2 .= "Sua senha de transferência não pode ser igual a senha de sua conta.";
        $error2 = 1;
    } elseif ($acc->password != encodePassword($_POST['pass2'])) {
        $errmsg2 .= "A senha de sua conta está incorreta!";
        $error2 = 1;
    } elseif ($_POST['trans'] != $_POST['trans2']) {
        $errmsg2 .= "Você não digitou as duas senhas corretamente!";
        $error2 = 1;
    } elseif (strlen($_POST['trans']) < 4) {
        $errmsg2 .= "Sua senha de transferência não pode ter menos de 4 caracteres.";
        $error2 = 1;
    } elseif (strlen($_POST['trans']) > 30) {
        $errmsg2 .= "Sua senha de transferência não pode ter mais de 30 caracteres.";
        $error2 = 1;
    }
    
    if ($error2 == 0) {
		$insert['player_id'] = $acc->id;
		$insert['msg'] = "Você alterou a senha de transferência de " . $player->username . ".";
		$insert['time'] = time();
		$query = $db->autoexecute('account_log', $insert, 'INSERT');

        $query = $db->execute("update `players` set `transpass`=? where `id`=?", array($_POST['trans'], $player->id));
        echo "<fieldset><legend><b>Sucesso</b></legend>Você alterou sua senha de transferência.<br/><a href=\"home.php\">Voltar</a>.</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;

    }
}

?>
<fieldset>
<p /><legend><b>Alterar senha de transferência</b></legend><p />
<table width="100%">
<form method="POST" action="account.php">
<tr><td width="25%"><b>Senha da conta</b>:</td><td><input type="password" name="pass2" size="20"/></td></tr>
<tr><td width="25%"><b>Senha de transferência</b>:</td><td><input type="password" name="oldtrans" size="20"/></td></tr>
<tr><td width="25%"><b>Nova senha</b>:</td><td><input type="password" name="trans" size="20"/></td></tr>
<tr><td width="25%"><b>Digite novamente</b>:</td><td><input type="password" name="trans2" size="20"/></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="changetrans" value="Alterar"></td></tr>
</table>
</form>
<p /><font color=red><?=$errmsg2?></font><p />
</fieldset>
<?php
}else{
echo "<fieldset>";
echo "<legend><b>Erro</b></legend>";
echo "Você não possui uma senha de transferência. <a href=\"home.php\">Voltar</a>.";
echo "</fieldset>";
}

include(__DIR__ . "/templates/private_footer.php");
?>
