<?php
include("lib.php");
define("PAGENAME", "Mudanças Recentes");
$player = check_user($secret_key, $db);

$error = 0;

include("templates/private_header.php");

if (($player->gm_rank >= 90)){

if(!empty($_GET['delid'])){
$id_log = $_GET['delid'];

if(OCv2::totaldados(changelog,id,$id_log,false)){
if(!$_GET['confirm'] == yes){
echo showAlert("Deseja realmente deletar este log? <a href='?delid=$id_log&confirm=yes'>Sim</a> | <a href='changelog.php'>Não</a>", "red");
}
if($_GET['confirm'] == yes){
$db->execute("DELETE FROM changelog WHERE id=$id_log");
echo showAlert("Log deletado com sucesso", "red");
}
}else{
echo showAlert("Log não encontrado", "red");
}
}

if(!empty($_GET['id'])){
$edit = $db->execute("select * from changelog where id = $_GET[id] limit 1");
if ($edit->recordcount() > 0){
while($lisquery = $edit->fetchrow())
{
$title_edit = $lisquery['title'];
$comentario_edit = $lisquery['descricao'];

}
}else{
echo showAlert("Log não encontrado", "red");
}
}

if($_POST['submit'] == 'Criar Log'){
$title = $_POST['title'];
$comentario = $_POST['comentario'];
$categoria_log = $_POST['categoria_log'];
$sistema_log = $_POST['sistema_log'];
$vota = $_POST['vota'];

if(!empty($title) and !empty($comentario) and !empty($categoria_log) and !empty($sistema_log) and !empty($vota)){

$insert['title'] = $title;
$insert['descricao'] = $comentario;
$insert['adm_name'] = $player->username;
$insert['time'] = time();
$insert['type'] = $categoria_log;
$insert['type_categoria'] = $sistema_log;
$insert['type_status'] = $vota;

$registra = $db->autoexecute('changelog', $insert, 'INSERT');
echo showAlert("Log adicionado com sucesso", "green");
}else{
echo showAlert("Dados Incompletos, Tente novamente.", "red");
}
}
}


if(!empty($_POST['categoria'])){
$query_1 = $db->execute("select * from list_sistemas where pg = 1 and id = $_POST[categoria] limit 1");
while($lisquery = $query_1->fetchrow())
{


if ($_POST['categoria'] == $lisquery['id']) {
	$searchtype = "`type`='".$lisquery[id]."'";
}else{
	$searchtype = "";
}
}
}


if(!empty($_POST['sistema'])){
$query_2 = $db->execute("select * from list_sistemas where pg = 0 and id = $_POST[sistema] limit 1");
while($lisquery = $query_2->fetchrow())
{
	
	if ($_POST['sistema'] == $lisquery['id']) {
	$searchsubtype = "`type_categoria`=$lisquery[id]";
	} 
}
}




if(!empty($searchtype) || !empty($searchsubtype)){
$and1 = "where";
}

if(!empty($searchtype) and !empty($searchsubtype)){
$and1 = "where";
$and = "and";
}

$query2 = $db->execute("select * from list_sistemas order by id");

while($lisquery = $query2->fetchrow())
{

	if ($_POST['sistema'] == $listquery['nome']) {
	$seletype = "sele" . $lisquery['id'] . "";
	}
} 


?>
<form method="post" action="">
<table width="100%" class="brown"  style='border:1px solid #b6804e;height:28px;background:url(images/bg-barra-form.png) center;'><tr>


<td>

<b>Categoria:</b>
<select name="categoria">
<option value="">Listar Todos Dados</option>
<?php 
$query = $db->execute("select * from list_sistemas where pg = 1 order by id");
while($lisquery = $query->fetchrow())
{
?>
<option value="<?php echo $lisquery['id']; ?>"><?php echo $lisquery['nome']; ?></option>
<?php
}
?>
</select>
</td>

<td >
<b>Sistema:</b>
<select name="sistema">
<option value="">Listar Todos Dados</option>
<?php 
$query = $db->execute("select * from list_sistemas where pg = 0 order by id");
while($lisquery = $query->fetchrow())
{
echo "<option value=\"".$lisquery['id']."\">".$lisquery['nome']."</option>";
}
?>
</select>
</td>

<th width="30%" ><input id="link" class="aff" type="submit" value="Procurar" /></th>
</table>
</form>



<!-- Painel ADM -->
<?php
if (($player->gm_rank >= 90))
{
?>




<div id="usr">
<form method="post" action="">
<table width="100%" >
<tr>
<tbody>
<tr>
<td class="brown" width="100%">
<center><b>Criar novo log</b></center>
</td>
</tr>
<tr class="salmon">
<td>
<b>Título:</b> 
<input name="title" size="26" value="<?php echo $title_edit; ?>" type="text"> 

<b>Categoria:</b>
<select name="categoria_log">
<?php 
$query = $db->execute("select * from list_sistemas where pg = 1 order by id");
while($lisquery = $query->fetchrow())
{
?>
<option value="<?php echo $lisquery['id']; ?>"><?php echo $lisquery['nome']; ?></option>
<?php
}
?>
</select>



<b>Sistema:</b>
<select name="sistema_log">
<?php 
$query = $db->execute("select * from list_sistemas where pg = 0 order by id");
while($lisquery = $query->fetchrow())
{
echo "<option value=\"".$lisquery['id']."\">".$lisquery['nome']."</option>";
}
?>
</select>
</form>
</td>
</tr>
<tr class="salmon">
<td>
<table width="100%" border="0">
<tbody>
<td style="width: 100%;">
<textarea name="comentario" rows="5"  class="ed"><?php echo $comentario_edit; ?></textarea>
</td>



<tr>
<td width="50%">
<input name="submit" value="Criar Log" type="submit">

<?php 
$query = $db->execute("select * from list_sistemas where pg = 2 order by id");
while($lisquery = $query->fetchrow())
{
echo "<input name=\"vota\" value=\"".$lisquery['id']."\" type=\"radio\"> ".$lisquery['nome']."";
}
?>

</td>
</td>



</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</form>

<?php
}
?>
<!-- Painel ADM -->

<p>
<table width="100%"><tbody>
<tr>
<td class="brown" >
<b>Lista de Alterações</b>
<img src="images/help.gif" title="header=[Informação] body=[<font size='1px'>Veja abaixo a lista de todas as alterações no game que foram feita ou estão em andamento.</font>]">

</td>
</tr>

<?php 
$query_global = $db->execute("select * from changelog $and1 $searchtype $and $searchsubtype order by id desc limit 20");
if ($query_global->recordcount() > 0){
while($member = $query_global->fetchrow()){


$query_3 = $db->execute("select * from list_sistemas where pg = 2 and id = $member[type_status]");
while($lisquery = $query_3->fetchrow()){

	if (isset($lisquery[color_class])) {
		$cor_bar = $lisquery['color_class'];
		$status_second = $lisquery['nome'];
	}
}

	
?>

<!-- inicio -->
<tr>
<td class="class_color_<?php echo $cor_bar; ?>" width="100%">
<table border="0" width="100%">
<tbody>
<tr>
<td width="80%">
<font size="2px"><?php echo $member['title'] ?> - <?php echo $member['adm_name'] ?> 
<?php
if (($player->gm_rank >= 90)){
?>
<b><a href="?id=<?php echo $member['id']; ?>">Editar</a> / <a href="?delid=<?php echo $member['id']; ?>">Deletar</a>
<?php
}
?>
</b><br> 
<b>Comentário:</b> <?php echo $member['descricao'] ?>
<?php
$query_n = $db->execute("select * from list_sistemas where id = $member[type_categoria]");

while($lisquery = $query_n->fetchrow())
{

	echo "<br/><b>Sistema:</b> $lisquery[nome]";
	}

?>
</font>
</td>
<th align="right" width="20%"><font size="2px"><?php echo $status_second; ?></font></th>
</table>
<!-- fim -->




<?php 
} 
}else{
?>
<tr>
<td class="on" width="100%">
<table border="0" width="100%">
<tbody>
<tr>
<td width="80%">
<font size="2px">Não foram encontrado dados disponíveis<br>

</font>
</td>
<th align="right" width="20%"><font size="2px"><?php echo $status_second; ?></font></th>
</table>
<?php
} 
?>
</tbody>

</table>
<p>



<?php
include("templates/private_footer.php");
?>