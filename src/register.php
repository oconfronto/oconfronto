<?php

declare(strict_types=1);

if (time() < 1345222800) {
    header("Location: beta.php?error=true");
    exit;
}

include(__DIR__ . "/lib.php");
define("PAGENAME", "Criar Conta");

// Use a more secure method for generating random strings
$string = bin2hex(random_bytes(16));

if (isset($_GET['r'])) {
    $_SESSION['ref'] = htmlspecialchars((string) $_GET['r'], ENT_QUOTES, 'UTF-8');
}

$usaar = $_SESSION['ref'] ?? "1";


$error = 0;

$erro1 = 0;
$erro2 = 0;
$erro3 = 0;
$erro4 = 0;
$erro5 = 0;

$certo1 = 0;
$certo2 = 0;
$certo3 = 0;
$certo4 = 0;
$certo5 = 0;

$msg1 = "";
$msg2 = "";
$msg3 = "";
$msg4 = "";
$msg5 = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Process form submission
    $conta2 = $_POST['conta2'] ?? '';
    $user_pass2 = $_POST['user_pass2'] ?? '';
    $conf_pass2 = $_POST['conf_pass2'] ?? '';
    $email2 = $_POST['email2'] ?? '';
    $conf_email2 = $_POST['conf_email2'] ?? '';

    //Use these variables instead of directly accessing $_POST
    //Check if conta has already been used
    $query = $db->execute("SELECT `id` FROM `accounts` WHERE `conta`=?", [$conta2]);
    //Check conta
    if (empty($conta2)) { // Changed from !empty to empty
        $msg1 = "Você precisa digitar o nome da conta desejada.<br />\n";
        $error = 1;
        $erro1 = 1;
    } elseif (strlen($conta2) < 3) {
        $msg1 = "Sua conta não pode ter menos de 3 caracteres!<br />\n";
        $error = 1;
        $erro1 = 1;
    } elseif (strlen($conta2) > 20) {
        $msg1 = "Sua conta deve ter 20 caracteres ou menos!<br />\n";
        $error = 1;
        $erro1 = 1;
    } elseif (!preg_match("/^[-_a-zA-Z0-9]+$/", $conta2)) {
        $msg1 = "Sua conta não pode conter <b>caracteres especiais</b>!<br />\n";
        $error = 1;
        $erro1 = 1;
    } elseif ($query->recordcount() > 0) {
        $msg1 = "Esta conta já está sendo usada!<br />\n";
        $error = 1;
        $erro1 = 1;
    }

    //Check password
    if (empty($user_pass2)) { // Changed from !empty to empty
        $msg2 = "Você precisa digitar uma senha!<br />\n";
        $error = 1;
        $erro2 = 1;
    } elseif (strlen($user_pass2) < 4) {
        $msg2 = "Sua senha deve ser maior que 3 caracteres!<br />\n";
        $error = 1;
        $erro2 = 1;
    }

    if (empty($conf_pass2)) { // Changed from !empty to empty
        $msg4 = "Você precisa confirmar a senha!<br />\n";
        $error = 1;
        $erro4 = 1;
    } elseif ($conf_pass2 != $user_pass2) {
        $msg4 = "Sua senha de confirmação está diferente da senha digitada!<br />\n";
        $error = 1;
        $erro4 = 1;
    }

    //Check email
    if (empty($email2)) { // Changed from !empty to empty
        $msg3 = "Você precisa digitar um email!<br />\n";
        $error = 1;
        $erro3 = 1;
    } elseif (strlen($email2) < 5) {
        $msg3 = "O seu endereço de email deve conter mais de 5 caracteres.<br />\n";
        $error = 1;
        $erro3 = 1;
    } elseif (!filter_var($email2, FILTER_VALIDATE_EMAIL)) {
        $msg3 = "O formato do seu email é inválido!<br />\n";
        $error = 1;
        $erro3 = 1;
    } else {
        //Check if email has already been used
        $query = $db->execute("SELECT `id` FROM `accounts` WHERE `email`=?", [$email2]);
        $query2 = $db->execute("SELECT * FROM `pending` WHERE `pending_id`=1 AND `pending_status`=?", [$email2]);
        if ($query->recordcount() > 0) {
            $msg3 = "Este email já está sendo usado por outra conta!<br />\n";
            $error = 1;
            $erro3 = 1;
        } elseif ($query2->recordcount() > 0) {
            $msg3 = "Este email já está em uso!<br />\n";
            $error = 1;
            $erro3 = 1;
        }
    }

    if (empty($conf_email2)) { // Changed from !empty to empty
        $msg5 = "Você precisa confirmar o email!<br />\n";
        $error = 1;
        $erro5 = 1;
    } elseif ($conf_email2 != $email2) {
        $msg5 = "Seu email de confirmação está diferente do email digitado!<br />\n";
        $error = 1;
        $erro5 = 1;
    }


    if ($error == 0) {

        $insert['conta'] = $conta2;
        $insert['password'] = encodePassword($user_pass2);
        $insert['email'] = $email2;
        $insert['registered'] = time();
        $insert['last_active'] = time();
        $insert['ip'] = $_SERVER['REMOTE_ADDR'];
        $insert['last_ip'] = $_SERVER['REMOTE_ADDR'];
        $insert['validkey'] = $string;
        $insert['ref'] = $usaar;
        $registra = $db->autoexecute('accounts', $insert, 'INSERT');

        $id = $db->Insert_ID();

        // INSERE DADOS DE CÃDIGO DE REFERENCIA DO CONVITE.
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

        if ($registra) {
            session_unset();
            session_start();
            $_SESSION['Login'] = ["account_id" => $id, "account" => $conta2, "key" => encodeSession(encodePassword($user_pass2))];


            include(__DIR__ . "/templates/header.php");
            echo '<span id="aviso-v"></span>';
            echo "<br/><center><p><b>Voc&ecirc; foi cadastrado com sucesso!<br />";
            echo 'Agora voc&ecirc; pode entrar no jogo. <a href="index.php">Clique aqui.</a></b></p></center><br/>';
            include(__DIR__ . "/templates/footer.php");
            exit;
        }


        // $certo4 = 0;
        // $certo5 = 0;

        // $msg4 = "";
        // $msg5 = "";

        // if ($_POST['register2']) {
        //     if (!$_POST['password3']) { //If password isn't filled in...
        //         $msg4 = "Voc&ecirc; precisa digitar uma senha!<br />\n"; //Add to error message
        //         $error = 2; //Set error check
        //         $erro4 = 1;
        //     } else if (($_POST['password2']) != ($_POST['password3'])) { //If password is too short...
        //         $msg4 = "As senhas digitadas não comferem!<br />\n"; //Add to error message
        //         $error = 2; //Set error check
        //         $erro4 = 1;
        //     }

        //     if (!$_POST['email3']) { //If email address isn't filled in...
        //         $msg5 = "Voc&ecirc; precisa digitar um email!<br />\n"; //Add to error message
        //         $error = 2; //Set error check
        //         $erro5 = 1;
        //     } else if (($_POST['email2']) != ($_POST['email3'])) { //If email address isn't filled in...
        //         $msg5 = "Os emails digitados não comferem!<br />\n"; //Add to error message
        //         $error = 2; //Set error check
        //         $erro5 = 1;
        //     }

        //     if ($error == 0) {
        //         $insert['conta'] = $_POST['conta2'];
        //         $insert['password'] = encodePassword($_POST['password2']);
        //         $insert['email'] = $_POST['email2'];
        //         $insert['registered'] = time();
        //         $insert['last_active'] = time();
        //         $insert['ip'] = $_SERVER['REMOTE_ADDR'];
        //         $insert['last_ip'] = $_SERVER['REMOTE_ADDR'];
        //         $insert['validkey'] = $string;
        //         $insert['ref'] = $usaar;
        //         $registra = $db->autoexecute('accounts', $insert, 'INSERT');

        //         $id = $db->Insert_ID();

        //         // INSERE DADOS DE CÃDIGO DE REFERENCIA DO CONVITE.
        //         $insert_ref['id_p_c'] = $id;
        //         $insert_ref['id_p_ref'] = $usaar;
        //         $insert_ref['date_regis'] = time();
        //         $insert_ref['event'] = $setting->event_convidados;
        //         $db->autoexecute('players_ref', $insert_ref, 'INSERT');

        //         // LOCURAS DO JULIANO PARTE 1
        //         /* $playerip = $db->execute("select `id` from `accounts` where `last_ip`=?", array($_SERVER['REMOTE_ADDR']));
        //             if (($playerip->recordcount() > 1) and ($usaar != 1)){
        //                 $db->execute("update `accounts` set `ref`=? where `id`=?", array(1, $player['id']));
        //             } */

        //         if ($registra) {
        //             session_unset();
        //             session_start();
        //             $_SESSION['Login'] = array("account_id" => $id, "account" => $_POST['conta2'], "key" => encodeSession(encodePassword($_POST['password2'])));


        //             include("templates/header.php");
        //             echo "<span id=\"aviso-v\"></span>";
        //             echo "<br/><center><p><b>Voc&ecirc; foi cadastrado com sucesso!<br />";
        //             echo "Agora voc&ecirc; pode entrar no jogo. <a href=\"index.php\">Clique aqui.</a></b></p></center><br/>";
        //             include("templates/footer.php");
        //             exit;
        //         }
        //     }
        // }

        // include("templates/header.php");
        // echo "<span id=\"aviso-v\">";
        // if ($msg4 != "") {
        //     echo $msg4;
        // } else if ($msg5 != "") {
        //     echo $msg5;
        // }

        // if ($_POST['register2']) {
        //     if ($msg4 == "") {
        //         $certo4 = 1;
        //     }
        //     if ($msg5 == "") {
        //         $certo5 = 1;
        //     }
        // }
        // echo "</span>";

        // echo "<center><p>Confirme suas informações para completar seu registro.</p></center>";
        // echo "<form action='register.php?confirm=true' method='post'>";

        // echo "<input type=\"hidden\" name=\"conta2\" value=\"" . $_POST['conta2'] . "\">";
        // echo "<input type=\"hidden\" name=\"password2\" value=\"" . $_POST['password2'] . "\">";
        // echo "<input type=\"hidden\" name=\"email2\" value=\"" . $_POST['email2'] . "\">";

        // echo "<p><table width=\"90%\" border=\"0px\" align=\"center\">";
        // echo "<tr><td width=\"28%\">";
        // echo "<b>Senha:</b></td><td width=\"72%\"><input type=\"password\" name=\"password3\" value=\"" . $_POST['password3'] . "\" class=\"inp\" size=\"20\">";

        // if ($erro4 == 1) {
        //     echo "<span id=\"erro\"></span>";
        // } else if ($certo4 == 1) {
        //     echo "<span id=\"certo\"></span>";
        // }

        // echo "</td></tr>";
        // echo "<tr><td width=\"28%\">";
        // echo "<b>Email:</b></td><td width=\"72%\"><input type=\"text\" name=\"email3\" class=\"inp\" value=\"" . $_POST['email3'] . "\" size=\"20\">";

        // if ($erro5 == 1) {
        //     echo "<span id=\"erro\"></span>";
        // } else if ($certo5 == 1) {
        //     echo "<span id=\"certo\"></span>";
        // }

        // echo "</td></tr>";
        // echo "</table>";
        // echo "</p>";
        // echo "<center>";
        // echo "<button type=\"submit\" name=\"register2\" value=\"Registrar\" class=\"reg\"></button>";
        // echo "</center>";
        // echo "</form>";
        // include("templates/footer.php");
        // exit;
    }
}


include(__DIR__ . "/templates/header.php");
echo '<span id="aviso-v">';

if ($msg1 !== "") {
    echo $msg1;
} elseif ($msg2 !== "") {
    echo $msg2;
} elseif ($msg3 !== "") {
    echo $msg3;
} elseif ($msg4 !== "") {
    echo $msg4;
} elseif ($msg5 !== "") {
    echo $msg5;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if ($msg1 === "") {
        $certo1 = 1;
    }

    if ($msg2 === "") {
        $certo2 = 1;
    }

    if ($msg3 === "") {
        $certo3 = 1;
    }

    if ($msg4 === "") {
        $certo4 = 1;
    }

    if ($msg5 === "") {
        $certo4 = 1;
    }
}

echo "</span>";

include(__DIR__ . "/box.php");
echo "<form action='register.php' method='post' autocomplete='off'>";
echo "<input type='text' name='fakeusernameremembered' style='display:none'>";
echo "<input type='password' name='fakeusernameremembered' style='display:none'>";
echo '<p><table width="90%" border="0px" align="center">';
echo "<tr>";
echo '<td width="28%">';
echo '<b>Conta:</b></td><td width="72%"><input type="text" id="conta" name="conta2" value="' . htmlspecialchars($conta2 ?? '') . '" class="inp" size="20"><span id="msgbox4">';

if ($erro1 == 1) {
    echo '<span id="erro"></span>';
} elseif ($certo1 == 1) {
    echo '<span id="certo"></span>';
}

echo "</span></td></tr>";
echo '<tr><td width="28%">';
echo "<b>Senha:</b></td><td width=\"72%\"><input autocomplete='new-password' id=\"user_pass\" type=\"password\" name=\"user_pass2\" value=\"" . htmlspecialchars($user_pass2 ?? '') . '" class="inp" size="20"><span id="msgbox7">';

if ($erro2 == 1) {
    echo '<span id="erro"></span>';
} elseif ($certo2 == 1) {
    echo '<span id="certo"></span>';
}

echo "</span></td></tr>";
echo '<tr><td width="28%">';
echo "<b>Confirmar Senha:</b></td><td width=\"72%\"><input autocomplete='new-password' id=\"conf_pass\" type=\"password\" name=\"conf_pass2\" value=\"" . htmlspecialchars($conf_pass2 ?? '') . '" class="inp" size="20"><span id="msgbox8">';

if ($erro4 == 1) {
    echo '<span id="erro"></span>';
} elseif ($certo4 == 1) {
    echo '<span id="certo"></span>';
}


echo "</td></tr>";
echo '<tr><td width="28%">';
echo '<b>Email:</b></td><td width="72%"><input type="text" id="emailbox" name="email2" class="inp" value="' . htmlspecialchars($email2 ?? '') . '" size="20"><span id="msgbox1">';

if ($erro3 == 1) {
    echo '<span id="erro"></span>';
} elseif ($certo3 == 1) {
    echo '<span id="certo"></span>';
}

echo "</td></tr>";
echo '<tr><td width="28%">';
echo '<b>Confirmar Email:</b></td><td width="72%"><input type="text" id="emailboxconf" name="conf_email2" class="inp" value="' . htmlspecialchars($conf_email2 ?? '') . '" size="20"><span id="msgbox2">';

if ($erro5 == 1) {
    echo '<span id="erro"></span>';
} elseif ($certo5 == 1) {
    echo '<span id="certo"></span>';
}

echo "</span></td></tr>";
echo "</table>";
echo '<center>Declaro que li e aceito os <a href="regras.php">termos de uso</a>.</center>';
echo "</p>";
echo "<center>";
echo '<button type="submit" name="register" value="Registrar" class="reg"></button>';
echo "</center>";
echo "</form>";
include(__DIR__ . "/templates/footer.php");
exit;


