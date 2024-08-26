<form target="pagseguro" method="post" action="https://pagseguro.uol.com.br/checkout/checkout.jhtml">
<?php
$randextraid = rand(400,500);
$idf = "".$randextraid."".$acc->id."".$randextraid.""
?>
<input type="hidden" name="email_cobranca" value="ju.rotta@gmail.com">
<input type="hidden" name="tipo" value="CP">
<input type="hidden" name="moeda" value="BRL">

<input type="hidden" name="item_id_1" value="1">
<input type="hidden" name="item_descr_1" value="Créditos">

<input type="hidden" name="item_valor_1" value="1">
<input type="hidden" name="item_frete_1" value="0">
<input type="hidden" name="item_peso_1" value="0">
<input type="hidden" name="ref_transacao" value="<?php echo $letrafinal = substr($idf, 3,-3); ?>">
<table border="0" cellpadding="4" cellspacing="1" width="100%" id="#estilo">
<tr bgcolor="#505050" class="white">
</tr>
<tr>
<td width="10%">ID único:</td>
<td><strong><?php echo $idf; ?></strong></td>
</tr>
<tr>
<td width="10%">Pontos:</td>
<td>
<input name="item_quant_1" type="text" value="1" size="5" maxlength="5">
</td>
</tr>

<td colspan="2">
<input type="image" src="http://www.kikoweb.com.br/product_images/b/doar_assina__34591.gif" name="submit" alt="Pague com PagSeguro - &eacute; r&aacute;pido, gr&aacute;tis e seguro!" />
</td>

</form>