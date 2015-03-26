<?php
$language['ACCOUNT_CREATED']='Cont Creat';
$language['USER_NAME']='Utilizator';
$language['USER_PWD_AGAIN']='Repetă parola';
$language['USER_PWD']='Parolă';
$language['USER_STYLE']='Skin';
$language['USER_LANGUE']='Limbă';
$language['IMAGE_CODE']='Cod Imagine';
$language['INSERT_USERNAME']='Trebuie să specifici un nume de utilizator!';
$language['INSERT_PASSWORD']='Trebuie să îţi alegi o parolă!';
$language['DIF_PASSWORDS']='Parolele nu se potrivesc una cu alta!';
$language['ERR_NO_EMAIL']='Adresa de e-mail specificată trebuie să fie validă';
$language['USER_EMAIL_AGAIN']='Repetă e-mail';
$language['ERR_NO_EMAIL_AGAIN']='Repetă e-mail';
$language['DIF_EMAIL']='Adresele de e-mail nu se potrivesc!';
$language['SECURITY_CODE']='Responde à pergunta';
# Password strength
$language['WEEK']='Slabă';
$language['MEDIUM']='Medie';
$language['SAFE']='Sigură';
$language['STRONG']='Puternică';
$language['ERR_GENERIC']='Eroare generală: '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
?>
