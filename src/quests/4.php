<?php

declare(strict_types=1);

if ($missao['quest_status'] == 1) {
    if ($_GET['next']) {
        $db->execute("update `quests` set `quest_status`='2' where `id`=?", array($missao['id']));
        header("Location: tavern.php?p=quests&start=".$quest['id']."");
        exit;
    }
    
    $a = "<i>Olá " . $player->username . ". Voc&ecirc; pode me ajudar?<br/>";
    $a .= "Preciso de sua ajuda para fazer uma entrega para o Lord Drofus. Estou sem tempo, e preciso que voc&ecirc; entregue um pacote para ele.<br>Se voc&ecirc; me ajudar, poderei fazer entregas para voc&ecirc; quando quiser. O que acha?</i>";
    $b = '<a href="tavern.php?p=quests&start='.$quest['id'].'&next=true">Aceitar</a><br/><a href="home.php">Recusar</a>';
    
} elseif ($missao['quest_status'] == 2) {
    $insert['player_id'] = $player->id;
    $insert['item_id'] = 116;
    $db->autoexecute('items', $insert, 'INSERT');
    $db->execute("update `quests` set `quest_status`='3', `extra`=? where `id`=?", array(time() + 900, $missao['id']));
    
    $a = "<i>Ótimo! Pegue este pacote e vá em direção à mansão de Lord Drofus.</i><br/><b>(Voc&ecirc; adiquiriu um pacote)</b>";
    $b = '<a href="tavern.php?p=quests&start='.$quest['id'].'">Continuar</a>';

} elseif ($missao['quest_status'] == 3) {
    if (time() > $missao['extra']) {
        $db->execute("update `quests` set `quest_status`='4' where `id`=?", array($missao['id']));
        header("Location: tavern.php?p=quests&start=".$quest['id']."");
        exit;
    }
    
    $a = "<i>Voc&ecirc; está a caminho da mansão de Lord Drofus.<br />Faltam " . ceil(($missao['extra'] - time()) / 60) . " minutos para voc&ecirc; chegar.</i>";
    $b = '<a href="home.php">Principal</a>';
} elseif ($missao['quest_status'] == 4) {
    if ($_GET['talk'] == 1) {
        $vesetemobox = $db->execute("select * from `items` where `item_id`=116 and `player_id`=?", array($player->id));
        if ($vesetemobox->recordcount() == 0) {
                  $a = "<i>Voc&ecirc; quer entregar um pacote? Estranho, pois não existe nenhum pacote no seu inventário.</i>";
                  $b = '<a href="home.php">Voltar</a>';
              } else {
                  $a = "<b>Mordomo:</b> <i>Bom, posso entregar esse pagote para o Lord Drofus, porém voc&ecirc; vai ter que me pagar ".$quest['cost']." moedas de ouro, caso contrário, ele não saberá que voc&ecirc; esteve aqui.</i>";
                  $b = '<a href="tavern.php?p=quests&start='.$quest['id'].'&pay=true">Pagar</a><br/><a href="home.php">Voltar</a>';
              }
    } elseif ($missao['pago'] == 't') {
        $db->execute("delete from `items` where `item_id`=116 and `player_id`=? limit 1", array($player->id));
        $db->execute("update `quests` set `quest_status`='5', `extra`=? where `id`=?", array(time() + 900, $missao['id']));
        $a = "<b>Mordomo:</b> <i>Obrigado. Entregarei o pacote ao Lord Drofus assim que ele chegar.</i>";
        $b = '<a href="tavern.php?p=quests&start='.$quest['id'].'">Voltar para cidade</a>';
    } else {
        $a = "<b>Mordomo:</b> <i>Olá senhor(a), o que deseja?</i>";
        $b = '<a href="tavern.php?p=quests&start='.$quest['id'].'&talk=1">Entregar um pacote</a><br/><a href="home.php">Voltar</a>';
    }
} elseif ($missao['quest_status'] == 5) {
    if (time() > $missao['extra']) {
        $db->execute("update `quests` set `quest_status`='6' where `id`=?", array($missao['id']));
        header("Location: tavern.php?p=quests&start=".$quest['id']."");
        exit;
    }
    
    $a = "<i>Voc&ecirc; está a caminho da cidade.<br />Faltam " . ceil(($missao['extra'] - time()) / 60) . " minutos para voc&ecirc; chegar.</i>";
    $b = '<a href="home.php">Principal</a>';
    
} elseif ($missao['quest_status'] == 6) {
    $db->execute("update `quests` set `quest_status`='7' where `id`=?", array($missao['id']));
    
    $a = "<i>Voc&ecirc; chegou na cidade.</i>";
    $b = '<a href="tavern.php?p=quests&start='.$quest['id'].'">Falar com Trevus</a>';
    
} elseif ($missao['quest_status'] == 7) {
    $db->execute("update `quests` set `quest_status`='90' where `id`=?", array($missao['id']));
    
    $a = "<i>Olá! Eu recebi uma mensagem de Lord Drofus, ele recebeu o pacote.</i><br /><i>Obrigado por me ajudar. Lembre-se, agora em diante sempre quando precisar enviar algo para alguém, acesse seu inventário.</i>";
    $b = '<a href="tavern.php?p=quests">Finalizar</a>';
}
