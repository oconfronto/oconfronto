<?php

declare(strict_types=1);

/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.ezrpgproject.com/   */
/*************************************/

error_reporting(E_ALL & ~E_NOTICE);
session_start();

include(__DIR__ . "/config.php");
include(__DIR__ . "/functions.php");
include(__DIR__ . "/autocron.php");

ini_set("allow_url_fopen", "On");
date_default_timezone_set('America/Sao_Paulo');
