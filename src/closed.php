<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Bem-Vindo!");
$escolheper = 33;

if ($setting->closed < time() && $_GET['beta'] != 'imjusttesting'){
	echo time();
	exit;
}

include(__DIR__ . "/templates/acc_header.php");
echo "<br/><br/><br/>";
echo "<center>Seja bem-vindo ao jogo. Depois de Várias atualizações, estamos prestes a abrir nossas portas novamente!</center>";
echo "<center>Aguarde, pois nesta sexta, as seis horas da tarde, voltaremos a ser um mundo repleto de mistérios e aventuras!</center>";
echo "<br/>";
echo '<script language="JavaScript">';
echo 'TargetDate = "07/08/2011 06:00 PM";';
echo "CountActive = true;";
echo "CountStepper = -1;";
echo "LeadingZero = true;";
echo 'DisplayFormat = "%%D%% Dias, %%H%% horas, %%M%% minutos e %%S%% segundos para a abertura dos reinos.";';
echo "FinishMessage = \"A espera acabou! Os reinos estão abertos!\";";
echo "</script>";
echo '<center><b><script language="JavaScript" src="static/js/countdown.js"></script></b></center>';
echo "<br/><br/>";
echo '<table align="center"><tr>';
echo "<td><a href=\"images/ss/ss1.png\" rel=\"lightbox[screens]\" title=\"Mudanças na interfaçe do jogo.\"><img src=\"static/images/ss/ss1.png\" width=\"128\" height=\"114\" border=\"2px\" alt=\"Batalhar\"/></a></td>";
echo "<td><a href=\"images/ss/ss2.png\" rel=\"lightbox[screens]\" title=\"Equipamentos, itens especiais, o ferreiro e o mercado fazem dos itens uma das principais moedas de troca do jogo.\"><img src=\"static/images/ss/ss2.png\" width=\"128\" height=\"114\" border=\"2px\" alt=\"Inventário\"/></a></td>";
echo "<td><a href=\"images/ss/ss3.png\" rel=\"lightbox[screens]\" title=\"Modos de luta interativos e mais dinâmicos.\"><img src=\"static/images/ss/ss3.png\" width=\"128\" height=\"114\" border=\"2px\" alt=\"Monstros\"/></a></td>";
echo '<td><a href="images/ss/ss4.png" rel="lightbox[screens]" title="Comunidade ampliada e novas tecnologias agora tornam os jogadores mais unidos."><img src="static/images/ss/ss4.png" width="128" height="114" border="2px" alt="Amigos"/></a></td>';
echo "<td><a href=\"images/ss/ss5.png\" rel=\"lightbox[screens]\" title=\"Sistema de reinos, onde jogadores mais experientes poderão ser eleitos imperadores e realizar eventos no reino.\"><img src=\"static/images/ss/ss5.png\" width=\"128\" height=\"114\" border=\"2px\" alt=\"Trabalho\"/></a></td>";
echo "</tr></table>";
include(__DIR__ . "/templates/acc_footer.php");
?>
