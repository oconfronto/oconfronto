<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Perfil");
$player = check_user($secret_key, $db);

include(__DIR__ . "/templates/private_header.php");

?>

<link rel="stylesheet" type="text/css" href="static/css/private/tabs.css" />
<script src="static/js/jquery.tabs.js"></script>

<ul class="tabs">
    <li><a href="#tab1">Gallery</a></li>
    <li><a href="#tab2">Submit</a></li>
</ul>

<div class="tab_container">
    <div id="tab1" class="tab_content">
        asiuodhiasudhad
    </div>
    <div id="tab2" class="tab_content">
      dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>dooorgas<br>
    </div>
</div>


<?php
	include(__DIR__ . "/templates/private_footer.php");
?>
