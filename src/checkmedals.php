<?php
$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='1'", array($player->id, 'Imortal'));
if ($medalha->recordcount() < 1) {
if (($player->level > 14) and ($player->deaths == 0)){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Imortal";
	$insert['type'] = '1';
	$insert['motivo'] = "Passou do nível 14 sem nunca ter morrido.";
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por passar do nível 14 sem nunca ter morrido.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você passou do nível 14 sem nunca ter morrido.<br/>";
echo "Uma medalha de bronze foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='2'", array($player->id, 'Imortal'));
if ($medalha->recordcount() < 1) {
if (($player->level > 24) and ($player->deaths == 0)){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Imortal";
	$insert['type'] = '2';
	$insert['motivo'] = "Passou do nível 24 sem nunca ter morrido.";
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por passar do nível 24 sem nunca ter morrido.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você passou do nível 24 sem nunca ter morrido.<br/>";
echo "Uma medalha de prata foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='3'", array($player->id, 'Imortal'));
if ($medalha->recordcount() < 1) {
if (($player->level > 34) and ($player->deaths == 0)){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Imortal";
	$insert['type'] = '3';
	$insert['motivo'] = "Passou do nível 34 sem nunca ter morrido.";
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por passar do nível 34 sem nunca ter morrido.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você passou do nível 34 sem nunca ter morrido.<br/>";
echo "Uma medalha de ouro foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='1'", array($player->id, 'Assassino'));
if ($medalha->recordcount() < 1) {
if ($player->kills > 500){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Assassino";
	$insert['type'] = '1';
	$insert['motivo'] = "Matou mais de 500 usuários.";
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por matar mais de 500 usuários.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você matou mais de 500 usuários.<br/>";
echo "Uma medalha de bronze foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='2'", array($player->id, 'Assassino'));
if ($medalha->recordcount() < 1) {
if ($player->kills > 1000){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Assassino";
	$insert['type'] = '2';
	$insert['motivo'] = "Matou mais de 1000 usuários.";
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por matar mais de 1000 usuários.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você matou mais de 1000 usuários.<br/>";
echo "Uma medalha de prata foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='3'", array($player->id, 'Assassino'));
if ($medalha->recordcount() < 1) {
if ($player->kills > 2000){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Assassino";
	$insert['type'] = '3';
	$insert['motivo'] = "Matou mais de 2000 usuários.";
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por matar mais de 2000 usuários.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você matou mais de 2000 usuários.<br/>";
echo "Uma medalha de ouro foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='1'", array($player->id, 'Exterminador'));
if ($medalha->recordcount() < 1) {
if ($player->monsterkilled > 10000){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Exterminador";
	$insert['motivo'] = "Matou mais de 10000 monstros.";
	$insert['type'] = '1';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por matar mais de 10000 monstros.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você matou mais de 10000 monstros.<br/>";
echo "Uma medalha de bronze foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='2'", array($player->id, 'Exterminador'));
if ($medalha->recordcount() < 1) {
if ($player->monsterkilled > 50000){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Exterminador";
	$insert['motivo'] = "Matou mais de 50000 monstros.";
	$insert['type'] = '2';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por matar mais de 50000 monstros.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você matou mais de 50000 monstros.<br/>";
echo "Uma medalha de prata foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='3'", array($player->id, 'Exterminador'));
if ($medalha->recordcount() < 1) {
if ($player->monsterkilled > 100000){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Exterminador";
	$insert['motivo'] = "Matou mais de 100000 monstros.";
	$insert['type'] = '3';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por matar mais de 100000 monstros.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você matou mais de 100000 monstros.<br/>";
echo "Uma medalha de ouro foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='1'", array($player->id, 'Milionário'));
if ($medalha->recordcount() < 1) {
if (($player->gold + $player->bank) > 10000000){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Milionário";
	$insert['motivo'] = "Juntou mais de 10 milhões em ouro.";
	$insert['type'] = '1';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por juntar mais de 10 milhões em ouro.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você juntou mais de 10 milhões em ouro.<br/>";
echo "Uma medalha de bronze foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='2'", array($player->id, 'Milionário'));
if ($medalha->recordcount() < 1) {
if (($player->gold + $player->bank) > 50000000){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Milionário";
	$insert['motivo'] = "Juntou mais de 50 milhões em ouro.";
	$insert['type'] = '2';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por juntar mais de 50 milhões em ouro.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você juntou mais de 50 milhões em ouro.<br/>";
echo "Uma medalha de prata foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='3'", array($player->id, 'Milionário'));
if ($medalha->recordcount() < 1) {
if (($player->gold + $player->bank) > 100000000){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Milionário";
	$insert['motivo'] = "Juntou mais de 100 milhões em ouro.";
	$insert['type'] = '3';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por juntar mais de 100 milhões em ouro.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você juntou mais de 100 milhões em ouro.<br/>";
echo "Uma medalha de ouro foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='1'", array($player->id, 'Indicador'));
if ($medalha->recordcount() < 1) {
if ($player->ref > 10){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Indicador";
	$insert['motivo'] = "Convidou mais de 10 amigos para o jogo.";
	$insert['type'] = '1';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por convidar mais de 10 amigos para o jogo.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você convidou mais de 10 amigos para o jogo.<br/>";
echo "Uma medalha de bronze foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='2'", array($player->id, 'Indicador'));
if ($medalha->recordcount() < 1) {
if ($player->ref > 25){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Indicador";
	$insert['motivo'] = "Convidou mais de 25 amigos para o jogo.";
	$insert['type'] = '2';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por convidar mais de 25 amigos para o jogo.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você convidou mais de 25 amigos para o jogo.<br/>";
echo "Uma medalha de prata foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='3'", array($player->id, 'Indicador'));
if ($medalha->recordcount() < 1) {
if ($player->ref > 50){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Indicador";
	$insert['motivo'] = "Convidou mais de 50 amigos para o jogo.";
	$insert['type'] = '3';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por convidar mais de 50 amigos para o jogo.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você convidou mais de 50 amigos para o jogo.<br/>";
echo "Uma medalha de ouro foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='1'", array($player->id, 'Veterano'));
if ($medalha->recordcount() < 1) {

	$diff = time() - $player->registered;
	$age = floor(($diff / 3600) / 24);

	if ($age >= 90){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Veterano";
	$insert['motivo'] = "Jogador é mais de 3 meses.";
	$insert['type'] = '1';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por jogar a mais de 3 meses.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você já é jogador a mais de 3 meses.<br/>";
echo "Uma medalha de bronze foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='2'", array($player->id, 'Veterano'));
if ($medalha->recordcount() < 1) {

	$diff = time() - $player->registered;
	$age = floor(($diff / 3600) / 24);

	if ($age >= 180){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Veterano";
	$insert['motivo'] = "Jogador é mais de 6 meses.";
	$insert['type'] = '2';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por jogar a mais de 6 meses.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você já é jogador a mais de 6 meses.<br/>";
echo "Uma medalha de prata foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

$medalha = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=? and `type`='3'", array($player->id, 'Veterano'));
if ($medalha->recordcount() < 1) {

	$diff = time() - $player->registered;
	$age = floor(($diff / 3600) / 24);

	if ($age >= 365){
	$insert['player_id'] = $player->id;   	  
	$insert['medalha'] = "Veterano";
	$insert['motivo'] = "Jogador é mais de 1 ano.";
	$insert['type'] = '3';
	$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		$insert['fname'] = $player->username;
		$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> ganhou uma medalha por jogar a mais de 1 ano.";
		$insert['time'] = time();
		$query = $db->autoexecute('log_friends', $insert, 'INSERT');

echo "Parabéns, você já é jogador a mais de 1 ano.<br/>";
echo "Uma medalha de ouro foi adicionada ao seu perfil por este motivo.<br/><a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}
}

?>