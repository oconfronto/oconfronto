<?php
    if ((time() < 1345222800) and (!$_GET['login']) and (!$_POST['login']))
    {
        header("Location: beta.php");
        exit;
    }
    
    include("lib.php");
    define("PAGENAME", "Principal");
    
    if ($_SESSION['Login'] != null)
    {
        $rematual = $db->GetOne("select `remember` from `accounts` where `id`=?", array($_SESSION['Login']['account_id']));
        if ($rematual == 't'){
            header("Location: characters.php");
            exit;
        }
    }
    
    $error = 0;
    $showerror = 0;
    $showcerto = 0;
    
    if ($_POST['login'])
    {
        $tentativas = $db->GetOne("select `tries` from `login_tries` where `ip`=?", array($ip));
        
        if ((!$_POST['username']) and (!$_POST['password']))
        {
            $errormsg = "Preencha todos os campos.";
            $showerror = 3;
            $error = 1;
        }
        elseif (!$_POST['username'])
        {
            $errormsg = "Por favor digite sua conta.";
            $showerror = 1;
            $error = 1;
        }
        elseif (!$_POST['password'])
        {
            $errormsg = "Por favor digite sua senha.";
            $showerror = 2;
            $error = 1;
        }
        elseif ($tentativas > 9)
        {
            $errormsg = "Você errou sua senha 10 vezes seguidas. Aguarde 30 minutos para poder tentar novamente.";
            $showerror = 3;
            $error = 1;
        }
        
        else if ($error == 0)
        {
            $query = $db->execute("select * from `accounts` where `conta`=? and `password`=?", array($_POST['username'], encodePassword($_POST['password'])));
            if ($query->recordcount() == 1) {
                $account = $query->fetchrow();
                $db->execute("update `accounts` set `ip`=? where `id`=?", array($ip, $account['id']));
                
                $_SESSION['Login'] = array("account_id" => $account['id'],"account" => $account['conta'],"key" => encodeSession($account['password'])); 
                header("Location: characters.php");
                exit;
            }
            else
            {
                $restantes = ceil(10 - $tentativas);
                $verificaConta = $db->execute("select `id` from `accounts` where `conta`=?", array($_POST['username']));
                if ($verificaConta->recordcount() == 0) {
                    $errormsg = "Conta incorreta! (" . $restantes . " tentativas restantes).";
                     $showerror = 1;
                } else {
                    $errormsg = "Senha incorreta! (" . $restantes . " tentativas restantes).";
                     $showerror = 2;
                     $showcerto = 1;
                }
                
                $bloqueiaip = $db->execute("select `tries` from `login_tries` where `ip`=?", array($ip));
                if ($bloqueiaip->recordcount() == 0) {
                    $insert['ip'] = $ip;
                    $insert['tries'] = 1;
                    $insert['time'] = time();
                    $db->autoexecute('login_tries', $insert, 'INSERT');
                }elseif ($bloqueiaip->recordcount() > 0) {
                    $db->execute("update `login_tries` set `tries`=`tries`+1 where `ip`=?", array($ip));
                }
                
                
                $error = 1;
                //Clear user's session data
                session_unset();
                session_destroy();
            }
        }
    }
    
    include("templates/header.php");
    ?>

    <span id="aviso-a">
    <?php echo $errormsg?>
    </span>
    <p>
    <form method="POST" action="index.php">
    <table width="90%" border="0px" align="center">
    <tr>
    <?php
        if ($_SESSION['Login']['account_id'] > 0)
        {
            $contaOn = $_SESSION['Login']['account'];
            unset($_SESSION['Login']['key']);
        } else {
            $contaOn = "";
        }
    ?>
    <td width="28%"><b>Conta:</b></td><td width="72%"><input type="text" class="inp" name="username" value="<?php if ($_POST['username'] != null) { echo $_POST['username']; } else { echo $contaOn; } ?>" size="20">
    <?php
        if (($showerror == 1) or ($showerror == 3)){
            echo "<span id=\"erro\"></span>";
        } else if (($showcerto == 1) or ($showcerto == 3)){
            echo "<span id=\"certo\"></span>";
        }
    ?></td>
    </tr>
    <tr>
    <td width="28%"><b>Senha:</b></td><td width="72%"><input type="password" name="password" class="inp" size="20">
    <?php
        if (($showerror == 2) or ($showerror == 3)){
            echo "<span id=\"erro\"></span>";
        } else if (($showcerto == 2) or ($showcerto == 3)){
            echo "<span id=\"certo\"></span>";
        }
        ?></td>
    </tr>
    </table>
    </p>
    <table width="91%" border="0px" align="center">
    <tr>
        <td>
            <center><button type="submit" name="login" value="Entrar" class="ent"></button></center>
        </td>
        <td>
            <font size="1px"><a href="register.php">Criar conta</a><br/></font>
            <font size="1px"><a href="forgot.php">Esqueceu a senha?</a></font>
        </td>
    </tr>
    </table>
    </form>

<?php
include("templates/footer.php");
?>