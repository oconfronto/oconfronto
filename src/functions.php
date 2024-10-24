<?php
// CLASSES NOVAS PARA OC VERSÃO 2.0 //
class OCv2
{
	function info_db($data, $data2, $data3, $data4)
	{
		$query = mysql_query("SELECT * FROM `$data` WHERE `$data2` = '$data3'");
		while ($row = mysql_fetch_array($query)) {
			return $row[$data4];
		}
		return false;
	}
	function totaldados($data, $data2 = false, $data3 = true, $vl = false)
	{
		if (!$vl) {
			$vll = '=';
		} else {
			$vll = '>';
		}
		if (!$data2 || !$data3) {
			$query = mysql_query("SELECT * FROM $data");
			return mysql_num_rows($query);
		} else {
			$query = mysql_query("SELECT * FROM $data WHERE $data2 $vll '$data3'")
				or print(ERROR);
			if (empty($erro)) {
				return mysql_num_rows($query);
			}
		}
		return false;
	}

	function tirarCMoeda($valor)
	{
		$pontos = '.';
		$virgula = ',';
		$result = str_replace($pontos, "", $valor);
		$result2 = str_replace($virgula, "", $result);
		return $result2;
	}
	function verificar($valor)
	{
		$pontos = ',';
		$virgula = '0';
		$result = str_replace($pontos, "", $valor);
		$result2 = str_replace($virgula, "", $result);
		return $result2;
	}
}
// FIM DO MEU CODE LINDO //

function encodePassword($password)
{

	$salt = $_ENV['PASSWORD_SALT'];
	$hash = sha1($password . $salt);

	for ($i = 0; $i < 1000; $i++) {
		$hash = sha1($hash);
	}

	return $hash;
}

function encodeSession($account_id)
{

	$pepper = "$n203hc29*&%&Hd";
	$hash = sha1($account_id . $pepper . $_SERVER["REMOTE_ADR"]);

	for ($i = 0; $i < 1000; $i++) {
		$hash = sha1($hash);
	}

	return $hash;
}


function check_acc($secret_key, &$db)
{
	if (!isset($_SESSION['Login'])) {
		session_unset();
		session_destroy();
		header("Location: index.php");
		exit;
	} else {
		$query = $db->execute("select * from `accounts` where `id`=? and `conta`=?", array($_SESSION['Login']['account_id'], $_SESSION['Login']['account']));
		$accarray = $query->fetchrow();

		if (($query->recordcount() != 1) or (encodeSession($accarray['password']) != $_SESSION['Login']['key'])) {
			session_unset();
			session_destroy();
			header("Location: index.php");
			exit;
		} else {
			foreach ($accarray as $key => $value) {
				$acc->$key = $value;
			}

			return $acc;
		}
	}
}


//Function to check if user is logged in, and if so, return user data as an object
function check_user($secret_key, &$db)
{
	if (!isset($_SESSION['Login'])) {
		session_unset();
		session_destroy();
		header("Location: index.php");
		exit;
	} else {
		$query = $db->execute("select * from `accounts` where `id`=? and `conta`=?", array($_SESSION['Login']['account_id'], $_SESSION['Login']['account']));
		$accarray = $query->fetchrow();

		if (($query->recordcount() != 1) or (encodeSession($accarray['password']) != $_SESSION['Login']['key'])) {
			session_unset();
			session_destroy();
			header("Location: index.php");
			exit;
		} else {
			if ($_SESSION['Login']['player_id']) {
				$query = $db->execute("select * from `players` where `id`=? and `acc_id`=?", array($_SESSION['Login']['player_id'], $_SESSION['Login']['account_id']));
				$playerarray = $query->fetchrow();

				if ($query->recordcount() == 1) {
					foreach ($playerarray as $key => $value) {
						$player->$key = $value;
					}

					return $player;
				} else {
					header("Location: characters.php");
					exit;
				}
			} else {
				header("Location: characters.php");
				exit;
			}
		}
	}
}

function multiploCinco($valor)
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

function maxHp(&$db, $phpid, $level, $reino = '1', $vip = '0')
{
	$bonus = 0;
	$queryBonuz = $db->execute("select `item_id`, `vit`, `item_bonus` from `items` where `player_id`=? and `status`='equipped'", array($phpid));
	while ($itemBonus = $queryBonuz->fetchrow()) {
		if ($itemBonus['vit'] > 0) {
			$bonus += ($itemBonus['vit'] * 20);
		} else {
			$itemBonusType = $db->GetOne("select `type` from `blueprint_items` where `id`=?", array($itemBonus['item_id']));
			if ($itemBonusType == 'amulet') {
				$itemBonusValue = $db->GetOne("select `effectiveness` from `blueprint_items` where `id`=?", array($itemBonus['item_id']));
				$bonus += (($itemBonusValue + ($itemBonus['item_bonus'] * 2)) * 20);
			}
		}
	}

	$playerVit = $db->GetOne("select `vitality` from `players` where `id`=?", array($phpid));

	if (($reino == '3') or ($vip > time())) {
		return multiploCinco(ceil(150 + ($level * 20)) * 1.08 + $bonus + (($playerVit - 1) * 20));
	} else {
		return ceil(150 + ($level * 20) + $bonus + (($playerVit - 1) * 20));
	}
}

function maxMana($level, $extramana = '0')
{
	$dividecinco = (($level + 1) / 5);
	$dividecinco = floor($dividecinco);
	return 75 + ($dividecinco * 15) + $extramana;
}

function maxExp($level)
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


function maxExpr($level)
{
	return multiploCinco((30 + ($level / 15)) * ($level + 1) * ($level + 1) - 20);
}

function maxEnergy($level, $vip = '0')
{
	if ($vip > time()) {
		$fdividevinte = (($level + 1) / 10);
	} else {
		$fdividevinte = (($level + 1) / 20);
	}
	$fdividevinte = floor($fdividevinte);
	return 100 + ($fdividevinte * 10);
}

/* function maxExp($level){
	return floor(30 * (($level + 1) * ($level + 1) * ($level + 1))/($level + 1));
} */



//Gets the number of unread messages
function unread_messages($id, &$db)
{
	$query = $db->getone("select count(*) as `count` from `mail` where `to`=? and `status`='unread'", array($id));
	return $query['count'];
}

//Gets new log messages
function unread_log($id, &$db)
{
	$query = $db->getone("select count(*) as `count` from `user_log` where `player_id`=? and `status`='unread'", array($id));
	return $query['count'];
}

//Insert a log message into the user logs
function addlog($id, $msg, &$db)
{
	$insert['player_id'] = $id;
	$insert['msg'] = $msg;
	$insert['time'] = time();
	$query = $db->autoexecute('user_log', $insert, 'INSERT');
}

//Insert a log message into the error log
function errorlog($msg, &$db)
{
	$insert['msg'] = $msg;
	$insert['time'] = time();
	$query = $db->autoexecute('log_errors', $insert, 'INSERT');
}

//Insert a log message into the GM log
function gmlog($msg, &$db)
{
	$insert['msg'] = $msg;
	$insert['time'] = time();
	$query = $db->autoexecute('log_gm', $insert, 'INSERT');
}

//Insert a log message into the forum log
function forumlog($msg, &$db, $type = 0, $post = 0)
{
	if (($type == 1) and ($post > 0)) {
		$insert['msg'] = $msg;
		$insert['time'] = time();
		$insert['type'] = $type;
		$insert['post'] = $post;
		$query = $db->autoexecute('log_forum', $insert, 'INSERT');
	} elseif (($type == 2) and ($post > 0)) {
		$insert['msg'] = $msg;
		$insert['time'] = time();
		$insert['type'] = $type;
		$insert['post'] = $post;
		$query = $db->autoexecute('log_forum', $insert, 'INSERT');
	} else {
		$insert['msg'] = $msg;
		$insert['time'] = time();
		$query = $db->autoexecute('log_forum', $insert, 'INSERT');
	}
}



//Get all settings variables
$query = $db->execute("select SQL_CACHE `name`, `value` from `settings`");
while ($set = $query->fetchrow()) {
	$setting->$set['name'] = $set['value'];
}

function textLimit($string, $length, $lineBreak = null, $replacer = '...')
{
	// Limitar o texto e adicionar reticências, se necessário
	if (strlen($string) > $length) {
		$string = (preg_match('/^(.*)\W.*$/', substr($string, 0, $length + 1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;
	}

	// Adicionar quebras de linha a cada X caracteres, se o parâmetro $lineBreak for passado
	if ($lineBreak !== null && $lineBreak > 0) {
		$string = wordwrap($string, $lineBreak, "<br>\n", true); // Garantir que as quebras sejam forçadas
	}

	return $string;
}


function antiBreak($comment, $leght)
{
	$array = explode(" ", $comment);

	for ($i = 0, $array_num = count($array); $i < $array_num; $i++) {
		$word_split = wordwrap($array[$i], $leght, " ", true);
		echo "$word_split ";
	}
}

//Get the player's IP address
$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];


//Gets the number of items owned
function item_count($id, $item, &$db)
{
	$query = $db->getone("select count(*) as `count` from `items` where `item_id`=? and `player_id`=?", array($item, $id));
	return $query['count'];
}


function show_prog_bar($width, $percent, $show, $type = 'green', $color = '#000')
{
	$font = 'Tahoma';
	$font_size = '8px';
	$font_weight = 'bold';

	$percent = min($percent, 100);
	$width -= 2;
	$result = (($percent * $width) / 100);
	$return = '';
	$return .= '<div name="progress">';
	$return .= '<div style="background: url(\'images/bars//progress.gif\') no-repeat; height: 13px; width: 1px; display: block; float: left"><!-- --></div>';
	$return .= '<div style="background: url(\'images/bars//bg.gif\'); height: 13px; width: ' . $width . 'px; display: block; float: left">';
	$return .= '<span style="background: url(\'images/bars/on_' . strtolower($type) . '.gif\'); display: block; float: left; width: ' . $result . 'px; height: 11px; margin: 1px 0; font-size: ' . $font_size . '; font-family: \'' . $font . '\'; line-height: 11px; font-weight: ' . $font_weight . '; text-align: right; color: ' . $color . '; letter-spacing: 1px;">&nbsp;' . $show . '&nbsp;</span>';

	$return .= '</div>';
	$return .= '<div style="background: url(\'images/bars//progress.gif\') no-repeat; height: 13px; width: 1px; display: block; float: left"><!-- --></div>';
	$return .= '</div>';
	return $return;
}

function showAlert($msg, $color = '#FFFDE0', $align = 'center', $link = NULL, $id = NULL)
{

	if ($color == 'red') {
		$color = "#EEA2A2";
	} elseif ($color == 'green') {
		$color = "#45E61D";
	} else {
		$color = '#FFFDE0';
	}

	if ($link) {
		$return .= "<a href=\"" . $link . "\" style=\"text-decoration: none;\">";
		$return .= "<div ";
		if (id) {
			$return .= "id = \"" . $id . "\" ";
		}
		$return .= "class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" style=\"color: #000000; padding: 5px; border: 1px solid #DEDEDE; margin-bottom: 10px; text-align: " . $align . ";\">";
	} else {
		$return .= "<div ";
		if (id) {
			$return .= "id = \"" . $id . "\" ";
		}
		$return .= "style=\"background-color:" . $color . "; padding: 5px; border: 1px solid #DEDEDE; margin-bottom: 10px; text-align: " . $align . ";\">";
	}
	$return .= $msg;
	$return .= "</div>";

	if ($link) {
		$return .= "</a>";
	}
	return $return;
}

function parseInt($string)
{
	//	return intval($string); 
	if (preg_match('/(\d+)/', $string, $array)) {
		return $array[1];
	} else {
		return 0;
	}
}


function showName($name, &$db, $status = 'on', $link = 'on')
{
	$ninguem = 0;
	if (($name == NULL) or ((is_numeric($name)) and ($name < 1))) {
		$ninguem = 5;
	} elseif (is_numeric($name)) {
		$user = $db->GetOne("select `username` from `players` where `id`=?", array($name));
	} else {
		$user = $name;
		$name = $db->GetOne("select `id` from `players` where `username`=?", array($name));
	}



	if ($ninguem != 5) {

		if ($status != off) {
			$player = check_user($secret_key, $db);
			$online = $db->execute("select `time` from `user_online` where `player_id`=?", array($name));
			$ignorado = $db->execute("select * from `ignored` where `uid`=? and `bid`=?", array($name, $player->id));
			if (($online->recordcount() > 0) and ($ignorado->recordcount() == 0)) {
				$check = $db->execute("select * from `pending` where `pending_id`=30 and `player_id`=?", array($name));
				if ($check->recordcount() == 0) {
					$return .= "<a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ", "_", $user) . "')\"><img src=\"images/online.png\" border=\"0px\"></a>";
				} else {
					$stattus = $check->fetchrow();
					if ($stattus['pending_status'] == 'ocp') {
						$return .= "<a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ", "_", $user) . "')\"><img src=\"images/ocupado.png\" border=\"0px\"></a>";
					} elseif ($stattus['pending_status'] == 'inv') {
						$return .= "<img src=\"images/invisivel.png\" border=\"0px\">";
					}
				}
			}
		}
		$get = $db->execute("select * from `players` where `username` = '$user' and subname > '2'");

		if ($get->recordcount() > 0) {

			while ($while_name = $get->fetchrow()) {
				$sub = $while_name['subname'];
				$pieces = explode(", ", $sub);


				$subname_set = " [<font color=\"" . $pieces[1] . "\">" . $pieces[0] . "</font>]";
			}
		}
		$closevip = false;
		$pvipaccid = $db->execute("select `acc_id` from `players` where `id`=?", array($name));
		$pviptime = $db->execute("select `vip` from `players` where `id`=?", array($name));
		if (parseInt($pviptime) > time()) {
			$hidevip = $db->execute("select * from `other` where `value`=? and `player_id`=?", array('hidevip', parseInt($pvipaccid)));
			if ($hidevip->recordcount() == 0) {
				$closevip = true;
			}
		}

		if ($link != off) {
			if ($closevip) {
				$return .= "<a href=\"profile.php?id=" . $user . "\"><font color=\"blue\">";
			} else {
				$return .= "<a href=\"profile.php?id=" . $user . "\">";
			}
			if ($user == $player->username) {
				$return .= "<b>" . $player->username . "</b>" . $subname_set . "";
			} else {
				$return .= "" . $user . "" . $subname_set . "";
			}
			$return .= "</a>";
		} else {
			if ($user == $player->username) {
				$return .= "<b>" . $player->username . "</b>";
			} else {
				$return .= $user;
			}
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
	$data = trim(htmlentities(strip_tags($data)));
	if (get_magic_quotes_gpc())
		$data = stripslashes($data);
	$data = mysql_real_escape_string($data);
	$data = str_replace("([^0-9])", "", $data) . "";
	return $data;
}

function send_mail($from_name, $mail_to, $subject, $body)
{
	include("config.php");
	require("../vendor/phpmailer/phpmailer/class.phpmailer.php");

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
