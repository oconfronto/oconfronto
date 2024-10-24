<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Opções da conta");
$acc = check_acc($secret_key, $db);

include(__DIR__ . "/templates/acc-header.php");

function generateAccountOptionLink($url, $text)
{
	return sprintf('<center><a href="%s">%s</a><br/></center>', $url, $text);
}
?>

<span id="aviso-a"></span>

<br />
<p>
	<?php
	$accountOptions = [
		'accpass.php' => 'Alterar senha desta conta.',
		'changemail.php' => 'Alterar email desta conta.',
		'editinfo.php' => 'Alterar configurações pessoais.',
		'transferchar.php' => 'Transferir personagem para esta conta.'
	];

	foreach ($accountOptions as $url => $text) {
		echo generateAccountOptionLink($url, $text);
	}
	?>

	<br />
	<center>
		<font size="1px">
			<a href="characters.php"><b>Voltar</b></a> -
			<a href="#" onclick="javascript:window.open('accountlog.php', '_blank','top=100, left=100, height=350, width=520, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');">
				Exibir logs da conta
			</a>
		</font>
	</center>
</p>

<?php
include(__DIR__ . "/templates/acc-footer.php");
?>
