<?php

if (($player->alerts > 998) and ($player->alerts != 'forever') and ($player->alerts < time())){
$unban = $db->execute("update `players` set `alerts`=0 where `id`=?", array($player->id));
include("templates/private_header.php");
echo "Seu banimento no fórum acabou!<br/><a href=\"select_forum.php\">Visitar o fórum</a>.";
include("templates/private_footer.php");
exit;
}

if ((($player->alerts > 99) and ($player->alerts < time())) or ($player->alerts == 'forever') or ($player->alerts > time())){
if (($player->alerts > 99) and ($player->alerts < time())){
include("templates/private_header.php");
echo "Seu alerta chegou a " . $player->alerts . "% e você não poderá visitar o fórum até que seu alerta baixe. Seu alerta cai 1% ao dia.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}else if ($player->alerts == 'forever'){
include("templates/private_header.php");
echo "Você foi banido do fórum permanentemente.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}else if ($player->alerts > time()){
$tempo0 = $player->alerts - time();
$tempo = ceil($tempo0/60/60/24);
include("templates/private_header.php");
echo "Você foi banido do fórum. Seu banimento irá acabar em " . $tempo . " dia(s).<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}
?>