<?php
include("lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

$error = 0;

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}


include("templates/private_header.php");

$price = (500 * $guild['members']);

//Guild Leader Admin check
if (($player->username != $guild['leader']) and ($player->username != $guild['vice'])) {
    echo "<p />Você não pode acessar esta página.<p />";
    echo "<a href=\"home.php\">Principal</a><p />";
} else {

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

if ($_POST['submit']) {

$arredonda = floor($_POST['days']);
$maximodedias = ($guild['pagopor'] + ($arredonda * 86400)) - time();
$price2 = ceil($price * $_POST['days']);

	if (!$_POST['days']){
    	$errmsg .= "Você precisa preencher todos os campos.";
    	$error = 1;
   	}
	else if(!is_numeric($_POST['days'])){
    	$errmsg .= "Este número de dias não é válido.";
    	$error = 1;
   	}
	else if($arredonda < 1){
    	$errmsg .= "Este número de dias não é válido.";
    	$error = 1;
   	}
	else if($price2 > $guild['gold']){
    	$errmsg .= "Seu clã não possui ouro suficiente para pagar por " . $arredonda . " dia(s).";
    	$error = 1;
   	}
	elseif($maximodedias > 5183999){
    	$errmsg .= "Você não pode deixar sue clã pago por mais de 60 dias.";
    	$error = 1;
   	}

		if ($error == 0){
		$tempoadicional = $guild['pagopor'] + ($arredonda * 86400);
		$query = $db->execute("update `guilds` set `gold`=?, `pagopor`=? where `id`=?", array($guild['gold'] - $price2, $tempoadicional, $guild['id']));
		$msg .= "Seu clã acaba de ser pago por mais " . $arredonda . " dia(s).";
		}
}

?>
<?=$msg?><font color=red><?=$errmsg?></font>
<fieldset>
<legend><b><?=$guild['name']?> :: Pagar pelo clã</b></legend>
<form method="POST" action="guild_admin_pay.php">
<b>Pagar por mais:</b> <input type="text" name="days" size="3" maxlength="3"/> dias.<p />
<input type="submit" name="submit" value="Pagar"> Cada dia custa <b><?=$price?> de ouro</b>.
</form>
</fieldset>
<b>Este clã está pago por:</b> <?=$valortempo2;?> <?=$auxiliar2;?>.<br>
Ele será deletado se o tempo acabar e você não pagar mais.

<?php
}
include("templates/private_footer.php");
?>