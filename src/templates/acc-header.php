<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

    <title>O Confronto :: <?php echo PAGENAME ?></title>

    <link rel="icon" type="image/x-icon" href="static/favicon.ico">
    <link rel="stylesheet" type="text/css" href="static/css/index.css" />
    <link rel="stylesheet" type="text/css" href="static/example2.css" />

    <script type="text/javascript" src="static/js/jquery.js"></script>

    <script type="text/javascript" src="static/jMyCarousel.js"></script>
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

    <Script Language=JavaScript>
        var nText = new Array()
        nText[0] = "<div>Escolha sua vocação.</div>";
        nText[1] = "<div>Os Cavaleiros possuem uma grande defesa mas um baixo ataque.</div>";
        nText[2] = "<div>Os Magos são nivelados em ataque e defesa.</div>";
        nText[3] = "<div>Os Arqueiros possuem um bom ataque mas uma defesa fraca.</div>"

        function swapText(isList) {
            txtIndex = isList.selectedIndex;
            document.getElementById('textDiv').innerHTML = nText[txtIndex];
        }
    </Script>
    <script
        async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1892370805366558"
        crossorigin="anonymous"
    >
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5C9CTZE98D"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-5C9CTZE98D');
    </script>
</head>

<body>
    <div id="tudo">

        <div id="topo"></div>

        <?php
        include(__DIR__ . "/notice_board.php");
        ?>

        <div id="box">
            <div class="bg-top"></div>
            <div class="bg-fundo">
                <div id="barra-top">
                    <a href="logout.php" id="log"></a>
                    <a href="characters.php" id="char"></a>
                </div>

                <div id="conteudos">