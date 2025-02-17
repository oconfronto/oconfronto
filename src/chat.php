<?php

declare(strict_types=1);

session_start();

if (($_GET['action'] ?? null) == "chatheartbeat") {
	chatHeartbeat();
}

if (($_GET['action'] ?? null) == "sendchat") {
	sendChat();
}

if (($_GET['action'] ?? null) == "closechat") {
	closeChat();
}

if (($_GET['action'] ?? null) == "startchatsession") {
	startChatSession();
}

if (!($_SESSION['chatHistory'] ?? null)) {
	$_SESSION['chatHistory'] = [];
}

if (!($_SESSION['openChatBoxes'] ?? null)) {
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
		if (!isset($_SESSION['chatHistory'][$chat['from']])) {
			$_SESSION['chatHistory'][$chat['from']] = '';
		}

		if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && !empty($_SESSION['chatHistory'][$chat['from']])) {
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
		foreach ($_SESSION['openChatBoxes'] ?? null as $chatbox => $void) {
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
	"level": "<?php echo $_SESSION['level'] ?? null; ?>",
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
	
	if (empty($_POST['to']) || empty($_POST['message'])) {
		echo "0";
		exit(0);
	}
	
	$from = str_replace(" ", "_", $player->username);
	$to = str_replace(" ", "_", $_POST['to']);
	$message = $_POST['message'];

	if (!isset($_SESSION['chatHistory'][$to])) {
		$_SESSION['chatHistory'][$to] = '';
	}

	$_SESSION['openChatBoxes'][$to] = date('Y-m-d H:i:s');
	
	$messagesan = sanitize($message);
	
	$_SESSION['chatHistory'][$to] .= <<<EOD
	{
		"s": "1",
		"f": "{$to}",
		"m": "{$messagesan}"
	},
EOD;

	unset($_SESSION['tsChatBoxes'][$to]);

	$sql = "insert into chat (chat.from, chat.to, message, sent) values (" . 
		   $db->qstr($from) . ", " . 
		   $db->qstr($to) . ", " . 
		   $db->qstr($message) . ", NOW())";
		   
	$query = $db->execute($sql);
	
	if ($query) {
		echo "1";
	} else {
		echo "0";
	}
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
