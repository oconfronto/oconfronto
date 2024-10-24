<?php

declare(strict_types=1);

$player = check_user($db);

if ($_GET['voltar'] == true) {
    include(__DIR__ . "/lib.php");
    header("Content-Type: text/html; charset=utf-8", true);
}

include(__DIR__ . "/itemstatus.php");

$tipoAtributo = "";


if ($player->voc == 'archer') {
    $tipoAtributo = "Pontaria";
} elseif ($player->voc == 'knight') {
    $tipoAtributo = "Força";
} elseif ($player->voc == 'mage') {
    $tipoAtributo = "Magia";
}


$atk = "";
$def = "";

if ($player->promoted == 't') {

    if ($player->level > 149) {
        if ($player->voc == 'archer') {
            $atk = "30%";
            $def = "20%";
        } elseif ($player->voc == 'knight') {
            $atk = "25%";
            $def = "21%";
        } elseif ($player->voc == 'mage') {
            $atk = "27%";
            $def = "20%";
        }
    } elseif ($player->level > 129) {
        if ($player->voc == 'archer') {
            $atk = "28%";
            $def = "18%";
        } elseif ($player->voc == 'knight') {
            $atk = "23%";
            $def = "20%";
        } elseif ($player->voc == 'mage') {
            $atk = "26%";
            $def = "18%";
        }
    } elseif ($player->level > 119) {
        if ($player->voc == 'archer') {
            $atk = "25%";
            $def = "16%";
        } elseif ($player->voc == 'knight') {
            $atk = "20%";
            $def = "17%";
        } elseif ($player->voc == 'mage') {
            $atk = "23%";
            $def = "16%";
        }
    } elseif ($player->level > 99) {
        if ($player->voc == 'archer') {
            $atk = "21%";
            $def = "13%";
        } elseif ($player->voc == 'knight') {
            $atk = "17%";
            $def = "15%";
        } elseif ($player->voc == 'mage') {
            $atk = "19%";
            $def = "13%";
        }
    } elseif ($player->level > 89) {
        if ($player->voc == 'archer') {
            $atk = "17%";
            $def = "11%";
        } elseif ($player->voc == 'knight') {
            $atk = "14%";
            $def = "12%";
        } elseif ($player->voc == 'mage') {
            $atk = "16%";
            $def = "11%";
        }
    } elseif ($player->voc == 'archer') {
        $atk = "13%";
        $def = "8%";
    } elseif ($player->voc == 'knight') {
        $atk = "10%";
        $def = "9%";
    } elseif ($player->voc == 'mage') {
        $atk = "12%";
        $def = "8%";
    }
} elseif ($player->promoted == 'p') {
    if ($player->voc == 'archer') {
        $atk = "36%";
        $def = "24%";
    } elseif ($player->voc == 'knight') {
        $atk = "30%";
        $def = "26%";
    } elseif ($player->voc == 'mage') {
        $atk = "33%";
        $def = "24%";
    }
}
?>

<div>
    <div>
        <table style="width:100%">
            <thead>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 10%;"></td>
                    <td style="width: 10%;font-size:11px">Pontos</td>
                    <td style="width: 10%;font-size:11px">Itens</td>
                    <td style="width: 60%;font-size:11px">Promote</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td style='font-weight:bold;text-align:right'><?= $tipoAtributo ?>: </td>
                    <td><?= $player->strength ?></td>
                    <td style='color:gray'>+<?= $forcaadebonus ?></td>
                    <td style='color:black'><?= $atk ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style='font-weight:bold;text-align:right'>Vitalidade: </td>
                    <td><?= $player->vitality ?></td>
                    <td style='color:green'>+<?= $vitalidadeeeeebonus ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style='font-weight:bold;text-align:right'>Agilidade: </td>
                    <td><?= $player->agility ?></td>
                    <td style='color:blue'>+<?= $agilidadeeedebonus ?></td>
                    <td style='color:black'><?= $def ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style='font-weight:bold;text-align:right'>Resistência: </td>
                    <td><?= $player->resistance ?></td>
                    <td style='color:red'>+<?= $resistenciaaaadebonus ?></td>
                    <td style='color:black'><?= $def ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="font-size:11px;text-align:center" id="vl_pontos">
        <b>Pontos de status: </b><?= $player->stat_points ?>
    </div>
</div>
