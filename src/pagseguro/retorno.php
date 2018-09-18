<?php

if (!defined('TOKEN')) define ('TOKEN', '');

/**
* RetornoPagSeguro
*
* Classe de manipulação para o retorno do post do pagseguro
*
* @package PagSeguro
*/
class RetornoPagSeguro {
/**
* _preparaDados
*
* Prepara os dados vindos do post e converte-os para url, adicionando
* o token do usuario quando necessario.
*
* @internal é usado pela {@see RetornoPAgSeguro::verifica} para gerar os,
* dados que serão enviados pelo PagSeguro
*
* @access private
*
* @param array $post Array contendo os posts do pagseguro
* @param bool $confirmacao Controlando a adicao do token no post
* @return string
*/
function _preparaDados($post, $confirmacao=true) {
if ('array' !== gettype($post)) $post=array();
if ($confirmacao) {
$post['Comando'] = 'validar';
$post['Token'] = TOKEN;
}
$retorno=array();
foreach ($post as $key=>$value){
if('string'!==gettype($value)) $post[$key]='';
$value=urlencode(stripslashes($value));
$retorno[]="{$key}={$value}";
}
return implode('&', $retorno);
}

/**
* _tipoEnvio
*
* Checa qual será a conexao de acordo com a versao do PHP
* preferencialmente em CURL ou via socket
*
* em CURL o retorno será:
* <code> array ('curl','https://pagseguro.uol.com.br/Security/NPI/Default.aspx') </code>
* já em socket o retorno será:
* <code> array ('fsocket', '/Security/NPI/Default.aspx', $objeto-de-conexao) </code>
* se não encontrar nenhum nem outro:
* <code> array ('','') </code>
*
* @access private
* @global string $_retPagSeguroErrNo Numero de erro do pagseguro
* @global string $_retPagSeguroErrStr Texto descritivo do erro do pagseguro
* @return array Array com as configurações
*
*/
function _tipoEnvio() {
//Prefira utilizar a função CURL do PHP
//Leia mais sobre CURL em: http://us3.php.net/curl
global $_retPagSeguroErrNo, $_retPagSeguroErrStr;
if (function_exists('curl_exec'))
return array('curl', 'https://pagseguro.uol.com.br/Security/NPI/Default.aspx');
elseif ((PHP_VERSION >= 4.3) && ($fp = @fsockopen('ssl://pagseguro.uol.com.br', 443, $_retPagSeguroErrNo, $_retPagSeguroErrStr, 30)))
return array('fsocket', '/Security/NPI/Default.aspx', $fp);
elseif ($fp = @fsockopen('pagseguro.uol.com.br', 80, $_retPagSeguroErrNo, $_retPagSeguroErrStr, 30))
return array('fsocket', '/Security/NPI/Default.aspx', $fp);
return array ('', '');
}

/**
* not_null
*
* Extraido de OScommerce 2.2 com base no original do pagseguro,
* Checa se o valor e nulo
*
* @access public
*
* @param mixed $value Variável a ser checada se é nula
* @return bool
*/
function not_null($value) {
if (is_array($value)) {
if (sizeof($value) > 0) {
return true;
} else {
return false;
}
} else {
if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
return true;
} else {
return false;
}
}
}

/**
* verifica
*
* Verifica o tipo de conexão aberta e envia os dados vindos
* do post
*
* @access public
*
* @use RetornoPagSeguro::_tipoenvio()
* @global string $_retPagSeguroErrNo Numero de erro do pagseguro
* @global string $_retPagSeguroErrStr Texto descritivo do erro do pagseguro
* @param array $post Array contendo os posts do pagseguro
* @param bool $tipoEnvio (opcional) Verifica o tipo de envio do post
* @return bool
*/
function verifica($post, $tipoEnvio=false) {
global $_retPagSeguroErrNo, $_retPagSeguroErrStr;
if ('array' !== gettype($tipoEnvio))
$tipoEnvio = RetornoPagSeguro::_tipoEnvio();
$spost=RetornoPagSeguro::_preparaDados($post);
if (!in_array($tipoEnvio[0], array('curl', 'fsocket')))
return false;
$confirma = false;
if ($tipoEnvio[0] === 'curl') {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tipoEnvio[1]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $spost);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($ch);
if (!RetornoPagSeguro::not_null($resp)) {
curl_setopt($ch, CURLOPT_URL, $tipoEnvio[1]);
$resp = curl_exec($ch);
}
curl_close($ch);
$confirma = (strcmp ($resp, 'VERIFICADO') == 0);
} elseif ($tipoEnvio[0] === 'fsocket') {
if (!$tipoEnvio[2]) {
die ("{$_retPagSeguroErrStr} ($_retPagSeguroErrNo)");
} else {
$cabecalho = "POST {$tipoEnvio[1]} HTTP/1.0\r\n";
$cabecalho .= "Content-Type: application/x-www-form-urlencoded\r\n";
$cabecalho .= "Content-Length: " . strlen($spost) . "\r\n\r\n";
$resp = '';
fwrite ($tipoEnvio[2], "{$cabecalho}{$spost}");
while (!feof($tipoEnvio[2])) {
$resp = fgets ($tipoEnvio[2], 1024);
if (strcmp ($resp, 'VERIFICADO') == 0) {
$confirma = (strcmp ($resp, 'VERIFICADO') == 0);
$confirma=true;
break;
}
}
fclose ($tipoEnvio[2]);
}
}
if ($confirma && function_exists('retorno_automatico')) {
$itens = array (
'VendedorEmail', 'TransacaoID', 'Referencia', 'TipoFrete',
'ValorFrete', 'Anotacao', 'DataTransacao', 'TipoPagamento',
'StatusTransacao', 'CliNome', 'CliEmail', 'CliEndereco',
'CliNumero', 'CliComplemento', 'CliBairro', 'CliCidade',
'CliEstado', 'CliCEP', 'CliTelefone', 'NumItens',
);
foreach ($itens as $item) {
if (!isset($post[$item])) $post[$item] = '';
if ($item=='ValorFrete') $post[$item] = str_replace(',', '.', $post[$item]);
}
$produtos = array ();
for ($i=1;isset($post["ProdID_{$i}"]);$i++) {
$produtos[] = array (
'ProdID' => $post["ProdID_{$i}"],
'ProdDescricao' => $post["ProdDescricao_{$i}"],
'ProdValor' => (double) (str_replace(',', '.', $post["ProdValor_{$i}"])),
'ProdQuantidade' => $post["ProdQuantidade_{$i}"],
'ProdFrete' => (double) (str_replace(',', '.', $post["ProdFrete_{$i}"])),
'ProdExtras' => (double) (str_replace(',', '.', $post["ProdExtras_{$i}"])),
);
}
retorno_automatico (
$post['VendedorEmail'], $post['TransacaoID'], $post['Referencia'], $post['TipoFrete'],
$post['ValorFrete'], $post['Anotacao'], $post['DataTransacao'], $post['TipoPagamento'],
$post['StatusTransacao'], $post['CliNome'], $post['CliEmail'], $post['CliEndereco'],
$post['CliNumero'], $post['CliComplemento'], $post['CliBairro'], $post['CliCidade'],
$post['CliEstado'], $post['CliCEP'], $post['CliTelefone'], $produtos, $post['NumItens']
);
}
return $confirma;
}
}

if ($_POST) {
RetornoPagSeguro::verifica($_POST);
die();
}


?> 