<?php
// Player Stats Template
?>
<table width="100%">
    <tr>
        <td class="brown" width="100%" colspan="2">
            <center>
                <b>Pontos de Status</b>
                <img src="static/images/help.gif" title="header=[Pontos de Status] body=[<font size='1px'>São utilizados para aumentar sua agilidade, vitalidade, etc. A cada nível que você passar você ganha 3 pontos de status. Quando isso ocorrer não se esqueça de utilizá-los!</font>]">
            </center>
        </td>
    </tr>
    <tr>
        <td class="salmon" height="80px" colspan="2">
            <div id="skills">
                <?php include(__DIR__ . "/../showskills.php"); ?>
            </div>
        </td>
    </tr>
    <tr>
        <td class="<?= $player->stat_points > 8 ? 'red' : 'on' ?>">
            <center>
                <font size="1px">
                    <b><a href="stat_points.php">Distribuir pontos</a></b>
                </font>
            </center>
        </td>
        <td class="<?= $player->level > 79 && $player->buystats == 0 ? 'red' : 'on' ?>">
            <center>
                <font size="1px">
                    <b><a href="buystats.php">Treinar</a></b>
                </font>
            </center>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <br/>
            <table width="100%">
                <tr>
                    <td class="brown" width="100%">
                        <center><b>Estender Mana</b></center>
                    </td>
                </tr>
                <tr>
                    <td class="salmon" height="100px">
                        <div id="maxmana">
                            <?php
                            $magiascount = $db->execute("select * from `magias` where `player_id`=?", [$player->id]);
                            if ($magiascount->recordcount() < 11): ?>
                                <br/><br/>
                                <center>Apenas jogadores que possuem todas as magias liberadas podem estender sua mana.</center>
                                <br/><br/>
                            <?php else: ?>
                                <br/>
                                <center>
                                    <img src="static/images/man.png">
                                    <img src="static/bargen.php?man">
                                    <?php if ($player->magic_points > 0): ?>
                                        <a href="javascript:void(0)" onclick="javascript:LoadPage('swap_spells.php?estender=true', 'maxmana')">
                                            <img src="static/images/addstat.png" border="0">
                                        </a>
                                    <?php else: ?>
                                        <img src="static/images/none.png" border="0">
                                    <?php endif; ?>
                                </center>
                                <center>
                                    <font size="1px">
                                        Estenda 2 pontos da sua mana<br/>
                                        máxima por 1 ponto místico.<br/><br/>
                                        <b>Você <?= $player->magic_points ?> tem ponto(s) místico(s).</b>
                                    </font>
                                </center>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table> 
