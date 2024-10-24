<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Enviar Imagens");
$msg = "";

if ($setting->allow_upload != t) {
    $player = check_user($secret_key, $db);
    include(__DIR__ . "/templates/private_header.php");
    echo "O envio de imagens está desativado no momento. <a href=\"home.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}
if ($_POST['upload'] && $_FILES["foto"]) {
    $erro = array();
    $config = array();
    // Prepara a variável do arquivo
    $arquivo = isset($_FILES["foto"]) ? $_FILES["foto"] : FALSE;
    // Tamanho máximo do arquivo (em bytes)
    $config["tamanho"] = 1048576;
    // Largura máxima (pixels)
    $config["largura"] = 1400;
    // Altura máxima (pixels)
    $config["altura"] = 1024;
    // Formulário postado... executa as ações
    if ($arquivo) {
        // Verifica se o mime-type do arquivo é de imagem
        if (!@GetImageSize($arquivo["tmp_name"]) || !preg_match("/^image\/(gif|bmp|png|jpg|jpeg)$/i", $arquivo["type"])) {
            $erro[] = "<span style=\"color: white; border: solid 1px ; background: red;\">Arquivo em formato inválido!</span><br/>- A imagem deve ser jpg, jpeg, png, bmp ou gif.";
        } else {
            // Verifica tamanho do arquivo
            if ($arquivo["size"] > $config["tamanho"]) {
                $erro[] = "<span style=\"color: white; border: solid 1px ; background: red;\">Arquivo em tamanho muito grande!</span><br>- A imagem deve ser de no máximo 1 MB.";
            }

            // Para verificar as dimensões da imagem
            $tamanhos = getimagesize($arquivo["tmp_name"]);

            // Verifica largura
            if ($tamanhos[0] > $config["largura"]) {
                $erro[] = "Largura da imagem não deve ultrapassar " . $config["largura"] . " pixels";
            }

            // Verifica altura
            if ($tamanhos[1] > $config["altura"]) {
                $erro[] = "Altura da imagem não deve ultrapassar " . $config["altura"] . " pixels";
            }
        }

        // Imprime as mensagens de erro
        if ($erro !== []) {
            foreach ($erro as $err) {
                $msg .= " - " . $err . "<BR>";
            }
        }

        // Verificaçãoo de dados OK, nenhum erro ocorrido, executa então o upload...
        else {
            // Pega extensão do arquivo
            preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);


            // Gera um nome único para a imagem
            $imagem_nome = md5(uniqid(time())) . "." . $ext[1];

            // Caminho de onde a imagem ficará
            $imagem_dir = "imgs/" . $imagem_nome;

            // Faz o upload da imagem
            move_uploaded_file($arquivo["tmp_name"], $imagem_dir);
            if ($_GET['avatar']) {
                $player = check_user($secret_key, $db);
                $db->execute("update `players` set `avatar`=? where `id`=?", array($imagem_dir, $player->id));
                header("Location: avatar.php?success=true");
                exit;
            }

            if ($_GET['cla']) {
                $player = check_user($secret_key, $db);
                $db->execute("update `guilds` set `img`=? where `id`=?", array($imagem_dir, $player->guild));
                header("Location: guild_admin.php?success=true");
                exit;
            }

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";
            $host = $_SERVER['HTTP_HOST'];
            $baseUrl = $protocol . $host;

            // $imageUrl = $baseUrl . "/imgs/nomedaimagem.jpg";

            $msg .= '<span style="color: white; border: solid 1px; background: green;">Sua imagem foi enviada com sucesso!</span><br/>';
            $msg .= sprintf('<b>Endereço:</b> <font size="1">%s/imgs/', $baseUrl) . $imagem_nome . sprintf(' <a href="%s/imgs/', $baseUrl) . $imagem_nome . '" target="blank"><b>Visualizar</b></a><font>';

        }
    }
}
if ($_GET['avatar']) {
    header("Location: avatar.php?msg=error");
    exit;
}

if ($_GET['cla']) {
    header("Location: guild_admin.php?msg=error");
    exit;
}
else {
    $player = check_user($secret_key, $db);
    include(__DIR__ . "/templates/private_header.php");

    echo "<fieldset>";
    echo "<legend><b>Enviar Imagens</b></legend>";
    echo '<form action="sendfiles.php" method="post" enctype="multipart/form-data">';
    echo '<input type="file" name="foto" size="30"><input type="submit" name="upload" value="Enviar">';
    echo "</form>";
    echo "</fieldset>";
    echo "<font size=\"1\">Aqui você pode enviar imagens para usar como avatar, no fórum, no perfil, etc.</font><br/><br/>";

    echo $msg;
    include(__DIR__ . "/templates/private_footer.php");
}
?>
