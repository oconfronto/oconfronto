<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Principal");
include(__DIR__ . "/templates/header.php");
echo '<br/><div id="countdown"></div>';

if ($_GET['error']) {
    echo '<table width="100%"><tr><td width="40px"><center><a href="http://facebook.com/ocrpg" target="_blank"><img src="static/images/facebook.png" style="width:25px;height:25px;" border="0px"></a></center></td><td><center><font size="2px">Os portões dos reinos ainda estão fechados!<br/>Você deverá aguardar para poder criar sua conta.</font></center></td></tr></table>';
    //echo "<center><p>Os portões dos reinos ainda estão fechados!<br/>Você deverá aguardar para poder se registrar no jogo.</p></center>";
} else {
    echo '<table width="100%"><tr><td width="40px"><center><a href="http://facebook.com/ocrpg" target="_blank"><img src="static/images/facebook.png" style="width:25px;height:25px;" border="0px"></a></center></td><td><center><font size="2px">Os portões dos reinos ainda estão fechados!<br/>A reabertura ocorrerá na sexta-feira, dia 17, às 14h.</font></center></td></tr></table>';
    //echo "<center><p id=\"note\"></p></center>";
}

echo '<script src="static/http://code.jquery.com/jquery-1.7.1.min.js"></script>';
echo '<script src="static/assets/countdown/jquery.countdown.js"></script>';
echo '<script src="static/assets/js/script.js"></script>';

include(__DIR__ . "/templates/footer.php");
