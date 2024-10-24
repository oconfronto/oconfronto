<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Imagens");

include(__DIR__ . "/templates/header.php");

?>
<style>

td.off {
background: #FFECCC;
}

td.on {
background: #FFFDE0;
}

</style>

<table width="100%" border="0">
  <tr>
    <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" height="200"><div align="center">
	<a href="images/ss/ss1.png" rel="lightbox[screens]" title="Várias formas de caça disponíveis."><img src="static/images/ss/ss1.png" width="180" height="160" border="2px" alt="Batalha" /></a><br />
	<br /><strong>Batalha</strong></div></td>
    <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" height="200"><div align="center">
	<a href="images/ss/ss2.png" rel="lightbox[screens]" title="Equipamentos, itens especiais, o ferreiro e o mercado fazem dos itens uma das principais moedas de troca do jogo."><img src="static/images/ss/ss2.png" width="180" height="160" border="2px" alt="Inventário" /></a><br />
        <br /><strong>Inventário</strong></div></td>
    <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" height="200"><div align="center">
	<a href="images/ss/ss3.png" rel="lightbox[screens]" title="A luta em turnos além de ser agradavel, permite o uso de magias e ataques especiais."><img src="static/images/ss/ss3.png" width="180" height="160" border="2px" alt="Monstros" /></a><br />
        <br /><strong>Monstros</strong></div></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="100%" border="0">
  <tr>
    <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" height="200"><div align="center">
	<a href="images/ss/ss4.png" rel="lightbox[screens]" title="Faça amigos, crie grupos e clãs. Converse, troque itens, e até caçe com seus amigos!"><img src="static/images/ss/ss4.png" width="180" height="160" border="2px" alt="Amigos" /></a><br />
        <br /><strong>Amigos</strong></div></td>
    <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" height="200"><div align="center">
	<a href="images/ss/ss5.png" rel="lightbox[screens]" title="Deixe seu personagem trabalhando antes de sair do jogo, e garanta um pouco de outro extra quando você voltar."><img src="static/images/ss/ss5.png" width="180" height="160" border="2px" alt="Trabalho" /></a><br />
	<br /><strong>Trabalho</strong></div></td>
    <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" height="200"><div align="center"><a href="images/ss/ss6.png" rel="lightbox[screens]"><img src="static/images/ss/ss6.png" width="180" height="160" border="2px" alt="Perfil" /></a><br />
      <br /><strong>Perfil</strong></div></td>
  </tr>
</table>

<?php
include(__DIR__ . "/templates/footer.php");
?>
