<player-top>
    <a href="avatar.php" class="avatar-container">
        <level-element>
            <?php echo $player->level ?>
        </level-element>
        <img
            src="<?php echo $player->avatar ? $player->avatar : "static/anonimo.gif" ?>"
            alt="<?php echo $player->username ?>"
            class="avatar-top"
        />
        <name-container><?php echo $player->username ?></name-container>
    </a>
    <bar-container>
        <gold-and-inventory>
            <gold-counter>
                <?php echo number_format($player->gold, 0, '', '.') ?>
            </gold-counter>
            <?php
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                if (isMobile($userAgent)) {
                    echo '<a href="inventory_mobile.php" class="inventory-button"></a>';
                }
            ?>
        </gold-and-inventory>
        <health-bar
            class="bar"
            style="--percentage: <?php echo ceil(($player->hp * 100) / $player->maxhp); ?>%;"
        >
            <?php echo $player->hp; ?> / <?php echo $player->maxhp; ?>
        </health-bar>
        <magic-bar
            class="bar"
            style="--percentage: <?php echo ceil(($player->mana * 100) / $player->maxmana); ?>%;"
        >
            <?php echo $player->mana; ?> / <?php echo $player->maxmana; ?>
        </magic-bar>
        <stamina-bar
            class="bar"
            style="--percentage: <?php echo ceil(($player->energy * 100) / $player->maxenergy); ?>%;"
        >
            <?php echo $player->energy; ?> / <?php echo $player->maxenergy; ?>
        </stamina-bar>
        
        <experience-bar
            class="bar"
            style="--percentage: <?php echo ceil(($player->exp * 100) / maxExp($player->level)); ?>%;"
        >
            <?php echo $player->exp; ?> / <?php echo maxExp($player->level); ?>
        </experience-bar>
    </bar-container>
    <?php
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!isMobile($userAgent)) {
            include_once __DIR__ . "/../showit.php";
        }
    ?>
</player-top>
