<?php

declare(strict_types=1);

/*************************************/
/*           ezRPG script            */
/*  ritten by Zen + Khashul + Jrotta */
/*    http://www.ezrpgproject.com/   */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Criar Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;
$goldcost = 200000;


if ($player->guild != NULL) {
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Clãs</b></legend>\n";
    echo "Você não pode ter mais de um clã.";
    echo "</fieldset>\n";
    echo '<a href="home.php">Voltar</a>.';
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

if ($player->gold < $goldcost) {
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Clãs</b></legend>\n";
    echo "Você não tem ouro suficiente para criar um clã.<br/>Você precisa de <b>" . $goldcost . "</b> moedas de ouro.";
    echo "</fieldset>\n";
    echo '<a href="guild_listing.php">Voltar</a>.';
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

if ($_POST['register'] ?? null) {

    $msg1 = '<font color="red">';
    $msg2 = '<font color="red">';
    $msg3 = '<font color="red">';

    $query = $db->execute("select `id` from `guilds` where `name`=? and `serv`=?", [$_POST['name'] ?? null, $player->serv]);

    $pat[0] = "/^\s+/";
    $pat[1] = "/\s{2,}/";
    $pat[2] = "/\s+\$/";
    $rep[0] = "";
    $rep[1] = " ";
    $rep[2] = "";
    $nomedecla = ucwords(preg_replace($pat, (string) $rep, (string) ($_POST['name'] ?? null)));

    $query2 = $db->execute("select `id` from `guilds` where `name`=? and `serv`=?", [$nomedecla, $player->serv]);

    if (!($_POST['name'] ?? null)) {
        //Add to error message
        $msg1 .= "Você precisa digitar um nome para o clã!<br />\n";
        $error = 1;
    } elseif (strlen((string) ($_POST['name'] ?? null)) < 3) {
        //Add to error message
        $msg1 .= "O nome do seu clã deve ser maior que 3 caracteres!<br />\n";
        $error = 1;
    } elseif (strlen((string) ($_POST['name'] ?? null)) > 20) {
        //Add to error message
        $msg1 .= "O nome do seu clã não pode ser maior que 20 caracteres!<br />\n";
        $error = 1;
    } elseif (preg_match("/^[A-Za-z[:space:]\-]+$/", (string) ($_POST['name'] ?? null)) === 0 || preg_match("/^[A-Za-z[:space:]\-]+$/", (string) ($_POST['name'] ?? null)) === false) {
        $msg1 .= "O nome de seu clã não pode conter <b>caracteres especiais!<br />\n";
        $error = 1;
        //Set error check
    } elseif ($query->recordcount() > 0) {
        $msg1 .= "Este nome já está sendo usado.<br />\n";
        //Set error check
        $error = 1;
    } elseif ($query2->recordcount() > 0) {
        $msg1 .= "Este nome já está sendo usado.<br />\n";
        //Set error check
        $error = 1;
    }

    if (!($_POST['tag'] ?? null)) {
        //Add to error message
        $msg2 .= "Você precisa digitar uma tag para o clâ!<br />\n";
        $error = 1;
    } elseif (strlen((string) ($_POST['tag'] ?? null)) < 2) {
        $msg2 .= "A tag do seu clã deve conter de 2 á 4 caracteres!<br />\n";
        //Set error check
        $error = 1;
    } elseif (strlen((string) ($_POST['tag'] ?? null)) > 4) {
        $msg2 .= "A tag do seu clã deve conter de 2 á 4 caracteres!<br />\n";
        //Set error check
        $error = 1;
    } elseif (preg_match("/^[-_a-zA-Z0-9]+$/", (string) ($_POST['tag'] ?? null)) === 0 || preg_match("/^[-_a-zA-Z0-9]+$/", (string) ($_POST['tag'] ?? null)) === false) {
        $msg2 .= "A tag do seu clã não pode conter <b>caracteres especiais!<br />\n";
        $error = 1;
        //Set error check
    }

    if (!($_POST['blurb'] ?? null)) {
        //Add to error message
        $msg3 .= "Você precisa digitar uma descrição para o clã!<br />\n";
        $error = 1;
    } elseif (strlen((string) ($_POST['tag'] ?? null)) > 5000) {
        $msg3 .= "A descrição do seu clã passou de 5000 caracteres!<br />\n";
        //Set error check
        $error = 1;
    }

    if ($error == 0) {
        $pat[0] = "/^\s+/";
        $pat[1] = "/\s{2,}/";
        $pat[2] = "/\s+\$/";
        $rep[0] = "";
        $rep[1] = " ";
        $rep[2] = "";
        $nomedecla = ucwords(preg_replace($pat, (string) $rep, (string) ($_POST['name'] ?? null)));

        $insert['name'] = $nomedecla;
        $insert['tag'] = $_POST['tag'];
        $insert['leader'] = $player->username;
        $tirahtmldades = strip_tags((string) ($_POST['blurb'] ?? null));
        $texto = nl2br($tirahtmldades);

        $insert['reino'] = $player->reino;
        $insert['blurb'] = $texto;
        $insert['pagopor'] = (time() + 950400);
        $insert['registered'] = time();
        $insert['serv'] = $player->serv;
        $query = $db->autoexecute('guilds', $insert, 'INSERT');

        $insertid = $db->Insert_ID();
        $query = $db->execute("update `players` set `guild`=?, `gold`=? where `id`=?", [$insertid, $player->gold - $goldcost, $player->id]);

        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Clãs</b></legend>\n";
        echo "Parabéns! Você acaba de criar um novo clã!";
        echo "</fieldset>\n";
        echo '<a href="guild_listing.php">Voltar</a>.';
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    //Username error
    $msg1 .= "</font>";
    $msg2 .= "</font>";
    $msg3 .= "</font>";
}


include(__DIR__ . "/templates/private_header.php");
?>
<script type="text/javascript" src="static/bbeditor/ed.js"></script>

<form method="POST" action="guild_register.php">
    <?php
    echo showAlert("Criar um clã custa " . $goldcost . " moedas de ouro.");
    ?>
    <table width="100%">
        <tr>
            <td width="25%"><span class="style1"><b>Nome do clã</b>:</span></td>
            <td><input name="name" type="text" value="<?= $_POST['name'] ?? null; ?>" /></td>
        </tr>
        <tr>
            <td colspan="2"><span class="style1">Insira o nome desejado para o clã. <i>Ex: Dragon Killers</i><br />
                    <?= $msg1; ?>
                    <br />
                </span></td>
        </tr>

        <tr>
            <td width="25%"><span class="style1"><b>Tag do clã</b>:</span></td>
            <td><input name="tag" type="text" value="<?= $_POST['tag'] ?? null; ?>" /></td>
        </tr>
        <tr>
            <td colspan="2"><span class="style1">Abreviação do seu clã. <i>Ex: DK</i><br />
                    <?= $msg2; ?>
                    <br />
                </span></td>
        </tr>

        <tr>
            <td width="25%"><span class="style1"><b>Descrição do clã</b>:</span></td>
            <td>
                <script>
                    edToolbar('blurb');
                </script><textarea name="blurb" id="blurb" rows="12" class="ed"><?= $_POST['blurb'] ?? null; ?></textarea><br>Máximo 5000 caracteres.<br />
                <?= $msg3; ?>
                <br />
            </td>
        </tr>

        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="register" value="Criar Clã">
            </td>
            <br />
        </tr>
    </table>
</form>
<p />

<?php include(__DIR__ . "/templates/private_footer.php"); ?>