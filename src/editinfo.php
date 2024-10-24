<?php
	declare(strict_types=1);

include(__DIR__ . "/lib.php");
	define("PAGENAME", "Informações Pessoais");
	$acc = check_acc($secret_key, $db);

	include(__DIR__ . "/templates/acc-header.php");

$error = 0;
$checkshowmail = $db->execute("select * from `other` where `value`=? and `player_id`=?", array(showmail, $acc->id));

if ($_POST['submit']) {
    if ((!$_POST['rlname'] | !$_POST['showmail'] | !$_POST['remember'] | !$_POST['sex']) !== 0) {
        $errmsg .= "Por favor preencha todos os campos!";
        $error = 1;
    } elseif (strlen($_POST['rlname']) < 3) {
        $errmsg .= "Seu nome deve ter mais que três caracteres!";
        $error = 1;
    } elseif ($_POST['showmail'] != 1 && $_POST['showmail'] != 2) {
        $errmsg .= "Um erro desconhecido ocorreu.";
        $error = 1;
    } elseif ($_POST['remember'] != 1 && $_POST['remember'] != 2) {
        $errmsg .= "Um erro desconhecido ocorreu.";
        $error = 1;
    } elseif ($_POST['sex'] != 1 && $_POST['sex'] != 2 && $_POST['sex'] != 3) {
        $errmsg .= "Um erro desconhecido ocorreu.";
        $error = 1;
    }
    
    if ($error == 0) {

	if ($_POST['sex'] == 2){
	$sexx = "m";
	}elseif ($_POST['sex'] == 3){
	$sexx = "f";
	}else{
	$sexx = "n";
	}

	$rememberr = $_POST['remember'] == 2 ? "t" : "f";

	if ($_POST['showmail'] == 2){
		if ($checkshowmail->recordcount() < 1) {
		$insert['player_id'] = $acc->id;
		$insert['value'] = showmail;
		$db->autoexecute('other', $insert, 'INSERT');
		}
	}else{
	$deleteshowmail = $db->execute("delete from `other` where `value`=? and `player_id`=?", array(showmail, $acc->id));
	}

        $query = $db->execute("update `accounts` set `name`=?, `sex`=?, `remember`=? where `id`=?", array($_POST['rlname'], $sexx, $rememberr, $acc->id));
        echo '<span id="aviso-a"></span>';
        echo "<br/><p><center><b>Informações pessoais alteradas com sucesso! <a href=\"characters.php\">Voltar</a>.</b></center></p><br/>";
        include(__DIR__ . "/templates/acc-footer.php");
        exit;
    }
}

$acc = check_acc($secret_key, $db);
$checkshowmail = $db->execute("select * from `other` where `value`=? and `player_id`=?", array(showmail, $acc->id));
    
    echo '<span id="aviso-a">';
    if ($errmsg != "") {
        echo $errmsg;
    }
    
    echo "</span>";
?>

<br/><p>
<table width="90%" align="center">
<form method="POST" action="editinfo.php">
<tr><td width="40%"><b>Nome real</b>:</td><td><input type="text" name="rlname" value="<?=$acc->name?>" class="inp" size="20"/></td></tr>
<?php
if ($acc->sex == "m"){
echo '<tr><td width="40%"><b>Sexo</b>:</td><td><select name="sex" class="inp"><option value="1">Selecione</option><option value="2" selected="selected">Masculino</option><option value="3">Feminino</option></select></td></tr>';
}elseif ($acc->sex == "f"){
echo '<tr><td width="40%"><b>Sexo</b>:</td><td><select name="sex" class="inp"><option value="1">Selecione</option><option value="2">Masculino</option><option value="3" selected="selected">Feminino</option></select></td></tr>';
}else{
echo '<tr><td width="40%"><b>Sexo</b>:</td><td><select name="sex" class="inp"><option value="1" selected="selected">Selecione</option><option value="2">Masculino</option><option value="3">Feminino</option></select></td></tr>';
}

if ($checkshowmail->recordcount() < 1){
echo "<tr><td width=\"40%\"><b>Mostrar email</b>:</td><td><select name=\"showmail\" class=\"inp\"><option value=\"1\" selected=\"selected\">Não</option><option value=\"2\">Sim</option></select></td></tr>";
}else{
echo "<tr><td width=\"40%\"><b>Mostrar email</b>:</td><td><select name=\"showmail\" class=\"inp\"><option value=\"1\">Não</option><option value=\"2\" selected=\"selected\">Sim</option></select></td></tr>";
}

if ($acc->remember != t){
echo "<tr><td width=\"40%\"><b>Lembrar Senha</b>:</td><td><select name=\"remember\" class=\"inp\"><option value=\"1\" selected=\"selected\">Não</option><option value=\"2\">Sim</option></select></td></tr>";
}else{
echo "<tr><td width=\"40%\"><b>Lembrar Senha</b>:</td><td><select name=\"remember\" class=\"inp\"><option value=\"1\">Não</option><option value=\"2\" selected=\"selected\">Sim</option></select></td></tr>";
}

?>
</table>
<center><button type="submit" name="submit" value="Atualizar" class="atualizar"></button></center>
</form>
</p>

<?php
	include(__DIR__ . "/templates/acc-footer.php");
?>
