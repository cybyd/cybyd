<?php
$language['charset']='ISO-8859-1';
$language['ACCOUNT_CREATED'] = 'Konto skapat';
$language['USER_NAME'] = 'Användare';
$language['USER_PWD_AGAIN'] = 'Upprepa Lösenord';
$language['USER_PWD'] = 'Lösenord';
$language['USER_STYLE'] = 'Stil';
$language['USER_LANGUE'] = 'Språk';
$language['IMAGE_CODE'] = 'Säkerhetskod';
$language['INSERT_USERNAME'] = 'Du måste ange ett användarnamn!';
$language['INSERT_PASSWORD'] = 'Du måste ange ett lösenord!';
$language['DIF_PASSWORDS'] = 'Lösnorden matchar inte varandra!';
$language['ERR_NO_EMAIL'] = 'Du måste ange en riktig E-post adress';
$language['USER_EMAIL_AGAIN'] = 'Upprepa E-postl adress';
$language['ERR_NO_EMAIL_AGAIN'] = 'Upprepa E-post adressen';
$language['DIF_EMAIL'] = 'E-post adresserna matchar inte varandra!';
$language['SECURITY_CODE'] = 'Svar på frågan';
# Password strength
$language['WEEK'] = 'Svagt';
$language['MEDIUM'] = 'Medium';
$language['SAFE'] = 'Säkert Lösenord';
$language['STRONG'] = 'Starkt Lösenord';
$language['ERR_GENERIC']='Generic Error: '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
?>
