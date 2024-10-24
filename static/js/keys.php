        <script>
            function domo(){
		<?php if($bixo->id != 0) { ?>
                jQuery(document).bind('keydown', 'Shift+backspace',function (evt){
			window.location = "monster.php?act=attack&id=<?=($bixo->id * $player->id);?>";
		});

                jQuery(document).bind('keydown', 'Shift+return',function (evt){
			window.location = "monster.php?act=attack&acabaluta=true";
		});

                jQuery(document).bind('keydown', 'Shift+space',function (evt){
			window.location = "monster.php?act=attack&hit=true";
		});

                jQuery(document).bind('keydown', 'Shift+1',function (evt){
			window.location = "monster.php?act=attack&magic=1";
		});

                jQuery(document).bind('keydown', 'Shift+2',function (evt){
			window.location = "monster.php?act=attack&magic=2";
		});

                jQuery(document).bind('keydown', 'Shift+3',function (evt){
			window.location = "monster.php?act=attack&magic=3";
		});

                jQuery(document).bind('keydown', 'Shift+4',function (evt){
			window.location = "monster.php?act=attack&magic=4";
		});

                jQuery(document).bind('keydown', 'Shift+5',function (evt){
			window.location = "monster.php?act=attack&magic=5";
		});

                jQuery(document).bind('keydown', 'Shift+6',function (evt){
			window.location = "monster.php?act=attack&magic=6";
		});

                jQuery(document).bind('keydown', 'Shift+7',function (evt){
			window.location = "monster.php?act=attack&magic=7";
		});

                jQuery(document).bind('keydown', 'Shift+8',function (evt){
			window.location = "monster.php?act=attack&magic=8";
		});

                jQuery(document).bind('keydown', 'Shift+9',function (evt){
			window.location = "monster.php?act=attack&magic=9";
		});

                jQuery(document).bind('keydown', 'Shift+0',function (evt){
			window.location = "monster.php?act=attack&magic=10";
		});

                jQuery(document).bind('keydown', 'Shift+-',function (evt){
			window.location = "monster.php?act=attack&magic=11";
		});

		<?php } ?>

                jQuery(document).bind('keydown', 'Alt+0',function (evt){
			window.location = "hospt.php?act=heal";
		});

                jQuery(document).bind('keydown', 'Alt+1',function (evt){
			window.location = "home.php";
		});

                jQuery(document).bind('keydown', 'Alt+2',function (evt){
			window.location = "bat.php";
		});

                jQuery(document).bind('keydown', 'Alt+3',function (evt){
			window.location = "hospt.php";
		});

                jQuery(document).bind('keydown', 'Alt+4',function (evt){
			window.location = "bank.php";
		});

                jQuery(document).bind('keydown', 'Alt+5',function (evt){
			window.location = "inventory.php";
		});

            }
            
            
            jQuery(document).ready(domo);
            
        </script>