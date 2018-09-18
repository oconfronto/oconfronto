<?php
    include("lib.php");
    define("PAGENAME", "Recuperar Senha");
    
    $error = 0;
    $errormsg = "";
    $showerror2 = 0;
    
    if(isset($_POST['forgot']))
    {
        $verForgot = $db->execute("select * from `accounts` where `email`=? and `conta`=?", array($_POST['emailf'], $_POST['username']));
        
        if ((!$_POST['username']) and (!$_POST['emailf'])){
            $errormsg = "Preencha todos os campos.";
            $showerror2 = 3;
            $error = 1;
        }
        
        elseif (!$_POST['username']){
            $errormsg = "O campo conta Ž obrigat—rio.";
            $showerror2 = 1;
            $error = 1;
        }
        
        elseif (!$_POST['emailf']){
            $errormsg = "O campo email Ž obrigat—rio.";
            $showerror2 = 2;
            $error = 1;
        }
        
        elseif ($verForgot->recordcount() != 1) {
            $errormsg = "Os dados digitados não conferem.";
            $showerror2 = 3;
            $error = 1;
        }
        
        if ($error == 0) {
            $recu = $verForgot->fetchrow();
            $subject = "Recuperar senha - O Confronto";
            $message .= "<html>\n"; 
            $message .= "<body style=\"background-color:#FFFDE0; color:#222\">\n"; 
            
            $message .= "<div style=\"padding:10px;margin-bottom:4px;background-color:#CEA663\">\n";
            $message .= "<a href=\"http://ocrpg.com/\" target=\"_blank\"><img alt=\"O Confronto\" height=\"30\" src=\"http://ocrpg.com/images/logo.gif\" style=\"display:block;border:0\" width=\"175\"></a>\n";
            $message .= "</div>\n";
            
            $message .= "<div style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;font-size:13px;margin:14px\">\n";
            $message .= "<br style=\"clear:both\">\n";
            $message .= "<p style=\"margin-top:0\">\n";
            $message .= "Parece que você solicitou uma nova senha para a conta <b>" . $recu['conta'] . "</b>.<br/>\n";
            $message .= "Para gerar uma nova senha, <a href=\"http://ocrpg.com/newpass.php?email=" . $recu['emailf'] . "&string=" . $recu['validkey'] . "\" target=\"_blank\">clique aqui</a>.\n";
            $message .= "</p>\n";
            
            $message .= "<p style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;margin-top:5px;font-size:11px;color:#666666\">\n";
            $message .= "Se você não solicitou uma nova senha apenas ignore este email.</p>\n";
            
            $message .= "<p style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;font-size:13px;line-height:18px;border-bottom:1px solid rgb(238, 238, 238);padding-bottom:10px\"></p>\n";
            
            $message .= "<p style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;margin-top:5px;font-size:10px;color:#888888;text-align:center;\">\n";
            $message .= "Copyright © 2008-2011 OC Productions<br/>\n";
            $message .= "Todos os direitos reservados.\n";
            $message .= "</p>\n";
            
            $message .= "</div>\n";
            $message .= "</body>\n"; 
            $message .= "</html>\n"; 
            
            $headers .= "MIME-Version: 1.0\n" ; 
            $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n"; 
            $headers .= "X-Priority: 1 (Higuest)\n"; 
            $headers .= "X-MSMail-Priority: High\n"; 
            $headers .= "Importance: High\n"; 
            $headers .= "From: noreply@ocrpg.com";
            
            mail( $recu['email'], $subject, $message, $headers );
            include("templates/header.php");
                echo "<span id=\"aviso-v\"></span>";
                echo "<br/><center><p><b>Sua senha foi enviada ao seu email.</b></p></center><br/>";
            include("templates/footer.php");
            exit;
        }
        
    }
    include("templates/header.php");
        echo "<span id=\"aviso-v\">" . $errormsg . "</span>";
        echo "<form action='forgot.php' method='post'><p><table width=\"90%\" border=\"0px\" align=\"center\">"; 
        echo "<tr><td width=\"28%\"><b>Conta:</b></td><td width=\"72%\"><input type='text' name='username' value=\"" . $_POST['username'] . "\" class=\"inp\" size=\"20\">";
        
        if (($showerror2 == 1) or ($showerror2 == 3)){
            echo "<span id=\"erro\"></span>";
        }
        
        echo "</td></tr>";
        echo "<tr><td width=\"28%\"><b>Email:</b></td><td width=\"72%\"><input type='text' name='emailf' value=\"" . $_POST['emailf'] . "\" class=\"inp\" size=\"20\">";
        
        if (($showerror2 == 2) or ($showerror2 == 3)){
            echo "<span id=\"erro\"></span>";
        }
        
        echo "</td></tr>";
        echo "</table></p><center><button type=\"submit\" name=\"forgot\" value=\"enviar email\" class=\"enviar\"></button></center></form>";
        echo "</fieldset>";
    include("templates/footer.php");
    exit;
?>