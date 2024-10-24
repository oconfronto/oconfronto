<?php
include("lib.php");
define("PAGENAME", "Loja VIP");
$acc = check_acc($secret_key, $db);
$player = check_user($secret_key, $db);

if (true)
{
    include("templates/private_header.php");
    echo "<i><center>A loja Vip ainda está fechada.<br/>Tente novamente amanha.</center></i>\n";
    include("templates/private_footer.php");
    exit;
}
switch($_GET['act'])
{
    case "credits":
        if (!$_GET['id']) {
            include("templates/private_header.php");
            echo "Escolha um valor válido de créditos para comprar. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
        }
        
        if ($_GET['id'] == 1) {
            $numero = 15;
            $cost = 7;
            $code = '<form target="pagseguro" action="https://pagseguro.uol.com.br/checkout/v2/payment.html" method="post"><input type="hidden" name="code" value="875CE2952424CF9664C26F8A2B215DCB" />';
        } elseif ($_GET['id'] == 2) {
            $numero = 25;
            $cost = 10.25;
            $code = '<form target="pagseguro" action="https://pagseguro.uol.com.br/checkout/v2/cart.html?action=add" method="post"><input type="hidden" name="itemCode" value="B9B76AAE4141E40884635FB35A019D9D" />';
        } elseif ($_GET['id'] == 3) {
            $numero = 50;
            $cost = 18.50;
            $code = '<form target="pagseguro" action="https://pagseguro.uol.com.br/checkout/v2/cart.html?action=add" method="post"><input type="hidden" name="itemCode" value="CB7A445AF0F079C77414AF8417A6896E" />';
        } elseif ($_GET['id'] == 4) {
            $numero = 75;
            $cost = 24;
            $code = '<form target="pagseguro" action="https://pagseguro.uol.com.br/checkout/v2/cart.html?action=add" method="post"><input type="hidden" name="itemCode" value="5BFAC4FDC2C2C223343EEFB33516246A" />';
        } elseif ($_GET['id'] == 5) {
            $numero = 100;
            $cost = 30;
            $code = '<form target="pagseguro" action="https://pagseguro.uol.com.br/checkout/v2/cart.html?action=add" method="post"><input type="hidden" name="itemCode" value="02082C193535D16DD4C76FBC72902165" />';
        } elseif ($_GET['id'] == 10) {
            $numero = 1;
            $cost = 0.05;
            $code = '<form target="pagseguro" action="https://pagseguro.uol.com.br/checkout/v2/cart.html?action=add" method="post"><input type="hidden" name="itemCode" value="79B4DE180E0E8C8AA4703F943695B729" />';
        } else {
            include("templates/private_header.php");
            echo "Escolha um valor válido de créditos para comprar. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
        }
        
        include("templates/private_header.php");
            echo "<br/><center>Você está prestes a realizar uma doação de <b>R$ " . $cost . "</b>, obtendo uma recompensa de <b>" . $numero . " créditos</b>.<br/>Ao efetuar o pagamento, utilize o email de sua conta na página do Pagseguro, e depois disso, por favor envie-nos o comprovante no fim da transação.</center>";
        echo "<p><center>";
        echo '<!-- INICIO FORMULARIO BOTAO PAGSEGURO -->';
        echo $code;
        echo '<input type="image" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/pagamentos/205x30-pagar-laranja.gif" name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!" />';
        echo '</form>';
        echo '<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->';
        echo "</center></p>";
        include("templates/private_footer.php");
    break;
        
    case "hide":
        if ($player->vip < time())
        {
            include("templates/private_header.php");
            echo "Você não é VIP. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
        }
            
        $checkshowvip = $db->execute("select * from `other` where `value`=? and `player_id`=?", array(hidevip, $player->acc_id));
        if ($checkshowvip->recordcount() < 1) {
            $insert['player_id'] = $player->acc_id;
            $insert['value'] = hidevip;
            $db->autoexecute('other', $insert, 'INSERT');
            
            include("templates/private_header.php");
            echo "Sua VIP agora está oculta. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
        } else {
            $db->execute("delete from `other` where `value`=? and `player_id`=?", array(hidevip, $player->acc_id));
            include("templates/private_header.php");
            echo "Sua VIP não está mais oculta. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
        }
    break;
        
	case "vip":
        if (($_GET['days'] != 7) and ($_GET['days'] != 30)) {
            include("templates/private_header.php");
            echo "Tempo de duração inválido. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
        }
        
        if ($_GET['days'] == 7) {
            $cost = 7;
        } else {
            $cost = 22;
        }
        
        if ($acc->creditos < $cost)
        {
            include("templates/private_header.php");
            echo "Você não possui créditos suficientes. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
        } else {
            if ($_GET['confirm']) {
                $db->execute("update `players` set `hp`=?, `maxhp`=? where `id`=?", array(maxHp($db, $player->id, ($player->level - 1), 3, $player->vip), maxHp($db, $player->id, ($player->level - 1), 3, $player->vip), $player->id));
                $db->execute("update `accounts` set `creditos`=`creditos`-? where `id`=?", array($cost, $acc->id));
                
                $db->execute("update `players` set `vip`=? where `acc_id`=?", array(time() + (86400 * $_GET['days']), $player->acc_id));
                
                include("templates/private_header.php");
                echo "Parabéns, sua VIP agora está ativa. <a href=\"home.php\">Voltar.</a>\n";
                include("templates/private_footer.php");
                break;
            } else {
                include("templates/private_header.php");
                echo "<table border=\"0px\" width=\"100%\"><tr>";
                echo "<th width=\"120px\"><center><a href=\"vip.php\" id=\"link\" style='color:#fff;text-align:center;' class=\"normal\"><b>Voltar</b></a></center></th>";
                echo "<td><center><p>Você está prestes a ativar os benefícios VIP em sua conta por <b>" . $_GET['days'] . " dias</b>,<br/>totalizando um valor de <b>" . $cost . " créditos</b>.</p></center></td>";
                echo "<th width=\"120px\" align=\"right\"><center><a href=\"vip.php?act=vip&days=" . $_GET['days'] . "&confirm=true\" style='color:#fff;text-align:center;' id=\"link\" class=\"neg\"><b>Confirmar</b></a></center></th>";
                echo "</tr></table>";
                include("templates/private_footer.php");
                break;
            }
        }
    break;

	case "buy":
        if (!$_GET['id']) {
            include("templates/private_header.php");
            echo "Este item não está a venda na loja VIP. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
        }
        
        $item = $db->execute("select * from `vip_shop` where `id`=?", array($_GET['id']));
		if ($item->recordcount() == 0)
		{
            include("templates/private_header.php");
            echo "Este item não está a venda na loja VIP. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
		}
		else
		{
			$item = $item->fetchrow();
        }
        
        if ($acc->creditos < $item['price'])
        {
            include("templates/private_header.php");
            echo "Você não possui créditos suficientes. <a href=\"vip.php\">Voltar.</a>\n";
            include("templates/private_footer.php");
            break;
        } else {
            if ($_GET['confirm']) {
                
                if ($item['type'] == 'item'){
					$itid = explode (", ", $item['value']);
                    foreach ($itid as $key_value)
                    {
                        $insert['player_id'] = $player->id;
                        $insert['item_id'] = $key_value;
                        $query = $db->autoexecute('items', $insert, 'INSERT');
                    }
				} elseif ($item['type'] == 'gold'){
                    $db->execute("update `players` set `gold`=`gold`+? where `id`=?", array($item['value'], $player->id));
				}
    
                $db->execute("update `accounts` set `creditos`=`creditos`-? where `id`=?", array($item['price'], $acc->id));
                $db->execute("update `vip_shop` set `sells`=`sells`+1 where `id`=?", array($_GET['id']));
                
                include("templates/private_header.php");
                echo "Compra efetuada com sucesso. <a href=\"vip.php\">Voltar.</a>\n";
                include("templates/private_footer.php");
                break;
            } else {
                include("templates/private_header.php");
                echo "<table border=\"0px\" width=\"100%\"><tr>";
                echo "<th width=\"120px\"><center><a href=\"vip.php\" id=\"link\" style='color:#fff;text-align:center;' class=\"normal\"><b>Voltar</b></a></center></th>";
                echo "<td><center><p>Você está prestes a comprar <b>" . $item['name'] . "</b>,<br/>por um valor de <b>" . $item['price'] . " créditos</b>.</p></center></td>";
                echo "<th width=\"120px\" align=\"right\"><center><a href=\"vip.php?act=buy&id=" . $_GET['id'] . "&confirm=true\" style='color:#fff;text-align:center;' id=\"link\" class=\"neg\"><b>Confirmar</b></a></center></th>";
                echo "</tr></table>";
                include("templates/private_footer.php");
                break;
            }
        }
	break;
	
	default:
	include("templates/private_header.php");
		echo "<i><center>Voc&ecirc; pode adiquirir itens únicos e muito especias no jogo atravéz desta loja VIP. Para adiquirir os itens voc&ecirc; vai precisará de créditos, que podem ser obtidos após realizar doações ao jogo. Sua doação será investida na manutenção e administração do jogo. Lembre-se que você está realizando uma colaboração com o jogo, e não poderemos devolver seu dinheiro no caso do projeto O Confronto acabar.</center></i><br />\n";
			echo "<fieldset>\n";
			echo "<legend><b>Créditos Especiais</b></legend>\n";
				echo "<table width=\"100%\">\n";
				echo "<tr><td width=\"10%\">";
					echo "<center><img src=\"static/images/itens/vgold1.gif\"/></center>";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "Ganhe 15 Créditos";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "<b>Ao doar:</b> R$ 7,00 <font size=\"1px\">(Bônus 0%)</font>";
				echo "</td>";
				echo "<td width=\"20%\">";
					echo "<center><a href=\"vip.php?act=credits&id=1\">Mais informações.</a></center>";
				echo "</td></tr>";

				echo "<tr><td width=\"10%\">";
					echo "<center><img src=\"static/images/itens/vgold2.gif\"/></center>";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "Ganhe 25 Créditos";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "<b>Ao doar:</b> R$ 10,25 <font size=\"1px\">(Bônus 10%)</font>";
				echo "</td>";
				echo "<td width=\"20%\">";
					echo "<center><a href=\"vip.php?act=credits&id=2\">Mais informações.</a></center>";
				echo "</td></tr>";

				echo "<tr><td width=\"10%\">";
					echo "<center><img src=\"static/images/itens/vgold3.gif\"/></center>";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "Ganhe 50 Créditos";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "<b>Ao doar:</b> R$ 18,50 <font size=\"1px\">(Bônus 20%)</font>";
				echo "</td>";
				echo "<td width=\"20%\">";
					echo "<center><a href=\"vip.php?act=credits&id=3\">Mais informações.</a></center>";
				echo "</td></tr>";

				echo "<tr><td width=\"10%\">";
					echo "<center><img src=\"static/images/itens/vgold4.gif\"/></center>";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "Ganhe 75 Créditos";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "<b>Ao doar:</b> R$ 24,00 <font size=\"1px\">(Bônus 30%)</font>";
				echo "</td>";
				echo "<td width=\"20%\">";
					echo "<center><a href=\"vip.php?act=credits&id=4\">Mais informações.</a></center>";
				echo "</td></tr>";

				echo "<tr><td width=\"10%\">";
					echo "<center><img src=\"static/images/itens/vgold5.gif\"/></center>";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "Ganhe 100 Créditos";
				echo "</td>";
				echo "<td width=\"35%\">";
					echo "<b>Ao doar:</b> R$ 30,00 <font size=\"1px\">(Bônus 35%)</font>";
				echo "</td>";
				echo "<td width=\"20%\">";
					echo "<center><a href=\"vip.php?act=credits&id=5\">Mais informações.</a></center>";
				echo "</td></tr>";

			echo "</table></fieldset>\n<br />";
			
			echo "<fieldset>\n";
			echo "<legend><b>Efetuar doação</b></legend>\n";
			include("pagseguro/pginclude.php");
		
			
			echo "</table></fieldset>\n<br />";
			
			echo "<fieldset>\n";
			echo "<legend><b>Ultimas Transações</b></legend>\n";
			?>
			
				
		<b>
		<table align="center" bgcolor="#CCCCCC" border="0" cellpadding="3" cellspacing="1" width="100%">
		<tbody>
		
		<?php
		$query = $db->execute("select * from pagsegurotransacoes where Referencia = '$acc->id' order by data");
		if ($query->recordcount() > 0){
		?>
		<tr>
		<th align="center" bgcolor="#E1CBA4" width="20%">Tipo</th>
		<th align="center" bgcolor="#E1CBA4" width="7%">Valor</th>
		<th align="center" bgcolor="#E1CBA4" width="10%">Creditos</th>
		<th align="center" bgcolor="#E1CBA4" width="10%">Bônus</th>
		<th align="center" bgcolor="#E1CBA4" width="30%">Status</th>
		<th align="center" bgcolor="#E1CBA4" width="15%">Data</th>
		</tr>
		<?php
		while($lisquery = $query->fetchrow()){
		$qt = $lisquery['ProdQuantidade'];
		$query2 = $db->execute("select * from list_credito where qt = '$qt' limit 1");
		if ($query2->recordcount() > 0){
		while($queryqt = $query2->fetchrow()){
		$bonuscredito = $queryqt['qt_reward'];
		}
		}else{
		$bonuscredito = "0";
		}
		$valor_p = OCv2::verificar($lisquery['ProdValor']);
		$valor_f = $valor_p * $lisquery['ProdQuantidade'];
		?>
		<!-- TABELA FISICA -->
		<tr class="off" onmouseover="this.className='on'" onmouseout="this.className='off'">
		<td><b><?php echo $lisquery['TipoPagamento']; ?></a></b></td>
		<td><b><?php echo OCv2::verificar($lisquery['ProdQuantidade']); ?>,00</a></b></td>
		<td align="center"><?php echo $lisquery['ProdQuantidade']; ?></td>
		<td align="center"><?php echo $bonuscredito; ?></td>
		<td align="center"><?php echo $lisquery['status']; ?></td>
		<td align="center"><?php echo $lisquery['Data']; ?></td>
		</tr>
		<!-- TABELA FISICA -->
		<?php
		}
		}else{
		echo "<th>Não foram encontradas transações em sua conta</th>";
		}
		?>
		
		</tbody>
		</table>
		
			<?php			
			echo "</fieldset>\n<br />";

        echo "<table width=\"100%\" border=\"0\"><tr>";
		echo "<td width=\"50%\">Benefícios disponíveis:</td>";
		echo "<td width=\"50%\" align=\"right\"><b>Seus créditos:</b> " . $acc->creditos . " créditos.</td>";
		echo "</tr></table>";
        
        echo "<fieldset>\n";
        echo "<legend><b>Conta Vip</b></legend>\n";
            echo "Transformando sua conta em uma conta vip trará benefícios á todos personagens em sua conta.<br/>";
            echo "<ul>";
            echo "<li>10% de experiência ao matar monstros.</li>";
            echo "<li>Benefícios de todos os reinos no mesmo personagem:";
                echo "<ul>";
                    echo "<li>Itens e bebidas são 10% mais baratos</li>";
                    echo "<li>Magias requerem 5 pontos de mana a menos.</li>";
                    echo "<li>Permite voc&ecirc; caçar e trabalhar por 1 hora extra.</li>";
                    echo "<li>Bônus de vida de aproximadamente 8% aos seus membros.</li>";
                echo "</ul>";
            echo "</li>";
            echo "<li>Nível mínimo requerido por itens reduzido em 10 níveis.</li>";
            echo "<li>Maior drop de items e poções nos monstros.</li>";
            echo "<li>Nome destacado na cor Azul. <font size=\"1px\">(pode ser desabilitado)</font></li>";
            echo "</ul>";
            if ($player->vip > time()) {
                echo "<div style=\"width:98%;align:center;text-align:center;\"><b>Sua conta vip já está ativa</b><br/><font size=\"1px\">" . ceil(($player->vip - time()) / 86400) . " dia(s) restante(s). ";
                $checkshowvip = $db->execute("select * from `other` where `value`=? and `player_id`=?", array(hidevip, $player->acc_id));
                if ($checkshowvip->recordcount() < 1) {
                    echo "<a href=\"vip.php?act=hide\">Esconder status VIP</a>";
                } else {
                    echo "<a href=\"vip.php?act=hide\">Mostrar status VIP</a>";
                }
                echo "</font></div>";
            } else {
                echo "<div style=\"width:98%;align:center;text-align:right;\"><b>7 dias</b>, por 7 créditos. <a href=\"vip.php?act=vip&days=7\">Mais informações</a><br/><b>30 dias</b>, por 22 créditos. <a href=\"vip.php?act=vip&days=30\">Mais informações</a></div>";
            }
        echo "</fieldset><br/>";
        
		echo "<table width=\"100%\" border=\"0\"><tr>";
		echo "<td width=\"50%\">Itens especiais disponíveis:</td>";
		echo "<td width=\"50%\" align=\"right\"><b>Seus créditos:</b> " . $acc->creditos . " créditos.</td>";
		echo "</tr></table>";

		$getitems = $db->execute("select * from `vip_shop` order by `type` desc, `price` asc");
		if ($getitems->recordcount() == 0)
		{
			echo "<center><i>Nenhum item especial disponível no momento.</i></center>";
		}
		else
		{
			while ($vipti = $getitems->fetchrow())
			{
				if ($vipti['type'] == 'item'){
					$itid = explode (", ", $vipti['value']);
						$itcount = 0;
						foreach ($itid as $key_value)
						{
							$itinfo = $db->execute("select * from `blueprint_items` where `id`=?", array($key_value));
							$item = $itinfo->fetchrow();

							$itimages .= "<img src=\"static/images/itens/" . $item['img'] . "\"/>";

							if ($item['type'] == 'amulet'){
								$itnames .= "" . $item['name'] . " <font size=\"1px\">(Vitalidade: " . $item['effectiveness'] . ", Nível " . $item['needlvl'] . "+)</font><br/>";
							}elseif ($item['type'] == 'boots') {
								$itnames .= "" . $item['name'] . " <font size=\"1px\">(Agilidade: " . $item['effectiveness'] . ", Nível " . $item['needlvl'] . "+)</font><br/>";
							}elseif ($item['type'] == 'weapon') {
								$itnames .= "" . $item['name'] . " <font size=\"1px\">(Ataque: " . $item['effectiveness'] . ", Nível " . $item['needlvl'] . "+)</font><br/>";
                            }elseif (($item['type'] == 'armor') or ($item['type'] == 'legs') or ($item['type'] == 'helmet')) {
                                $itnames .= "" . $item['name'] . " <font size=\"1px\">(Defesa: " . $item['effectiveness'] . ", Nível " . $item['needlvl'] . "+)</font><br/>";
							}elseif ($item['type'] == 'shield') {
								$itnames .= "" . $item['name'] . " <font size=\"1px\">(Defesa: " . $item['effectiveness'] . ", Nível " . $item['needlvl'] . "+)<br/><i>Arqueiros não podem usar escudos</i>.</font><br/ >";
							}else{
								$itnames .= "" . $item['name'] . " <font size=\"1px\">(Nível " . $item['needlvl'] . "+)<br/ ><i>" . $item['description'] . "</i></font><br/>";
							}

							$itcount = $itcount + 1;
						}

					echo "<fieldset>\n";
					echo "<legend><b>" . $vipti['name'] . "</b></legend>\n";
					echo "<table width=\"100%\">\n";
						echo "<tr><td width=\"" . ($itcount * 6) . "%\">";
							echo "<center>" . $itimages . "</center>";
						echo "</td><td width=\"" . (75 - ($itcount * 6)) . "%\">";
							echo $itnames;
						echo "</td><td width=\"" . (100 - (($itcount * 6) + (75 - ($itcount * 6)))) . "%\">";
							echo "<b>Preço:</b> " . $vipti['price'] . " créditos.<br />";
							echo "<a href=\"vip.php?act=buy&id=" . $vipti['id'] . "\">Comprar</a><br />";
						echo "</td></tr>";
					echo "</table>\n";
					echo "</fieldset>\n";
				$itimages = NULL;
				$itnames = NULL;

				}elseif ($vipti['type'] == 'gold'){
					echo "<fieldset>\n";
					echo "<legend><b>Moedas de ouro</b></legend>\n";
					echo "<table width=\"100%\">\n";
						echo "<tr><td width=\"6%\">";
							echo "<center><img src=\"static/images/itens/normalgold.gif\"/></center>";
						echo "</td><td width=\"69%\">";
							echo "" . $vipti['value'] . " de moedas de ouro.";
						echo "</td><td width=\"25%\">";
							echo "<b>Preço:</b> " . $vipti['price'] . " créditos.<br />";
							echo "<a href=\"vip.php?act=buy&id=" . $vipti['id'] . "\">Comprar</a><br />";
						echo "</td></tr>";
					echo "</table>\n";
					echo "</fieldset>\n";
				}
			}

		}
        
        if ($player->vip > time())
        {
            echo "<center><p>Se você se tornar um usuário VIP poderá usar items de nível ".($player->level+10)." ou mais.</p></center>";
        }

	include("templates/private_footer.php");
	break;
}
?>