<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}


include(__DIR__ . "/templates/private_header.php");

$price = (500 * $guild['members']);

//Guild Leader Admin check
if ($player->username != ($guild['leader'] ?? null) && $player->username != ($guild['vice'] ?? null)) {
    echo "<p />Você não pode acessar esta página.<p />";
    echo '<a href="home.php">Principal</a><p />';
} else {

    $valortempo = $guild['pagopor'] - time();
    if ($valortempo < 60) {
        $valortempo2 = $valortempo;
        $auxiliar2 = "segundo(s)";
    } elseif ($valortempo < 3600) {
        $valortempo2 = floor($valortempo / 60);
        $auxiliar2 = "minuto(s)";
    } elseif ($valortempo < 86400) {
        $valortempo2 = floor($valortempo / 3600);
        $auxiliar2 = "hora(s)";
    } elseif ($valortempo > 86400) {
        $valortempo2 = floor($valortempo / 86400);
        $auxiliar2 = "dia(s)";
    }

    if ($_POST['submit'] ?? null) {
        // Convert days to float before using floor()
        $days = filter_var($_POST['days'] ?? '', FILTER_VALIDATE_FLOAT);
        
        if ($days === false) {
            $errmsg .= "Este número de dias não é válido.";
            $error = 1;
        } else {
            $arredonda = floor($days);
            $maximodedias = ($guild['pagopor'] + ($arredonda * 86400)) - time();
            $price2 = ceil($price * $arredonda);

            if ($arredonda < 1) {
                $errmsg .= "Este número de dias não é válido.";
                $error = 1;
            } elseif ($price2 > ($guild['gold'] ?? null)) {
                $errmsg .= "Seu clã não possui ouro suficiente para pagar por " . $arredonda . " dia(s).";
                $error = 1;
            } elseif ($maximodedias > 5183999) {
                $errmsg .= "Você não pode deixar seu clã pago por mais de 60 dias.";
                $error = 1;
            }

            if ($error == 0) {
                $tempoadicional = $guild['pagopor'] + ($arredonda * 86400);
                $query = $db->execute("update `guilds` set `gold`=?, `pagopor`=? where `id`=?", [$guild['gold'] - $price2, $tempoadicional, $guild['id'] ?? null]);
                $msg .= "Seu clã acaba de ser pago por mais " . $arredonda . " dia(s).";
            }
        }
    }

?>
    <?= $msg ?><font color=red><?= $errmsg ?></font>
    <fieldset>
        <legend><b><?= $guild['name'] ?? null ?> :: Pagar pelo clã</b></legend>
        <form method="POST" action="guild_admin_pay.php">
            <b>Pagar por mais:</b> <input type="text" name="days" size="3" maxlength="3" /> dias.
            <p />
            <input type="submit" name="submit" value="Pagar"> Cada dia custa <b><?= $price ?> de ouro</b>.
        </form>
    </fieldset>
    <b>Este clã está pago por:</b> <?= $valortempo2; ?> <?= $auxiliar2; ?>.<br>
    Ele será deletado se o tempo acabar e você não pagar mais.

<?php
}

include(__DIR__ . "/templates/private_footer.php");
?>
