<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Sobre o Jogo");
include(__DIR__ . "/templates/header.php");

$usaar = $_GET['r'] ?: "1";

?>

<fieldset>
    <legend><b>O Confronto</b></legend>
    O Confronto é um jogo web-based medieval. Em sua jornada, você irá seguir os passos de um guerreiro medieval, lutando contra monstros e criaturas, fazendo missões e participando de muitas aventuras.
    <br /><br />
    Monstros, PVP, Torneios, Missões, Magias, Trabalhos, Mercado, e muitas outras funções irão te surpreender neste game.
    <br /><br />
    Comece já a jogar e descubra este novo mundo. <a href="register.php?r=<?php echo $usaar ?>">Clique aqui para se registar</a>.
</fieldset>

<?php
include(__DIR__ . "/templates/footer.php");
?>