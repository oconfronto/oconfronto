<?php
include("lib.php");
define("PAGENAME", "Confirma‹o de Pagamento");

include("templates/header.php");
echo "<span id=\"aviso-a\">";
echo "</span>";
echo "<p><center>Pagamento efetuado com sucesso!<br/><a href=\"characters.php\">Clique aqui</a> para entrar em sua conta.</center></p>";
include("templates/footer.php");
exit;
?>