<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");

$error = 0;
if ($_POST['login']) {
    $tentativas = $db->GetOne("select `tries` from `login_tries` where `ip`=?", [$ip]);

    if (!$_POST['username']) {
        $errormsg = "Por favor digite sua conta.";
        $error = 1;
    } elseif (!$_POST['password']) {
        $errormsg = "Por favor digite sua senha.";
        $error = 1;
    } elseif ($tentativas > 9) {
        $errormsg = "Você errou sua senha 10 vezes seguidas. Aguarde 30 minutos para poder tentar novamente.";
        $error = 1;
    } elseif ($error === 0) {
        $query = $db->execute("select * from `accounts` where `conta`=? and `password`=?", [$_POST['username'], encodePassword($_POST['password'])]);
        if ($query->recordcount() == 1) {
            $account = $query->fetchrow();
            $db->execute("update `accounts` set `ip`=? where `id`=?", [$ip, $account['id']]);

            $_SESSION['Login'] = ["account_id" => $account['id'], "account" => $account['conta'], "key" => encodeSession($account['password'])];
            header("Location: characters.php");
            exit;
        }

        $restantes = ceil(10 - $tentativas);
        $errormsg = "Conta ou senha incorreta! (" . $restantes . " tentativas restantes).";
        $bloqueiaip = $db->execute("select `tries` from `login_tries` where `ip`=?", [$ip]);
        if ($bloqueiaip->recordcount() == 0) {
            $insert['ip'] = $ip;
            $insert['tries'] = 1;
            $insert['time'] = time();
            $query = $db->autoexecute('login_tries', $insert, 'INSERT');
        } elseif ($bloqueiaip->recordcount() > 0) {
            $query = $db->execute("update `ip` set `login_tries`=`tries`+1 where `ip`=?", [$ip]);
        }

        $error = 1;
        //Clear user's session data
        session_unset();
        session_destroy();
    }
}

include(__DIR__ . "/templates/header.php");
?>

<span id="aviso-a">
    <?php echo $errormsg ?>
</span>
<p>
<form method="POST" action="index.php">
    <table width="90%" border="0px" align="center">
        <tr>
            <td width="30%"><b>Conta:</b></td>
            <td width="70%"><input type="text" class="inp" name="username" value="<?php echo $_POST['username'] ?>" size="20"><span id="erro"></span></td>
        </tr>
        <tr>
            <td width="30%"><b>Senha:</b></td>
            <td width="70%"><input type="password" name="password" class="inp" size="20"><span id="certo"></span></td>
        </tr>
    </table>
    </p>
    <center>
        <button type="submit" name="login" value="Entrar" class="ent"></button>
    </center>
</form>