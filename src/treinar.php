ï»¿<?php

if(isset($_POST['treinar_strength'])){
	if($_POST['restante']<0){ echo "<script>self.location='?p=home'</script>"; break; }
	$strength=$_POST['treinar_strength'];
	$vitality=$_POST['treinar_vitality'];
	$agility=$_POST['treinar_agility'];
	if(($strength<=0)or($vitality<=0)or($agility<=0)){ echo "<script>self.location='?p=home'</script>"; break; }
	$total=0;
	if($strength>$player->strength) do{
		$total=$total+round(($player->strength*2)+($player->strength*$player->strength)+($player->strength*0.2));
		$player->strength=$player->strength+1;
	} while($strength>$player->strength);
	if($vitality>$player->vitality) do{
		$total=$total+round(($player->vitality*2)+($player->vitality*$player->vitality)+($player->vitality*0.2));
		$player->vitality=$player->vitality+1;
	} while($vitality>$player->vitality);
	if($agility>$player->agility) do{
		$total=$total+round(($player->agility*2)+($player->agility*$player->agility)+($player->agility*0.2));
		$player->agility=$player->agility+1;
	} while($agility>$player->agility);
	if($total>$player->gold){ echo "<script>self.location='?p=treinar&msg=2'</script>"; break; }
	mysql_query("UPDATE players SET gold=gold-$total, strength=$strength, vitality=$vitality, agility=$agility WHERE id=".$player->id);
	echo "<script>self.location='?p=treinar&msg=1&gold=".$total."'</script>";
}
?>
<script>
var strength=<?php echo $player->strength; ?>;
var taipadrao=strength;
var vitality=<?php echo $player->vitality; ?>;
var ninpadrao=vitality;
var agility=<?php echo $player->agility; ?>;
var genpadrao=agility;
var total=0;
var gold=<?php echo $player->gold; ?>;
function visibility(){
	setastrength=document.getElementById('taidown');
	setavitality=document.getElementById('nindown');
	setaagility=document.getElementById('gendown');
	if(strength<=taipadrao) setastrength.style.visibility='hidden'; else setastrength.style.visibility='visible';
	if(vitality<=ninpadrao) setavitality.style.visibility='hidden'; else setavitality.style.visibility='visible';
	if(agility<=genpadrao) setaagility.style.visibility='hidden'; else setaagility.style.visibility='visible';
	if((strength<=taipadrao)&&(vitality<=ninpadrao)&&(agility<=genpadrao)) document.getElementById('treinar_button').style.display='none'; else document.getElementById('treinar_button').style.display='block';
}
function float2moeda(num) {
   x = 0;
   if(num<0) {
      num = Math.abs(num);
      x = 1;
   }
      if(isNaN(num)) num = "0";
      cents = Math.floor((num*100+0.5)%100);
   num = Math.floor((num*100+0.5)/100).toString();
   if(cents < 10) cents = "0" + cents;
      for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
         num = num.substring(0,num.length-(4*i+3))+'.'
               +num.substring(num.length-(4*i+3));
			   ret = num + ',' + cents;
			   if (x == 1) ret = ' - ' + ret;return ret;
}
function soma(ind,direcao){
	if(ind=='tai'){
		if(direcao=='up')
			somar=Math.round((strength*2)+(strength*strength)+(strength*0.2));
		else {
			somar=Math.round(((strength-1)*2)+((strength-1)*(strength-1))+((strength-1)*0.2));
			somar=(somar)*(-1);
		}
	}
	if(ind=='nin'){
		if(direcao=='up')
			somar=Math.round((vitality*2)+(vitality*vitality)+(vitality*0.2));
		else {
			somar=Math.round(((vitality-1)*2)+((vitality-1)*(vitality-1))+((vitality-1)*0.2));
			somar=(somar)*(-1);
		}
	}
	if(ind=='gen'){
		if(direcao=='up')
			somar=Math.round((agility*2)+(agility*agility)+(agility*0.2));
		else {
			somar=Math.round(((agility-1)*2)+((agility-1)*(agility-1))+((agility-1)*0.2));
			somar=(somar)*(-1);
		}
	}
	total=total+somar;
	restante=gold-total;
	document.getElementById('totaltreinar').innerHTML=float2moeda(total);
	document.getElementById('resttreinar').innerHTML=float2moeda(restante);
	document.forms[0].restante.value=restante;
}
function newvalues(att){
	if(att=='tai'){
		valor=Math.round((strength*2)+(strength*strength)+(strength*0.2));
		document.getElementById('taivalue').innerHTML=float2moeda(valor)+' gold';
	} else
	if(att=='nin'){
		valor=Math.round((vitality*2)+(vitality*vitality)+(vitality*0.2));
		document.getElementById('ninvalue').innerHTML=float2moeda(valor)+' gold';
	} else
	if(att=='gen'){
		valor=Math.round((agility*2)+(agility*agility)+(agility*0.2));
		document.getElementById('genvalue').innerHTML=float2moeda(valor)+' gold';
	}
}
function sortNumber(a,b){
	return b - a;
}
function atualizabarras(){
	max=194;
	array=new Array(strength,vitality,agility);
	array.sort(sortNumber);
	array2=new Array(strength,vitality,agility);
	if(array[0]==array2[0]) document.getElementById('taibar').setAttribute('width',Math.round((max*array[0])/array[0])); else
	if(array[1]==array2[0]) document.getElementById('taibar').setAttribute('width',Math.round((max*array[1])/array[0])); else
	if(array[2]==array2[0]) document.getElementById('taibar').setAttribute('width',Math.round((max*array[2])/array[0]));
	if(array[0]==array2[1]) document.getElementById('ninbar').setAttribute('width',Math.round((max*array[0])/array[0])); else
	if(array[1]==array2[1]) document.getElementById('ninbar').setAttribute('width',Math.round((max*array[1])/array[0])); else
	if(array[2]==array2[1]) document.getElementById('ninbar').setAttribute('width',Math.round((max*array[2])/array[0]));
	if(array[0]==array2[2]) document.getElementById('genbar').setAttribute('width',Math.round((max*array[0])/array[0])); else
	if(array[1]==array2[2]) document.getElementById('genbar').setAttribute('width',Math.round((max*array[1])/array[0])); else
	if(array[2]==array2[2]) document.getElementById('genbar').setAttribute('width',Math.round((max*array[2])/array[0]));
}
function change(at,dir){
	if(dir>0)
		soma(at,'up');
	else
		soma(at,'down');
	if(at=='tai'){
		el=document.getElementById('tai');
		el.innerHTML=strength+dir;
		strength=strength+dir;
		document.forms[0].treinar_strength.value=(document.forms[0].treinar_strength.value*1)+dir;
		visibility();
		newvalues('tai');
	} else
	if(at=='nin'){
		el=document.getElementById('nin');
		el.innerHTML=vitality+dir;
		vitality=vitality+dir;
		document.forms[0].treinar_vitality.value=(document.forms[0].treinar_vitality.value*1)+dir;
		visibility();
		newvalues('nin');
	} else
	if(at=='gen'){
		el=document.getElementById('gen');
		el.innerHTML=agility+dir;
		agility=agility+dir;
		document.forms[0].treinar_agility.value=(document.forms[0].treinar_agility.value*1)+dir;
		visibility();
		newvalues('gen');
	}
	if(restante<0)
		document.getElementById('treinar_button').style.display='none';
	atualizabarras();
}
</script>
<?php
$max=194;
function equacao($atr){
	$resultado=round(($atr*2)+($atr*$atr)+($atr*0.2));
	return $resultado;
}
$src="static/_images/bars/bar.png";
$array=array("t"=>$player->strength,"n"=>$player->vitality,"g"=>$player->agility);
rsort($array);
$array2=array("t"=>100,"n"=>100,"g"=>100);
arsort($array2);
?>
<div class="box_top">Treino</div>
<form method="post" action="?p=treinar" onsubmit="subm.value='Carregando...';subm.disabled=true;">
<input type="hidden" id="treinar_strength" name="treinar_strength" value="<?php echo $player->strength; ?>" />
<input type="hidden" id="treinar_vitality" name="treinar_vitality" value="<?php echo $player->vitality; ?>" />
<input type="hidden" id="treinar_agility" name="treinar_agility" value="<?php echo $player->agility; ?>" />
<input type="hidden" id="restante" name="restante" value="" />
<div class="box_middle">Esta é sua área de treino de atributos. Utilize as setas abaixo para aumentar ou diminuir os atributos, e assim que estiver satisfeito, clique no botão Treinar. Os gold só serão gastos após a confirmação do treino.<div class="sep"></div><img src="static/http://img36.imageshack.us/img36/5319/treinoo.jpg" border="0">
	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: if(isset($_GET['gold'])) $gold=$_GET['gold']; else $gold=0; $msg='Treino realizado com sucesso! Foram gastos <b>'.number_format($gold,2,',','.').' gold</b> para realizar o treino.'; break;
			case 2: $msg='gold insuficientes!'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	} ?>
	<div style="padding-left:5px;background:url(_images/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="static/_images/gold.png" width="14" height="14" align="absmiddle" /> <b>Meus gold: <?php echo number_format($player->gold,2,',','.'); ?> gold</b></div>
    <div class="sep"></div>
	<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
        	<td width="13%" align="right" style="padding-right:10px;"><b>strength:</b></td>
          <td><img src="static/_images/bars/bar_left.jpg" /><?php
			if($array[0]==$array2["t"]) echo '<img id="taibar" src="static/'.$src.'" width="'.($max*$array[0])/$array[0].'" height="22" />'; else
			if($array[1]==$array2["t"]) echo '<img id="taibar" src="static/'.$src.'" width="'.($max*$array[1])/$array[0].'" height="22" />'; else
			if($array[2]==$array2["t"]) echo '<img id="taibar" src="static/'.$src.'" width="'.($max*$array[2])/$array[0].'" height="22" />';
			?><img src="static/_images/bars/bar_right.jpg" />
    		</td>
            <td width="8%"><img src="static/_images/up_arrow.png" style="cursor:pointer" onclick="change('tai',1);" /> <img id="taidown" src="static/_images/down_arrow.png" style="cursor:pointer;visibility:hidden;" onclick="change('tai',-1);" /></td>
            <td width="12%" align="center"><b>| <span id="tai"><?php echo $player->strength; ?></span> |</b></td>
          <td width="22%" align="right"><b><div id="taivalue"><?php echo number_format(equacao($player->strength),2,',','.'); ?> gold</div></b></td>
        </tr>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>vitality:</b></td>
          <td><img src="static/_images/bars/bar_left.jpg" /><?php
			if($array[0]==$array2["n"]) echo '<img id="ninbar" src="static/'.$src.'" width="'.($max*$array[0])/$array[0].'" height="22" />'; else
			if($array[1]==$array2["n"]) echo '<img id="ninbar" src="static/'.$src.'" width="'.($max*$array[1])/$array[0].'" height="22" />'; else
			if($array[2]==$array2["n"]) echo '<img id="ninbar" src="static/'.$src.'" width="'.($max*$array[2])/$array[0].'" height="22" />';
			?><img src="static/_images/bars/bar_right.jpg" />
            </td>
            <td><img src="static/_images/up_arrow.png" style="cursor:pointer" onclick="change('nin',1);" /> <img id="nindown" src="static/_images/down_arrow.png" style="cursor:pointer;visibility:hidden;" onclick="change('nin',-1);" /></td>
            <td align="center"><b>| <span id="nin"><?php echo $player->vitality; ?></span> |</b></td>
          <td align="right"><b><div id="ninvalue"><?php echo number_format(equacao($player->vitality),2,',','.'); ?> gold</div></b></td>
        </tr>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>agility:</b></td>
          <td><img src="static/_images/bars/bar_left.jpg" /><?php
			if($array[0]==$array2["g"]) echo '<img id="genbar" src="static/'.$src.'" width="'.($max*$array[0])/$array[0].'" height="22" />'; else
			if($array[1]==$array2["g"]) echo '<img id="genbar" src="static/'.$src.'" width="'.($max*$array[1])/$array[0].'" height="22" />'; else
			if($array[2]==$array2["g"]) echo '<img id="genbar" src="static/'.$src.'" width="'.($max*$array[2])/$array[0].'" height="22" />';
			?><img src="static/_images/bars/bar_right.jpg" />
          </td>
            <td><img src="static/_images/up_arrow.png" style="cursor:pointer" onclick="change('gen',1);" /> <img id="gendown" src="static/_images/down_arrow.png" style="cursor:pointer;visibility:hidden;" onclick="change('gen',-1);" /></td>
            <td align="center"><b>| <span id="gen"><?php echo $player->agility; ?></span> |</b></td>
          <td align="right"><b><div id="genvalue"><?php echo number_format(equacao($player->agility),2,',','.'); ?> gold</div></b></td>
        </tr>
    </table>
  <div class="sep"></div>
    <div style="padding-left:5px;background:url(_images/gradient.jpg) repeat-y"><img src="static/_images/gold_neg.png" align="absmiddle" /> <b>Total: <span id="totaltreinar"><?php echo number_format(0,2,',','.'); ?></span> gold</b></div>
    <div class="sep"></div>
    <div style="padding-left:5px;background:url(_images/gradient.jpg) repeat-y"><img src="static/_images/gold.png" width="14" height="14" align="absmiddle" /> <b>Restará: <span id="resttreinar"><?php echo number_format($player->gold,2,',','.'); ?></span> gold</b></div>
    <div id="treinar_button" style="display:none;"><div class="sep"></div>
    <div align="center"><input type="submit" id="subm" name="subm" class="botao" value="Treinar" /></div></div>
</div>
</form>
<div class="box_bottom"></div>
