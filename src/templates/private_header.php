<?php

declare(strict_types=1);

$currentfile = $_SERVER["SCRIPT_NAME"];
$parts = explode('/', (string) $currentfile);
$currentfile = $parts[count($parts) - 1];

$userAgent = $_SERVER['HTTP_USER_AGENT'];

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

    $tut = $checatutoriallido->fetchrow();
    if ((($tut['pending_status'] ?? null) == 1 || $player->reino == 0) && $currentfile !== 'start.php') {
        header("Location: start.php");
        exit;
    }

    if (($tut['pending_status'] ?? null) == 2 && $currentfile !== 'start.php') {
        header("Location: start.php");
        exit;
    }

    if (($tut['pending_status'] ?? null) == 3 && $currentfile !== 'stat_points.php') {
        header("Location: stat_points.php");
        exit;
    }

    if (($tut['pending_status'] ?? null) == 4) {
        if (isMobile($userAgent)) {
            if ($currentfile !== 'inventory_mobile.php') {
                header("Location: inventory_mobile.php");
                exit;
            }
        } elseif ($currentfile !== 'inventory.php') {
            header("Location: inventory.php");
            exit;
        }
    } elseif (($tut['pending_status'] ?? null) == 5 && $currentfile !== 'home.php') {
        header("Location: home.php");
        exit;
    } elseif (($tut['pending_status'] ?? null) == 6 && $currentfile !== 'monster.php') {
        header("Location: monster.php");
        exit;
    } elseif (($tut['pending_status'] ?? null) == 7 && $currentfile !== 'start.php') {
        header("Location: start.php");
        exit;
    } elseif (($tut['pending_status'] ?? null) == 8 && $currentfile !== 'start.php') {
        header("Location: start.php");
        exit;
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />

    <title>O Confronto :: <?php echo PAGENAME ?></title>
    <link rel="icon" type="image/x-icon" href="static/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Pixelify Sans Without Digits';
            src: url('static/fonts/PixelifySans.ttf') format('truetype');
            /* Include all characters except digits */
            unicode-range: U+0000-002F, U+003A-FFFF;
        }

        * {
            font-family: 'Pixelify Sans Without Digits', monospace, sans-serif !important;
        }
    </style>
    <link href="static/css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="static/css/css.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="static/css/boxover.css" />
    <link rel="stylesheet" type="text/css" href="static/css/inventory.css" />
    <link rel="stylesheet" type="text/css" href="static/css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="static/css/private/menu-inventario.css" />
    <link rel="stylesheet" type="text/css" href="static/css/private/magias.css" />
    <link rel="stylesheet" type="text/css" href="static/css/private/tabs.css" />
    <link rel="stylesheet" type="text/css" href="static/css/private/slidemenu.css" />
    <link rel="stylesheet" type="text/css" href="static/css/private/player-top.css" />
    <link rel="stylesheet" type="text/css" href="static/css/private/showit.css" />
    <link type="text/css" rel="stylesheet" media="all" href="static/css/chat.css" />
    <script type="text/javascript">
        function Ajax(page, usediv) {
            var
                $http
            $self = arguments.callee;

            if (window.XMLHttpRequest) {
                $http = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                try {
                    $http = new ActiveXObject('Msxml2.XMLHTTP');
                } catch (e) {
                    $http = new ActiveXObject('Microsoft.XMLHTTP');
                }
            }

            if ($http) {
                $http.onreadystatechange = function() {
                    if (/4|^complete$/.test($http.readyState)) {
                        document.getElementById(usediv).innerHTML = $http.responseText;
                        setTimeout(function() {
                            Ajax(page, usediv);
                        }, 1500);
                    }
                };
                $http.open('GET', page, true);
                $http.send(null);
            }
        }
    </script>

    <script type="text/javascript">
        function LoadPage(page, usediv) {
            // Set up request varible
            try {
                xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}

            document.getElementById(usediv).innerHTML = '';
            //send data
            xmlhttp.onreadystatechange = function() {
                //Check page is completed and there were no problems.
                if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)) {
                    //Write data returned to page
                    document.getElementById(usediv).innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", page, true);
            xmlhttp.send(null);
            //Stop any link loading normaly
            return false;
        }
    </script>

    <script src="static/js/jquery.js"></script>
    <script type="text/javascript" src="static/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="static/js/gaitamenu.js"></script>
    <script type="text/javascript" src="static/js/timecountdown.js"></script>
    <script src="static/js/jquery.tabs.js"></script>


    <script type="text/javascript" src="static/js/drag.js"></script>
    <!-- initialize drag and drop -->
    <?php
    // Exemplo de inclusão condicional do script no cabeçalho

    // Verifica se a página atual é 'inventory.php'
    if ($currentfile === 'inventory.php') {
    ?>
        <script type="text/javascript">
            // onload event
            window.onload = function() {
                // Verifica se é um dispositivo móvel
                var isMobile = /Mobi|Android/i.test(navigator.userAgent);
                if (isMobile) {
                    // Redireciona para uma página diferente para dispositivos móveis
                    var btnMobile = document.getElementById('btn_mobile');
                    if (btnMobile) {
                        btnMobile.style.display = 'flex';
                    }
                }

                rd = REDIPS.drag; // referência à classe REDIPS.drag
                // inicialização
                rd.init();

                rd.mark.exception.amulet = 'amulet';
                rd.mark.exception.helmet = 'helmet';
                rd.mark.exception.weapon = 'weapon';
                rd.mark.exception.armor = 'armor';
                rd.mark.exception.shield = 'shield';
                rd.mark.exception.ring = 'ring';
                rd.mark.exception.legs = 'legs';
                rd.mark.exception.boots = 'boots';

                // esta função (manipulador de eventos) é chamada após o elemento ser solto
                REDIPS.drag.myhandler_dropped = function() {
                    var obj_old = REDIPS.drag.obj_old; // referência ao objeto original
                    var target_cell = REDIPS.drag.target_cell; // referência à célula de destino			

                    // se o elemento DIV foi colocado em uma célula permitida
                    if (rd.target_cell.className.indexOf(rd.mark.exception[rd.obj.id]) !== -1) {
                        if (REDIPS.drag.target_cell !== REDIPS.drag.source_cell) {
                            var itclassname = rd.obj_old.className;
                            var itid = itclassname.split(' ')[1];
                            window.location.href = 'equipit.php?itid=' + itid;
                        } else {
                            window.location.href = 'inventory.php';
                        }
                    } else if (REDIPS.drag.target_cell !== REDIPS.drag.source_cell) {
                        var itclassname = rd.obj_old.className;
                        var itid = itclassname.split(' ')[1];

                        if (rd.target_cell.className == 'sell') {
                            window.location.href = 'inventory.php?sellit=' + itid;
                        } else if (rd.target_cell.className == 'mature') {
                            window.location.href = 'inventory.php?mature=' + itid;
                        } else {
                            var tileclassname = rd.target_cell.className;
                            var tileid = tileclassname.split(' ')[1];
                            window.location.href = 'moveit.php?itid=' + itid + '&tile=' + tileid;
                        }
                    } else {
                        window.location.href = 'inventory.php';
                    }
                }
            }
        </script>
    <?php
    }
    ?>

    <script type="text/javascript" src="static/js/ajax.js"></script>
    <script type="text/javascript" src="static/js/boxover.js"></script>

    <script language="JavaScript">
        function BattleDivDown() {
            document.getElementById('logdebatalha').scrollTop += 1000000;
        }

        function ChatDivDown() {
            document.getElementById('chatdiv').scrollTop += 1000000;
        }
    </script>
    <script type="text/javascript" src="static/js/pagamentos.js"></script>
    <script type="text/javascript" src="static/bbeditor/ed.js"></script>

    <?php
    if ($currentfile === 'stat_points.php') {
        echo '<script type="text/javascript" src="static/js/checkStatus.js"></script>';
    }
    ?>
    <script
        async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1892370805366558"
        crossorigin="anonymous">
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5C9CTZE98D"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-5C9CTZE98D');
    </script>
</head>
<?php
if ($currentfile === 'inventory.php') {
    echo "<body>";
} elseif ($currentfile === 'stat_points.php') {
    echo '<body onload="testee(' . $player->stat_points . ');">';
} else {
    echo "<body>";
}

$mailcount = $db->execute("select `id` from `mail` where `to`=? and `status`='unread'", [$player->id]);
$logcount0 = $db->execute("select `id` from `user_log` where `player_id`=? and `status`='unread'", [$player->id]);
$logcount1 = $db->execute("select `id` from `logbat` where `player_id`=? and `status`='unread'", [$player->id]);
$logcount2 = $db->execute("select `id` from `log_gold` where `player_id`=? and `status`='unread'", [$player->id]);
$logcount3 = $db->execute("select `id` from `log_item` where `player_id`=? and `status`='unread'", [$player->id]);
$logcount4 = $db->execute("select `id` from `account_log` where `player_id`=? and `status`='unread'", [$player->acc_id]);
$logscount = $logcount0->recordcount() + $logcount1->recordcount() + $logcount2->recordcount() + $logcount3->recordcount() + $logcount4->recordcount();
?>

<div style="position: relative; width: 100%;">
    <div style="display: flex; width: 100%;">
        <?php
        $verificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", [$player->id, time()]);
        if ($verificpotion->recordcount() > 0) {
            $selct = $verificpotion->fetchrow();
            $valortempo = $selct['time'] - time();
            if ($valortempo < 60) {
                $auxiliar = "segundo(s)";
            } elseif ($valortempo < 3600) {
                $valortempo = ceil($valortempo / 60);
                $auxiliar = "minuto(s)";
            } elseif ($valortempo < 86400) {
                $valortempo = ceil($valortempo / 3600);
                $auxiliar = "hora(s)";
            }

            $potname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", [$selct['item_id'] ?? null]);
            $potdesc = $db->GetOne("select `description` from `blueprint_items` where `id`=?", [$selct['item_id'] ?? null]);
            $potimg = $db->GetOne("select `img` from `blueprint_items` where `id`=?", [$selct['item_id'] ?? null]);
        ?>
            <div
                title="header=[<?php echo $potname; ?>] body=[<?php echo $potdesc; ?><br><font size=1><?php echo $valortempo; ?> <?php echo $auxiliar; ?> restante(s).</font>]">
                <div class="potionimg"><a href="tavern.php?act=buy&id=182"><img
                            src="static/images/itens/<?php echo $potimg; ?>" border=0></a>
                </div>
            </div>
        <?php }
        ?>

        <?php include_once __DIR__ . "/player-top.php"; ?>
    </div>
    <div style="text-align: center; background: #00000011; padding: 0.5rem;">
        <?php include(__DIR__ . "/../showmsg.php"); ?>
    </div>
        <div class='top-menu'>
            <ul>
                <li><a href='#'><b><?php echo $player->username ?></b></a>
                    <ul>
                        <li><a href='profile.php?id=<?php echo $player->username ?>'><?php echo $player->username ?></a></li>
                        <li><a href="home.php">Principal</a></li>
                        <li><a href="log.php">Log (<?php echo $logscount; ?>)</a></li>
                        <?php
                            if (isMobile($userAgent)) {
                                echo '<li><a href="inventory_mobile.php">Inventário</a></li>';
                            } else {
                                echo '<li><a href="inventory.php">Inventário</a></li>';
                            }
                        ?>
                        <li><a href="bat.php">Batalhar</a></li>
                        <li><a href="work.php">Trabalhar</a></li>
                    </ul>
                </li>
                <li><a href='#'><b>Reino</b></a>
                    <ul>
                        <li><a href="reino.php">Castelo</a></li>
                        <li><a href="bank.php">Banco</a></li>
                        <li><a href="shop.php">Ferreiro</a></li>
                        <li><a href="market.php">Mercado</a></li>
                        <li><a href="dungeon.php">Arena</a></li>
                        <li><a href="tavern.php">Taverna</a></li>
                        <li><a href="hospital.php">Hospital</a></li>
                        <li><a href="lottery.php">Loteria</a></li>
                        <li><a href="tournament.php">Torneio</a></li>
                    </ul>
                </li>
                <li><a href='#'><span><b>Comunidade</b></span></a>
                    <ul>
                        <li><a href="https://discord.gg/rwuy3npeum" target="_blank">Discord</a></li>
                        <li><a href="guild_listing.php">Clãs</a></li>
                        <li><a href="members.php">Ranking</a></li>
                        <li><a href="mail.php">Mensagens (<?php echo $mailcount->recordcount(); ?>)</a></li>
                        <li><a href="friendlist.php">Amigos</a></li>
                    </ul>
                </li>
                <li><a href='#'><span><b>Conta</b></span></a>
                    <ul>
                        <li><a href="editinfo.php">Configurações</a></li>
                        <li><a href="logoutchar.php">Personagens</a></li>
                        <li><a href="logout.php">Sair</a></li>
                    </ul>
                </li>
            </ul>
        </div>
</div>
<div class="conteudo">

    <script>
        var refreshId = setInterval(function() {
            $('#usr').load('engine.php?header=true');
        }, 2500);
    </script>

    <div id="usr"><?php include(__DIR__ . "/../engine.php"); ?></div>