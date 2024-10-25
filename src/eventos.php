<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Editar perfil");
$player = check_user($db);

$error = 0;

include(__DIR__ . "/templates/private_header.php");

if ($player->gm_rank > 4) {
    if ($_POST['submit1']) {
        if (!$_POST['endlotto'] || !$_POST['winid'] && !$_POST['winid2'] || !$_POST['preco']) {
            $errmsg1 .= "Por favor preencha todos os campos!";
            $error = 1;
        } elseif ($_POST['endlotto'] < (time() + 82800)) {
            $errmsg1 .= "O tempo de premiação é muito curto";
            $error = 1;
        } elseif ($_POST['winid'] == 0 && $_POST['winid2'] < 5000) {
            $errmsg1 .= "Selecione um prêmio melhor.";
            $error = 1;
        } elseif ($_POST['preco'] < 1 || $_POST['preco'] > 100000) {
            $errmsg1 .= "O preço do ticket é muito caro!";
            $error = 1;
        }

        if ($error == 0) {

            $premiacao = $_POST['winid'] > 0 && $_POST['winid'] < 1000 ? $_POST['winid'] : $_POST['winid2'];

            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endlotto'], "end_lotto_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$premiacao, "win_id_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco'], "lottery_price_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "lottery_1"]);

            $msg1 .= "Você começou uma loteria com sucesso.<br/><a href=\"eventos.php\">Voltar</a>.";
        }
    } elseif ($_POST['submit2']) {
        if (!$_POST['endlotto'] || !$_POST['winid'] && !$_POST['winid2'] || !$_POST['preco']) {
            $errmsg1 .= "Por favor preencha todos os campos!";
            $error = 1;
        } elseif ($_POST['endlotto'] < (time() + 82800)) {
            $errmsg1 .= "O tempo de premiação é muito curto";
            $error = 1;
        } elseif ($_POST['winid'] == 0 && $_POST['winid2'] < 5000) {
            $errmsg1 .= "Selecione um prêmio melhor.";
            $error = 1;
        } elseif ($_POST['preco'] < 1 || $_POST['preco'] > 100000) {
            $errmsg1 .= "O preço do ticket é muito caro!";
            $error = 1;
        }

        if ($error == 0) {

            $premiacao = $_POST['winid'] > 0 && $_POST['winid'] < 1000 ? $_POST['winid'] : $_POST['winid2'];

            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endlotto'], "end_lotto_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$premiacao, "win_id_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco'], "lottery_price_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "lottery_2"]);

            $msg1 .= "Você começou uma loteria com sucesso.<br/><a href=\"eventos.php\">Voltar</a>.";
        }
    } elseif ($_POST['submit3']) {
        if (!$_POST['endtour'] || !$_POST['premo1'] || !$_POST['premo2'] || !$_POST['premo3'] || !$_POST['premo4'] || !$_POST['premo5'] || !$_POST['preco1'] || !$_POST['preco2'] || !$_POST['preco3'] || !$_POST['preco4'] || !$_POST['preco5']) {
            $errmsg2 .= "Por favor preencha todos os campos!";
            $error = 1;
        } elseif ($_POST['endtour'] < (time() + 82800)) {
            $errmsg2 .= "O tempo para o inicio do torneio é muito curto";
            $error = 1;
        } elseif ($_POST['premo1'] < 1 || $_POST['premo1'] > 99999999) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        } elseif ($_POST['premo2'] < 1 || $_POST['premo2'] > 99999999) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        } elseif ($_POST['premo3'] < 1 || $_POST['premo3'] > 99999999) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        } elseif ($_POST['premo4'] < 1 || $_POST['premo4'] > 99999999) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        } elseif ($_POST['premo5'] < 1 || $_POST['premo5'] > 99999999) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        } elseif ($_POST['preco1'] < 1 || $_POST['preco1'] > 100000) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        } elseif ($_POST['preco2'] < 1 || $_POST['preco2'] > 100000) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        } elseif ($_POST['preco3'] < 1 || $_POST['preco3'] > 100000) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        } elseif ($_POST['preco4'] < 1 || $_POST['preco4'] > 100000) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        } elseif ($_POST['preco5'] < 1 || $_POST['preco5'] > 100000) {
            $errmsg2 .= "O preço para ingressar no torneio é muito caro!";
            $error = 1;
        }

        if ($error == 0) {

            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_1_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_2_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_3_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_4_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_5_1"]);

            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_1_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_2_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_3_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_4_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], "end_tour_5_2"]);


            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo1'], "tour_win_1_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo2'], "tour_win_2_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo3'], "tour_win_3_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo4'], "tour_win_4_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo5'], "tour_win_5_1"]);

            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo1'], "tour_win_1_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo2'], "tour_win_2_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo3'], "tour_win_3_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo4'], "tour_win_4_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo5'], "tour_win_5_2"]);


            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco1'], "tour_price_1_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco2'], "tour_price_2_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco3'], "tour_price_3_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco4'], "tour_price_4_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco5'], "tour_price_5_1"]);

            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco1'], "tour_price_1_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco2'], "tour_price_2_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco3'], "tour_price_3_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco4'], "tour_price_4_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco5'], "tour_price_5_2"]);


            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_1_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_2_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_3_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_4_1"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_5_1"]);

            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_1_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_2_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_3_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_4_2"]);
            $query = $db->execute("update `settings` set `value`=? where `name`=?", ["t", "tournament_5_2"]);

            $msg2 .= "Você começou um torneio com sucesso.<br/><a href=\"eventos.php\">Voltar</a>.";
        }
    }

?>

    <fieldset>
        <legend><b>Adicionar Loteria - Servidor 1</b></legend>
        <?php
        if ($setting->lottery_1 != "t") {
        ?>
            <table width="100%">
                <form method="POST" action="eventos.php">
                    <tr>
                        <td width="30%"><b>Acaba as</b>:</td>
                        <td><input type="text" name="endlotto" value="<?= $setting->end_lotto_1 ?>" size="20" /></td>
                    </tr>

                    <tr>
                        <td width="30%"><b>Prêmio Item</b>:</td>
                        <td>
                            <?php
                            $itemsid = $db->execute("select `id`, `name` from `blueprint_items`");
                            echo '<select name="winid"><option value="0">Selecione</option>';
                            while ($result = $itemsid->fetchrow()) {
                                echo sprintf('<option value="%s">%s</option>', $result["id"], $result["name"]);
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="30%"><b>Prêmio Ouro</b>:</td>
                        <td><input type="text" name="winid2" value="<?= $setting->win_id_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Preço Ticket</b>:</td>
                        <td><input type="text" name="preco" value="<?= $setting->lottery_price_1 ?>" size="20" /></td>
                    </tr>

                    <tr>
                        <td colspan="2" align="center"><input type="submit" name="submit1" value="Iniciar Loteria"></td>
                    </tr>
            </table>
            </form>
        <?php
        } else {
            echo "<br/><center><b>A loteria está acontecendo neste momento.</b></center>";
        }
        ?>
        <br />
        <center><b>
                <font color=green><?= $msg1 ?></font>
            </b></center>
        <br />
        <center><b>
                <font color=red><?= $errmsg1 ?></font>
            </b></center>
    </fieldset>

    <br /><br />

    <fieldset>
        <legend><b>Adicionar Loteria - Servidor 2</b></legend>
        <?php
        if ($setting->lottery_2 != "t") {
        ?>
            <table width="100%">
                <form method="POST" action="eventos.php">
                    <tr>
                        <td width="30%"><b>Acaba as</b>:</td>
                        <td><input type="text" name="endlotto" value="<?= $setting->end_lotto_1 ?>" size="20" /></td>
                    </tr>

                    <tr>
                        <td width="30%"><b>Prêmio Item</b>:</td>
                        <td>
                            <?php
                            $itemsid = $db->execute("select `id`, `name` from `blueprint_items`");
                            echo '<select name="winid"><option value="0">Selecione</option>';
                            while ($result = $itemsid->fetchrow()) {
                                echo sprintf('<option value="%s">%s</option>', $result["id"], $result["name"]);
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="30%"><b>Prêmio Ouro</b>:</td>
                        <td><input type="text" name="winid2" value="<?= $setting->win_id_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Preço Ticket</b>:</td>
                        <td><input type="text" name="preco" value="<?= $setting->lottery_price_1 ?>" size="20" /></td>
                    </tr>

                    <tr>
                        <td colspan="2" align="center"><input type="submit" name="submit2" value="Iniciar Loteria"></td>
                    </tr>
            </table>
            </form>
        <?php
        } else {
            echo "<br/><center><b>A loteria está acontecendo neste momento.</b></center>";
        }
        ?>
        <br />
        <center><b>
                <font color=green><?= $msg1 ?></font>
            </b></center>
        <br />
        <center><b>
                <font color=red><?= $errmsg1 ?></font>
            </b></center>
    </fieldset>

    <br /><br />

    <fieldset>
        <legend><b>Adicionar Torneio</b></legend>
        <?php
        if ($setting->tournament_1_1 != "t") {
        ?>
            <table width="100%">
                <form method="POST" action="eventos.php">
                    <tr>
                        <td width="30%"><b>Começa</b>:</td>
                        <td><input type="text" name="endtour" value="<?= $setting->end_tour_1_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Prêmio - Tier 1</b>:</td>
                        <td><input type="text" name="premo1" value="<?= $setting->tour_win_1_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Prêmio - Tier 2</b>:</td>
                        <td><input type="text" name="premo2" value="<?= $setting->tour_win_2_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Prêmio - Tier 3</b>:</td>
                        <td><input type="text" name="premo3" value="<?= $setting->tour_win_3_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Prêmio - Tier 4</b>:</td>
                        <td><input type="text" name="premo4" value="<?= $setting->tour_win_4_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Prêmio - Tier 5</b>:</td>
                        <td><input type="text" name="premo5" value="<?= $setting->tour_win_5_1 ?>" size="20" /></td>
                    </tr>

                    <tr>
                        <td width="30%"><b>Preço - Tier 1</b>:</td>
                        <td><input type="text" name="preco1" value="<?= $setting->tour_price_1_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Preço - Tier 2</b>:</td>
                        <td><input type="text" name="preco2" value="<?= $setting->tour_price_2_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Preço - Tier 3</b>:</td>
                        <td><input type="text" name="preco3" value="<?= $setting->tour_price_3_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Preço - Tier 4</b>:</td>
                        <td><input type="text" name="preco4" value="<?= $setting->tour_price_4_1 ?>" size="20" /></td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Preço - Tier 5</b>:</td>
                        <td><input type="text" name="preco5" value="<?= $setting->tour_price_5_1 ?>" size="20" /></td>
                    </tr>

                    <tr>
                        <td colspan="2" align="center"><input type="submit" name="submit3" value="Iniciar Torneio"></td>
                    </tr>
            </table>
            </form>
        <?php
        } else {
            echo "<br/><center><b>O torneio está acontecendo neste momento.</b></center>";
        }
        ?>
        <br />
        <center><b>
                <font color=green><?= $msg2 ?></font>
            </b></center>
        <br />
        <center><b>
                <font color=red><?= $errmsg2 ?></font>
            </b></center>
    </fieldset>

<?php
} else {
    echo "Você não pode acessar esta página.<br/><a href=\"home.php\">Voltar</a>.";
}

include(__DIR__ . "/templates/private_footer.php");
?>