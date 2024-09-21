<?php
include("lib.php");
define("PAGENAME", "Confirmao");

if ($_GET['id'])
{
    $verpagamentoid = $db->execute("select `id` from `payments` where `codigo`=?", array($_GET['id']));
    if ($verpagamentoid->recordcount() == 0) {
        $insert['codigo'] = $_GET['id'];
        $insert['date'] = date("F j, Y, g:i:s a");
        $db->autoexecute('payments', $insert, 'INSERT');
    }
}

    if ($_GET['comprovante']) {
        $error = 0;
        $errormsg = 1;
        if ($_GET['submit'])
        {
            if (!$_GET['conta'])
            {
                $errormsg = "Por favor digite sua conta.";
                $error = 1;
            }
            
            if ($error == 0)
            {
                $query = $db->execute("select * from `accounts` where `conta`=?", array($_GET['conta']));
                if ($query->recordcount() == 1) {
                    $verpagamento = $db->execute("select `id` from `payments` where `codigo`=?", array($_GET['id']));
                    if ($verpagamento->recordcount() == 1) {
                        $pay = $verpagamento->fetchrow();
                        $db->execute("update `payments` set `conta`=? where `id`=?", array($_GET['conta'], $pay['id']));
                    } else {
                        $insert['codigo'] = $_GET['id'];
                        $insert['conta'] = $_GET['conta'];
                        $insert['date'] = date("F j, Y, g:i:s a");
                        $db->autoexecute('payments', $insert, 'INSERT');
                    }
                    
                    if ($_GET['manda'])
                    {
                        if ($_GET['send'])
                        {
                            $errormsg = "Imagem invlida.";
                            $error = 1;
                        } else {
                        
                            $verpagamento = $db->execute("select `id` from `payments` where `codigo`=?", array($_GET['id']));
                            if ($verpagamento->recordcount() == 1) {
                                $pay = $verpagamento->fetchrow();
                                $db->execute("update `payments` set `img`=? where `id`=?", array($_GET['img'], $pay['id']));
                            } else {
                                $insert['codigo'] = $_GET['id'];
                                $insert['conta'] = $_GET['conta'];
                                $insert['img'] = $_GET['img'];
                                $insert['date'] = date("F j, Y, g:i:s a");
                                $db->autoexecute('payments', $insert, 'INSERT');
                            }
                            
                            include("templates/header.php");
                            echo "<span id=\"aviso-a\"></span>";
                            echo "<center><p>Dados enviados com sucesso.</p></center>";
                            include("templates/footer.php");
                            exit;
                        }
                    }
                    
                    include("templates/header.php");
                    if ($errormsg != 1) {
                        echo "<span id=\"aviso-a\">" . $errormsg . "</span>";
                    } else {
                        echo "<span id=\"aviso-a\"><font size=\"1px\">Envie-nos o comprovante do seu pagamento e tenha seus crditos liberados mais rapidamente. (Opcional)</font></span>";
                    }
                    echo "<form method=\"POST\" action=\"sendfiles.php?submit=true&manda=true&pay=true&comprovante=true&id=" . $_GET['id'] . "&conta=" . $_GET['conta'] . "\" enctype=\"multipart/form-data\">";
                    echo "<p><center><table width=\"90%\" border=\"0px\" align=\"center\">";
                    echo "<tr><td width=\"28%\"><b>Comprovante:</b></td><td width=\"72%\"><input type=\"file\" name=\"foto\" size=\"30\"></td></tr>";
                    echo "</table></p><button type=\"submit\" name=\"upload\" value=\"Enviar\" class=\"enviar\"></button><br/><font size=\"1px\"><a href=\"payment.php?comprovante=true&submit=true&manda=true&conta=".$_GET['conta']."&img=".$_GET['img']."\">Finalizar sem Comprovante</a></font></center>";
                    echo "</form>";
                    include("templates/footer.php");
                    exit;
                } else {
                    $errormsg = "Conta incorreta!";
                    $error = 1;
                }
            }
        }
        
        include("templates/header.php");
        if ($errormsg != 1) {
            echo "<span id=\"aviso-a\">" . $errormsg . "</span>";
        } else {
            echo "<span id=\"aviso-a\"><font size=\"1px\">Informe-nos a conta do personagem que dever receber os crditos.</font></span>";
        }
            echo "<form method=\"GET\" action=\"payment.php\" enctype=\"multipart/form-data\">";
            echo "<p><center><table width=\"90%\" border=\"0px\" align=\"center\">";
            echo "<input type=\"hidden\" name=\"pay\" value=\"true\">";
            echo "<input type=\"hidden\" name=\"comprovante\" value=\"true\">";
            echo "<input type=\"hidden\" name=\"submit\" value=\"true\">";
            echo "<input type=\"hidden\" name=\"id\" value=\"" . $_GET['id'] . "\">";
            echo "<tr><td width=\"28%\"><b>Conta:</b></td><td width=\"72%\"><input type=\"text\" class=\"inp\" name=\"conta\" value=\"" . $_GET['conta'] . "\" size=\"20\"></td></tr>";
            echo "</table></p><button type=\"submit\" name=\"upload\" value=\"Enviar\" class=\"enviar\"></button></center>";
            echo "</form>";
        include("templates/footer.php");
        exit;
    }
include("templates/header.php");
echo "<span id=\"aviso-a\">";
echo "</span>";
echo "<p><center>Pagamento efetuado com sucesso!<br/><a href=\"payment.php?comprovante=true&id=" . $_GET['id'] . "\">Clique aqui</a> para continuar.</center></p>";
include("templates/footer.php");
exit;
?>