<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

    <title>O Confronto :: <?php echo PAGENAME ?></title>

    <link rel="icon" type="image/x-icon" href="static/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
    <style>
		@font-face {
		font-family: 'Pixelify Sans Without Digits';
		src: url('static/fonts/PixelifySans.ttf') format('truetype');
		/* Include all characters except digits */
		unicode-range: U+0000-002F, U+003A-FFFF;
		}

		* {
			font-family: 'Pixelify Sans Without Digits', monospace, sans-serif !important;
		}
	</style>
    <link rel="stylesheet" type="text/css" href="static/css/index.css" />
    <link rel="stylesheet" type="text/css" href="static/example2.css" />

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" />
    <link rel="stylesheet" href="static/assets/countdown/jquery.countdown.css" />

    <script type="text/javascript" src="static/js/jquery.js"></script>
    <script type="text/javascript" src="static/jMyCarousel.js"></script>
    <!-- Optional -->
    <script type="text/javascript">
        $(function() {

            $(".jMyCarousel").jMyCarousel({
                visible: '3',
                eltByElt: true,
                evtStart: 'mousedown',
                evtStop: 'mouseup'
            });

        });
    </script>
    <script
        async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1892370805366558"
        crossorigin="anonymous">
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5C9CTZE98D"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-5C9CTZE98D');
    </script>
</head>

<body>
    <?php
    if ($_GET['r'] ?? null) {
        $_SESSION['ref'] = $_GET['r'];
        $linkref = $_GET['r'];
    } else {
        $linkref = "";
    }
    ?>
    <div id="tudo">
        <div id="topo"></div>
        <img src="static/images/logo-dark.png" style="position: absolute; top: 2rem; left: 0; right: 0; margin: auto;" />
        <div style="position: absolute;left: 0;right: 0;top: 0;bottom: 0;margin: auto;width: fit-content;height: fit-content;">
            <div class="bg-top"></div>
            <div class="bg-fundo">
                <div id="barra-top">
                    <a href="register.php?ref=<?php echo $linkref; ?>" id="reg"></a>
                    <a href="index.php?login=true&ref=<?php echo $linkref; ?>" id="entrar"></a>
                </div>

                <div id="conteudos">