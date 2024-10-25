<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");

$query = $db->execute("select `username`, `level`, `guild`, `voc`, `promoted` from `players` where `id`=?", [$_GET['id']]);
$user = $query->fetchrow();

if ($user['voc'] == 'archer') {
	$useimage = "images/arqueiro.png";
	if ($user['promoted'] == "f") {
		$voca = "Cacador";
	} elseif ($user['promoted'] == "p") {
		$voca = "Arqueiro Royal";
	} else {
		$voca = "Arqueiro";
	}
} elseif ($user['voc'] == 'knight') {
	$useimage = "images/cavaleiro.png";
	if ($user['promoted'] != "f") {
		$voca = "Guerreiro";
	} elseif ($user['promoted'] == "p") {
		$voca = "Cavaleiro";
	} else {
		$voca = "Espadachim";
	}
} elseif ($user['voc'] == 'mage') {
	$useimage = "images/mago.png";
	if ($user['promoted'] != "f") {
		$voca = "Bruxo";
	} elseif ($user['promoted'] == "p") {
		$voca = "Arquimago";
	} else {
		$voca = "Mago";
	}
}


function LoadPNG($imgname): \GdImage|false
{
	$im = @imagecreatefrompng($imgname); /* Attempt to open */
	if (!$im || !$_GET['id']) { /* See if it failed */
		$im = imagecreatetruecolor(150, 30); /* Create a blank image */
		$bgc = imagecolorallocate($im, 255, 255, 255);
		$tc = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
		/* Output an errmsg */
		imagestring($im, 1, 5, 5, "Erro carregando a imagem...", $tc);
	}
 
	return $im;
}

header('Content-Type: image/png');
$img = LoadPNG($useimage);

if ($user['voc'] == 'archer') {
	$color = imagecolorallocate($img, 0, 0, 0);
} elseif ($user['voc'] == 'knight') {
	$color = imagecolorallocate($img, 255, 255, 255);
} elseif ($user['voc'] == 'mage') {
	$color = imagecolorallocate($img, 255, 255, 255);
}

imagestring($img, 2, 10, 135, (string) $domain, $color);


imagettftext($img, 15, 0, 63, 30, $color, "font.ttf", ucfirst((string) $user['username']));
imagettftext($img, 15, 0, 59, 60, $color, "font.ttf", (string) $user['level']);

if ($user['guild'] == NULL || $user['guild'] == '') {
	$gangue = "nenhum";
} else {
	$gangue = $db->GetOne("select `name` from `guilds` where `id`=?", [$user['guild']]);
}

imagettftext($img, 15, 0, 48, 90, $color, "font.ttf", (string) $gangue);
imagettftext($img, 15, 0, 83, 120, $color, "font.ttf", (string) $voca);



imagepng($img);

?>
