<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Principal");

if ($_SESSION['Login'] ?? null) {
    $rematual = $db->GetOne("select `remember` from `accounts` where `id`=?", [($_SESSION['Login'] ?? null)['account_id'] ?? null]);
    if ($rematual == 't') {
        header("Location: characters.php");
        exit;
    }
}

$error = 0;
$showerror = 0;
$showcerto = 0;
$errormsg = ''; // Initialize $errormsg

if ($_POST['login'] ?? null) {
    $tentativas = $db->GetOne("select `tries` from `login_tries` where `ip`=?", [$ip]);

    if (!($_POST['username'] ?? null) && !($_POST['password'] ?? null)) {
        $errormsg = "Preencha todos os campos.";
        $showerror = 3;
        $error = 1;
    } elseif (!($_POST['username'] ?? null)) {
        $errormsg = "Por favor digite sua conta.";
        $showerror = 1;
        $error = 1;
    } elseif (!($_POST['password'] ?? null)) {
        $errormsg = "Por favor digite sua senha.";
        $showerror = 2;
        $error = 1;
    } elseif ($tentativas > 9) {
        $errormsg = "Você errou sua senha 10 vezes seguidas. Aguarde 30 minutos para poder tentar novamente.";
        $showerror = 3;
        $error = 1;
    } elseif ($error === 0) {
        $query = $db->execute("select * from `accounts` where `conta`=? and `password`=?", [$_POST['username'] ?? null, encodePassword($_POST['password'])]);
        if ($query->recordcount() == 1) {
            $account = $query->fetchrow();
            $db->execute("update `accounts` set `ip`=? where `id`=?", [$ip, $account['id'] ?? null]);

            $_SESSION['Login'] = ["account_id" => $account['id'] ?? null, "account" => $account['conta'] ?? null, "key" => encodeSession($account['password'])];
            header("Location: characters.php");
            exit;
        }

        $restantes = ceil(10 - $tentativas);
        $verificaConta = $db->execute("select `id` from `accounts` where `conta`=?", [$_POST['username'] ?? null]);
        if ($verificaConta->recordcount() == 0) {
            $errormsg = "Conta incorreta! (" . $restantes . " tentativas restantes).";
            $showerror = 1;
        } else {
            $errormsg = "Senha incorreta! (" . $restantes . " tentativas restantes).";
            $showerror = 2;
            $showcerto = 1;
        }

        $bloqueiaip = $db->execute("select `tries` from `login_tries` where `ip`=?", [$ip]);
        if ($bloqueiaip->recordcount() == 0) {
            $insert['ip'] = $ip;
            $insert['tries'] = 1;
            $insert['time'] = time();
            $db->autoexecute('login_tries', $insert, 'INSERT');
        } elseif ($bloqueiaip->recordcount() > 0) {
            $db->execute("update `login_tries` set `tries`=`tries`+1 where `ip`=?", [$ip]);
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
    <?php echo htmlspecialchars($errormsg); ?>
</span>
<p>
<form method="POST" action="index.php">
    <table width="90%" border="0px" align="center">
        <tr>
            <?php
            $contaOn = "";
            if (($_SESSION['Login'] ?? null) && ($_SESSION['Login']['account_id'] ?? null) && (($_SESSION['Login'] ?? null)['account_id'] ?? null) > 0) {
                $contaOn = $_SESSION['Login']['account'] ?? "";
            }
            ?>
            <td width="28%"><b>Conta:</b></td>
            <td width="72%"><input type="text" class="inp" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? $contaOn); ?>" size="20">
                <?php
                if ($showerror == 1 || $showerror == 3) {
                    echo '<span id="erro"></span>';
                } elseif ($showcerto == 1 || $showcerto == 3) {
                    echo '<span id="certo"></span>';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td width="28%"><b>Senha:</b></td>
            <td width="72%"><input type="password" name="password" class="inp" size="20">
                <?php
                if ($showerror == 2 || $showerror == 3) {
                    echo '<span id="erro"></span>';
                } elseif ($showcerto == 2 || $showcerto == 3) {
                    echo '<span id="certo"></span>';
                }

                ?>
            </td>
        </tr>
    </table>
    </p>
    <table width="91%" border="0px" align="center">
        <tr>
            <td>
                <center><button type="submit" name="login" value="Entrar" class="ent"></button></center>
            </td>
            <td>
                <font size="1px"><a href="register.php">Criar conta</a><br /></font>
                <font size="1px"><a href="forgot.php">Esqueceu a senha?</a></font>
            </td>
        </tr>
    </table>
</form>

<?php
include(__DIR__ . "/templates/footer.php");
?>
