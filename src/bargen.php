<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);

$pbonusfor = 0;
$pbonusvit = 0;
$pbonusagi = 0;
$pbonusres = 0;
$countstats = $db->query("select `for`, `vit`, `agi`, `res` from `items` where `player_id`=? and `status`='equipped'", [$player->id]);
while ($count = $countstats->fetchrow()) {
	$pbonusfor += $count['for'];
	$pbonusvit += $count['vit'];
	$pbonusagi += $count['agi'];
	$pbonusres += $count['res'];
}


include(__DIR__ . '/barclass.php');
if (isset($_REQUEST['exp'])) {
	$bar = new barGen();	// Load the class
	$bar->setWidth(450);	// Set the width
	$bar->setHeight(12);	// Set the height
	$bar->setFontSize(1);	// Set the font size
	$bar->makeBar();		// Start the bar

	$bar->setFillColor(184, 148, 1);
	$bar->setData(maxExp($player->level), $player->exp);
} elseif (isset($_REQUEST['hp'])) {
	$bar = new barGen();	// Load the class
	$bar->setWidth(150);	// Set the width
	$bar->setHeight(12);	// Set the height
	$bar->setFontSize(1);	// Set the font size
	$bar->makeBar();		// Start the bar

	$bar->setFillColor(167, 3, 1);
	$bar->setData($player->maxhp, $player->hp);
} elseif (isset($_REQUEST['mana'])) {
	$bar = new barGen();	// Load the class
	$bar->setWidth(150);	// Set the width
	$bar->setHeight(12);	// Set the height
	$bar->setFontSize(1);	// Set the font size
	$bar->makeBar();		// Start the bar

	$bar->setFillColor(9, 42, 83);
	$bar->setData($player->maxmana, $player->mana);
} elseif (isset($_REQUEST['energy'])) {
	$bar = new barGen();	// Load the class
	$bar->setWidth(150);	// Set the width
	$bar->setHeight(12);	// Set the height
	$bar->setFontSize(1);	// Set the font size
	$bar->makeBar();		// Start the bar

	$bar->setFillColor(0, 81, 0);
	$bar->setData($player->maxenergy, $player->energy);
} elseif (isset($_REQUEST['for'])) {
	$bar = new barGen();	// Load the class
	$bar->setWidth(130);	// Set the width
	$bar->setHeight(12);	// Set the height
	$bar->setFontSize(0);	// Set the font size
	$bar->makeBar();		// Start the bar

	$bar->setFillColor(120, 120, 120);
	$bar->setData(($player->vitality + $player->agility + $player->resistance + $player->strength + $pbonusfor + $pbonusvit + $pbonusagi + $pbonusres), ($player->strength + $pbonusfor));
} elseif (isset($_REQUEST['vit'])) {
	$bar = new barGen();	// Load the class
	$bar->setWidth(130);	// Set the width
	$bar->setHeight(12);	// Set the height
	$bar->setFontSize(0);	// Set the font size
	$bar->makeBar();		// Start the bar

	$bar->setFillColor(0, 128, 0);
	$bar->setData(($player->vitality + $player->agility + $player->resistance + $player->strength + $pbonusfor + $pbonusvit + $pbonusagi + $pbonusres), $player->vitality + $pbonusvit);
} elseif (isset($_REQUEST['agi'])) {
	$bar = new barGen();	// Load the class
	$bar->setWidth(130);	// Set the width
	$bar->setHeight(12);	// Set the height
	$bar->setFontSize(0);	// Set the font size
	$bar->makeBar();		// Start the bar

	$bar->setFillColor(0, 0, 255);
	$bar->setData(($player->vitality + $player->agility + $player->resistance + $player->strength + $pbonusfor + $pbonusvit + $pbonusagi + $pbonusres), $player->agility + $pbonusagi);
} elseif (isset($_REQUEST['res'])) {
	$bar = new barGen();	// Load the class
	$bar->setWidth(130);	// Set the width
	$bar->setHeight(12);	// Set the height
	$bar->setFontSize(0);	// Set the font size
	$bar->makeBar();		// Start the bar

	$bar->setFillColor(255, 0, 0);
	$bar->setData(($player->vitality + $player->agility + $player->resistance + $player->strength + $pbonusfor + $pbonusvit + $pbonusagi + $pbonusres), $player->resistance + $pbonusres);
} elseif (isset($_REQUEST['man'])) {
	$bar = new barGen();	// Load the class
	$bar->setWidth(130);	// Set the width
	$bar->setHeight(12);	// Set the height
	$bar->setFontSize(0);	// Set the font size
	$bar->makeBar();		// Start the bar

	$bar->setFillColor(0, 0, 255);
	$bar->setData(($player->maxmana + (($player->level + 9) * 2)), $player->maxmana + $player->extramana);
} else {
	exit();
}

$bar->generateBar();
