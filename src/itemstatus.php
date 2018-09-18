<?php

$checkitem1 = $db->execute("select items.for, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='amulet' and items.status='equipped'", array($player->id));
$checkitem1 = $checkitem1->fetchrow();
$val888r1 = $checkitem1['for'];

$checkitem2 = $db->execute("select items.for, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='armor' and items.status='equipped'", array($player->id));
$checkitem2 = $checkitem2->fetchrow();
$val888r2 = $checkitem2['for'];

$checkitem3 = $db->execute("select items.for, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='boots' and items.status='equipped'", array($player->id));
$checkitem3 = $checkitem3->fetchrow();
$val888r3 = $checkitem3['for'];

$checkitem4 = $db->execute("select items.for, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='helmet' and items.status='equipped'", array($player->id));
$checkitem4 = $checkitem4->fetchrow();
$val888r4 = $checkitem4['for'];

$checkitem5 = $db->execute("select items.for, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='legs' and items.status='equipped'", array($player->id));
$checkitem5 = $checkitem5->fetchrow();
$val888r5 = $checkitem5['for'];

$checkitem6 = $db->execute("select items.for, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='shield' and items.status='equipped'", array($player->id));
$checkitem6 = $checkitem6->fetchrow();
$val888r6 = $checkitem6['for'];

$checkitem7 = $db->execute("select items.for, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='weapon' and items.status='equipped'", array($player->id));
$checkitem7 = $checkitem7->fetchrow();
$val888r7 = $checkitem7['for'];

$forcaadebonus = $val888r1 + $val888r2 + $val888r3 + $val888r4 + $val888r5 + $val888r6 + $val888r7;




$checkitem1 = $db->execute("select items.vit, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='amulet' and items.status='equipped'", array($player->id));
$checkitem1 = $checkitem1->fetchrow();
$val888r1 = $checkitem1['vit'];

$checkitem2 = $db->execute("select items.vit, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='armor' and items.status='equipped'", array($player->id));
$checkitem2 = $checkitem2->fetchrow();
$val888r2 = $checkitem2['vit'];

$checkitem3 = $db->execute("select items.vit, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='boots' and items.status='equipped'", array($player->id));
$checkitem3 = $checkitem3->fetchrow();
$val888r3 = $checkitem3['vit'];

$checkitem4 = $db->execute("select items.vit, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='helmet' and items.status='equipped'", array($player->id));
$checkitem4 = $checkitem4->fetchrow();
$val888r4 = $checkitem4['vit'];

$checkitem5 = $db->execute("select items.vit, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='legs' and items.status='equipped'", array($player->id));
$checkitem5 = $checkitem5->fetchrow();
$val888r5 = $checkitem5['vit'];

$checkitem6 = $db->execute("select items.vit, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='shield' and items.status='equipped'", array($player->id));
$checkitem6 = $checkitem6->fetchrow();
$val888r6 = $checkitem6['vit'];

$checkitem7 = $db->execute("select items.vit, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='weapon' and items.status='equipped'", array($player->id));
$checkitem7 = $checkitem7->fetchrow();
$val888r7 = $checkitem7['vit'];

$vitalidadeeeeebonus = $val888r1 + $val888r2 + $val888r3 + $val888r4 + $val888r5 + $val888r6 + $val888r7;



$checkitem1 = $db->execute("select items.agi, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='amulet' and items.status='equipped'", array($player->id));
$checkitem1 = $checkitem1->fetchrow();
$val888r1 = $checkitem1['agi'];

$checkitem2 = $db->execute("select items.agi, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='armor' and items.status='equipped'", array($player->id));
$checkitem2 = $checkitem2->fetchrow();
$val888r2 = $checkitem2['agi'];

$checkitem3 = $db->execute("select items.agi, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='boots' and items.status='equipped'", array($player->id));
$checkitem3 = $checkitem3->fetchrow();
$val888r3 = $checkitem3['agi'];

$checkitem4 = $db->execute("select items.agi, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='helmet' and items.status='equipped'", array($player->id));
$checkitem4 = $checkitem4->fetchrow();
$val888r4 = $checkitem4['agi'];

$checkitem5 = $db->execute("select items.agi, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='legs' and items.status='equipped'", array($player->id));
$checkitem5 = $checkitem5->fetchrow();
$val888r5 = $checkitem5['agi'];

$checkitem6 = $db->execute("select items.agi, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='shield' and items.status='equipped'", array($player->id));
$checkitem6 = $checkitem6->fetchrow();
$val888r6 = $checkitem6['agi'];

$checkitem7 = $db->execute("select items.agi, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='weapon' and items.status='equipped'", array($player->id));
$checkitem7 = $checkitem7->fetchrow();
$val888r7 = $checkitem7['agi'];

$agilidadeeedebonus = $val888r1 + $val888r2 + $val888r3 + $val888r4 + $val888r5 + $val888r6 + $val888r7;



$checkitem1 = $db->execute("select items.res, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='amulet' and items.status='equipped'", array($player->id));
$checkitem1 = $checkitem1->fetchrow();
$val888r1 = $checkitem1['res'];

$checkitem2 = $db->execute("select items.res, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='armor' and items.status='equipped'", array($player->id));
$checkitem2 = $checkitem2->fetchrow();
$val888r2 = $checkitem2['res'];

$checkitem3 = $db->execute("select items.res, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='boots' and items.status='equipped'", array($player->id));
$checkitem3 = $checkitem3->fetchrow();
$val888r3 = $checkitem3['res'];

$checkitem4 = $db->execute("select items.res, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='helmet' and items.status='equipped'", array($player->id));
$checkitem4 = $checkitem4->fetchrow();
$val888r4 = $checkitem4['res'];

$checkitem5 = $db->execute("select items.res, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='legs' and items.status='equipped'", array($player->id));
$checkitem5 = $checkitem5->fetchrow();
$val888r5 = $checkitem5['res'];

$checkitem6 = $db->execute("select items.res, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='shield' and items.status='equipped'", array($player->id));
$checkitem6 = $checkitem6->fetchrow();
$val888r6 = $checkitem6['res'];

$checkitem7 = $db->execute("select items.res, blueprint_items.id from `items`, `blueprint_items` where items.player_id=? and blueprint_items.id=items.item_id and blueprint_items.type='weapon' and items.status='equipped'", array($player->id));
$checkitem7 = $checkitem7->fetchrow();
$val888r7 = $checkitem7['res'];

$resistenciaaaadebonus = $val888r1 + $val888r2 + $val888r3 + $val888r4 + $val888r5 + $val888r6 + $val888r7;




?>