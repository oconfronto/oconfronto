<?php
include("lib.php");
define("PAGENAME", "Recuperar senha");
$acc = check_acc($secret_key, $db);
$player = check_user($secret_key, $db);
include("checkbattle.php");

include("templates/private_header.php");

$soma1 = rand(1, 70);
$soma2 = rand(1, 10);

if (isset($_POST['submit'])) {
    $getaccount = $db->execute("select `id` from `accounts` where `id`=? and `email`=?", array($player->acc_id, $_POST['email']));

    if ($_POST['email'] != $_POST['email1']) {
        print "Os emails digitados são diferentes. <a href='forgottrans.php'>Voltar</a>.";
        include("templates/footer.php");
        exit;
    }

    if ($_POST['email'] != $acc->email) {
        print "O email digitado não confere com o email de sua conta. <a href='forgottrans.php'>Voltar</a>.";
        include("templates/footer.php");
        exit;
    }

    if (($_POST['seguranca']) != ($_SESSION['v1'] + $_SESSION['v2'])) {
        print "O código de segurança está incorreto. <a href='forgottrans.php'>Voltar</a>.";
        include("templates/footer.php");
        exit;
    }


    if ($getaccount->recordcount() != 1) {
        print "Nenhuma conta utiliza este email. <a href='forgottrans.php'>Voltar</a>.";
        include("templates/footer.php");
        exit;
    } else {
        $subject = "Você solicitou sua senha de transferência por email";

        $body = "
            <h2>Vocï¿½ solicitou sua senha de transfêrencia.<br><br>
            <font color=blue>Sua senha de transferência ï¿½: " . $player->transpass . "</font></h2>
            <br><br><br>
        ";

        $insert['player_id'] = $player->id;
        $insert['msg'] = "Você solicitou sua senha de transferência por email.";
        $insert['time'] = time();
        $query = $db->autoexecute('account_log', $insert, 'INSERT');

        $sent_mail = send_mail("O Confronto RPG", $acc->email, $assunto, $mail->Body);

        if ($sent_mail) {
            print "Sua senha foi enviada ao seu email. <a href=\"home.php\">Voltar.</a>";
        } else {
            print "Ocorreu um erro, tente novamente. <a href=\"forgottrans.php\">Voltar.</a>";
        }

        include("templates/footer.php");
        exit;
    }
} else {
    print "<fieldset><legend><b>Recuperar senha de transferência</b></legend>\n";
    print "<table><form action='forgottrans.php' method='post'>";
    print "<tr><td><b>Email:</b></td><td><input type='text' name='email' size='25'></td></tr>";
    print "<tr><td><b>Email novamente:</b></td><td><input type='text' name='email1' size='25'></td></tr>";
    print "<tr><td width=\"40%\"><b>Segurança</b>:</td><td><b>" . $soma1 . " + " . $soma2 . " =</b> <input type=\"text\" name=\"seguranca\" size=\"3\"/></td></tr>";
    print "<tr><td colspan=\"2\"><font size=\"1\">Digite a soma de <b>" . $soma1 . " + " . $soma2 . "</b>.</font></td></tr>";
    $_SESSION['v1'] = $soma1;
    $_SESSION['v2'] = $soma2;
    print "</table>";
}
print "</fieldset><br/><input type='submit' name='submit' value='Enviar senha para o Email'></form>";
include("templates/private_footer.php");
?>
