<?php
include("../config.php");
header('Content-Type: text/html; charset=ISO-8859-1');

define('TOKEN', 'D829224E7F6243F89AF1EC852054B457');


class PagSeguroNpi {
	
	private $timeout = 20; // Timeout em segundos
	
	public function notificationPost() {
		$postdata = 'Comando=validar&Token='.TOKEN;
		foreach ($_POST as $key => $value) {
			$valued    = $this->clearStr($value);
			$postdata .= "&$key=$valued";
		}
		return $this->verify($postdata);
	}
	
	private function clearStr($str) {
		if (!get_magic_quotes_gpc()) {
			$str = addslashes($str);
		}
		return $str;
	}
	
	private function verify($data) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://pagseguro.uol.com.br/pagseguro-ws/checkout/NPI.jhtml");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = trim(curl_exec($curl));
		curl_close($curl);
		return $result;
	}
	
	function verificar($valor) {
        $pontos = ',';
        $virgula = '0';
        $result = str_replace($pontos, "", $valor);
        $result2 = str_replace($virgula, "", $result);
        return $result2;
}

}

if (count($_POST) > 0) {
	
	
	// POST recebido, indica que é a requisição do NPI.
	$npi = new PagSeguroNpi();
	$result = $npi->notificationPost();
	
	$transacaoID = isset($_POST['TransacaoID']) ? $_POST['TransacaoID'] : '';
	
	
	
	if ($result == "VERIFICADO") {
	mysql_query("INSERT into pagsegurotransacoes SET
	TransacaoID='$transacaoID',
	VendedorEmail='$_POST[VendedorEmail]',
	Referencia='$_POST[Referencia]',
	TipoPagamento='$_POST[TipoPagamento]',
	StatusTransacao='$_POST[StatusTransacao]',
	ProdQuantidade='$_POST[ProdQuantidade_1]',
	ProdValor='$_POST[ProdValor_1]',
	CliNome='$_POST[CliNome]',	
	CliEmail='$_POST[CliEmail]',	
	CliEndereco='$_POST[CliEndereco]',	
	CliNumero='$_POST[CliNumero]',	
	CliComplemento='$_POST[CliComplemento]',	
	CliBairro='$_POST[CliBairro]',	
	CliCidade='$_POST[CliCidade]',	
	CliEstado='$_POST[CliEstado]',	
	CliCEP='$_POST[CliCEP]',	
	CliTelefone='$_POST[CliTelefone]',
	Data=now()
	");
	
	if($_POST['StatusTransacao'] == 'Devolvido') {
	mysql_query("UPDATE `pagsegurotransacoes` SET status = 'Dinheiro Devolvido' WHERE `TransacaoID` = '$transacaoID'");
	}
	
	if($_POST['StatusTransacao'] == 'Aprovado') {
			
		$acc_id = $_POST['Referencia'];
		$quantidade = $_POST['ProdQuantidade_1'];
		$valor_produto = $_POST['ProdValor_1'];
		$valor_produto_edit = $npi->verificar($valor_produto) * $quantidade;
		
		if($quantidade == $valor_produto_edit){
		//CODIGO JULIANO
		$verificaAccount = $db->execute("select `conta` from `accounts` where `id`=?", array($acc_id));
		if ($verificaAccount->recordcount() > 0) {
			$verificaCreditos = $db->execute("select `qt_reward` from `list_credito` where `qt`=?", array($quantidade));
			if ($verificaCreditos->recordcount() > 0) {
					//credito encontrado na tabela
					$getCredito = $verificaCreditos->fetchrow();
					$db->execute("update `accounts` set `creditos`=`creditos`+? where `id`=?", array($getCredito['qt_reward'], $acc_id));
				} else {
					//credito nao encontrado na tabela
					$db->execute("update `accounts` set `creditos`=`creditos`+? where `id`=?", array($quantidade, $acc_id));
			
				}
		} else {
			mysql_query("UPDATE `pagsegurotransacoes` SET status = 'ERRO #1' WHERE `TransacaoID` = '$transacaoID'");
		}
			mysql_query("UPDATE `pagsegurotransacoes` SET status = 'Créditos Adicionados' WHERE `TransacaoID` = '$transacaoID'");
	}else{
	mysql_query("UPDATE `pagsegurotransacoes` SET status = 'ERRO #2' WHERE `TransacaoID` = '$transacaoID'");
	}
	}
	if($_POST['StatusTransacao'] == 'Aguardando Pagto') {
	mysql_query("UPDATE `pagsegurotransacoes` SET status = 'Aguardando Pagamento' WHERE `TransacaoID` = '$transacaoID'");
	}
	
	if($_POST['StatusTransacao'] == 'Em Analise') {
		mysql_query("UPDATE `pagsegurotransacoes` SET status = 'Pagamento aprovado, em análise pelo PagSeguro' WHERE `TransacaoID` = '$transacaoID'");
	}
	
	if($_POST['StatusTransacao'] == 'Cancelado') {
		mysql_query("UPDATE `pagsegurotransacoes` SET status = 'Pagamento cancelado pelo PagSeguro' WHERE `TransacaoID` = '$transacaoID'");
	}
	
	} else if ($result == "FALSO") {
		//O post não foi validado pelo PagSeguro.
	} else {
		//Erro na integração com o PagSeguro.
	}
	
} else {
	// POST não recebido, indica que a requisição é o retorno do Checkout PagSeguro.
	// No término do checkout o usuário é redirecionado para este bloco.
	?>
    <h3>Obrigado por efetuar a compra.</h3>
    <?php
}

?>