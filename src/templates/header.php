<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta http-equiv="Pragma" content="no-cache"/>
	<meta http-equiv="Expires" content="-1"/>

        <title>O Confronto :: <?php echo PAGENAME?></title>

	<link rel="stylesheet" type="text/css" href="./css/index.css"/>
    <link rel="stylesheet" type="text/css" href="example2.css"/>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" />
    <link rel="stylesheet" href="./assets/countdown/jquery.countdown.css" />
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="jMyCarousel.js"></script>
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
</head>
<body>
<?php
if ($_GET['r'])
{
    $_SESSION['ref'] = $_GET['r'];
    $linkref = $_GET['r'];
} else {
    $linkref = 1;
}
?>

        <div id="tudo">

                <div id="topo"></div>

<div id="box">
    <div class="bg-top"></div>
    <div class="bg-fundo">
        <div id="barra-top">
            <a href="register.php?ref=<?php echo $linkref; ?>" id="reg"></a>
            <a href="index.php?login=true&ref=<?php echo $linkref; ?>" id="entrar"></a>
        </div>

        <div id="conteudos">