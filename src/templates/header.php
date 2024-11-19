<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

    <title>O Confronto :: <?php echo PAGENAME ?></title>

    <link rel="icon" type="image/x-icon" href="static/favicon.ico">
    <link rel="stylesheet" type="text/css" href="static/css/index.css" />
    <link rel="stylesheet" type="text/css" href="static/example2.css" />

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" />
    <link rel="stylesheet" href="static/assets/countdown/jquery.countdown.css" />
    <!--[if lt IE 9]>
    <script src="static/http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

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
    if (isset($_GET['r'])) {
        $_SESSION['ref'] = $_GET['r'];
        $linkref = $_GET['r'];
    } else {
        $linkref = "";
    }
    ?>
    <div id="tudo">

        <div id="topo"></div>

        <?php
        include(__DIR__ . "/../notice_board.php");
        ?>

        <div id="box">
            <div class="bg-top"></div>
            <div class="bg-fundo">
                <div id="barra-top">
                    <a href="register.php?ref=<?php echo $linkref; ?>" id="reg"></a>
                    <a href="index.php?login=true&ref=<?php echo $linkref; ?>" id="entrar"></a>
                </div>

                <div id="conteudos">