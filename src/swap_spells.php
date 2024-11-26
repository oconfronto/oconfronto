<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);
header("Content-Type: text/html; charset=utf-8", true);

// Checks if the action is 'extend'
if (isset($_GET['estender'])) {
    $magiascount = $db->execute("select * from `magias` where `player_id`=?", [$player->id]);
    if ($magiascount->recordcount() < 11) {
        echo "<br/><center>Apenas jogadores que possuem todas as magias liberadas podem estender sua mana.</center><br/><br/>";
        exit;
    }

    if ($player->magic_points > 0) {
        $db->execute("update `players` set `mana`=`maxmana`+2, `maxmana`=`maxmana`+2, `extramana`=`extramana`+2, `magic_points`=`magic_points`-1 where `id`=?", [$player->id]);
        $player = check_user($db);
        echo '<br/><center><img src="static/images/man.png"><img src="static/bargen.php?man">';
        if ($player->magic_points > 0) {
            echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_spells.php?estender=true', 'maxmana')\"><img src=\"static/images/addstat.png\" border=\"0px\"></a>";
        } else {
            echo '<img src="static/images/none.png" border="0px">';
        }

        echo "</center>";
        echo "<center><font size=\"1px\">Estenda 2 pontos da sua mana<br/>máxima por 1 ponto místico.<br/><br/><b>Você " . $player->magic_points . " tem ponto(s) místico(s).</b></font></center>";
        exit;
    }
} elseif (isset($_GET['use'])) {
    // Checks if 'spell' is defined and is numeric
    if (isset($_GET['spell']) && is_numeric($_GET['spell'])) {
        $getid = ceil((float)$_GET['spell']);
        $magic = $db->execute("select * from `magias` where `id`=? and `player_id`=?", [$getid, $player->id]);

        if ($magic->recordcount() == 1) {
            $magia = $magic->fetchrow();
            if ($magia['used'] == 'f') {
                // Activate the magic
                $result = $db->execute("update `magias` set `used`='t' where `id`=? and `player_id`=?", [$getid, $player->id]);
                echo $result ? "Habilidade ativada com sucesso." : "Erro ao ativar a habilidade.";
            } else {
                // Disable magic
                $result = $db->execute("update `magias` set `used`='f' where `id`=? and `player_id`=?", [$getid, $player->id]);
                echo $result ? "Habilidade desativada com sucesso." : "Erro ao desativar a habilidade.";
            }
            header("Location: showspells.php?voltar=true");
            exit;
        } else {
            echo "Feitiço inválido ou não encontrado.";
            exit;
        }
    } else {
        echo "Feitiço inválido.";
        exit;
    }
} elseif (isset($_GET['act'])) {
    if (isset($_GET['spell']) && is_numeric($_GET['spell'])) {
        $getid = (int)$_GET['spell'];  // Convert directly to integer for spell ID
        $magic = $db->execute("select * from `blueprint_magias` where `id`=?", [$getid]);
    } else {
        echo "Feitiço inválido.";
        exit;
    }   

    if (isset($_GET['spell']) && $_GET['confirm'] != 'yes' && is_numeric($_GET['spell']) && $magic->recordcount() == 1) {
        $magic2 = $db->execute("select * from `magias` where `magia_id`=? and `player_id`=?", [$getid, $player->id]);
        if ($magic2->recordcount() > 0) {
            echo "Você já possui esse feitiço. <a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('showspells.php?voltar=true', 'comfirm')\">Voltar</a>.";
            exit;
        }

        $magia = $magic->fetchrow();
        echo "<b>" . $magia['nome'] . ":</b> " . $magia['descri'] . "<br/>";
        echo "Deseja comprar o feitiço <b>" . $magia['nome'] . "</b> por <b>" . $magia['cost'] . "</b> pontos místicos?<br/><br/>";
        echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_spells.php?act=buy&spell=" . $getid . "&confirm=yes', 'comfirm')\">Sim</a> | <a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('showspells.php?voltar=true', 'comfirm')\">Não</a>";
    } elseif (isset($_GET['spell']) && $_GET['confirm'] == 'yes' && is_numeric($_GET['spell']) && $magic->recordcount() == 1) {
        $magic2 = $db->execute("select * from `magias` where `magia_id`=? and `player_id`=?", [$getid, $player->id]);
        if ($magic2->recordcount() > 0) {
            echo "Você já possui esse feitiço. <a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('showspells.php?voltar=true', 'comfirm')\">Voltar</a>.";
            exit;
        }

        $magia = $magic->fetchrow();
        if ($magia['precisa'] != 'f') {
            $nescecita = explode(", ", (string) $magia['precisa']);
            $soma = 0;
            foreach ($nescecita as $necessidade) {
                $verifica = $db->execute("select * from `magias` where `magia_id`=? and `player_id`=?", [$necessidade, $player->id]);
                $soma += $verifica->recordcount();
            }

            if ($soma < count($nescecita)) {
                echo "Você precisa comprar os feitiços anteriores antes de comprar o feitiço <b>" . $magia['nome'] . "</b>. <a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('showspells.php?voltar=true', 'comfirm')\">Voltar</a>.";
                exit;
            }
        }

        if ($magia['cost'] > $player->magic_points) {
            echo "Você não possui pontos místicos suficientes para comprar este feitiço.<br/>Você ganha 1 ponto místico a cada nível que passa. <a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('showspells.php?voltar=true', 'comfirm')\">Voltar</a>.";
            exit;
        }

        $insert['player_id'] = $player->id;
        $insert['magia_id'] = $getid;
        $db->autoexecute('magias', $insert, 'INSERT');
        $db->execute("update `players` set `magic_points`=? where `id`=?", [$player->magic_points - $magia['cost'], $player->id]);

        $tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=5 and `player_id`=?", [$player->id]);
        if ($getid == 4 && $tutorial->recordcount() > 0) {
            echo "Você acaba de comprar o feitiço <b>" . $magia['nome'] . "</b> por <b>" . $magia['cost'] . "</b> pontos místicos.<br/><a href=\"start.php?act=6\"><b>Continuar Tutorial</b></a>.";
            exit;
        }

        echo "Você acaba de comprar o feitiço <b>" . $magia['nome'] . "</b> por <b>" . $magia['cost'] . "</b> pontos místicos. <a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('showspells.php?voltar=true', 'comfirm')\">Voltar</a>.";
        exit;
    }
}