<?php

require_once (__DIR__ . "/encryption.php");

$ENCRYPTION_KEY = 'HFErdfguhidb*IKMNB$%^&y54jyrngSDXCVuju7y65$E67u5yjtnfgVFE$%^U';
$HMAC_SALT = '87654E5$E67u5yjtnfgVjknjbHVGFTR%$rt';

$rawdata = http_build_query(array(
    "auth_id" => 155,                                   // mdl_users.id of the user you want to log in as
    "activity" => 1,                                        // the 1-based index of the activity to open (typically 1)
));

// bad data throws exceptions
/*
$rawdata = http_build_query(array(
    "userid" => 2                                       // throw new Exception("OpenSesame: Fatal error, cannot continue.");
));
*/

/* now we encrypt the whole querystring and jam it onto the data parameter */
$enclib = new OpenSesame_Encryption($ENCRYPTION_KEY, $HMAC_SALT);
$data = bin2hex($enclib->encrypt($rawdata)); // more querystring-safe than base64

// the url of the opensesame endpoint in moodle
$url = "http://your.moodle.server.dev/auth/opensesame/login.php?data=$data";

echo "<p><a href='$url' target='_blank'>Log on with OpenSesame</a></p>";

?>

<p>Open the link in an incognito window to try it multiple times.</p>
<p>Refresh this page to change the encryption value.</p>
