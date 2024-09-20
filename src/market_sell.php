<?php
include("lib.php");
define("PAGENAME", "Mercado");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");

if ($player->level < 15)
{
	include("templates/private_header.php");
	echo "<i>Você precisa ter nível 15 ou mais para vender items.</i><br/>\n";
	echo '<a href="market.php">Voltar</a>.';
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}

switch($_GET['act'])
{
	case "sell":
		{
			include("templates/private_header.php");

			$gsadasdiiii = $db->execute("select items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where items.id=? and items.player_id=? and items.item_id=blueprint_items.id", array($_GET['item'], $player->id));
			$goooosdsfds = $gsadasdiiii->fetchrow();

				if ($gsadasdiiii->recordcount() == 0){
			 	echo "Você não possui este item.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}

				if ($goooosdsfds['mark'] == t){
			 	echo "Este item já está á venda!<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}

				if ($goooosdsfds['status'] == 'equipped'){
			 	echo "O item que você deseja vender está em uso. Desequipe-o e tente novamente.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}

				if (($goooosdsfds['item_id'] == 111) or ($goooosdsfds['item_id'] == 116) or ($goooosdsfds['item_id'] == 163) or ($goooosdsfds['item_id'] == 168)){
			 	echo "Você não pode vender este item.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}
	
				if ($goooosdsfds['type'] == 'stone'){
			 	echo "Você não pode vender pedras no mercado.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}

				if ($goooosdsfds['item_bonus'] > 0){
				$bonus1 = " +" . $goooosdsfds['item_bonus'] . "";
				}
				if ($goooosdsfds['for'] > 0){
				$bonus2 = " <font color=\"gray\">+" . $goooosdsfds['for'] . "F</font>";
				}
				if ($goooosdsfds['vit'] > 0){
				$bonus3 = " <font color=\"green\">+" . $goooosdsfds['vit'] . "V</font>";
				}
				if ($goooosdsfds['agi'] > 0){
				$bonus4 = " <font color=\"blue\">+" . $goooosdsfds['agi'] . "A</font>";
				}
				if ($goooosdsfds['res'] > 0){
				$bonus5 = " <font color=\"red\">+" . $goooosdsfds['res'] . "R</font>";
				}

			?>
			Então você quer vender seu iten? ótimo! Me diga quando você quer por ele. Mas lembre-se, este mercado não funciona de graça, você tem que nos pagar 5% de comissão.<br /><br />
			<form method="POST" action="market_sell.php?act=confirm" >
			<table>
			<tr><td><b>Vender:</b></td><td><?php echo "" . $goooosdsfds['name'] . "" . $bonus1 . "" . $bonus2 . "" . $bonus3 . "" . $bonus4 . "" . $bonus5 . "";?></td></tr>
			<input type="hidden" name="act" value="confirm">
			<input type="hidden" name="item" value="<?php echo $_GET['item']; ?>">
			<tr><td><b>Preço:</b></td><td><input type="text" name="price" size="15"></td></tr>
			<?php
			if ($player->transpass != f){
				echo "<tr><td><b>Senha de<br/>transferência:</b></td><td><input type=\"password\" name=\"passcode\" size=\"20\"/></td></tr>";
			}
			?>
			</table>
			<input type="submit" value="Adicionar ao mercado">
			</form>
			<?php
			include("templates/private_footer.php");
			//end of sell action
			break;
		}
		case "confirm":
			{

			if ($player->transpass != f){
			if (!$_POST['passcode']){
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Erro</b></legend>\n";
        		echo "Preencha todos os campos.<br />";
        		echo "<a href=\"market.php\">Voltar</a>.";
			echo "</fieldset>";
        		include("templates/private_footer.php");
			break;
			}

			if (strtolower($_POST['passcode']) != strtolower($player->transpass)){
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Erro</b></legend>\n";
        		echo "Sua senha de transferência está incorreta.<br />";
        		echo "<a href=\"market.php\">Voltar</a>.";
			echo "</fieldset>";
        		include("templates/private_footer.php");
			break;
			}
			}

			if (!$_POST['item']){
				include("templates/private_header.php"); 
			 	echo "Um erro desconhecido ocorreu.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}

			$verificaall = $db->execute("select items.item_id, items.status, items.mark, items.item_bonus, items.for, items.vit, items.agi, items.res, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where items.id=? and items.player_id=? and items.item_id=blueprint_items.id", array($_POST['item'], $player->id));
				if ($verificaall->recordcount() == 0){
				include("templates/private_header.php"); 
			 	echo "Você não possui este item.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}

			$ver = $verificaall->fetchrow();

				if ($ver['mark'] == 't'){
				include("templates/private_header.php"); 
			 	echo "Este item já está no mercado.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}

				if ($ver['status'] == 'equipped'){
				include("templates/private_header.php"); 
			 	echo "O item que você deseja vender está em uso. Desequipe-o e tente novamente.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}



			$item=stripslashes($_POST['item']);

			if (!$_POST['price']){
				include("templates/private_header.php"); 
			 	echo "Você precisa preencher todos os campos.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}

			if (!is_numeric($_POST['price'])) 	
			{
				include("templates/private_header.php"); 
			 	echo "O valor que você inseriu não é válido.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}

			if ($_POST['price'] < 100) 	
			{
				include("templates/private_header.php"); 
			 	echo "O preço não pode ser menor que 100.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}


			if ($_POST['price'] > 50000000) 	
			{
				include("templates/private_header.php"); 
			 	echo "O preço não pode ser maior que 50 milhões.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}

			$price=stripslashes($_POST['price']);
			$price=ceil($price);

			$fee=ceil($price/100);
			$fee=ceil($fee * 5);
			if($price<=0){
				include("templates/private_header.php"); 
			 	echo "Desculpe, mas nós não permitimos que os usuários dêem itens. <a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}
				$gsassaaa = $db->execute("select blueprint_items.name, items.item_id from `blueprint_items`, `items` where items.id=? and blueprint_items.id=items.item_id", array($item));
				$gooooa = $gsassaaa->fetchrow();
				include("templates/private_header.php"); 
			?>
			Você tem certeza que quer vender seu/sua <b><?php echo $gooooa['name']; ?> por <?php echo $price; ?> de ouro</b>? Você precisará nos pagar <b><?php echo $fee; ?> de ouro</b>, que é nossa comissão.<br/><br/>
			<form method="post" action="market_sell.php?act=list">
			<input type="hidden" name="item" value="<?php echo $item; ?>">
			<input type="hidden" name="price" value="<?php echo $price; ?>">
			<input type="submit" name="list" value="Sim, tenho certeza!">
			</form>
			<?php
			}
			include("templates/private_footer.php");
			break;


		case "list":
			{

			if (!$_POST['item']){
				include("templates/private_header.php"); 
			 	echo "Um erro desconhecido ocorreu.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}

			$verificaall = $db->execute("select items.item_id, items.status, items.mark, items.item_bonus, items.for, items.vit, items.agi, items.res, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where items.id=? and items.player_id=? and items.item_id=blueprint_items.id", array($_POST['item'], $player->id));
				if ($verificaall->recordcount() == 0){
			include("templates/private_header.php"); 
			 	echo "Você não possui este item.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}

			$ver = $verificaall->fetchrow();

				if ($ver['mark'] == 't'){
				include("templates/private_header.php"); 
			 	echo "Este item já está no mercado.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}

				if ($ver['status'] == 'equipped'){
				include("templates/private_header.php"); 
			 	echo "O item que você deseja vender está em uso. Desequipe-o e tente novamente.<br/><a href=\"market.php\">Voltar</a>.";
			 	include("templates/private_footer.php");
				break; 
				}

			$item=stripslashes($_POST['item']);


			if (!$_POST['price']){
				include("templates/private_header.php"); 
			 	echo "Você precisa preencher todos os campos.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}

			if (!is_numeric($_POST['price'])) 	
			{
				include("templates/private_header.php"); 
			 	echo "O valor que você inseriu não é válido.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}

			if ($_POST['price'] < 100) 	
			{
				include("templates/private_header.php"); 
			 	echo "O preço não pode ser menor que 100.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}


			if ($_POST['price'] > 50000000) 	
			{
				include("templates/private_header.php"); 
			 	echo "O preço não pode ser maior que 50 milhões.<br/><a href=\"market.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break; 
			}

			$price=stripslashes($_POST['price']);
			$price=ceil($price);

			$fee=ceil($price/100);
			$fee=ceil($fee * 5);

			if($player->gold < $fee){
			include("templates/private_header.php");
			echo "Você não tem ouro para pagar nossa comissão.";	
			include("templates/private_footer.php");	
			}else {
				$insert['market_id'] = $item;
				$insert['ite_id'] = $ver['item_id'];
				$insert['price']= $price;
				$insert['seller'] = $player->username;
				$insert['expira'] = ceil(time() + 1209600);
				$insert['serv'] = $player->serv;
				$query2 = $db->autoexecute('market', $insert, 'INSERT');
			//remove fee from player
			$query02 = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold - $fee, $player->id));
			$query03 = $db->execute("update `items` set `mark`='t', `status`='unequipped' where `id`=?", array($item));

			include("templates/private_header.php");
			echo "Agora seu item está disponivel no mercado! <a href=\"market.php\">Voltar</a>.";	
			include("templates/private_footer.php");

			}
			break;
			}
 	default:
 
//Default Page
include("templates/private_header.php");
?>
<?php
//Pull info from blueprint items and then compare them to items list to get count.
$query = $db->execute("select * from blueprint_items order by blueprint_items.name asc");
if ($query->recordcount() == 0)
{
	echo "<br /><b>Você não tem itens para vender.</b>";
}
$abaioepa = $db->execute("select `id` from `items` where `player_id`=?", array($player->id));
if ($abaioepa->recordcount() == 0)
{
	echo "<br /><b>Você não tem itens para vender.</b>";
	include("templates/private_footer.php");
	exit;
}
else
{
	echo "<fieldset><legend><b>Quais itens você gostaria de vender?</b></legend>";
	echo "<table width=\"100%\" border=\"0\">";
	echo "<tr>";
	echo "<th><b>Item</b></td>";
	echo "<th><b>Ação</b></td>";
	echo "</tr>";
	$gettheitemuniqid = $db->execute("select items.id, items.item_bonus, items.for, items.vit, items.agi, items.res, blueprint_items.name from `items`, `blueprint_items` where `player_id`=? and mark!='t' and items.item_id=blueprint_items.id order by blueprint_items.name asc", array($player->id));

			while ($gettheitemuniqiditem = $gettheitemuniqid->fetchrow())
			{
				if ($gettheitemuniqiditem['item_bonus'] > 0){
				$bonus01 = " +" . $gettheitemuniqiditem['item_bonus'] . "";
				}else{
				$bonus01 = "";
				}
				if ($gettheitemuniqiditem['for'] > 0){
				$bonus02 = " <font color=\"gray\">+" . $gettheitemuniqiditem['for'] . "F</font>";
				}else{
				$bonus02 = "";
				}
				if ($gettheitemuniqiditem['vit'] > 0){
				$bonus03 = " <font color=\"green\">+" . $gettheitemuniqiditem['vit'] . "V</font>";
				}else{
				$bonus03 = "";
				}
				if ($gettheitemuniqiditem['agi'] > 0){
				$bonus04 = " <font color=\"blue\">+" . $gettheitemuniqiditem['agi'] . "A</font>";
				}else{
				$bonus04 = "";
				}
				if ($gettheitemuniqiditem['res'] > 0){
				$bonus05 = " <font color=\"red\">+" . $gettheitemuniqiditem['res'] . "R</font>";
				}else{
				$bonus05 = "";
				}
				echo "<tr><td>" . $gettheitemuniqiditem['name'] . "" . $bonus01 . "" . $bonus02 . "" . $bonus03 . "" . $bonus04 . "" . $bonus05 . "</td><td><a href=\"market_sell.php?act=sell&item=" . $gettheitemuniqiditem['id'] . "\">Vender</a></td></tr>";
			}	
			echo "</table></fieldset>";
}
?>
<?php 
include("templates/private_footer.php");
}
?>