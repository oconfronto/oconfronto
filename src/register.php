<?php
   if (time() < 1345222800)
    {
            header("Location: beta.php?error=true");
            exit;
    }
    
include("lib.php");
define("PAGENAME", "Criar Conta");
    
srand((double)microtime()*1000000);  //sets random seed
$string = md5(rand(0,1000000));

if ($_GET['r'])
{
    $_SESSION['ref'] = $_GET['r'];
}

if (($_SESSION['ref'] != null) and (is_numeric($_SESSION['ref'])))
{
    $usaar = $_SESSION['ref'];
} else {
    $usaar = "1";
}

    
    $error = 0;
    
    $erro1 = 0;
    $erro2 = 0;
    $erro3 = 0;
    
    $certo1 = 0;
    $certo2 = 0;
    $certo3 = 0;
    
    $msg1 = "";
    $msg2 = "";
    $msg3 = "";
    
    if (($_POST['register']) or ($_GET['confirm']))
    {
        //Check if conta has already been used
        $query = $db->execute("select `id` from `accounts` where `conta`=?", array($_POST['conta2']));
        //Check conta
        if (!$_POST['conta2']) { //If conta isn't filled in...
            $msg1 = "Voc&ecirc; precisa digitar o nome da conta desejada.<br />\n"; //Add to error message
            $error = 1; //Set error check
            $erro1 = 1;
        }
        
        elseif (strlen($_POST['conta2']) < 3)
        { //If conta is too short...
            $msg1 = "Sua conta não pode ter menos de 3 caracteres!<br />\n"; //Add to error message
            $error = 1; //Set error check
            $erro1 = 1;
        }
        else if (strlen($_POST['conta2']) > 20)
        { //If conta is too short...
            $msg1 = "Sua conta deve ter 20 caracteres ou menos!<br />\n"; //Add to error message
            $error = 1; //Set error check
            $erro1 = 1;
        }
        else if (!preg_match("/^[-_a-zA-Z0-9]+$/", $_POST['conta2']))
        { //If conta contains illegal characters...
            $msg1 = "Sua conta não pode conter <b>caracteres especiais</b>!<br />\n"; //Add to error message
            $error = 1; //Set error check
            $erro1 = 1;
        }
        else if ($query->recordcount() > 0)
        {
            $msg1 = "Esta conta já está sendo usuada!<br />\n";
            $error = 1; //Set error check
            $erro1 = 1;
        }
        
        //Check password
        if (!$_POST['password2'])
        { //If password isn't filled in...
            $msg2 = "Voc&ecirc; precisa digitar uma senha!<br />\n"; //Add to error message
            $error = 1; //Set error check
            $erro2 = 1;
        }
        else if (strlen($_POST['password2']) < 4)
        { //If password is too short...
            $msg2 = "Sua senha deve ser maior que 3 caracteres!<br />\n"; //Add to error message
            $error = 1; //Set error check
            $erro2 = 1;
        }
        
        
        //Check email
        if (!$_POST['email2'])
        { //If email address isn't filled in...
            $msg3 = "Voc&ecirc; precisa digitar um email!<br />\n"; //Add to error message
            $error = 1; //Set error check
            $erro3 = 1;
        }
        else if (strlen($_POST['email2']) < 5)
        { //If email is too short...
            $msg3 = "O seu endereço de email deve conter mais de 5 caracteres.<br />\n"; //Add to error message
            $error = 1; //Set error check
            $erro3 = 1;
        }
        else if (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email2']))
        {
            $msg3 = "O formato do seu email é inválido!<br />\n"; //Add to error message
            $error = 1; //Set error check
            $erro3 = 1;
        }
        else
        {
            //Check if email has already been used
            $query = $db->execute("select `id` from `accounts` where `email`=?", array($_POST['email2']));
            $query2 = $db->execute("select * from `pending` where `pending_id`=1 and `pending_status`=?", array($_POST['email2']));
            if ($query->recordcount() > 0)
            {
                $msg3 = "Este email já está sendo usado por outra conta!<br />\n";
                $error = 1; //Set error check
                $erro3 = 1;
            }
            else if ($query2->recordcount() > 0)
            {
                $msg3 = "Este email já está em uso!<br />\n";
                $error = 1; //Set error check
                $erro3 = 1;
            }
        }
        
        
        if ($error == 0)
        {
            $certo4 = 0;
            $certo5 = 0;
            
            $msg4 = "";
            $msg5 = "";
            
            if ($_POST['register2'])
            {
                if (!$_POST['password3'])
                { //If password isn't filled in...
                    $msg4 = "Voc&ecirc; precisa digitar uma senha!<br />\n"; //Add to error message
                    $error = 2; //Set error check
                    $erro4 = 1;
                }
                else if (($_POST['password2']) != ($_POST['password3']))
                { //If password is too short...
                    $msg4 = "As senhas digitadas não comferem!<br />\n"; //Add to error message
                    $error = 2; //Set error check
                    $erro4 = 1;
                }
                
                if (!$_POST['email3'])
                { //If email address isn't filled in...
                    $msg5 = "Voc&ecirc; precisa digitar um email!<br />\n"; //Add to error message
                    $error = 2; //Set error check
                    $erro5 = 1;
                }
                else if (($_POST['email2']) != ($_POST['email3']))
                { //If email address isn't filled in...
                    $msg5 = "Os emails digitados não comferem!<br />\n"; //Add to error message
                    $error = 2; //Set error check
                    $erro5 = 1;
                }
                
                if ($error == 0) {
                    $insert['conta'] = $_POST['conta2'];
                    $insert['password'] = encodePassword($_POST['password2']);
                    $insert['email'] = $_POST['email2'];
                    $insert['registered'] = time();
                    $insert['last_active'] = time();
                    $insert['ip'] = $_SERVER['REMOTE_ADDR'];
                    $insert['last_ip'] = $_SERVER['REMOTE_ADDR'];
                    $insert['validkey'] = $string;
                    $insert['ref'] = $usaar;
                    $registra = $db->autoexecute('accounts', $insert, 'INSERT');
                    
                    $id = $db->Insert_ID();
					
					// INSERE DADOS DE CÓDIGO DE REFERENCIA DO CONVITE.
					$insert_ref['id_p_c'] = $id;
					$insert_ref['id_p_ref'] = $usaar;
					$insert_ref['date_regis'] = time();
					$insert_ref['event'] = $setting->event_convidados;
					$db->autoexecute('players_ref', $insert_ref, 'INSERT');
                    
					// LOCURAS DO JULIANO PARTE 1
                    /* $playerip = $db->execute("select `id` from `accounts` where `last_ip`=?", array($_SERVER['REMOTE_ADDR']));
                    if (($playerip->recordcount() > 1) and ($usaar != 1)){
                        $db->execute("update `accounts` set `ref`=? where `id`=?", array(1, $player['id']));
                    } */
                    
                    if ($registra)
                    {
                        session_unset();
                        session_start();
                        $_SESSION['Login'] = array("account_id" => $id,"account" => $_POST['conta2'],"key" => encodeSession(encodePassword($_POST['password2']))); 
                        
                        
                        include("templates/header.php");
                        echo "<span id=\"aviso-v\"></span>";
                        echo "<br/><center><p><b>Voc&ecirc; foi cadastrado com sucesso!<br />";
                        echo "Agora voc&ecirc; pode entrar no jogo. <a href=\"index.php\">Clique aqui.</a></b></p></center><br/>";
                        include("templates/footer.php");
                        exit;
                    }
                }
            }
            
            include("templates/header.php");
                echo "<span id=\"aviso-v\">";
                if ($msg4 != "") {
                    echo $msg4;
                } else if ($msg5 != "") {
                    echo $msg5;
                }
                
                if ($_POST['register2'])
                {
                    if ($msg4 == "") {
                        $certo4 = 1;
                    }
                    if ($msg5 == "") {
                        $certo5 = 1;
                    }
                }
                echo "</span>";

                echo "<center><p>Confirme suas informações para completar seu registro.</p></center>";
                echo "<form action='register.php?confirm=true' method='post'>";
                
                echo "<input type=\"hidden\" name=\"conta2\" value=\"" . $_POST['conta2'] . "\">";
                echo "<input type=\"hidden\" name=\"password2\" value=\"" . $_POST['password2'] . "\">";
                echo "<input type=\"hidden\" name=\"email2\" value=\"" . $_POST['email2'] . "\">";
                
                echo "<p><table width=\"90%\" border=\"0px\" align=\"center\">";
                echo "<tr><td width=\"28%\">";
                echo "<b>Senha:</b></td><td width=\"72%\"><input type=\"password\" name=\"password3\" value=\"" . $_POST['password3'] . "\" class=\"inp\" size=\"20\">";

                if ($erro4 == 1){
                    echo "<span id=\"erro\"></span>";
                } else if ($certo4 == 1){
                    echo "<span id=\"certo\"></span>";
                }

                echo "</td></tr>";
                echo "<tr><td width=\"28%\">";
                echo "<b>Email:</b></td><td width=\"72%\"><input type=\"text\" name=\"email3\" class=\"inp\" value=\"" . $_POST['email3'] . "\" size=\"20\">";

                if ($erro5 == 1){
                    echo "<span id=\"erro\"></span>";
                } else if ($certo5 == 1){
                    echo "<span id=\"certo\"></span>";
                }

                echo "</td></tr>";
                echo "</table>";
                echo "</p>";
                echo "<center>";
                echo "<button type=\"submit\" name=\"register2\" value=\"Registrar\" class=\"reg\"></button>";
                echo "</center>";
                echo "</form>";
            include("templates/footer.php");
            exit;

        }
	}
    

include("templates/header.php");
echo "<span id=\"aviso-v\">";

if ($msg1 != "") {
    echo $msg1;
} else if ($msg2 != "") {
    echo $msg2;
} else if ($msg3 != "") {
    echo $msg3;
}
    
    if ($_POST['register'])
    {
        if ($msg1 == "") {
            $certo1 = 1;
        }
        if ($msg2 == "") {
            $certo2 = 1;
        }
        if ($msg3 == "") {
            $certo3 = 1;
        }
    }
    
echo "</span>";
    
include("box.php");
echo "<form action='register.php' method='post'>";
echo "<p><table width=\"90%\" border=\"0px\" align=\"center\">";
echo "<tr>";
echo "<td width=\"28%\">";
echo "<b>Conta:</b></td><td width=\"72%\"><input type=\"text\" id=\"conta\" name=\"conta2\" value=\"" . $_POST['conta2'] . "\" class=\"inp\" size=\"20\"><span id=\"msgbox4\">";

if ($erro1 == 1){
    echo "<span id=\"erro\"></span>";
} else if ($certo1 == 1){
    echo "<span id=\"certo\"></span>";
}

echo "</span></td></tr>";
echo "<tr><td width=\"28%\">";
echo "<b>Senha:</b></td><td width=\"72%\"><input type=\"password\" name=\"password2\" value=\"" . $_POST['password2'] . "\" class=\"inp\" size=\"20\">";

if ($erro2 == 1){
    echo "<span id=\"erro\"></span>";
} else if ($certo2 == 1){
    echo "<span id=\"certo\"></span>";
}

echo "</td></tr>";
echo "<tr><td width=\"28%\">";
echo "<b>Email:</b></td><td width=\"72%\"><input type=\"text\" id=\"emailbox\" name=\"email2\" class=\"inp\" value=\"" . $_POST['email2'] . "\" size=\"20\"><span id=\"msgbox2\">";

if ($erro3 == 1){
    echo "<span id=\"erro\"></span>";
} else if ($certo3 == 1){
    echo "<span id=\"certo\"></span>";
}

echo "</span></td></tr>";
echo "</table>";
echo "<center>Declaro que li e aceito os <a href=\"regras.php\">termos de uso</a>.</center>";
echo "</p>";
echo "<center>";
echo "<button type=\"submit\" name=\"register\" value=\"Registrar\" class=\"reg\"></button>";
echo "</center>";
echo "</form>";
include("templates/footer.php");
exit;
?>