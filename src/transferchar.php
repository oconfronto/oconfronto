<?php

	include("lib.php");
	define("PAGENAME", "Transferir Personagem");
	$acc = check_acc($secret_key, $db);

if ($_GET['cancel'])
{
$cancel0 = $db->execute("select * from `pending` where `pending_id`=4 and `pending_other`=?", array($acc->id));
	if ($cancel0->recordcount() > 0){
	$dileti = $db->execute("delete from `pending` where `pending_id`=4 and `pending_other`=?", array($acc->id));
	include("templates/acc-header.php");
    echo "<span id=\"aviso-a\"></span>";
	echo "<br/><p><center>Você cancelou a solicitação de transferência de personagem. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
	include("templates/acc-footer.php");
	exit;
	}else{
	include("templates/acc-header.php");
    echo "<span id=\"aviso-a\"></span>";
	echo "<br/><p><center>Nenhuma solicitação de transferência encontrada. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
	include("templates/acc-footer.php");
	exit;
	}
}

		$error = 0;

$querynumplayers = $db->execute("select `id` from `players` where `acc_id`=?", array($acc->id));
if ($querynumplayers->recordcount() > 19)
{
include("templates/acc-header.php");
echo "<span id=\"aviso-a\"></span>";
echo "<br/><p><center>Você já atingiu o número máximo de personagens por conta, vinte.<br/>Você não pode mais adicionar personagens nesta conta. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
include("templates/acc-footer.php");
exit;
}

if (!$_GET['id'])
{
	include("templates/acc-header.php");
	echo "<span id=\"aviso-a\"><font size=\"1px\">Digite o personagem que você deseja transferir para sua conta.</font></span>";
	echo "<p><form method=\"get\" action=\"transferchar.php\"><table width=\"90%\" align=\"center\"><tr><td width=\"37%\"><b>Personagem:</b></td><td width=\"62%\"><input type=\"text\" name=\"id\" class=\"inp\" size=\"20\"/></td></tr></table><br/><center><button type=\"submit\" name=\"submit\" value=\"Enviar\" class=\"enviar\"></button></center></form></p>";
	include("templates/acc-footer.php");
	exit;
}else{
	$query0 = $db->execute("select * from `players` where `username`=?", array($_GET['id']));
       	$query1 = $db->execute("select * from `pending` where `pending_id`=4 and `pending_status`=?", array($_GET['id']));
       	$query2 = $db->execute("select * from `pending` where `pending_id`=4 and `pending_other`=?", array($char['acc_id']));
       	$query3 = $db->execute("select * from `pending` where `pending_id`=4 and `player_id`=?", array($acc->id));

	if ($query0->recordcount() != 1){
	include("templates/acc-header.php");
    echo "<span id=\"aviso-a\"></span>";
	echo "<br/><p><center>Personagem não encontrado. <a href=\"transferchar.php\">Voltar</a>.</center></p></p><br/>";
	include("templates/acc-footer.php");
	exit;
	}else{
	$char = $query0->fetchrow();
	}

	if ($char['acc_id'] == $acc->id){
	include("templates/acc-header.php");
    echo "<span id=\"aviso-a\"></span>";
	echo "<br/><p><center>Este personagem já pertence a sua conta. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
	include("templates/acc-footer.php");
	exit;
	}

	if ($query1->recordcount() > 0){
	include("templates/acc-header.php");
    echo "<span id=\"aviso-a\"></span>";
	echo "<br/><p><center>Já existe uma solicitação de transferência pendente com este personagem. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
	include("templates/acc-footer.php");
	exit;
	}

	if ($query2->recordcount() > 0){
	include("templates/acc-header.php");
    echo "<span id=\"aviso-a\"></span>";
	echo "<br/><p><center>Já existe uma solicitação de transferência pendente com a conta deste personagem. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
	include("templates/acc-footer.php");
	exit;
	}

	if ($query3->recordcount() > 0){
	include("templates/acc-header.php");
    echo "<span id=\"aviso-a\"></span>";
	echo "<br/><p><center>Já existe uma solicitação de transferência pendente com sua conta. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
	include("templates/acc-footer.php");
	exit;
	}

	if ($_POST['submit']){
		$cconta = $db->GetOne("select `conta` from `accounts` where `id`=?", array($char['acc_id']));
		$ccontappassss = $db->GetOne("select `password` from `accounts` where `id`=?", array($char['acc_id']));

        $lock = 0;
        $tentativas = $db->GetOne("select `tries` from `login_tries` where `ip`=?", array($ip));
		if ((!$_POST['conta']) or (!$_POST['senhadaconta'])){
            $errmsg .= "Preencha todos os campos";
            $error = 1;
		}else if ((!$_POST['transferpass']) and ($char['transpass'] != f)){
            $errmsg .= "Preencha todos os campos";
            $error = 1;
		}else if ($_POST['conta'] != $cconta){
            $errmsg .= "A conta não confere com o personagem.";
            $error = 1;
            $lock = 1;
		}else if (encodePassword($_POST['senhadaconta']) != $ccontappassss){
            $errmsg .= "A senha não confere com o personagem.";
            $error = 1;
            $lock = 1;
		}else if (($_POST['transferpass'] != $char['transpass']) and ($char['transpass'] != f)){
            $errmsg .= "A senha de tranferência não confere com o personagem.";
            $error = 1;
            $lock = 1;
		}else if ($tentativas > 9) {
            $errmsg .= "Você errou sua senha 10 vezes seguidas.<br/>Aguarde 30 minutos para poder tentar novamente.";
            $error = 1;
            $lock = 1;
        }
        
        if ($lock == 1) {
            $bloqueiaip = $db->execute("select `tries` from `login_tries` where `ip`=?", array($ip));
            if ($bloqueiaip->recordcount() == 0) {
                $insert['ip'] = $ip;
                $insert['tries'] = 1;
                $insert['time'] = time();
                $db->autoexecute('login_tries', $insert, 'INSERT');
            }elseif ($bloqueiaip->recordcount() > 0) {
                $query = $db->execute("update `login_tries` set `tries`=`tries`+1 where `ip`=?", array($ip));
            }
            
        }

		if ($error == 0){
            $insert['player_id'] = $acc->id;
            $insert['pending_id'] = 4;   	  
            $insert['pending_status'] = $char['username'];
            $insert['pending_time'] = (time() + 1296000);
            $insert['pending_other'] = $char['acc_id'];
            $query = $db->autoexecute('pending', $insert, 'INSERT');

            include("templates/acc-header.php");
            echo "<span id=\"aviso-a\"></span>";
            echo "<br/><p><center>Você solicitou a tranferência de " . $char['username'] . " para sua conta.<br/>Você terá que aguardar 14 dias para ver " . $char['username'] . " em sua conta. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
            include("templates/acc-footer.php");
            exit;
		}

	}

	include("templates/acc-header.php");
	echo "<span id=\"aviso-a\">";
    if ($errmsg != "")
    {
        echo $errmsg;
    } else {
        echo "<font size=\"1px\">Digite as informações de " . $char['username'] . " abaixo.</font>";
    }
    echo "</span>";
?>

<p>
<form method="POST" action="transferchar.php?id=<?=$_GET['id'];?>">
<table width="90%" align="center">
<tr><td width="27%"><b>Conta</b>:</td><td><input type="password" name="conta" class="inp" value="<?=$_POST['conta'];?>" size="20"/></td></tr>
<tr><td width="27%"><b>Senha</b>:</td><td><input type="password" name="senhadaconta" class="inp" value="<?=$_POST['senhadaconta'];?>" size="20"/></td></tr>
<?php
if ($char['transpass'] != f){
echo "<tr><td width=\"27%\"><b>Senha de Tranferência</b>:</td><td><input type=\"password\" name=\"transferpass\" class=\"inp\" value=\"" . $_POST['transferpass'] . "\" size=\"20\"/></td></tr>";
}
?>
</table>
<br/><center><button type="submit" name="submit" value="Enviar" class="enviar"></button></center>
</form>
</p>

<?php
include("templates/acc-footer.php");
exit;
}
?>