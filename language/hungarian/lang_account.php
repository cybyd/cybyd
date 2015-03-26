<?php
$language["ACCOUNT_CREATED"]="Regisztrálj";
$language["USER_NAME"]="Felhasználó";
$language["USER_PWD_AGAIN"]="Jelszó újból";
$language["USER_PWD"]="Jelszó";
$language["USER_STYLE"]="Stílus";
$language["USER_LANGUE"]="Nyelv";
$language["IMAGE_CODE"]="Képkód";
$language["INSERT_USERNAME"]="Írd be a felhasználói neved!";
$language["INSERT_PASSWORD"]="Írd be a jelszavad!";
$language["DIF_PASSWORDS"]="Nem egyezik a jelszó!";
$language["ERR_NO_EMAIL"]="Írj be egy létező e-mail címet";
$language["USER_EMAIL_AGAIN"]="Email címed újból";
$language["ERR_NO_EMAIL_AGAIN"]="Új email";
$language["DIF_EMAIL"]="Az email címek nem egyeznek!";
$language["SECURITY_CODE"]="Jelszó erőssége";
# Password strength
$language["WEEK"]="Rövid";
$language["MEDIUM"]="Közepes";
$language["SAFE"]="Biztonságos";
$language["STRONG"]="Erős";
$language["ERR_GENERIC"]='Generic Error: '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
?>
