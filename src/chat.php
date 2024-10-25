<?php

declare(strict_types=1);

session_start();

if ($_GET['action'] == "chatheartbeat") {
	chatHeartbeat();
}

if ($_GET['action'] == "sendchat") {
	sendChat();
}

if ($_GET['action'] == "closechat") {
	closeChat();
}

if ($_GET['action'] == "startchatsession") {
	startChatSession();
}

if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = [];
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = [];
}

function chatHeartbeat(): void
{

	include(__DIR__ . "/lib.php");
	$player = check_user($db);

	$sql = "select * from chat where (chat.to = '" . str_replace(" ", "_", $player->username) . "' AND recd = 0) order by id ASC";
	$query = $db->execute($sql);
	$items = '';

	$chatBoxes = [];

	while ($chat = $query->fetchrow()) {

		if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && isset($_SESSION['chatHistory'][$chat['from']])) {
			$items = $_SESSION['chatHistory'][$chat['from']];
		}

		$chat['message'] = sanitize($chat['message']);

		$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;

		if (!isset($_SESSION['chatHistory'][$chat['from']])) {
			$_SESSION['chatHistory'][$chat['from']] = '';
		}

		$_SESSION['chatHistory'][$chat['from']] .= <<<EOD
						   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;

		unset($_SESSION['tsChatBoxes'][$chat['from']]);
		$_SESSION['openChatBoxes'][$chat['from']] = $chat['sent'];
	}

	if (!empty($_SESSION['openChatBoxes'])) {
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
			if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {

				$now = time() - strtotime((string) $time);

				$mes = date("M", strtotime((string) $time));
				$mes_ano["Jan"] = "Jan";
				$mes_ano["Feb"] = "Fev";
				$mes_ano["Mar"] = "Mar";
				$mes_ano["Apr"] = "Abr";
				$mes_ano["May"] = "Mai";
				$mes_ano["Jun"] = "Jun";
				$mes_ano["Jul"] = "Jul";
				$mes_ano["Aug"] = "Ago";
				$mes_ano["Sep"] = "Set";
				$mes_ano["Oct"] = "Out";
				$mes_ano["Nov"] = "Nov";
				$mes_ano["Dec"] = "Dez";

				$message = "Enviado em " . date('d', strtotime((string) $time)) . " " . $mes_ano[$mes] . ", " . date('g:i A', strtotime((string) $time)) . "";

				if ($now > (10800 + 240)) {
					$items .= <<<EOD
{
"s": "2",
"f": "{$chatbox}",
"m": "{$message}"
},
EOD;

					if (!isset($_SESSION['chatHistory'][$chatbox])) {
						$_SESSION['chatHistory'][$chatbox] = '';
					}

					$_SESSION['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "{$chatbox}",
"m": "{$message}"
},
EOD;
					$_SESSION['tsChatBoxes'][$chatbox] = 1;
				}
			}
		}
	}

	$sql = "update chat set recd = 1 where chat.to = '" . str_replace(" ", "_", $player->username) . "' and recd = 0";
	$query = $db->execute($sql);

	if ($items !== '') {
		$items = substr($items, 0, -1);
	}

	header('Content-type: application/json');
?>
	{
	"items": [
	<?php echo $items; ?>
	]
	}

<?php
	exit(0);
}

function chatBoxSession($chatbox)
{

	return $_SESSION['chatHistory'][$chatbox] ?? '';
}

function startChatSession(): void
{
	$items = '';
	if (!empty($_SESSION['openChatBoxes'])) {
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
			$items .= chatBoxSession($chatbox);
		}
	}


	if ($items !== '') {
		$items = substr($items, 0, -1);
	}

	header('Content-type: application/json');
	include(__DIR__ . "/lib.php");
	$player = check_user($db);
?>
	{
	"username": "<?php echo str_replace(" ", "_", $player->username); ?>",
	"level": "<?php echo $_SESSION['level']; ?>",
	"items": [
	<?php echo $items; ?>
	]
	}

<?php


	exit(0);
}

function sendChat(): void
{
	include(__DIR__ . "/lib.php");
	$player = check_user($db);
	$from = str_replace(" ", "_", $player->username);
	$to = str_replace(" ", "_", $_POST['to']);
	$message = $_POST['message'];

	$_SESSION['openChatBoxes'][str_replace(" ", "_", $_POST['to'])] = date('d-m-Y H:i:s', time());

	$messagesan = sanitize($message);

	if (!isset($_SESSION['chatHistory'][str_replace(" ", "_", $_POST['to'])])) {
		$_SESSION['chatHistory'][str_replace(" ", "_", $_POST['to'])] = '';
	}

	$_SESSION['chatHistory'][str_replace(" ", "_", $_POST['to'])] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}"
	   },
EOD;


	unset($_SESSION['tsChatBoxes'][str_replace(" ", "_", $_POST['to'])]);

	$sql = "insert into chat (chat.from,chat.to,message,sent) values ('" . $db->qstr($from) . "', '" . $db->qstr($to) . "','" . $db->qstr($message) . "',NOW())";
	$query = $db->execute($sql);
	echo "1";
	exit(0);
}

function closeChat(): void
{

	unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);

	echo "1";
	exit(0);
}

function sanitize($text): string
{
	$text = htmlspecialchars((string) $text, ENT_QUOTES);
	$text = str_replace("\n\r", "\n", $text);
	$text = str_replace("\r\n", "\n", $text);
	return str_replace("\n", "<br>", $text);
}
