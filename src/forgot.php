<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Recuperar Senha");

$domain = "ocrpg.net";
$from = "suporte@ocrpg.net";

$error = 0;
$errormsg = "";
$showerror2 = 0;

if (isset($_POST['forgot'])) {
    $verForgot = $db->execute("select * from `accounts` where `email`=? and `conta`=?", [$_POST['emailf'], $_POST['username']]);

    if (!$_POST['username'] && !$_POST['emailf']) {
        $errormsg = "Preencha todos os campos.";
        $showerror2 = 3;
        $error = 1;
    } elseif (!$_POST['username']) {
        $errormsg = "O campo conta é obrigatório.";
        $showerror2 = 1;
        $error = 1;
    } elseif (!$_POST['emailf']) {
        $errormsg = "O campo email é obrigatório.";
        $showerror2 = 2;
        $error = 1;
    } elseif ($verForgot->recordcount() != 1) {
        $errormsg = "Os dados digitados não conferem.";
        $showerror2 = 3;
        $error = 1;
    }

    if ($error == 0) {
        $recu = $verForgot->fetchrow();
        $subject = "Recuperar senha - O Confronto";

        $message = "<html><body style=\"background-color:#FFFDE0; color:#222\">\n";
        $message .= "<div style=\"padding:10px;margin-bottom:4px;background-color:#CEA663\">\n";
        $message .= '<a href="' . $domain . '" target="_blank"><img alt="O Confronto" height="30" src="static/' . $domain . "/images/logo.gif\" style=\"display:block;border:0\" width=\"175\"></a>\n";
        $message .= "</div>\n";
        $message .= "<div style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;font-size:13px;margin:14px\">\n";
        $message .= "<p>Parece que você solicitou uma nova senha para a conta <b>" . $recu['conta'] . "</b>.<br/>\n";
        $message .= 'Para gerar uma nova senha, <a href="' . $domain . "/newpass.php?email=" . $recu['email'] . "&string=" . $recu['validkey'] . "\" target=\"_blank\">clique aqui</a>.</p>\n";
        $message .= "<p>Se você não solicitou uma nova senha apenas ignore este email.</p>\n";
        $message .= "</div></body></html>";

        $sent_mail = send_mail("O Confronto RPG", $recu['email'], $subject, $message);

        if ($sent_mail) {
            include(__DIR__ . "/templates/header.php");
            echo '<span id="aviso-v"></span>';
            echo "<br/><center><p><b>Sua senha foi enviada ao seu email.</b></p></center><br/>";
            include(__DIR__ . "/templates/footer.php");
            exit;
        }

        echo "Erro ao enviar o email.";
    }
}

include(__DIR__ . "/templates/header.php");
echo '<span id="aviso-v">' . $errormsg . "</span>";
echo "<form action='forgot.php' method='post'><p><table width=\"90%\" border=\"0px\" align=\"center\">";
echo "<tr><td width=\"28%\"><b>Conta:</b></td><td width=\"72%\"><input type='text' name='username' value=\"" . $_POST['username'] . '" class="inp" size="20">';

if ($showerror2 == 1 || $showerror2 == 3) {
    echo '<span id="erro"></span>';
}

echo "</td></tr>";
echo "<tr><td width=\"28%\"><b>Email:</b></td><td width=\"72%\"><input type='text' name='emailf' value=\"" . $_POST['emailf'] . '" class="inp" size="20">';

if ($showerror2 == 2 || $showerror2 == 3) {
    echo '<span id="erro"></span>';
}

echo "</td></tr>";
echo '</table></p><center><button type="submit" name="forgot" value="enviar email" class="enviar"></button></center></form>';
echo "</fieldset>";
include(__DIR__ . "/templates/footer.php");
exit;
?>
