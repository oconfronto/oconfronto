<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.ezrpgproject.com/   */
/*************************************/

include("lib.php");
define("PAGENAME", "Principal");
$player = check_user($secret_key, $db);

include("templates/private_header.php");
?>

<?php

/* All form fields are automatically passed to the PHP script through the array $HTTP_POST_VARS. */
$email = $HTTP_POST_VARS['email'];
$subject = $HTTP_POST_VARS['subject'];
$message = $HTTP_POST_VARS['message'];

/* PHP form validation: the script checks that the Email field contains a valid email address and the Subject field isn't empty. preg_match performs a regular expression match. It's a very powerful PHP function to validate form fields and other strings - see PHP manual for details. */
if (!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $email)) {
  echo "<h4>Invalid email address</h4>";
  echo "<a href='javascript:history.back(1);'>Back</a>";
} elseif ($subject == "") {
  echo "<h4>No subject</h4>";
  echo "<a href='javascript:history.back(1);'>Back</a>";
}

/* Sends the mail and outputs the "Thank you" string if the mail is successfully sent, or the error string otherwise. */
elseif (mail($email,$subject,$message)) {
  echo "Obrigado por responder nossa pergunta";
} else {
  echo "Ocorreu um erro na resposta! Tente enviar a resposta manualmente para o seguinte email: <b>ezrpg@ymail.com</b>";
}
?>

<?php
include("templates/private_footer.php");
?>