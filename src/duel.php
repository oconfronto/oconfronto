<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Duelos");
$player = check_user($db);

$checabattalha = $db->execute("select `hp` from `bixos` where `player_id`=? and `type`!=98 and `type`!=99", [$player->id]);
$verificaLuta = $db->execute("select `id` from `duels` where `status`='s' and (`p_id`=? or `e_id`=?)", [$player->id, $player->id]);
if ($checabattalha->recordcount() > 0) {
    if ($_GET['nolayout']) {
        header("Location: monster.php?act=attack&nolayout=true");
    } else {
        header("Location: monster.php?act=attack");
    }

    exit;
}

include(__DIR__ . "/checkwork.php");

if ($_GET['start']) {
    $verificaLuta = $db->execute("select `id` from `duels` where `status`!='w' and `status`!='z' and (`p_id`=? or `e_id`=?)", [$player->id, $player->id]);
    if ($verificaLuta->recordcount() > 0) {
        if ($_GET['nolayout']) {
            header("Location: duel.php?luta=true&new=true&nolayout=true");
        } else {
            header("Location: duel.php?luta=true&new=true");
        }

        exit;
    }

    $checkDuelStart = $db->execute("select `id` from `duels` where `status`='w' and `id`=? and (`p_id`=? or `e_id`=?)", [$_GET['start'], $player->id, $player->id]);
    if ($checkDuelStart->recordcount() > 0) {
        $db->execute("update `duels` set `status`='t' where `id`=?", [$_GET['start']]);
        if ($_GET['nolayout']) {
            header("Location: duel.php?luta=true&new=true&nolayout=true");
        } else {
            header("Location: duel.php?luta=true&new=true");
        }

        exit;
    }

    if (!$_GET['nolayout']) {
        include(__DIR__ . "/templates/private_header.php");
    } else {
        header("Content-Type: text/html; charset=utf-8", true);
    }

    echo "Desafio não encontrado.<br/><a href=\"duel.php\">Voltar</a>.";
    if (!$_GET['nolayout']) {
        include(__DIR__ . "/templates/private_footer.php");
    }

    exit;
}

if ($_GET['luta'] || $verificaLuta->recordcount() > 0) {
    $verificaLuta = $db->execute("select * from `duels` where `status`!='w' and (`p_id`=? or `e_id`=?) order by `status` asc, `id` desc limit 1", [$player->id, $player->id]);
    if ($verificaLuta->recordcount() == 0) {
        if ($_GET['nolayout']) {
            header("Location: duel.php?nolayout=true");
        } else {
            header("Location: duel.php");
        }

        exit;
    }

    $luta = $verificaLuta->fetchrow();

    if ($luta['status'] == 'z' && $_GET['new']) {
        $db->execute("delete from `duels` where `id`=?", [$luta['id']]);
        if ($_GET['nolayout']) {
            header("Location: duel.php?luta=true&new=true&nolayout=true");
        } else {
            header("Location: duel.php?luta=true&new=true");
        }

        exit;
    }

    if ($luta['p_id'] == $player->id) {
        $getEnemy = $db->execute("select * from `players` where `id`=?", [$luta['e_id']]);
    } else {
        $getEnemy = $db->execute("select * from `players` where `id`=?", [$luta['p_id']]);
    }

    $enemy1 = $getEnemy->fetchrow();
    foreach ($enemy1 as $key => $value) {
        $enemy->$key = $value;
    }

    $type = $player->id == $luta['p_id'] ? $luta['p_type'] : $luta['e_type'];

    if ($luta['status'] != 'z' && $luta['status'] != 's') {
        if ($player->hp <= 0 && $type != 99) {
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_header.php");
            } else {
                header("Content-Type: text/html; charset=utf-8", true);
            }

            echo "Você está morto! <a href=\"duel.php\"/>Voltar</a>.";
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_footer.php");
            }

            exit;
        }

        if ($player->energy < 10 && $type != 99) {
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_header.php");
            } else {
                header("Content-Type: text/html; charset=utf-8", true);
            }

            echo "Você não tem energia suficiente! <a href=\"duel.php\"/>Voltar</a>.";
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_footer.php");
            }

            exit;
        }

        if ($enemy->energy < 10 && $type != 99) {
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_header.php");
            } else {
                header("Content-Type: text/html; charset=utf-8", true);
            }

            echo "Seu inimigo não tem energia suficiente! <a href=\"duel.php\"/>Voltar</a>.";
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_footer.php");
            }

            exit;
        }

        if ($enemy->hp <= 0 && $type != 99) {
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_header.php");
            } else {
                header("Content-Type: text/html; charset=utf-8", true);
            }

            echo "Este usuário está morto! <a href=\"duel.php\"/>Voltar</a>.";
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_footer.php");
            }

            exit;
        }

        if ($enemy->ban > time() && $type != 99) {
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_header.php");
            } else {
                header("Content-Type: text/html; charset=utf-8", true);
            }

            echo "Este usuário está banido! <a href=\"duel.php\"/>Voltar</a>.";
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_footer.php");
            }

            return;
        }

        $checkenyrowk = $db->GetOne("select `status` from `work` where `player_id`=? order by `start` DESC", [$enemy->id]);
        $checkenyhunt = $db->GetOne("select `status` from `hunt` where `player_id`=? order by `start` DESC", [$enemy->id]);
        if ($type != 99 && (($checkenyrowk == "t" || $checkenyhunt == "t") && $enemy->tour == 'f')) {
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_header.php");
            } else {
                header("Content-Type: text/html; charset=utf-8", true);
            }

            echo "Você não encontrou o usuário " . $enemy->username . "! Ele deve estar trabalhando ou caçando. <a href=\"duel.php\">Voltar</a>.";
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_footer.php");
            }

            return;
        }

        $duelCheckOnline = $db->execute("select `id` from `user_online` where `player_id`=?", [$enemy->id]);
        if ($duelCheckOnline->recordcount() == 0 && $type != 99) {
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_header.php");
            } else {
                header("Content-Type: text/html; charset=utf-8", true);
            }

            echo "" . $enemy->username . " está offline e não pode duelar. <a href=\"duel.php\">Voltar</a>.";
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_footer.php");
            }

            return;
        }
    }

    if ($luta['status'] == 't') {
        $db->execute("update `duels` set `status`=?, `timeout`=? where `id`=?", [$enemy->id, time() + 45, $luta['id']]);
        $verificaLuta = $db->execute("select * from `duels` where `id`=?", [$luta['id']]);
        $luta = $verificaLuta->fetchrow();
    }

    if (is_numeric($luta['status']) && $luta['status'] != $player->id) {
        unset($_SESSION['statusduellog']);
        if ($luta['timeout'] > time()) {
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_header.php");
            } else {
                header("Content-Type: text/html; charset=utf-8", true);
            }

            echo '<script type="text/javascript">';
            echo "setTimeout(function() { Ajax('duel.php?luta=true&nolayout=true', 'battle'); }, 1000);";
            echo "</script>";

            echo '<div id="swap"></div><div id="battle">';
            echo showAlert('<table width="100%"><tr><td width="50%" align="left">Aguardando oponente...</td><td width="50%" align="right">( ' . ($luta['timeout'] - time()) . " )</td></tr></table>");
            echo "<br/><center><i>Seu oponente deve estar online, e aceitar o desafio nos próximos 45 segundos.</i></center>";
            echo "</div>";
            if (!$_GET['nolayout']) {
                include(__DIR__ . "/templates/private_footer.php");
            }

            /* include("templates/private_header.php");
            echo showAlert("<table width=\"100%\"><tr><td width=\"50%\" align=\"left\">Aguardando oponente...</td><td width=\"50%\" align=\"right\"><div id=\"counter\" align=\"right\"></div></td></tr></table>");
            echo "<script type=\"text/javascript\">";
            echo "javascript_countdown.init('" . ($luta['timeout'] - time()) . "', 'counter');";
            echo "</script>";
            
            echo "<br/><center><i>Seu oponente deve estar online, e aceitar o desafio nos próximos 45 segundos.</i></center>";
            include("templates/private_footer.php"); */
            exit;
        }

        $db->execute("update `duels` set `status`='w', `timeout`='0' where `id`=?", [$luta['id']]);
        if ($_GET['nolayout']) {
            header("Location: duel.php?error=noresponse&nolayout=true");
        } else {
            header("Location: duel.php?error=noresponse");
        }

        exit;
    }

    if ($luta['status'] != 's' && $luta['status'] != 'z') {
        unset($_SESSION['statusduellog']);
        $db->execute("update `duels` set `status`='s', `timeout`=? where `id`=?", [time() + 30, $luta['id']]);
        $verificaLuta = $db->execute("select * from `duels` where `id`=?", [$luta['id']]);
        $luta = $verificaLuta->fetchrow();
    }

    if ($luta['status'] == 's') {
        //Get enemy's bonuses from equipment
        $query = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", [$enemy->id]);
        $enemy->atkbonus = ($query->recordcount() == 1) ? $query->fetchrow() : 0;
        $query50 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", [$enemy->id]);
        $enemy->defbonus1 = ($query50->recordcount() == 1) ? $query50->fetchrow() : 0;
        $query51 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", [$enemy->id]);
        $enemy->defbonus2 = ($query51->recordcount() == 1) ? $query51->fetchrow() : 0;
        $query52 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", [$enemy->id]);
        $enemy->defbonus3 = ($query52->recordcount() == 1) ? $query52->fetchrow() : 0;
        $query54 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='shield' and items.status='equipped'", [$enemy->id]);
        $enemy->defbonus5 = ($query54->recordcount() == 1) ? $query54->fetchrow() : 0;
        $query55 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", [$enemy->id]);
        $enemy->agibonus6 = ($query55->recordcount() == 1) ? $query55->fetchrow() : 0;

        $enybonusfor = 0;
        $enybonusagi = 0;
        $enybonusres = 0;
        $countstats = $db->query("select `for`, `vit`, `agi`, `res` from `items` where `player_id`=? and `status`='equipped'", [$enemy->id]);
        while ($count = $countstats->fetchrow()) {
            $enybonusfor += $count['for'];
            $enybonusagi += $count['agi'];
            $enybonusres += $count['res'];
        }

        $everificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", [$enemy->id, time()]);
        if ($everificpotion->recordcount() > 0) {
            $selct = $everificpotion->fetchrow();
            $getpotion = $db->execute("select * from `for_use` where `item_id`=?", [$selct['item_id']]);
            $potbonus = $getpotion->fetchrow();
            $enemy->strength = ceil($enemy->strength + (($enemy->strength / 100) * ($potbonus['for'])));
            $enemy->vitality = ceil($enemy->vitality + (($enemy->vitality / 100) * ($potbonus['vit'])));
            $enemy->agility = ceil($enemy->agility + (($enemy->agility / 100) * ($potbonus['agi'])));
            $enemy->resistance = ceil($enemy->resistance + (($enemy->resistance / 100) * ($potbonus['res'])));
        }

        //Get player's bonuses from equipment
        $query = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", [$player->id]);
        $player->atkbonus = ($query->recordcount() == 1) ? $query->fetchrow() : 0;
        $query50 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", [$player->id]);
        $player->defbonus1 = ($query50->recordcount() == 1) ? $query50->fetchrow() : 0;
        $query51 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", [$player->id]);
        $player->defbonus2 = ($query51->recordcount() == 1) ? $query51->fetchrow() : 0;
        $query52 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", [$player->id]);
        $player->defbonus3 = ($query52->recordcount() == 1) ? $query52->fetchrow() : 0;
        $query54 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='shield' and items.status='equipped'", [$player->id]);
        $player->defbonus5 = ($query54->recordcount() == 1) ? $query54->fetchrow() : 0;
        $query55 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", [$player->id]);
        $player->agibonus6 = ($query55->recordcount() == 1) ? $query55->fetchrow() : 0;

        $pbonusfor = 0;
        $pbonusagi = 0;
        $pbonusres = 0;
        $countstats = $db->query("select `for`, `vit`, `agi`, `res` from `items` where `player_id`=? and `status`='equipped'", [$player->id]);
        while ($count = $countstats->fetchrow()) {
            $pbonusfor += $count['for'];
            $pbonusagi += $count['agi'];
            $pbonusres += $count['res'];
        }

        $pverificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", [$player->id, time()]);
        if ($pverificpotion->recordcount() > 0) {
            $selct = $pverificpotion->fetchrow();
            $getpotion = $db->execute("select * from `for_use` where `item_id`=?", [$selct['item_id']]);
            $potbonus = $getpotion->fetchrow();
            $player->strength = ceil($player->strength + (($player->strength / 100) * ($potbonus['for'])));
            $player->vitality = ceil($player->vitality + (($player->vitality / 100) * ($potbonus['vit'])));
            $player->agility = ceil($player->agility + (($player->agility / 100) * ($potbonus['agi'])));
            $player->resistance = ceil($player->resistance + (($player->resistance / 100) * ($potbonus['res'])));
        }


        $checamagiastatus = $db->execute("select * from `magias` where `magia_id`=5 and `player_id`=?", [$player->id]);
        if ($player->voc == 'archer') {
            if ($checamagiastatus->recordcount() > 0) {
                $varataque = 0.31;
                $vardefesa = 0.15;
                $vardivide = 0.14;
            } else {
                $varataque = 0.29;
                $vardefesa = 0.14;
                $vardivide = 0.13;
            }
        } elseif ($player->voc == 'mage') {
            if ($checamagiastatus->recordcount() > 0) {
                $varataque = 0.265;
                $vardefesa = 0.15;
                $vardivide = 0.14;
            } else {
                $varataque = 0.245;
                $vardefesa = 0.14;
                $vardivide = 0.13;
            }
        } elseif ($player->voc == 'knight') {
            if ($checamagiastatus->recordcount() > 0) {
                $varataque = 0.22;
                $vardefesa = 0.17;
                $vardivide = 0.15;
            } else {
                $varataque = 0.20;
                $vardefesa = 0.16;
                $vardivide = 0.14;
            }
        }


        if ($player->promoted == 'f') {
            $multipleatk = 1 + ($varataque * 1.6);
            $multipledef = 1 + ($vardefesa * 1.6);
            $divideres = 2.3 - ($vardivide * 1.6);
        } elseif ($player->promoted == 't') {
            $multipleatk = 1 + ($varataque * 2.4);
            $multipledef = 1 + ($vardefesa * 2.4);
            $divideres = 2.3 - ($vardivide * 2.4);
        } elseif ($player->promoted == 'p') {
            $multipleatk = 1 + ($varataque * 3.2);
            $multipledef = 1 + ($vardefesa * 3.2);
            $divideres = 2.3 - ($vardivide * 3.2);
        } else {
            echo "um erro foi encontrado em seu personagem, contate o administrador.";
            exit;
        }

        $enychecamagiastatus = $db->execute("select * from `magias` where `magia_id`=5 and `player_id`=?", [$enemy->id]);
        if ($enemy->voc == 'archer') {
            if ($enychecamagiastatus->recordcount() > 0) {
                $varataque = 0.31;
                $vardefesa = 0.13;
                $vardivide = 0.13;
            } else {
                $varataque = 0.29;
                $vardefesa = 0.12;
                $vardivide = 0.12;
            }
        } elseif ($enemy->voc == 'mage') {
            if ($enychecamagiastatus->recordcount() > 0) {
                $varataque = 0.265;
                $vardefesa = 0.15;
                $vardivide = 0.14;
            } else {
                $varataque = 0.245;
                $vardefesa = 0.14;
                $vardivide = 0.13;
            }
        } elseif ($enemy->voc == 'knight') {
            if ($enychecamagiastatus->recordcount() > 0) {
                $varataque = 0.22;
                $vardefesa = 0.17;
                $vardivide = 0.15;
            } else {
                $varataque = 0.20;
                $vardefesa = 0.16;
                $vardivide = 0.14;
            }
        }


        if ($enemy->promoted == 'f') {
            $enymultipleatk = 1 + ($varataque * 1.6);
            $enymultipledef = 1 + ($vardefesa * 1.6);
            $enydivideres = 2.3 - ($vardivide * 1.6);
        } elseif ($enemy->promoted == 't') {
            $enymultipleatk = 1 + ($varataque * 2.4);
            $enymultipledef = 1 + ($vardefesa * 2.4);
            $enydivideres = 2.3 - ($vardivide * 2.4);
        } elseif ($enemy->promoted == 'p') {
            $enymultipleatk = 1 + ($varataque * 3.2);
            $enymultipledef = 1 + ($vardefesa * 3.2);
            $enydivideres = 2.3 - ($vardivide * 3.2);
        } else {
            echo "um erro foi encontrado em seu personagem, contate o administrador.";
            exit;
        }

        //Calculate some variables that will be used
        $forcadoplayer = ceil(($player->strength + $player->atkbonus['effectiveness'] + ($player->atkbonus['item_bonus'] * 2)  + $pbonusfor) * $multipleatk);
        $agilidadedoplayer = ceil($player->agility + $player->agibonus6['effectiveness'] + ($player->agibonus6['item_bonus'] * 2) + $pbonusagi);
        $resistenciadoplayer = ceil(($player->resistance + ($player->defbonus1['effectiveness'] + $player->defbonus2['effectiveness'] + $player->defbonus3['effectiveness'] + $player->defbonus5['effectiveness']) + (($player->defbonus1['item_bonus'] * 2) + ($player->defbonus2['item_bonus'] * 2) + ($player->defbonus3['item_bonus'] * 2) + ($player->defbonus5['item_bonus'] * 2)) + $pbonusres) * $multipledef);

        $forcadoenemy = ceil(($enemy->strength + $enemy->atkbonus['effectiveness'] + ($enemy->atkbonus['item_bonus'] * 2) + $enybonusfor) * $enymultipleatk);
        $agilidadedoenemy = ceil($enemy->agility + $enemy->agibonus6['effectiveness'] + ($enemy->agibonus6['item_bonus'] * 2) + $enybonusagi);
        $resistenciadoenemy = ceil(($enemy->resistance + ($enemy->defbonus1['effectiveness'] + $enemy->defbonus2['effectiveness'] + $enemy->defbonus3['effectiveness'] + $enemy->defbonus5['effectiveness']) + (($enemy->defbonus1['item_bonus'] * 2) + ($enemy->defbonus2['item_bonus'] * 2) + ($enemy->defbonus3['item_bonus'] * 2) + ($enemy->defbonus5['item_bonus'] * 2)) + $enybonusres) * $enymultipledef);

        $enemy->strdiff = (($forcadoenemy - $forcadoplayer) > 0) ? ($forcadoenemy - $forcadoplayer) : 0;
        $enemy->resdiff = (($resistenciadoenemy - ($resistenciadoplayer * 1.5)) > 0) ? ($resistenciadoenemy - $resistenciadoplayer) : 0;
        $enemy->agidiff = (($agilidadedoenemy - $agilidadedoplayer) > 0) ? ($agilidadedoenemy - $agilidadedoplayer) : 0;
        $enemy->leveldiff = (($enemy->level - $player->level) > 0) ? ($enemy->level - $player->level) : 0;
        $player->strdiff = (($forcadoplayer - $forcadoenemy) > 0) ? ($forcadoplayer - $forcadoenemy) : 0;
        $player->resdiff = (($resistenciadoplayer - $resistenciadoenemy) > 0) ? ($resistenciadoplayer - $resistenciadoenemy) : 0;
        $player->agidiff = (($agilidadedoplayer - $agilidadedoenemy) > 0) ? ($agilidadedoplayer - $agilidadedoenemy) : 0;
        $player->leveldiff = (($player->level - $enemy->level) > 0) ? ($player->level - $enemy->level) : 0;
        $totalstr = $forcadoenemy + $forcadoplayer;
        $totalres = $resistenciadoenemy + $resistenciadoplayer;
        $totalagi = $agilidadedoenemy + $agilidadedoplayer;
        $totallevel = $enemy->level + $player->level;


        //Calculate the damage to be dealt by each player (dependent on strength and agility)
        $enemy->maxdmg = ceil($forcadoenemy - ($resistenciadoplayer / $divideres));
        $enemy->maxdmg -= intval($enemy->maxdmg * ($player->leveldiff / $totallevel));
        $enemy->maxdmg = ($enemy->maxdmg <= 2) ? 2 : $enemy->maxdmg; //Set 2 as the minimum damage
        $enemy->mindmg = (($enemy->maxdmg - 4) < 1) ? 1 : ($enemy->maxdmg - 4); //Set a minimum damage range of maxdmg-4
        $player->maxdmg = ceil($forcadoplayer - ($resistenciadoenemy / $enydivideres));
        $player->maxdmg -= intval($player->maxdmg * ($enemy->leveldiff / $totallevel));
        $player->maxdmg = ($player->maxdmg <= 2) ? 2 : $player->maxdmg; //Set 2 as the minimum damage
        $player->mindmg = (($player->maxdmg - 4) < 1) ? 1 : ($player->maxdmg - 4); //Set a minimum damage range of maxdmg-4

        //Calculate battle 'combos' - how many times in a row a player can attack (dependent on agility)
        $enemy->combo = ceil($agilidadedoenemy / $agilidadedoplayer);
        $enemy->combo = ($enemy->combo > 3) ? 3 : $enemy->combo;
        $player->combo = ceil($agilidadedoplayer / $agilidadedoenemy);
        $player->combo = ($player->combo > 3) ? 3 : $player->combo;

        //Calculate the chance to miss opposing player
        $enemy->miss = intval(($player->agidiff / $totalagi) * 100);
        $enemy->miss = ($enemy->miss > 12) ? 12 : $enemy->miss; //Maximum miss chance of 12% (possible to change in admin panel?)
        $enemy->miss = max(5, $enemy->miss); //Minimum miss chance of 5%
        $player->miss = intval(($enemy->agidiff / $totalagi) * 100);
        $player->miss = ($player->miss > 12) ? 12 : $player->miss; //Maximum miss chance of 12%
        $player->miss = max(5, $player->miss); //Minimum miss chance of 5%
    }

    $duellog = $luta['log'] == null || $luta['log'] == "" ? [] : unserialize($luta['log']);

    if ($player->hp > 0 && $enemy->hp > 0 && $luta['status'] != 'z') {
        if ($player->id == $luta['p_id'] && $luta['vez'] == 'p' || $player->id != $luta['p_id'] && $luta['vez'] == 'e') {
            $prox = $player->id == $luta['p_id'] ? "e" : "p";
            if ($type == 0 && $type != 95) {
                $otroatak = 5;
            } elseif ($type == 97) {
                include(__DIR__ . "/duel/atacahit.php");
            } elseif ($type == 96) {
                include(__DIR__ . "/duel/fugir.php");
            } elseif ($type == 1) {
                $checamagicum = $db->execute("select * from `magias` where `magia_id`=1 and `player_id`=?", [$player->id]);
                if ($checamagicum->recordcount() > 0) {
                    include(__DIR__ . "/duel/reforco.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            } elseif ($type == 2) {
                $checamagicdois = $db->execute("select * from `magias` where `magia_id`=2 and `player_id`=?", [$player->id]);
                if ($checamagicdois->recordcount() > 0) {
                    include(__DIR__ . "/duel/agressivo.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            } elseif ($type == 3) {
                $checamagitrei = $db->execute("select * from `magias` where `magia_id`=3 and `player_id`=?", [$player->id]);
                if ($checamagitrei->recordcount() > 0) {
                    include(__DIR__ . "/duel/triplohit.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            } elseif ($type == 4) {
                $checamagcuato = $db->execute("select * from `magias` where `magia_id`=4 and `player_id`=?", [$player->id]);
                if ($checamagcuato->recordcount() > 0) {
                    include(__DIR__ . "/duel/curar.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            } elseif ($type == 6) {
                $checamagiccivo = $db->execute("select * from `magias` where `magia_id`=6 and `player_id`=?", [$player->id]);
                if ($checamagiccivo->recordcount() > 0) {
                    include(__DIR__ . "/duel/defesatripla.php");
                } else {
                    include(__DIR__ . "/duele/atacahit.php");
                }
            } elseif ($type == 7) {
                $checamagicsies = $db->execute("select * from `magias` where `magia_id`=7 and `player_id`=?", [$player->id]);
                if ($checamagicsies->recordcount() > 0) {
                    include(__DIR__ . "/duel/resistencia.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            } elseif ($type == 8) {
                $checamagicsete = $db->execute("select * from `magias` where `magia_id`=8 and `player_id`=?", [$player->id]);
                if ($checamagicsete->recordcount() > 0) {
                    include(__DIR__ . "/duel/quintohit.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            } elseif ($type == 9) {
                $checamagicotho = $db->execute("select * from `magias` where `magia_id`=9 and `player_id`=?", [$player->id]);
                if ($checamagicotho->recordcount() > 0) {
                    include(__DIR__ . "/duel/defesaquinta.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            } elseif ($type == 10) {
                $checamagicumueve = $db->execute("select * from `magias` where `magia_id`=10 and `player_id`=?", [$player->id]);
                if ($checamagicumueve->recordcount() > 0) {
                    include(__DIR__ . "/duel/escudo.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            } elseif ($type == 11) {
                $checamagidiez = $db->execute("select * from `magias` where `magia_id`=11 and `player_id`=?", [$player->id]);
                if ($checamagidiez->recordcount() > 0) {
                    include(__DIR__ . "/duel/tontura.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            } elseif ($type == 12) {
                $checamagiconze = $db->execute("select * from `magias` where `magia_id`=12 and `player_id`=?", [$player->id]);
                if ($checamagiconze->recordcount() > 0) {
                    include(__DIR__ . "/duel/subita.php");
                } else {
                    include(__DIR__ . "/duel/atacahit.php");
                }
            }

            if (time() >= $luta['timeout']) {
                if ($luta['extra'] != $player->id) {
                    array_unshift($duellog, "14, " . $player->username . "");
                    $db->execute("update `duels` set `extra`=? where `id`=?", [$player->id, $luta['id']]);
                    $otroatak = 3;
                } else {
                    array_unshift($duellog, "13, " . $player->username . "");
                    $db->execute("update `duels` set `extra`='0' where `id`=?", [$luta['id']]);
                    $db->execute("update `players` set `hp`='0', `deadtime`=? where `id`=?", [time() + $setting->dead_time, $player->id]);
                    $morreu = 5;
                }
            }

            if ($otroatak != 5) {
                if ($otroatak != 3 && $luta['extra'] == $player->id) {
                    $db->execute("update `duels` set `extra`='0' where `id`=?", [$luta['id']]);
                }

                $db->execute("update `duels` set `p_type`='0', `e_type`='0', `vez`=?, `log`=?, `timeout`=? where `id`=?", [$prox, serialize($duellog), time() + 30, $luta['id']]);
                if ($player->id == $luta['p_id']) {
                    $db->execute("update `duels` set `p_turnos`=`p_turnos`-1 where `p_turnos`>0 and `id`=?", [$luta['id']]);
                } else {
                    $db->execute("update `duels` set `e_turnos`=`e_turnos`-1 where `e_turnos`>0 and `id`=?", [$luta['id']]);
                }

                $db->execute("update `duels` set `p_magia`='0' where `p_turnos`='0' and `id`=?", [$luta['id']]);
                $db->execute("update `duels` set `e_magia`='0' where `e_turnos`='0' and `id`=?", [$luta['id']]);
            }
        } elseif (time() >= $luta['timeout']) {
            if ($luta['extra'] != $enemy->id) {
                array_unshift($duellog, "14, " . $enemy->username . "");
                $db->execute("update `duels` set `extra`=? where `id`=?", [$enemy->id, $luta['id']]);

                $prox = $enemy->id == $luta['p_id'] ? "e" : "p";

                $db->execute("update `duels` set `p_type`='0', `e_type`='0', `vez`=?, `log`=?, `timeout`=? where `id`=?", [$prox, serialize($duellog), time() + 30, $luta['id']]);
                if ($player->id == $luta['p_id']) {
                    $db->execute("update `duels` set `p_turnos`=`p_turnos`-1 where `p_turnos`>0 and `id`=?", [$luta['id']]);
                } else {
                    $db->execute("update `duels` set `e_turnos`=`e_turnos`-1 where `e_turnos`>0 and `id`=?", [$luta['id']]);
                }

                $db->execute("update `duels` set `p_magia`='0' where `p_turnos`='0' and `id`=?", [$luta['id']]);
                $db->execute("update `duels` set `e_magia`='0' where `e_turnos`='0' and `id`=?", [$luta['id']]);
            } else {
                array_unshift($duellog, "13, " . $enemy->username . "");
                $db->execute("update `duels` set `extra`='0' where `id`=?", [$luta['id']]);
                $db->execute("update `players` set `hp`='0', `deadtime`=? where `id`=?", [time() + $setting->dead_time, $enemy->id]);
                $matou = 5;
            }
        }
    }

    if ($fugiu == 5) {
        $db->execute("update `duels` set `p_type`='99', `e_type`='99', `status`='z', `extra`=? where `id`=?", [$player->id, $luta['id']]);
        $output .= showAlert("<b>Você fugiu da luta com sucesso!</b>", "green");
        array_unshift($duellog, "13, " . $player->username . ", " . $enemy->username . "");
    }

    if (($player->hp < 1 || $morreu == 5) && $type != 99) {
        $exploss1 = $player->level * 7;
        $exploss2 = (($player->level - $enemy->level) > 0) ? ($enemy->level - $player->level) * 4 : 0;
        $exploss = $exploss1 + $exploss2;

        $output .= showAlert("<b>Você morreu!</b><br/>Você perdeu " . $exploss . " pontos de experiência.", "red");
        $db->execute("update `players` set `energy`=`energy`-?, `exp`=`exp`-?, `deaths`=`deaths`+1, `hp`=0, `mana`=0, `deadtime`=? where `id`=?", [10, $exploss, time() + $setting->dead_time, $player->id]);
        if ($player->id == $luta['p_id']) {
            $db->execute("update `duels` set `p_type`='99', `status`='z' where `id`=?", [$luta['id']]);
        } else {
            $db->execute("update `duels` set `e_type`='99', `status`='z' where `id`=?", [$luta['id']]);
        }
    }

    if (($enemy->hp < 1 || $matou == 5) && $type != 99) {
        $expwin1 = $enemy->level * 20;
        $expwin2 = (($player->level - $enemy->level) > 0) ? $expwin1 - (($player->level - $enemy->level) * 3) : $expwin1 + (($player->level - $enemy->level) * 3);
        $expwin2 = ($expwin2 <= 0) ? 1 : $expwin2;
        $expwin3 = round(0.9 * $expwin2);
        $expwin = ceil(random_int(intval($expwin3), intval($expwin2)));

        $output .= showAlert("<b>Você matou " . $enemy->username . "!</b><br/>Você ganhou " . $expwin . " pontos de experiência.", "green");

        if ($expwin + $player->exp >= maxExp($player->level)) //Player gained a level!
        {
            //Update player, gained a level
            $depoput .= "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><u><b>Você passou de nível!</b></u></div>";
            $newexp = $expwin + $player->exp - maxExp($player->level);

            $db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", [maxMana($player->level, $player->extramana), maxMana($player->level, $player->extramana), $player->id]);
            $db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", [maxEnergy($player->level, $player->vip), $player->id]);

            $db->execute("update `players` set `magic_points`=`magic_points`+1, `stat_points`=`stat_points`+3, `level`=`level`+1, `hp`=?, `maxhp`=?, `exp`=`exp`+?, `energy`=`energy`-10 where `id`=?", [maxHp($db, $player->id, $player->level, $player->reino, $player->vip), maxHp($db, $player->id, $player->level, $player->reino, $player->vip), $newexp, $player->id]);
        } else {
            //Update player
            $db->execute("update `players` set `exp`=`exp`+?, `hp`=?, `energy`=`energy`-10 where `id`=?", [$expwin, $player->hp, $player->id]);
        }

        if ($player->reino == $enemy->reino) {
            $db->execute("update `players` set `akills`=`akills`+1 where `id`=?", [$player->id]);
        } else {
            $db->execute("update `players` set `kills`=`kills`+1 where `id`=?", [$player->id]);
        }

        if ($player->id == $luta['p_id']) {
            $db->execute("update `duels` set `p_type`='99', `status`='z', `venceu`=? where `id`=?", [$player->id, $luta['id']]);
        } else {
            $db->execute("update `duels` set `e_type`='99', `status`='z', `venceu`=? where `id`=?", [$player->id, $luta['id']]);
        }
    }

    if ($luta['status'] == 'z' && $_SESSION['statusduellog'] == null) {
        if ($luta['venceu'] == $player->id) {
            $output .= showAlert("<b>Você venceu o duelo contra " . $enemy->username . "</b>", "green");
        } elseif ($luta['venceu'] == $enemy->id) {
            $output .= showAlert("<b>Você foi derrotado no duelo contra " . $enemy->username . "</b>", "red");
        } elseif ($luta['extra'] == $player->id) {
            $output .= showAlert("<b>Você fugiu da luta com sucesso!</b>", "green");
        } elseif ($luta['extra'] == $enemy->id) {
            $output .= showAlert("<b>" . $enemy->username . " fugiu durante a luta.</b>", "red");
        }
    }

    if (!$_GET['nolayout']) {
        include(__DIR__ . "/templates/private_header.php");
    } else {
        header("Content-Type: text/html; charset=utf-8", true);
    }

    echo '<script type="text/javascript">';
    echo "setTimeout(function() { Ajax('duel.php?luta=true&nolayout=true', 'battle'); }, 1500);";
    echo "</script>";

    echo '<div id="swap"></div><div id="battle">';
    if ($output) {
        $_SESSION['statusduellog'] = $output;
    }

    echo $_SESSION['statusduellog'];

    //enquanto a luta n acabou
    if ($luta['status'] != 'z' && $player->hp > 0 && $enemy->hp > 0 && $matou != 5 && $morreu != 5 && $luta['p_type'] != '99' && $luta['e_type'] != '99') {
        echo '<table width="100%">';
        echo "<tr>";
        echo '<td width="39%" align="center">';
        echo '<table width="100%" align="center"><tr>';
        echo '<th width="50px"><center><img src="static/' . $player->avatar . '" width="42px" height="42px" alt="' . $player->username . '" border="1px"></center></th>';
        echo "<td>";
        echo "<b>" . showName($player->id, $db, 'on', 'off') . '</b> <font size="1px">nível ' . $player->level . "</font><br/>";

        echo show_prog_bar(155, ceil(($player->hp / $player->maxhp) * 100), strval($player->hp), 'red', '#FFF');
        echo "<br />";
        echo show_prog_bar(155, ceil(($player->mana / $player->maxmana) * 100), strval($player->mana), 'blue', '#FFF');

        echo "</td>";
        echo '</tr></table><font size="1px">';

        if ($player->id == $luta['p_id']) {
            $showMagia = $luta['p_magia'];
            $showTurnos = $luta['p_turnos'];
        } else {
            $showMagia = $luta['e_magia'];
            $showTurnos = $luta['e_turnos'];
        }

        if ($showMagia == '1' && $showTurnos > 0) //reforço
        {
            echo "Força + 15% ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '2' && $showTurnos > 0) //agressivo
        {
            echo "Força + 45% / Resistência - 15% ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '6' && $showTurnos > 0) //defesa
        {
            echo "Feitiço de defesa ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '7' && $showTurnos > 0) //
        {
            echo "Resistência + 20% ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '10' && $showTurnos > 0) //escudo
        {
            echo "Escudo místico ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '11' && $showTurnos > 0) //agressivo
        {
            echo "Tontura ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '12' && $showTurnos > 0) //agressivo
        {
            echo "Força + 35% ( " . $showTurnos . " turnos )";
        }

        echo "</td>";
        echo '<td width="22%" align="center">';
        echo "<br/><b>VS</b><br/>";
        if ($player->id == $luta['p_id'] && $luta['vez'] == 'p' || $player->id != $luta['p_id'] && $luta['vez'] == 'e') {
            echo '<font size="1px">Sua vez. ( ' . ($luta['timeout'] - time()) . " )</font>";
        } else {
            echo '<font size="1px">Aguardando oponente. ( ' . ($luta['timeout'] - time()) . " )</font>";
        }

        echo "</td>";
        echo '<td width="39%" align="center">';
        echo '<table width="100%" align="center"><tr>';
        echo "<td>";

        echo '<div style="float: right; text-align: right;">';
        echo "<b>" . showName($enemy->id, $db, 'on', 'off') . '</b> <font size="1px">nível ' . $enemy->level . "</font><br/>";
        echo show_prog_bar(155, ceil(($enemy->hp / $enemy->maxhp) * 100), strval($enemy->hp), 'red', '#FFF');
        echo "<br />";
        echo show_prog_bar(155, ceil(($enemy->mana / $enemy->maxmana) * 100), strval($enemy->mana), 'blue', '#FFF');
        echo "<div>";

        echo "</td>";
        echo '<th width="50px"><center><img src="static/' . $enemy->avatar . '" width="42px" height="42px" alt="' . $enemy->username . '" border="1px"></center></th>';
        echo '</tr></table><font size="1px">';
        if ($enemy->id == $luta['p_id']) {
            $showMagia = $luta['p_magia'];
            $showTurnos = $luta['p_turnos'];
        } else {
            $showMagia = $luta['e_magia'];
            $showTurnos = $luta['e_turnos'];
        }

        if ($showMagia == '1' && $showTurnos > 0) //reforço
        {
            echo "Força + 15% ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '2' && $showTurnos > 0) //agressivo
        {
            echo "Força + 45% / Resistência - 15% ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '6' && $showTurnos > 0) //defesa
        {
            echo "Feitiço de defesa ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '7' && $showTurnos > 0) //
        {
            echo "Resistência + 20% ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '10' && $showTurnos > 0) //escudo
        {
            echo "Escudo místico ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '11' && $showTurnos > 0) //agressivo
        {
            echo "Tontura ( " . $showTurnos . " turnos )";
        } elseif ($showMagia == '12' && $showTurnos > 0) //agressivo
        {
            echo "Força + 35% ( " . $showTurnos . " turnos )";
        }

        echo "</font></td>";
        echo "</tr>";
        echo "</table><br/>";
    }

    echo '<div id="logdebatalha" class="scroll" style="background-color:#FFFDE0; overflow: auto; height:220px; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
    foreach ($duellog as $log) {
        $log = explode(", ", (string) $log);
        if ($log[1] == $player->username) {
            echo '<div style="text-align: left">';
            $lado = 1;
        } else {
            echo '<div style="text-align: right">';
            $lado = 2;
        }

        if ($log[0] == 1) {
            if ($lado == 1) {
                echo '<font color="green">';
                echo "Você atacou " . $log[2] . " e tirou " . $log[3] . " de vida.";
                echo "</font>";
            } else {
                echo '<font color="red">';
                echo "" . $log[1] . " te atacou e você perdeu " . $log[3] . " de vida.";
                echo "</font>";
            }
        } elseif ($log[0] == 2) {
            if ($lado == 1) {
                echo '<font color="blue">';
                echo "Você deu um " . $log[4] . " em " . $log[2] . " e tirou " . $log[3] . " de vida.";
                echo "</font>";
            } else {
                echo '<font color="purple">';
                echo "" . $log[1] . " te deu um " . $log[4] . " e você perdeu " . $log[3] . " de vida.";
                echo "</font>";
            }
        } elseif ($log[0] == 3) {
            if ($lado == 1) {
                echo '<font color="blue">';
                echo "Você lanãou o feitiço " . $log[2] . ".";
                echo "</font>";
            } else {
                echo '<font color="purple">';
                echo "" . $log[1] . " lanãou o feitiço " . $log[2] . ".";
                echo "</font>";
            }
        } elseif ($log[0] == 6) {
            if ($lado == 1) {
                echo '<font color="black">';
                echo "Você tentou lançar um feitiço mas está sem mana suficiente.";
                echo "</font>";
            } else {
                echo '<font color="black">';
                echo "" . $log[1] . " tentou te lançar um feitiço mas está sem mana suficiente.";
                echo "</font>";
            }
        } elseif ($log[0] == 7) {
            if ($lado == 1) {
                echo '<font color="black">';
                echo "Você não pode ativar um feitiço passivo enquanto outro está ativo.";
                echo "</font>";
            }
        } elseif ($log[0] == 8) {
            if ($lado == 1) {
                echo '<font color="black">';
                echo "Você tentou lançar um feitiço em  " . $log[2] . " mas errou!";
                echo "</font>";
            } else {
                echo '<font color="black">';
                echo "" . $log[1] . " tentou te lançar um feitiço mas errou!";
                echo "</font>";
            }
        } elseif ($log[0] == 10) {
            if ($lado == 1) {
                echo '<font color="purple">';
                echo "Você tentou atacar " . $log[2] . " mas seu ataque voltou e você perdeu " . $log[3] . " de vida.";
                echo "</font>";
            } else {
                echo '<font color="blue">';
                echo "" . $log[1] . " tentou te atacar mas seu ataque voltou e ele perdeu " . $log[3] . " de vida.";
                echo "</font>";
            }
        } elseif ($log[0] == 11) {
            if ($lado == 1) {
                echo '<font color="black">';
                echo "Você tentou fugir mas falhou.";
                echo "</font>";
            } else {
                echo '<font color="black">';
                echo "" . $log[1] . " tentou fugir mas falhou.";
                echo "</font>";
            }
        } elseif ($log[0] == 12) {
            if ($lado == 1) {
                echo '<font color="black">';
                echo "Você fugiu da luta com sucesso!";
                echo "</font>";
            } else {
                echo '<font color="black">';
                echo "" . $log[1] . " fugiu durante a luta.";
                echo "</font>";
            }
        } elseif ($log[0] == 13) {
            if ($lado == 1) {
                echo '<font color="black">';
                echo "Você demorou demais para reagir e " . $log[2] . " te massacrou!";
                echo "</font>";
            } else {
                echo '<font color="black">';
                echo "" . $log[1] . " demorou demais para reagir e você venceu!";
                echo "</font>";
            }
        } elseif ($log[0] == 14) {
            if ($lado == 1) {
                echo '<font color="black">';
                echo "Você demorou demais para responder e perdeu a vez!";
                echo "</font>";
            } else {
                echo '<font color="black">';
                echo "" . $log[1] . " demorou demais para reagir e perdeu a vez!";
                echo "</font>";
            }
        } elseif ($lado == 1) {
            echo '<font color="black">';
            echo "Você tentou atacar " . $log[2] . " mas errou!";
            echo "</font>";
        } else {
            echo '<font color="black">';
            echo "" . $log[1] . " tentou te atacar mas errou!";
            echo "</font>";
        }

        echo "</div>";
    }

    echo "</div>";

    if ($player->hp < 1 || $enemy->hp < 1 || $matou == 5 || $morreu == 5 || $luta['p_type'] == '99' || $luta['e_type'] == '99') {
        include(__DIR__ . "/healcost.php");
        if ($heal > 0 && $player->gold > $cost) {
            echo showAlert("<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('heal.php', 'swap')\">Clique aqui</a> para recuperar toda sua vida por <b>" . $cost . "</b> de ouro.", "white", "left");
        } elseif ($heal > 0 && $player->gold > 0) {
            echo showAlert("<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('heal.php', 'swap')\">Clique aqui</a> para recuperar parte da sua vida por <b>" . $cost2 . "</b> de ouro.", "white", "left");
        }
    } elseif (($player->id == $luta['p_id'] && $luta['vez'] == 'p' || $player->id != $luta['p_id'] && $luta['vez'] == 'e') && $luta['status'] != 'z') {
        //se for a vez do player
        echo '<table width="100%" height="43px" border="0px"><tr><td width="85%" bgcolor="#E1CBA4">';
        echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_duel.php?type=97', 'swap')\"><img src=\"static/images/magias/hit.png\" style=\"border: 0px; padding-top: 3px; padding-left: 5px; z-index: 3;\" border=\"0\" /></a>";
        $vermagia = $db->execute("select magias.magia_id, blueprint_magias.nome, blueprint_magias.descri, blueprint_magias.mana from `magias`, `blueprint_magias` where magias.magia_id=blueprint_magias.id and magias.used=? and magias.magia_id!=5 and magias.player_id=?", ["t", $player->id]);
        while ($result = $vermagia->fetchrow()) {

            echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_duel.php?type=" . $result['magia_id'] . "', 'swap')\">";

            if ($bixo->type != $result['magia_id']) {
                echo '<img src="static/images/magias/black.png" style="border: 0px; padding-top: 3px; padding-left: 5px; position: absolute; z-index: 3;" title="header=[' . $result['nome'] . "] body=[" . $result['descri'] . " <b>Mana:</b> " . $result['mana'] . ']"/>';
                echo '<img src="static/images/magias/' . $result['magia_id'] . '.png" style="border: 0px; padding-top: 3px; padding-left: 5px; z-index: 2;"/>';
            } else {
                echo '<img src="static/images/magias/' . $result['magia_id'] . '.png" style="border: 0px; padding-top: 3px; padding-left: 5px; z-index: 2;" title="header=[' . $result['nome'] . "] body=[" . $result['descri'] . " <b>Mana:</b> " . $result['mana'] . ']"/>';
            }

            echo "</a>";
        }

        echo '</td><th width="15%" bgcolor="#E1CBA4">';
        echo '<center><font size="1px"><a href="swap_duel.php?type=96"><b>Fugir</b></a></font></center>';
        echo "</th></tr></table>";
    } elseif ($luta['status'] != 'z') {
        echo '<table width="100%" height="43px" border="0px"><tr><td width="85%" bgcolor="#cccccc">';
        echo '<img src="static/images/magias/hit.png" style="border: 0px; padding-top: 3px; padding-left: 5px; z-index: 3;" border="0" />';
        $vermagia = $db->execute("select magias.magia_id, blueprint_magias.nome, blueprint_magias.descri, blueprint_magias.mana from `magias`, `blueprint_magias` where magias.magia_id=blueprint_magias.id and magias.used=? and magias.magia_id!=5 and magias.player_id=?", ["t", $player->id]);
        while ($result = $vermagia->fetchrow()) {
            if ($bixo->type != $result['magia_id']) {
                echo '<img src="static/images/magias/black.png" style="border: 0px; padding-top: 3px; padding-left: 5px; position: absolute; z-index: 3;" title="header=[' . $result['nome'] . "] body=[" . $result['descri'] . " <b>Mana:</b> " . $result['mana'] . ']"/>';
                echo '<img src="static/images/magias/' . $result['magia_id'] . '.png" style="border: 0px; padding-top: 3px; padding-left: 5px; z-index: 2;"/>';
            } else {
                echo '<img src="static/images/magias/' . $result['magia_id'] . '.png" style="border: 0px; padding-top: 3px; padding-left: 5px; z-index: 2;" title="header=[' . $result['nome'] . "] body=[" . $result['descri'] . " <b>Mana:</b> " . $result['mana'] . ']"/>';
            }
        }

        echo '</td><th width="15%" bgcolor="#cccccc">';
        echo '<center><font size="1px"><b>Aguarde sua vez</b></font></center>';
        echo "</th></tr></table>";
    }

    echo "</div>";

    if (!$_GET['nolayout']) {
        include(__DIR__ . "/templates/private_footer.php");
    }

    exit;
}

include(__DIR__ . "/checkhp.php");
if (!$_GET['nolayout']) {
    include(__DIR__ . "/templates/private_header.php");
} else {
    header("Content-Type: text/html; charset=utf-8", true);
}

if ($_GET['remove']) {
    $checkDuelRemove = $db->execute("select `id` from `duels` where `status`='w' and `id`=? and (`p_id`=? or `e_id`=?)", [$_GET['remove'], $player->id, $player->id]);
    if ($checkDuelRemove->recordcount() > 0) {
        $db->execute("delete from `duels` where `id`=? and (`p_id`=? or `e_id`=?)", [$_GET['remove'], $player->id, $player->id]);
        echo showAlert("Desafio removido com sucesso.", "green");
    } else {
        echo showAlert("Este desafio não existe.", "red");
    }
}

if ($_POST['desafiar']) {
    $checkEnemy = $db->execute("select `id` from `players` where `username`=?", [$_POST['username']]);
    if ($checkEnemy->recordcount() > 0) {
        $getEnemyId = $db->GetOne("select `id` from `players` where `username`=?", [$_POST['username']]);
    }

    if (!$_POST['username'] || $_POST['username'] == null) {
        echo showAlert("Digite o nome do jogador que você deseja desafiar.", "red");
    } elseif ($checkEnemy->recordcount() == 0) {
        echo showAlert("O jogador " . $_POST['username'] . " não existe.", "red");
    } elseif ($player->id == $getEnemyId) {
        echo showAlert("Você não pode desafiar você mesmo.", "red");
    } else {
        $checkDuel = $db->execute("select `id` from `duels` where `status`='w' and ((`p_id`=? and `e_id`=?) or (`p_id`=? and `e_id`=?))", [$player->id, $getEnemyId, $getEnemyId, $player->id]);
        if ($checkDuel->recordcount() == 0) {
            $insert['p_id'] = $player->id;
            $insert['e_id'] = $getEnemyId;
            $db->autoexecute('duels', $insert, 'INSERT');

            $logmsg = "<b>" . $player->username . '</b> te desafiou para um duelo. <a href="duel.php">Clique aqui</a> para ver seus desafios.';
            addlog($getEnemyId, $logmsg, $db);

            echo showAlert("Proposta de desafio enviada.", "green");
        } else {
            echo showAlert("Já existe um desafio entre você e " . $_POST['username'] . " pendente.", "red");
        }
    }
}

if ($_GET['error'] == 'noresponse') {
    echo showAlert("Seu oponente não aceitou o desafio no período determinado.", "red");
}

echo "<center><i>Duelos são a melhor maneira de se provar quem é o melhor guerreiro,<br/>eles podem lhe ajudar a ganhar grandes quantias de experiência, mas você não conseguirá ganhar ouro por aqui.</i></center><br/>";

echo '<script type="text/javascript">';
echo "setTimeout(function() { Ajax('showduels.php?header=true', 'showduels'); }, 2500);";
echo "</script>";

echo '<div id="showduels">';
include(__DIR__ . "/showduels.php");
echo "</div>";

echo '<br/><br/><form method="POST" action="duel.php">';
echo '<table width="95%" align="center" style="background-color: #FFFDE0;">';
echo '<tr><td align="center" bgcolor="#E1CBA4" colspan="3"><b>Desafiar jogador</b></td></tr>';
echo '<tr style="background-color: #FFFDE0;">';
echo "<th width=\"30%\" align=\"center\"><b>Usuário</b>:</th>";
echo '<th width="40%" align="center"><input type="text" name="username" size="30" /></th>';
echo '<th width="30%" align="center"><input type="submit" name="desafiar" value="Desafiar" /></th>';
echo "</tr>";
echo "</table>";
echo "</form>";

if (!$_GET['nolayout']) {
    include(__DIR__ . "/templates/private_footer.php");
}

exit;
