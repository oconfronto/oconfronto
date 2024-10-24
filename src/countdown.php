<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Principal");

include(__DIR__ . "/templates/header.php");
?>
<span id="aviso-a"></span>


<div id="countdown"></div>
<p id="note"></p>

<!-- JavaScript includes -->
<script src="static/http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="static/assets/countdown/jquery.countdown.js"></script>
<script src="static/assets/js/script.js"></script>

<?php
include(__DIR__ . "/templates/footer.php");
?>
