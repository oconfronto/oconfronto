<?
/*
Captcha ZDR
This is simple powerfull captcha tool writen in PHP
for protecting your web FORMS from spamers.

Copyright (C) 2007  Zdravko Shishmanov 
Country: Bulgaria 
Email: zdrsoft@yahoo.com
http://www.webtoolbag.com

GNU General Public License

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.s
*/
//error_reporting(0);
session_start();
include("class/captchaZDR.php");

$capt = new captchaZDR;
$capt->display();
?>
