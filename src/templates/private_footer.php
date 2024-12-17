<span id="playSound"></span>
</div>
</div>
</td>
</tr>
</table>


</div>

<?php
$check = $db->execute("select * from `pending` where `pending_id`=30 and `player_id`=?", [$player->id]);
if ($check->recordcount() == 0) {
    echo '<script type="text/javascript" src="static/js/chat.js"></script>';
} else {
    $stattus = $check->fetchrow();
    if (($stattus['pending_status'] ?? null) != 'inv') {
        echo '<script type="text/javascript" src="static/js/chat.js"></script>';
    }
}
?>

<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-6607673-3']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
</script>
</body>

</html>