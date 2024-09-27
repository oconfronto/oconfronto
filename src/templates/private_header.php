<?php
$currentfile = $_SERVER["SCRIPT_NAME"];
$parts = explode('/', $currentfile);
$currentfile = $parts[count($parts) - 1];

$userAgent = $_SERVER['HTTP_USER_AGENT'];
function isMobile($userAgent)
{
    return preg_match('/Mobile|Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/', $userAgent);
}

$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=90 and `player_id`=?", array($player->id));
if ($tutorial->recordcount() == 0) {
    $checatutoriallido = $db->execute("select * from `pending` where `pending_id`=2 and `player_id`=?", array($player->id));
    if ($checatutoriallido->recordcount() == 0) {
        $insert['player_id'] = $player->id;
        $insert['pending_id'] = 2;
        $insert['pending_status'] = 1;
        $insert['pending_time'] = time();
        $query = $db->autoexecute('pending', $insert, 'INSERT');
        header("Location: start.php");
        exit;
    } else {
        $tut = $checatutoriallido->fetchrow();
        if ((($tut['pending_status'] == 1) or ($player->reino == 0)) and ($currentfile != 'start.php')) {
            header("Location: start.php");
            exit;
        } elseif (($tut['pending_status'] == 2) and ($currentfile != 'start.php')) {
            header("Location: start.php");
            exit;
        } elseif (($tut['pending_status'] == 3) and ($currentfile != 'stat_points.php')) {
            header("Location: stat_points.php");
            exit;
        } elseif ($tut['pending_status'] == 4) {
            if (isMobile($userAgent)) {
                if ($currentfile != 'inventory_mobile.php') {
                    header("Location: inventory_mobile.php");
                    exit;
                }
            } else {
                if ($currentfile != 'inventory.php') {
                    header("Location: inventory.php");
                    exit;
                }
            }
        } elseif (($tut['pending_status'] == 5) and ($currentfile != 'home.php')) {
            header("Location: home.php");
            exit;
        } elseif (($tut['pending_status'] == 6) and ($currentfile != 'monster.php')) {
            header("Location: monster.php");
            exit;
        } elseif (($tut['pending_status'] == 7) and ($currentfile != 'start.php')) {
            header("Location: start.php");
            exit;
        } elseif (($tut['pending_status'] == 8) and ($currentfile != 'start.php')) {
            header("Location: start.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

    <title>O Confronto :: <?php echo PAGENAME ?></title>
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/css.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/boxover.css" />
    <link rel="stylesheet" type="text/css" href="css/inventory.css" />
    <link rel="stylesheet" type="text/css" href="css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="css/private/menu-inventario.css" />
    <link rel="stylesheet" type="text/css" href="css/private/magias.css" />
    <link rel="stylesheet" type="text/css" href="css/private/tabs.css" />
    <link rel="stylesheet" type="text/css" href="css/private/slidemenu.css" />
    <link type="text/css" rel="stylesheet" media="all" href="css/chat.css" />
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

    <script src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/gaitamenu.js"></script>
    <script type="text/javascript" src="js/timecountdown.js"></script>
    <script src="js/jquery.tabs.js"></script>


    <script type="text/javascript" src="js/drag.js"></script>
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

    <script type="text/javascript" src="js/ajax.js"></script>
    <script type="text/javascript" src="js/boxover.js"></script>

    <script language="JavaScript">
        function BattleDivDown() {
            document.getElementById('logdebatalha').scrollTop += 1000000;
        }

        function ChatDivDown() {
            document.getElementById('chatdiv').scrollTop += 1000000;
        }
    </script>
    <script type="text/javascript" src="js/pagamentos.js"></script>
    <script type="text/javascript" src="bbeditor/ed.js"></script>

    <?php
    if ($currentfile == 'stat_points.php') {
        echo "<script type=\"text/javascript\" src=\"js/checkStatus.js\"></script>";
    }
    ?>
    <script
        async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1892370805366558"
        crossorigin="anonymous"
    >
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5C9CTZE98D"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-5C9CTZE98D');
    </script>
</head>
<div id="applixir_vanishing_div" hidden style="z-index: 1000">
     <iframe id="applixir_parent" ></iframe>
</div>
<?php
if ($currentfile == 'inventory.php') {
    echo "<body>";
} elseif ($currentfile == 'stat_points.php') {
    echo "<body onload=\"testee(" . $player->stat_points . ");\">";
} else {
    echo "<body>";
}

$mailcount = $db->execute("select `id` from `mail` where `to`=? and `status`='unread'", array($player->id));
$logcount0 = $db->execute("select `id` from `user_log` where `player_id`=? and `status`='unread'", array($player->id));
$logcount1 = $db->execute("select `id` from `logbat` where `player_id`=? and `status`='unread'", array($player->id));
$logcount2 = $db->execute("select `id` from `log_gold` where `player_id`=? and `status`='unread'", array($player->id));
$logcount3 = $db->execute("select `id` from `log_item` where `player_id`=? and `status`='unread'", array($player->id));
$logcount4 = $db->execute("select `id` from `account_log` where `player_id`=? and `status`='unread'", array($player->acc_id));
$logscount = $logcount0->recordcount() + $logcount1->recordcount() + $logcount2->recordcount() + $logcount3->recordcount() + $logcount4->recordcount();
?>

<div id="tudo" style="position: relative;">
    <img src="images/topo.jpg" style="position:absolute;width:100%;z-index: 0;">
    <div class="msg">
        <div class="ic-msg"></div><?php include("showmsg.php"); ?>
    </div>
    <table class="lol">
        <tr>
            <td valign="top" width="220px">
                <div class="left" style="position:relative;z-index: 1;">
                    <div class="leftcon">

                        <img src="images/menu/personagem.png"
                            style="-webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" border="0">

                        <?php
                        $verificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", array($player->id, time()));
                        if ($verificpotion->recordcount() > 0) {
                            $selct = $verificpotion->fetchrow();
                            $valortempo = $selct['time'] - time();
                            if ($valortempo < 60) {
                                $valortempo = $valortempo;
                                $auxiliar = "segundo(s)";
                            } else if ($valortempo < 3600) {
                                $valortempo = ceil($valortempo / 60);
                                $auxiliar = "minuto(s)";
                            } else if ($valortempo < 86400) {
                                $valortempo = ceil($valortempo / 3600);
                                $auxiliar = "hora(s)";
                            }

                            $potname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", array($selct['item_id']));
                            $potdesc = $db->GetOne("select `description` from `blueprint_items` where `id`=?", array($selct['item_id']));
                            $potimg = $db->GetOne("select `img` from `blueprint_items` where `id`=?", array($selct['item_id']));

                        ?>
                            <div
                                title="header=[<?php echo $potname; ?>] body=[<?php echo $potdesc; ?><br><font size=1><?php echo $valortempo; ?> <?php echo $auxiliar; ?> restante(s).</font>]">
                                <div class="potionimg"><a href="tavern.php?act=buy&id=182"><img
                                            src="images/itens/<?php echo $potimg; ?>" border=0></a>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="avatar"><a href="avatar.php"><img src="<?php echo $player->avatar ?>"
                                    border="0px"></a></div>



                        <div class="bg-barras">
                            <div class="ic-hp">
                                <div class="bg-bar">
                                    <div id="bar-hp" class="bg-hp"
                                        style="width:<?php echo ceil(($player->hp * 100) / $player->maxhp); ?>%">
                                        <span><?php echo $player->hp; ?> / <?php echo $player->maxhp; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="ic-mp">
                                <div class="bg-bar">
                                    <div id="bar-mp" class="bg-mp"
                                        style="width:<?php echo ceil(($player->mana * 100) / $player->maxmana); ?>%">
                                        <span><?php echo $player->mana; ?> / <?php echo $player->maxmana; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="ic-en">
                                <div class="bg-bar">
                                    <div id="bar-en" class="bg-en"
                                        style="width:<?php echo ceil(($player->energy * 100) / $player->maxenergy); ?>%">
                                        <span><?php echo $player->energy; ?> / <?php echo $player->maxenergy; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <span id="mudar1"><img src="images/menu/on1.png" border="0px"></span>
                        <div id="gaita1">
                            <?php include("showit.php"); ?>

                            <div class="moedas">
                                <div class="ic-moeda"></div>
                                <div id="player-gold" class="ouro"><?php echo number_format($player->gold, 0, '', '.') ?> moedas</div>
                            </div>
                        </div>

                        <br />
                        <span id="mudar2"><img src="images/menu/on2.png" border="0px"></span>
                        <div id="gaita2">

                            <?php
                            echo "<table border=\"0px\" cellpadding=\"0px\" cellspacing=\"0px\"  class=\"friend\">";
                            $query = $db->execute("select `fname` from `friends` WHERE `uid`=? order by `fname` asc", array($player->acc_id));
                            if ($query->recordcount() == 0) {
                                echo "<tr class=\"amigo\"><th><center>Você não tem amigos.</center></th></tr>";
                            } else {
                                $bool = "o";
                                while ($friend = $query->fetchrow()) {
                                    $name = $db->GetOne("select `id` from `players` where `username`=?", array($friend['fname']));
                                    $friendlevel = $db->getone("select `level` from `players` where `id`=?", array($name));

                                    echo "<tr class=\"amig" . $bool . "\">";
                                    echo "<th>&nbsp;<b>" . showName($name, $db, 'off', 'off') . "</b></th>";
                                    echo "<th><b>Nv. " . $friendlevel . "</b></th>";


                                    $online = $db->execute("select `time` from `user_online` where `player_id`=?", array($name));
                                    $ignorado = $db->execute("select * from `ignored` where `uid`=? and `bid`=?", array($name, $player->id));
                                    if (($online->recordcount() > 0) and ($ignorado->recordcount() == 0)) {
                                        $check = $db->execute("select * from `pending` where `pending_id`=30 and `player_id`=?", array($name));
                                        if ($check->recordcount() == 0) {
                                            echo "<th><center><a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ", "_", showName($name, $db, 'off', 'off')) . "')\"><img src=\"images/images/on.png\" border=\"0px\"></a></center></th>";
                                        } else {
                                            $stattus = $check->fetchrow();
                                            if ($stattus['pending_status'] == 'ocp') {
                                                echo "<th><center><a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ", "_", showName($name, $db, 'off', 'off')) . "')\"><img src=\"images/images/ocp.png\" border=\"0px\"></a></center></th>";
                                            } elseif ($stattus['pending_status'] == 'inv') {
                                                echo "<th><center><img src=\"images/images/off.png\" border=\"0px\"></center></th>";
                                            }
                                        }
                                    } else {
                                        echo "<th><center><img src=\"images/images/off.png\" border=\"0px\"></center></th>";
                                    }

                                    echo "</tr>";
                                    $bool = ($bool == "o") ? "oo" : "o";
                                }
                            }
                            echo "</table>";
                            ?>
                        </div>
                        <br />

                    </div>
                </div>

                <br /><br /><br />
            </td>

            <td valign="top">
                <div style="margin-right:-2px;position:abosolute;">
                    <div class='cssmenu'>
                        <ul>
                            <li><a href='#'><b><?php echo $player->username ?></b></a>
                                <ul>
                                    <li><a href='profile.php?id=<?php echo $player->username ?>'><?php echo $player->username ?></a></li>
                                    <li><a href="home.php">Principal</a></li>
                                    <li><a href="log.php">Log (<?php echo $logscount; ?>)</a></li>
                                    <li><a href="inventory.php">Inventário</a></li>
                                    <li><a href="bat.php">Batalhar</a></li>
                                    <li><a href="work.php">Trabalhar</a></li>
                                    <!-- <li><a href="earn.php">
                                            <font color="gold">Ganhar ouro</font>
                                        </a></li>
                                    <li><a href="vip.php">
                                            <font color="gold">Loja VIP</font>
                                        </a></li> -->
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
                                    <li><a href="online.php">Chat</a></li>
                                    <li><a href="select_forum.php">Fórum</a></li>
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

                <div class="contop">
                    <table width="100%">
                        <tr>
                            <td id="nv_atual" style="width: 15%;padding-top:5px;text-align:center;"><b>Nível:</b>
                                <?php echo $player->level ?></td>

                            <?php
                            $style = 'display: none;';
                            $progress = ($player->exp * 100) / maxExp($player->level);
                            if (is_numeric($progress) && $progress > 0 && $progress <= 100) {
                                $style = "width: " . round($progress) . "%; display: block;";
                            }
                            ?>

                            <td style="width: 70%;">
                                <div
                                    title="header=[Experiência] body=[Exp: <?php echo number_format($player->exp); ?> / <?php echo number_format(maxExp($player->level)); ?>]">
                                    <div id="ex-bg">
                                        <span id="expbarText"><?php echo number_format($player->exp); ?> /
                                            <?php echo number_format(maxExp($player->level)); ?>
                                            (<?php echo number_format(($player->exp * 100) / maxExp($player->level)); ?>%)</span>
                                        <div id="expbar" style="<?= $style ?>"></div>
                                    </div>
                                </div>
                            </td>
                </div>
            <td id="nv_futuro" style="width: 15%;padding-top:5px;text-align:center;"><b>Nível:</b> <?php echo $player->level + 1 ?>
        </tr>
    </table>
</div>
<div class="conteudo">

    <script>
        var refreshId = setInterval(function() {
            $('#usr').load('engine.php?header=true');
        }, 2500);
    </script>

    <div id="usr"><?php include("engine.php"); ?></div>