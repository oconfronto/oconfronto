<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Principal");
$player = check_user($db);

$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=90 and `player_id`=?", [$player->id]);
if ($tutorial->recordcount() == 0) {
    $checatutoriallido = $db->execute("select * from `pending` where `pending_id`=2 and `player_id`=?", [$player->id]);
    if ($checatutoriallido->recordcount() == 0) {
        $insert['player_id'] = $player->id;
        $insert['pending_id'] = 2;
        $insert['pending_status'] = 1;
        $insert['pending_time'] = time();
        $query = $db->autoexecute('pending', $insert, 'INSERT');
        header("Location: start.php");
        exit;
    }
}

include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checktasks.php");
include(__DIR__ . "/templates/private_header.php");
include(__DIR__ . "/checkmedals.php");

$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=5 and `player_id`=?", [$player->id]);
if ($tutorial->recordcount() > 0) {
    $tutorial = $db->execute("select * from `magias` where `magia_id`=? and `player_id`=?", [4, $player->id]);
    if ($tutorial->recordcount() == 0) {
        echo showAlert("<table width=\"100%\"><tr><td width=\"90%\">A cada nível que você passa, você ganha 1 <u>ponto místico</u>.<br/><font size=\"1px\">Com os pontos místicos você pode treinar <u>novos feitiços</u>.</font><br/><br/>Agora, treine o feitiço <b>Cura</b> para continuar.</td><th><font size=\"1px\"><a href=\"start.php?act=6\">Próximo</a></font></th></tr></table>", "white", "left");
    } else {
        echo showAlert("ótimo, <a href=\"start.php?act=6\">clique aqui</a> para continuar seu tutorial.", "green");
    }
}

include(__DIR__ . "/checkquest.php");

// Check last received item and notify
$query2 = $db->execute(sprintf('select * from `items` where `player_id`= %s and `item_event` = 1', $player->id));
if ($query2) {
    while ($row = $query2->fetchrow()) {
        $id = $row['id'];
        $item_id = $row['item_id'];
        $item_bonus = $row['item_bonus'];
        
        $query3 = $db->execute('select * from `blueprint_items` where `id`= ' . $item_id);
        if ($query3) {
            while ($row2 = $query3->fetchrow()) {
                $item_name = $row2['name'];
            }
            echo showAlert("Você acaba de ganhar o Item <u>" . $item_name . " +" . $item_bonus . "</u> do Evento Convide Amigos, Parabéns !", "green");
            $db->execute("update `items` set `item_event`=? where `id`=? ", ['0', $id]);
        }
    }
}

// Helper function to get vocation name
function getVocationName($voc, $promoted) {
    $vocations = [
        'archer' => [
            'f' => 'Caçador',
            't' => 'Arqueiro',
            's' => 'Arqueiro',
            'r' => 'Arqueiro',
            'p' => 'Arqueiro Royal'
        ],
        'knight' => [
            'f' => 'Espadachim',
            't' => 'Guerreiro',
            's' => 'Guerreiro',
            'r' => 'Guerreiro',
            'p' => 'Cavaleiro'
        ],
        'mage' => [
            'f' => 'Bruxo',
            't' => 'Mago',
            's' => 'Mago',
            'r' => 'Mago',
            'p' => 'Arquimago'
        ]
    ];
    
    return $vocations[$voc][$promoted] ?? 'Desconhecido';
}

// Helper function to get kingdom name
function getKingdomName($reino) {
    $kingdoms = [
        1 => 'Cathal',
        2 => 'Eroda',
        3 => 'Turkic'
    ];
    return $kingdoms[$reino] ?? 'Nenhum';
}

// Get registration month in Portuguese
$mes = date("M", $player->registered);
$mes_ano = [
    "Jan" => "Janeiro",
    "Feb" => "Fevereiro",
    "Mar" => "Março",
    "Apr" => "Abril",
    "May" => "Maio",
    "Jun" => "Junho",
    "Jul" => "Julho",
    "Aug" => "Agosto",
    "Sep" => "Setembro",
    "Oct" => "Outubro",
    "Nov" => "Novembro",
    "Dec" => "Dezembro"
];

// Get clan name
$nomecla = $db->GetOne("select `name` from `guilds` where `id`=?", [$player->guild]);

// Get player ranking
$sql = "select id from players where gm_rank<10 and serv=" . $player->serv . " order by level desc, exp desc";
$dados = $db->execute($sql);
$ranking = 0;
$i = 1;
while ($linha = $dados->fetchrow()) {
    if (($linha['id'] ?? null) == $player->id) {
        $ranking = $i;
    }
    ++$i;
}

?>

<!-- Main Content -->
<table width="100%">
    <tr style="display: flex; flex-wrap: wrap;">
        <td width="60%" style="flex: 3;">
            <table width="100%">
                <tr>
                    <td class="brown" width="100%">
                        <center><b><?= $player->username ?></b></center>
                    </td>
                </tr>
                <tr>
                    <td class="salmon" height="80px">
                        <table style='padding:14px;' width="100%">
                            <tr>
                                <td width="20%"><b>Vocação:</b></td>
                                <td width="55%"><?= getVocationName($player->voc, $player->promoted) ?></td>
                                <th rowspan="4" width="25%">
                                    <center>
                                        <font size="1px">Ranking</font><br/>
                                        <?= $ranking ?>º
                                    </center>
                                </th>
                            </tr>
                            <tr>
                                <td><b>Reino:</b></td>
                                <td><?= getKingdomName($player->reino) ?></td>
                            </tr>
                            <tr>
                                <td><b>Clã:</b></td>
                                <td>
                                    <?php if ($nomecla): ?>
                                        <a href="guild_home.php"><?= $nomecla ?></a>
                                    <?php else: ?>
                                        Nenhum
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Registrado:</b></td>
                                <td>
                                    <?= date("d", $player->registered) ?> de <?= $mes_ano[$mes] ?> de <?= date("Y, g:i A", $player->registered) ?>.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="<?= $player->magic_points > 29 ? 'red' : 'on' ?>">
                        <center id="vl_pontosMisticos">
                            <font size="1px"><b>Pontos místicos:</b> <?= $player->magic_points ?></font>
                        </center>
                    </td>
                </tr>
                
                <!-- Spells Section -->
                <tr>
                    <td>
                        <br/>
                        <table width="100%">
                            <tr>
                                <td class="brown" width="80%">
                                    <center><b>Magias</b></center>
                                </td>
                                <td class="brown" width="20%">
                                    <center>
                                        <font size="1px">
                                            <a href="stat_points.php?act=magiasreset">Reorganizar</a>
                                        </font>
                                    </center>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="comfirm" style="background-color: #FFFDE0; padding: 5px; text-align: center;" height="100px">
                                        <?php include(__DIR__ . "/showspells.php"); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        
        <!-- Right Column -->
        <td width="40%" style="flex: 2;">
            <?php include(__DIR__ . "/templates/player-stats.php"); ?>
        </td>
    </tr>
</table>

<br/>

<!-- Tasks and Friends Section -->
<table width="100%">
    <tr>
        <td width="50%">
            <?php include(__DIR__ . "/templates/player-tasks.php"); ?>
        </td>
        <td width="50%">
            <?php include(__DIR__ . "/templates/player-friends.php"); ?>
        </td>
    </tr>
</table>

<?php
// Update user record if needed
$totalon = $db->execute("select `player_id` from `user_online`");
if ($totalon->recordcount() > $setting->user_record) {
    $query = $db->execute("update `settings` set `value`=? where `name`='user_record'", [$totalon->recordcount()]);
}

include(__DIR__ . "/templates/private_footer.php");
exit;
