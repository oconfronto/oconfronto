<?php

include("lib.php");
define("PAGENAME", "Hospital");
$player = check_user($secret_key, $db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'recover_energy') {
    $query = $db->execute("update `players` set `energy`=? where `id`=?", array($player->maxenergy, $player->id));
    if ($query) {
        echo json_encode(array('status' => 'success', 'message' => 'Energia recuperada com sucesso!'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Erro ao recuperar energia.'));
    }
    exit;
}
?>

<?php include("templates/private_header.php"); ?>

<button type="button" id="showRewardAdButton">Assistir vídeo para recuperar energia</button>

<script type="text/javascript" src="https://cdn.applixir.com/applixir.sdk4.0m.js" ></script>
<script type="application/javascript">
    const options = {
        zoneId: '2050',
        userId: '<?php echo $player->id; ?>',
        accountId: '8399',
        siteId: '8929',
        adStatusCb: adStatusCallback,
    };

    function adStatusCallback(status) {
        if (status === 'ad-rewarded') {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    alert(response.message);
                }
            };
            xhr.send('action=recover_energy');
        }
    }

    document.getElementById('showRewardAdButton').onclick = () => invokeApplixirVideoUnit(options)
</script>

<?php include("templates/private_footer.php"); ?>
