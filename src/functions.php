<?php

declare(strict_types=1);

include(__DIR__ . "/config.php");

// CLASSES NOVAS PARA OC VERSÃO 2.0 //
class OCv2
{
    public function __construct(private $db) {}

    public function info_db($data, $data2, $data3, $data4)
    {
        $query = $this->db->execute(sprintf("SELECT * FROM `%s` WHERE `%s` = '%s'", $data, $data2, $data3));
        if ($query === false) {
            die("Query failed: " . $this->db->ErrorMsg());
        }

        $row = $query->FetchRow();
        return $row ? $row[$data4] : false;
    }

    public function totaldados(string $data, $data2 = false, $data3 = true, $vl = false)
    {
        $vll = $vl ? '>' : '=';

        if (!$data2 || !$data3) {
            $query = $this->db->execute('SELECT * FROM ' . $data);
        } else {
            $query = $this->db->execute(sprintf("SELECT * FROM %s WHERE %s %s '%s'", $data, $data2, $vll, $data3));
        }

        if ($query === false) {
            die("Query failed: " . $this->db->ErrorMsg());
        }

        return $query->RecordCount();
    }

    public function tirarCMoeda($valor): array|string
    {
        $pontos = '.';
        $virgula = ',';
        $result = str_replace($pontos, "", $valor);
        return str_replace($virgula, "", $result);
    }

    public function verificar($valor): array|string
    {
        $pontos = ',';
        $virgula = '0';
        $result = str_replace($pontos, "", $valor);
        return str_replace($virgula, "", $result);
    }
}

// FIM DO MEU CODE LINDO //

function encodePassword(string $password): string
{

    $salt = getenv('PASSWORD_SALT');
    $hash = sha1($password . $salt);

    for ($i = 0; $i < 1000; ++$i) {
        $hash = sha1($hash);
    }

    return $hash;
}

function encodeSession(string $account_id): string
{

    $pepper = '$n203hc29*&%&Hd';
    $hash = sha1($account_id . $pepper . $_SERVER["DOMAIN"]);

    for ($i = 0; $i < 1000; ++$i) {
        $hash = sha1($hash);
    }

    return $hash;
}


function check_acc(&$db): \stdClass
{
    if (!isset($_SESSION['Login'])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    $query = $db->execute("SELECT * FROM `accounts` WHERE `id`=? AND `conta`=?", [$_SESSION['Login']['account_id'], $_SESSION['Login']['account']]);
    $accarray = $query->FetchRow();
    if ($query->RecordCount() != 1 || encodeSession($accarray['password']) != $_SESSION['Login']['key']) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    $acc = new stdClass();
    foreach ($accarray as $key => $value) {
        $acc->$key = $value;
    }

    return $acc;
}


//Function to check if user is logged in, and if so, return user data as an object
function check_user(&$db)
{
    if (!isset($_SESSION['Login'])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    $query = $db->execute("SELECT * FROM `accounts` WHERE `id`=? AND `conta`=?", [$_SESSION['Login']['account_id'], $_SESSION['Login']['account']]);
    $accarray = $query->FetchRow();
    if ($query->RecordCount() != 1 || encodeSession($accarray['password']) != $_SESSION['Login']['key']) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    if ($_SESSION['Login']['player_id']) {
        $query = $db->execute("SELECT * FROM `players` WHERE `id`=? AND `acc_id`=?", [$_SESSION['Login']['player_id'], $_SESSION['Login']['account_id']]);
        $playerarray = $query->FetchRow();
        if ($query->RecordCount() == 1) {
            $player = new stdClass();
            foreach ($playerarray as $key => $value) {
                $player->$key = $value;
            }

            return $player;
        }

        header("Location: characters.php");
        exit;
    }

    header("Location: characters.php");
    exit;
}

function multiploCinco($valor): float
{
    return round($valor / 5) * 5;
}

/* function maxHp($level, $reino = '1', $vip = '0'){
    if (($reino == '3') or ($vip > time())) {
        return multiploCinco(ceil(100 + (($level + 1) * 20)) * 1.08);
    } else {
        return ceil(100 + (($level + 1) * 20));
    }
} */

function maxHp(&$db, $phpid, $level, $reino = '1', $vip = '0'): float
{
    $bonus = 0;
    $queryBonuz = $db->execute("SELECT `item_id`, `vit`, `item_bonus` FROM `items` WHERE `player_id`=? AND `status`='equipped'", [$phpid]);
    while ($itemBonus = $queryBonuz->FetchRow()) {
        if ($itemBonus['vit'] > 0) {
            $bonus += ($itemBonus['vit'] * 20);
        } else {
            $itemBonusType = $db->GetOne("SELECT `type` FROM `blueprint_items` WHERE `id`=?", [$itemBonus['item_id']]);
            if ($itemBonusType == 'amulet') {
                $itemBonusValue = $db->GetOne("SELECT `effectiveness` FROM `blueprint_items` WHERE `id`=?", [$itemBonus['item_id']]);
                $bonus += (($itemBonusValue + ($itemBonus['item_bonus'] * 2)) * 20);
            }
        }
    }

    $playerVit = $db->GetOne("SELECT `vitality` FROM `players` WHERE `id`=?", [$phpid]);

    if ($reino == '3' || $vip > time()) {
        return multiploCinco(ceil(150 + ($level * 20)) * 1.08 + $bonus + (($playerVit - 1) * 20));
    }

    return ceil(150 + ($level * 20) + $bonus + (($playerVit - 1) * 20));
}

function maxMana($level, $extramana = '0'): float
{
    $dividecinco = (($level + 1) / 5);
    $dividecinco = floor($dividecinco);
    return 75 + ($dividecinco * 15) + $extramana;
}

function maxExp($level): float
{
    if ($level < 10) {
        $bonus = 5;
    } elseif ($level < 30) {
        $bonus = 4;
    } elseif ($level < 60) {
        $bonus = 3;
    } elseif ($level < 80) {
        $bonus = 2;
    } elseif ($level < 120) {
        $bonus = 1;
    } else {
        $bonus = 0;
    }

    return multiploCinco((30 + ($level / 15) - $bonus) * ($level + 1) * ($level + 1));
}


function maxExpr($level): float
{
    return multiploCinco((30 + ($level / 15)) * ($level + 1) * ($level + 1) - 20);
}

function maxEnergy($level, $vip = '0'): float
{
    $fdividevinte = $vip > time() ? ($level + 1) / 10 : ($level + 1) / 20;

    $fdividevinte = floor($fdividevinte);
    return 100 + ($fdividevinte * 10);
}

/* function maxExp($level){
    return floor(30 * (($level + 1) * ($level + 1) * ($level + 1))/($level + 1));
} */



//Gets the number of unread messages
function unread_messages($id, &$db)
{
    $query = $db->GetOne("SELECT COUNT(*) AS `count` FROM `mail` WHERE `to`=? AND `status`='unread'", [$id]);
    return $query['count'];
}

//Gets new log messages
function unread_log($id, &$db)
{
    $query = $db->GetOne("SELECT COUNT(*) AS `count` FROM `user_log` WHERE `player_id`=? AND `status`='unread'", [$id]);
    return $query['count'];
}

//Insert a log message into the user logs
function addlog($id, $msg, &$db): void
{
    $insert['player_id'] = $id;
    $insert['msg'] = $msg;
    $insert['time'] = time();
    $db->Autoexecute('user_log', $insert, 'INSERT');
}

//Insert a log message into the error log
function errorlog($msg, &$db): void
{
    $insert['msg'] = $msg;
    $insert['time'] = time();
    $db->Autoexecute('log_errors', $insert, 'INSERT');
}

//Insert a log message into the GM log
function gmlog($msg, &$db): void
{
    $insert['msg'] = $msg;
    $insert['time'] = time();
    $db->Autoexecute('log_gm', $insert, 'INSERT');
}

//Insert a log message into the forum log
function forumlog($msg, &$db, $type = 0, $post = 0): void
{
    if ($type == 1 && $post > 0) {
        $insert['msg'] = $msg;
        $insert['time'] = time();
        $insert['type'] = $type;
        $insert['post'] = $post;
        $query = $db->Autoexecute('log_forum', $insert, 'INSERT');
    } elseif ($type == 2 && $post > 0) {
        $insert['msg'] = $msg;
        $insert['time'] = time();
        $insert['type'] = $type;
        $insert['post'] = $post;
        $query = $db->Autoexecute('log_forum', $insert, 'INSERT');
    } else {
        $insert['msg'] = $msg;
        $insert['time'] = time();
        $query = $db->Autoexecute('log_forum', $insert, 'INSERT');
    }
}



//Get all settings variables
$query = $db->execute("SELECT `name`, `value` FROM `settings`");
if ($query === false) {
    die("Query failed: " . $db->ErrorMsg());
}

$setting = new stdClass();
while ($set = $query->FetchRow()) {
    $setting->{$set['name']} = $set['value'];
}

function textLimit($string, $length, $lineBreak = null, string $replacer = '...')
{
    // Limitar o texto e adicionar reticências, se necessário
    if (strlen((string) $string) > $length) {
        $string = (preg_match('/^(.*)\W.*$/', substr((string) $string, 0, $length + 1), $matches) ? $matches[1] : substr((string) $string, 0, $length)) . $replacer;
    }

    // Adicionar quebras de linha a cada X caracteres, se o parâmetro $lineBreak for passado
    if ($lineBreak !== null && $lineBreak > 0) {
        $string = wordwrap((string) $string, $lineBreak, "<br>\n", true); // Garantir que as quebras sejam forçadas
    }

    return $string;
}


function antiBreak($comment, $leght): void
{
    $array = explode(" ", (string) $comment);

    for ($i = 0, $array_num = count($array); $i < $array_num; ++$i) {
        $word_split = wordwrap($array[$i], $leght, " ", true);
        echo $word_split . ' ';
    }
}

//Get the player's IP address
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];


//Gets the number of items owned
function item_count($id, $item, &$db)
{
    $query = $db->GetOne("SELECT COUNT(*) AS `count` FROM `items` WHERE `item_id`=? AND `player_id`=?", [$item, $id]);
    return $query['count'];
}


function show_prog_bar($width, $percent, string $show, $type = 'green', string $color = '#000'): string
{
    $font = 'Tahoma';
    $font_size = '8px';
    $font_weight = 'bold';

    $percent = min($percent, 100);
    $width -= 2;
    $result = (($percent * $width) / 100);
    $return = '';
    $return .= '<div name="progress">';
    $return .= '<div style="background: url(\'static/images/bars//progress.gif\') no-repeat; height: 13px; width: 1px; display: block; float: left"><!-- --></div>';
    $return .= '<div style="background: url(\'static/images/bars//bg.gif\'); height: 13px; width: ' . $width . 'px; display: block; float: left">';
    $return .= '<span style="background: url(\'static/images/bars/on_' . strtolower((string) $type) . ".gif'); display: block; float: left; width: " . $result . 'px; height: 11px; margin: 1px 0; font-size: ' . $font_size . "; font-family: '" . $font . "'; line-height: 11px; font-weight: " . $font_weight . '; text-align: right; color: ' . $color . '; letter-spacing: 1px;">&nbsp;' . $show . '&nbsp;</span>';

    $return .= '</div>';
    $return .= '<div style="background: url(\'static/images/bars//progress.gif\') no-repeat; height: 13px; width: 1px; display: block; float: left"><!-- --></div>';
    return $return . '</div>';
}

function showAlert(string $msg, $color = '#FFFDE0', string $align = 'center', $link = NULL, $id = NULL): string
{

    if ($color == 'red') {
        $color = "#EEA2A2";
    } elseif ($color == 'green') {
        $color = "#45E61D";
    } else {
        $color = '#FFFDE0';
    }

    if ($link) {
        $return .= '<a href="' . $link . '" style="text-decoration: none;">';
        $return .= "<div ";
        $return .= 'id = "' . $id . '" ';

        $return .= "class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" style=\"color: #000000; padding: 5px; border: 1px solid #DEDEDE; margin-bottom: 10px; text-align: " . $align . ';">';
    } else {
        $return .= "<div ";
        $return .= 'id = "' . $id . '" ';

        $return .= 'style="background-color:' . $color . "; padding: 5px; border: 1px solid #DEDEDE; margin-bottom: 10px; text-align: " . $align . ';">';
    }

    $return .= $msg;
    $return .= "</div>";

    if ($link) {
        $return .= "</a>";
    }

    return $return;
}

function parseInt($string): string|int
{
    //	return intval($string); 
    if (preg_match('/(\d+)/', (string) $string, $array)) {
        return $array[1];
    }

    return 0;
}


function showName($name, &$db, $status = 'on', $link = 'on'): string
{
    $ninguem = 0;
    if ($name == NULL || is_numeric($name) && $name < 1) {
        $ninguem = 5;
    } elseif (is_numeric($name)) {
        $user = $db->GetOne("SELECT `username` FROM `players` WHERE `id`=?", [$name]);
    } else {
        $user = $name;
        $name = $db->GetOne("SELECT `id` FROM `players` WHERE `username`=?", [$name]);
    }



    if ($ninguem != 5) {

        if ($status != "off") {
            $player = check_user($db);
            $online = $db->execute("SELECT `time` FROM `user_online` WHERE `player_id`=?", [$name]);
            $ignorado = $db->execute("SELECT * FROM `ignored` WHERE `uid`=? AND `bid`=?", [$name, $player->id]);
            if ($online->RecordCount() > 0 && $ignorado->RecordCount() == 0) {
                $check = $db->execute("SELECT * FROM `pending` WHERE `pending_id`=30 AND `player_id`=?", [$name]);
                if ($check->RecordCount() == 0) {
                    $return .= "<a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ", "_", $user) . "')\"><img src=\"static/images/online.png\" border=\"0px\"></a>";
                } else {
                    $stattus = $check->FetchRow();
                    if ($stattus['pending_status'] == 'ocp') {
                        $return .= "<a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ", "_", $user) . "')\"><img src=\"static/images/ocupado.png\" border=\"0px\"></a>";
                    } elseif ($stattus['pending_status'] == 'inv') {
                        $return .= '<img src="static/images/invisivel.png" border="0px">';
                    }
                }
            }
        }

        $get = $db->execute(sprintf("SELECT * FROM `players` WHERE `username` = '%s' AND subname > '2'", $user));

        if ($get->RecordCount() > 0) {

            while ($while_name = $get->FetchRow()) {
                $sub = $while_name['subname'];
                $pieces = explode(", ", (string) $sub);


                $subname_set = ' [<font color="' . $pieces[1] . '">' . $pieces[0] . "</font>]";
            }
        }

        $closevip = false;
        $pvipaccid = $db->execute("SELECT `acc_id` FROM `players` WHERE `id`=?", [$name]);
        $pviptime = $db->execute("SELECT `vip` FROM `players` WHERE `id`=?", [$name]);
        if (parseInt($pviptime) > time()) {
            $hidevip = $db->execute("SELECT * FROM `other` WHERE `value`=? AND `player_id`=?", ['hidevip', parseInt($pvipaccid)]);
            if ($hidevip->RecordCount() == 0) {
                $closevip = true;
            }
        }

        if ($link != "off") {
            if ($closevip) {
                $return .= '<a href="profile.php?id=' . $user . '"><font color="blue">';
            } else {
                $return .= '<a href="profile.php?id=' . $user . '">';
            }

            if ($user == $player->username) {
                $return .= "<b>" . $player->username . "</b>" . $subname_set . "";
            } else {
                $return .= "" . $user . "" . $subname_set . "";
            }

            $return .= "</a>";
        } elseif ($user == $player->username) {
            $return .= "<b>" . $player->username . "</b>";
        } else {
            $return .= $user;
        }

        if ($closevip) {
            $return .= "</font>";
        }
    } else {
        $return = "Ninguém";
    }

    return $return;
}

function filtro($data)
{
    $data = trim(htmlentities(strip_tags((string) $data)));

    // Remove the deprecated check
    $data = $db->real_escape_string($data);
    return str_replace("([^0-9])", "", $data) . "";
}

function send_mail($from_name, $mail_to, $subject, $body)
{
    include(__DIR__ . "/config.php");
    require(__DIR__ . "/../vendor/phpmailer/phpmailer/class.phpmailer.php");

    $mail = new PHPMailer();
    $mail->isSMTP();

    $mail->Host = $smtp_host;
    $mail->SMTPAuth = $has_smtp_auth;
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;
    $mail->SMTPSecure = $smtp_security_method;
    $mail->Port = $smtp_port;

    $mail->From = $smtp_username;
    $mail->FromName = $from_name;
    $mail->addAddress($mail_to);

    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body = $body;

    return $mail->send();
}
