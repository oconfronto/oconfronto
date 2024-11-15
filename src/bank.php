<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Banco");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkwork.php");

$lockedgold = 0;

/* $countlocked = $db->execute("select `prize` from `duels` where `owner`=? and (`active`='w' or `active`='t')", array($player->id));
while($count = $countlocked->fetchrow())
{
$lockedgold += $lockedgold + $count['prize'];
} */

if (isset($_POST['deposit'])) {
    $deposita = (new OCv2($db))->tirarCMoeda($_POST['deposit']);
    if (!is_numeric($deposita)) {
        $msg = showAlert("Esta quantia de ouro não é válida!", "red");
    } else {
        $deposita = floatval($deposita);
        $deposita = floor($deposita);
        if ($deposita > $player->gold || $deposita < 1) {
            $msg = showAlert("Você não pode depositar esta quantia de ouro.", "red");
        } else {
            $query = $db->execute("update `players` set `bank`=?, `gold`=? where `id`=?", [$player->bank + $deposita, $player->gold - $deposita, $player->id]);
            $msg = showAlert("Você depositou " . $_POST['deposit'] . " moedas de ouro na sua conta.", "green");
            $player = check_user($db); //Get new stats so new amount of gold is displayed on left menu
        }
    }
} elseif (isset($_POST['withdraw'])) {
    $saca = (new OCv2($db))->tirarCMoeda($_POST['withdraw']);
    if (!is_numeric($saca)) {
        $msg = showAlert("Esta quantia de ouro não é válida!", "red");
    } else {
        $saca = floatval($saca);
        $saca = floor($saca);
        if ($saca > ($player->bank - $lockedgold) || $saca < 1) {
            $msg = showAlert("Você não tem esta quantia de dinheiro na sua conta do banco!", "red");
        } else {
            $query = $db->execute("update `players` set `bank`=?, `gold`=? where `id`=?", [$player->bank - $saca, $player->gold + $saca, $player->id]);
            $msg = showAlert("Você retirou " . $_POST['withdraw'] . " moedas de ouro de sua conta.", "green");
            $player = check_user($db); //Get new stats so new amount of gold is displayed on left menu
        }
    }
}

include(__DIR__ . "/templates/private_header.php");

if (isset($msg)) {
    echo $msg;
}

echo "<fieldset style='padding:0px;border:1px solid #b9892f;'>";
echo "<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Banco</b></fieldset>";
echo '<div style="float:left;width:80px;"></div>';
echo '<div style="padding-left:25px;"><b>Bem vindo ao Banco!</b><p>';
echo "<i>Bem-Vindo ao banco. Todo seu dinheiro aqui depositado estará protegido.
<br>Além do mais, quanto mais dinheiro você depositar, mais juros poderá ganhar.</i>";
echo "</p></div></fieldset>";
$depositar = "";
$depositar .= '<p><form method="post" action="bank.php">';
$depositar .= '<input type="text" name="deposit" size="15" value="' . number_format($player->gold) . '" />';
$depositar .= '<input type="submit" name="bank_action" value="Depositar"/>';
$depositar .= "</form></p>";
$depositar .= "<i>Você tem <b>" . number_format($player->gold) . "</b> de ouro com você.</i>";

$sacar = "";
$sacar .= '<p><form method="post" action="bank.php">';
$sacar .= '<input type="text" name="withdraw" size="15" value="' . (number_format($player->bank - $lockedgold)) . '" />';
$sacar .= '<input type="submit" name="bank_action" value="Retirar"/>';
$sacar .= "</form></p>";
$sacar .= "<i>Você tem <b>" . (number_format($player->bank - $lockedgold)) . "</b> de ouro na sua conta bancária.</i>";

echo '<table width="100%">';
echo "<tr>";
echo "<td width=\"50%\"><fieldset style='border:0px;text-align:center;'><b>Depositar Ouro</b></fieldset>" . showAlert($depositar) . "</td>";
echo "<td width=\"50%\"><fieldset style='border:0px;text-align:center;'><b>Retirar Ouro</b></fieldset>" . showAlert($sacar) . "";
if (($player->bank + $player->gold) > $setting->bank_limit) {
    echo "<center><font size=\"1px\">Sua fortuna já passou de " . $setting->bank_limit . ", agora você não receberá mais juros!</font></center>";
} else {
    echo '<center><font size="1px">Seu ouro depositado se valoriza ' . $setting->bank_interest_rate . "% ao dia.</font></center>";
}

echo "</td>";
echo "<tr>";
echo "</table>";
?>

<?php
echo "<fieldset style='padding:0px;border:1px solid #b9892f;'>";
echo "<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Transferir Ouro</b></fieldset>";
if ($player->level < $setting->activate_level) {
    echo "<div style=\"padding-left:25px;padding-bottom:10px;\">Para poder fazer transferências bancárias sua conta precisa estar ativa.<br/>Ela será ativada automaticamente quando você alcançar o nível " . $setting->activate_level . ".</div>";
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

if ($player->transpass == "f") {
    echo '<form method="POST" action="transferpass.php">';
    echo "<center><i>Escolha uma senha de transferência para enviar ouro e itens</i><p><font size=\"1px\"><b>Senha:</b></font> <input type=\"password\" name=\"pass\" size=\"15\"/> <font size=\"1px\"><b>Confirme:</b></font> <input type=\"password\" name=\"pass2\" size=\"15\"/> <input type=\"submit\" name=\"submit\" value=\"Definir Senha\"></p><br/><font size=\"1px\">Lembre-se desta senha, ela sempre será usada para fazer transferências bancárias.</font></center>";
    echo "</form></fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

echo '<form method="POST" action="transfer.php">';
echo "<table><tr><td width=\"30%\"><b>Usuário:</b></td><td width=\"70%\"><input autocomplete='off' type=\"text\" name=\"username\" size=\"20\"/></td></tr>";
echo "<tr><td width=\"30%\"><b>Quantia:</b></td><td width=\"70%\"><input autocomplete='off' type=\"text\" name=\"amount\" size=\"20\"/></td></tr>";
echo "<tr><td width=\"30%\"><b>Senha de transferência:</b></td><td width=\"70%\"><input autocomplete='off' type=\"password\" name=\"passcode\" size=\"20\"/> <input type=\"submit\" name=\"submit\" value=\"Enviar\"></td></tr></table>";
echo "</form>";
echo "<fieldset style='border:0px;text-align:center;'>";
echo "<font size=\"1\"><a href=\"forgottrans.php\"><b>Esqueceu sua senha de transferência?</b></a> - <a href=\"account.php\"><b>Alterar senha de transferência</b></a></font></fieldset>";
echo "</fieldset>";
echo "<center><font size=1><a href=\"#\" onclick=\"javascript:window.open('loggold.php', '_blank','top=100, left=100, height=350, width=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Transferências realizadas nos últimos 14 dias.</a></font></center>";
include(__DIR__ . "/templates/private_footer.php");
exit;
?>
