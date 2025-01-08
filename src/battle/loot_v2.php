<?php
declare(strict_types=1);
#Variables
##Chance item quest percentage
$chanceItemQuestPercentage = 10 * $rateDrop; 
##end Chance item quest percentage
##chance item drop percentage
$chanceItemDropPercentage = 5 * $rateDrop;
###Type method item drop
$dropdefault = false;
if ($dropdefault) {
    $variable = $db->execute("select `id`, `name` from `blueprint_items`
    where `type`!=?
    and `type`!=?
    and `type`!=?
    and `type`!=?
    and `type`!=?
    and `price` between ? and ?
    order by rand() limit 1",
    ["addon", "quest", "stone", "potion", "ring", $expdomonstro * 2.5, $expdomonstro * 3.5]);
}else{
    $lvlMin= 5;
    $lvlMax= 5;
    $levelMinimo = ($enemy->level - $lvlMin < 1) ? 1 : $enemy->level - $lvlMin;
    $levelMaximo = $enemy->level + $lvlMax;
    $variable = $db->execute("select `id`, `name` from `blueprint_items`
    where `type`!=?
    and `type`!=?
    and `type`!=?
    and `type`!=?
    and `type`!=?
    and `needlvl` between ? and ?
    order by rand() limit 1",
    ["addon", "quest", "stone", "potion", "ring", $levelMinimo, $levelMaximo]);
}
###end Type method item drop
###attributes item chance
$sorteiabonus1Percentage = 20 * $rateDrop; // Força / Magia
$sorteiabonus2Percentage = 20 * $rateDrop; // Vitalidade
$sorteiabonus3Percentage = 20 * $rateDrop; // Agilidade
$sorteiabonus4Percentage = 20 * $rateDrop; // Resistência
###end attributes item chance
##end chance item drop percentage
##chance item potion drop percentage
$chanceItemPotionPercentage = 8 * $rateDrop;
$levelSmallPotion = 50;
$levelHighPotion = 100;
$chanceSmallPotionPercentage = 35;
$chancePotionPercentage = 25;
$chanceHighPotionPercentage = 25;
##end chance item potion drop percentage
##chance rings drop percentage
$chanceRingDropType1_Percentage = 5 * $rateDrop;
$chanceRingType1_Percentage = 25;
$chanceRingDropType2_Percentage = 5 * $rateDrop;
$chanceRingType2_Percentage = 50;
##end chance rings drop percentage
##chance values drop percentage
$chanceOddinOrbsDropPercentage = 2 * $rateDrop;
$chanceGoldenBarDropPercentage = 2 * $rateDrop;
$chanceMagicCrystalDropPercentage = 2 * $rateDrop;
##end chance values drop percentage
#end Variables

function chance($percentage) {
    return random_int(1, 1000) <= ($percentage * 10);
}

if ($enemy->loot > 1) {
    
    if (chance($chanceItemQuestPercentage)) {
        $veositemz = $db->execute("select `item_id`, `item_prepo`, `item_name` from `loot` where `monster_id`=?", [$enemy->id]);
        if ($veositemz->recordcount() == 0) {
            $mensagem = "Contate ao administrador que o monstro " . $enemy->username . " está com erros.";
        } else {
            $loot_item = $veositemz->fetchrow();
            $mensagem = "<u><b>Você encontrou " . $loot_item['item_prepo'] . " " . $loot_item['item_name'] . " com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
            $lootstatus = 5;
            $loot_id = $loot_item['item_id'];
            $lootbonus1 = 0;
            $lootbonus2 = 0;
            $lootbonus3 = 0;
            $lootbonus4 = 0;
        }
    } else {
        $lootstatus = 2;
    }
} elseif ($enemy->loot == 1) {
    
    if (chance($chanceItemDropPercentage)) {
        $sorteiaitem = $variable;
        if ($sorteiaitem->recordcount() == 0) {
            $mensagem = "Contate ao administrador que o monstro " . $enemy->username . " está com erros.";
            $lootstatus = 2;
        } else {
            $loot_item2 = $sorteiaitem->fetchrow();
            
            if (chance($sorteiabonus1Percentage)) {
                $lootbonus1 = random_int(1, 4);
                $lootbonus1m = " +" . $lootbonus1 . "F";
            } else {
                $lootbonus1 = 0;
                $lootbonus1m = "";
            }
            
            if (chance($sorteiabonus2Percentage)) {
                $lootbonus2 = random_int(1, 4);
                $lootbonus2m = " +" . $lootbonus2 . "V";
            } else {
                $lootbonus2 = 0;
                $lootbonus2m = "";
            }
            
            if (chance($sorteiabonus3Percentage)) {
                $lootbonus3 = random_int(1, 4);
                $lootbonus3m = " +" . $lootbonus3 . "A";
            } else {
                $lootbonus3 = 0;
                $lootbonus3m = "";
            }

            
            if (chance($sorteiabonus4Percentage)) {
                $lootbonus4 = random_int(1, 4);
                $lootbonus4m = " +" . $lootbonus4 . "R";
            } else {
                $lootbonus4 = 0;
                $lootbonus4m = "";
            }

            $mensagem = "<u><b>Você encontrou um(a) " . $loot_item2['name'] . "" . $lootbonus1m . "" . $lootbonus2m . "" . $lootbonus3m . "" . $lootbonus4m . " com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
            $lootstatus = 5;
            $loot_id = $loot_item2['id'];
        }
    } else {
        $lootstatus = 2;
    }
}

if ($lootstatus == 2) {
    
    if (chance($chanceItemPotionPercentage)) {
        if ($player->level < $levelSmallPotion) {       
            if (chance($chanceSmallPotionPercentage)) {
                $mensagem = "<u><b>Você encontrou uma Mana Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
                $lootstatus = 5;
                $loot_id = 150;
                $lootbonus1 = 0;
                $lootbonus2 = 0;
                $lootbonus3 = 0;
                $lootbonus4 = 0;
            } else {
                $mensagem = "<u><b>Você encontrou uma Health Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
                $lootstatus = 5;
                $loot_id = 136;
                $lootbonus1 = 0;
                $lootbonus2 = 0;
                $lootbonus3 = 0;
                $lootbonus4 = 0;
            }
        } elseif ($player->level >= $levelSmallPotion && $player->level < $levelHighPotion) {            
            if (chance($chancePotionPercentage)) {
                $mensagem = "<u><b>Você encontrou uma Health Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
                $lootstatus = 5;
                $loot_id = 136;
                $lootbonus1 = 0;
                $lootbonus2 = 0;
                $lootbonus3 = 0;
                $lootbonus4 = 0;
            } elseif (chance($chancePotionPercentage)) {
                $mensagem = "<u><b>Você encontrou uma Mana Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
                $lootstatus = 5;
                $loot_id = 150;
                $lootbonus1 = 0;
                $lootbonus2 = 0;
                $lootbonus3 = 0;
                $lootbonus4 = 0;
            } else {
                $mensagem = "<u><b>Você encontrou uma Energy Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
                $lootstatus = 5;
                $loot_id = 137;
                $lootbonus1 = 0;
                $lootbonus2 = 0;
                $lootbonus3 = 0;
                $lootbonus4 = 0;
            }
        } else {            
            if (chance($chanceHighPotionPercentage)) {
                $mensagem = "<u><b>Você encontrou uma Big Health Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
                $lootstatus = 5;
                $loot_id = 148;
                $lootbonus1 = 0;
                $lootbonus2 = 0;
                $lootbonus3 = 0;
                $lootbonus4 = 0;
            } elseif (chance($chanceHighPotionPercentage)) {
                $mensagem = "<u><b>Você encontrou uma Mana Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
                $lootstatus = 5;
                $loot_id = 150;
                $lootbonus1 = 0;
                $lootbonus2 = 0;
                $lootbonus3 = 0;
                $lootbonus4 = 0;
            } else {
                $mensagem = "<u><b>Você encontrou uma Energy Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
                $lootstatus = 5;
                $loot_id = 137;
                $lootbonus1 = 0;
                $lootbonus2 = 0;
                $lootbonus3 = 0;
                $lootbonus4 = 0;
            }
        }
    }
}

if ($lootstatus == 2 && $player->level > 10) {    
    if (chance($chanceRingDropType1_Percentage)) {        
        if (chance($chanceRingType1_Percentage)) {
            $mensagem = "<u><b>Você encontrou um Strength Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
            $lootstatus = 5;
            $loot_id = 164;
            $lootbonus1 = 10;
            $lootbonus2 = 0;
            $lootbonus3 = 0;
            $lootbonus4 = 0;
        } elseif (chance($chanceRingType1_Percentage)) {
            $mensagem = "<u><b>Você encontrou um Vitality Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
            $lootstatus = 5;
            $loot_id = 165;
            $lootbonus1 = 0;
            $lootbonus2 = 10;
            $lootbonus3 = 0;
            $lootbonus4 = 0;
        } elseif (chance($chanceRingType1_Percentage)) {
            $mensagem = "<u><b>Você encontrou um Agility Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
            $lootstatus = 5;
            $loot_id = 166;
            $lootbonus1 = 0;
            $lootbonus2 = 0;
            $lootbonus3 = 10;
            $lootbonus4 = 0;
        } elseif (chance($chanceRingType1_Percentage)) {
            $mensagem = "<u><b>Você encontrou um Resistance Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
            $lootstatus = 5;
            $loot_id = 167;
            $lootbonus1 = 0;
            $lootbonus2 = 0;
            $lootbonus3 = 0;
            $lootbonus4 = 10;
        }
    } else {
        $lootstatus = 2;
    }
}

if ($lootstatus == 2) {    
    if (chance($chanceRingDropType2_Percentage)) {        
        if (chance($chanceRingType2_Percentage)) {
            $mensagem = "<u><b>Você encontrou um Dark Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
            $lootstatus = 5;
            $loot_id = 169;
            $lootbonus1 = 10;
            $lootbonus2 = 0;
            $lootbonus3 = 0;
            $lootbonus4 = 15;
        } elseif (chance($chanceRingType2_Percentage)) {
            $mensagem = "<u><b>Você encontrou um Energy Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
            $lootstatus = 5;
            $loot_id = 170;
            $lootbonus1 = 0;
            $lootbonus2 = 15;
            $lootbonus3 = 15;
            $lootbonus4 = 5;
        }
    } else {
        $lootstatus = 2;
    }
}

if ($lootstatus == 2 && $player->level > 75) {    
    if (chance($chanceOddinOrbsDropPercentage)) {
        $mensagem = "<u><b>Você encontrou um Oddin Orb com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
        $lootstatus = 5;
        $loot_id = 156;
        $lootbonus1 = 0;
        $lootbonus2 = 0;
        $lootbonus3 = 0;
        $lootbonus4 = 0;
    } else {
        $lootstatus = 2;
    }
}

if ($lootstatus == 2 && $player->level > 90) {    
    if (chance($chanceGoldenBarDropPercentage)) {
        $mensagem = "<u><b>Você encontrou uma Magic Golden Bar com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
        $lootstatus = 5;
        $loot_id = 157;
        $lootbonus1 = 0;
        $lootbonus2 = 0;
        $lootbonus3 = 0;
        $lootbonus4 = 0;
    } else {
        $lootstatus = 2;
    }
}

if ($lootstatus == 2 && $player->level > 120) {    
    if (chance($chanceMagicCrystalDropPercentage)) {
        $mensagem = "<u><b>Você encontrou um Magic Crystal com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
        $lootstatus = 5;
        $loot_id = 177;
        $lootbonus1 = 0;
        $lootbonus2 = 0;
        $lootbonus3 = 0;
        $lootbonus4 = 0;
    } else {
        $lootstatus = 2;
    }
}