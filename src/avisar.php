<?php
    $error = 0;
    $errormsg = "";
    
    if(isset($_POST['forgot']))
    {
        
        if (!$_POST['emailf']){
            $errormsg = "O campo email Ž obrigat—rio.";
            $error = 1;
        }
        
        
        if ($error == 0) {
            $subject = "O Confronto - Reabertura";
            $message .= "<html>\n"; 
            $message .= "<body style=\"background-color:#FFFDE0; color:#222\">\n"; 
            
            $message .= "<html>\n"; 
            $message .= "<body style=\"background-color:#FFFDE0; color:#222\">\n"; 
            
            $message .= "<div style=\"padding:10px;margin-bottom:4px;background-color:#CEA663\">\n";
            $message .= "<a href=\"http://facebook.com/ocrpg\" target=\"_blank\"><img alt=\"O Confronto\" height=\"30px\" src=\"http://s17.postimage.org/zfus2jepn/logo.gif\" style=\"display:block;border:0\" width=\"175px\"></a>\n";
            $message .= "</div>\n";
            
            $message .= "<div style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;font-size:13px;margin:14px\">\n";
            $message .= "<br style=\"clear:both\">\n";
            $message .= "<p style=\"margin-top:0\">\n";
            $message .= "O maior jogo de estrat&eacute;gia medieval logo estar&aacute; de volta!<br/>\n";
            $message .= "Estamos trabalhando nos &uacute;ltimos meses na reabertura do jogo, agora de n&iacute;vel profissional e com diversas novidades.<br/>\n";
            $message .= "Para ficar por dentro da reabertura do jogo e ficar ligado nas novidades, acesse: <a href=\"http://facebook.com/ocrpg\" target=\"_blank\">facebook.com/ocrpg</a>\n";
            $message .= "</p>\n";
            
            $message .= "<p style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;margin-top:5px;font-size:11px;color:#666666\">\n";
            $message .= "Se poss&iacute;vel, nos ajude a divulgar este email e reunir os apaixonados pelo game novamente.</p>\n";
            
            $message .= "<p style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;font-size:13px;line-height:18px;border-bottom:1px solid rgb(238, 238, 238);padding-bottom:10px\"></p>\n";
            
            $message .= "<p style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;margin-top:5px;font-size:10px;color:#888888;text-align:center;\">\n";
            $message .= "Copyright © 2008-2012 OC Productions<br/>\n";
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
            $headers .= "From: no-reply@narutostorm.kinghost.net";
            
            mail( $_POST['emailf'], $subject, $message, $headers );
                echo "<br/><center><p><b>Emails enviados com sucesso</b></p></center><br/>";
            exit;
        }
        
    }

    
        echo "<form action='avisar.php' method='post'><p><table width=\"90%\" border=\"0px\" align=\"center\">"; 
        echo "<tr><td width=\"28%\"><b>Email:</b></td><td width=\"72%\"><TEXTAREA COLS=40 ROWS=5 NAME=\"emailf\"></TEXTAREA>";
        echo "</td></tr>";
        echo "</table></p><center><input type=\"submit\" name=\"forgot\" value=\"enviar emails\"></button></center></form>";
        echo "</fieldset>";
    exit;
?>