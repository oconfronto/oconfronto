<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Confirmao de Pagamento");

include(__DIR__ . "/templates/header.php");
echo '<span id="aviso-a">';
echo "</span>";
echo '<p><center>Pagamento efetuado com sucesso!<br/><a href="characters.php">Clique aqui</a> para entrar em sua conta.</center></p>';
include(__DIR__ . "/templates/footer.php");
exit;
?>
