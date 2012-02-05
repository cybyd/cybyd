<?php

// smf_import.php language file

$language['charset']='ISO-8859-1';
$lang[0]='Ja';
$lang[1]='Nej';
$lang[2]='<center><u><strong><font size="4" face="Arial">Iscensätta 1: Initialbokstav Krav</font></strong></u></center><br />';
$lang[3]='<center><strong><font size="2" face="Arial">SMF arkiv erbjuda i "smf" mappen?<font color="';
$lang[4]='">&nbsp;&nbsp;&nbsp; ';
$lang[5]='</font></center></strong>';
$lang[6]='<br /><center>snälla <a target="_new" href="http://www.simplemachines.org/download/">Ladda hem SMF</a> och ladda upp innehållet av de arkiv i "smf" mappen.<br />Om man har i "smf" mappen varsågod frambringa någon i ditt förföljare rot och ladda upp<br/>innehållet av de arkiv att det.<br /><br />när det väl är uppladdat så ska det sluta med ett litet p för att användas med $lang[8]
$lang[7]='<br /><center>P'; //använd P som versal för användning med $lang[8]
$lang[8]='arrendera installerare av SMF genom att <a target="_new" href="smf/install.php">Tryck här</a>*<br /><br /><strong>* Snälla använd samma databas inloggning detaljer som dom använt till din tracker,<br />du kan använda vilken databas prefix du vill (undantaget som du använde när<br />tracker blev installerad )<br /><br />';
$lang[9]='<font color="#0000FF" size="3">Du får uppfriska den sida en gång så du har de nödvändig bestyr!</font></strong></center>';
$lang[10]='<center><strong>SMF installerad?<font color="';
$lang[11]='Fil hittades inte!';
$lang[12]='Filen hittades men det går inte att skriva till den!';
$lang[13]='<center><strong>Standard SMF Engelska error filen är tillgänglig och skrivbar?<font color="';
$lang[14]='<center><strong>smf.sql filen behövs i "sql" mappen?<font color="';
$lang[15]='<br /><center><strong>språk fil (';
$lang[16]=')<br />saknas, gör dig säker <font color="#FF0000"><u>alla SMF filer</u></font> blev uppladdade!<br /><br />';
$lang[17]=')<br />är inte skrivbar, <font color="#FF0000"><u>snälla ändra rättigheterna till 777</u></font><br /><br />';
$lang[18]='<br /><center><strong>smf.sql är saknad, <font color="#FF0000"><u>snälla gör dig säker på att filen finns i "sql" mappen.</u></font><br />(det borde funka bättre med Xbtit!)<br /><br />';
$lang[19]='<br /><center>Alla krav är uppfyllda, varsågod <a href="';
$lang[20]='">tryck här för att fortsätta</a></center>';
$lang[21]='<center><u><strong><font size="4" face="Arial">Iscensätta 2: Initialbokstav System</font></strong></u></center><br />';
$lang[22]='<center>verifierat allt är i ordning det är dags att modifiera databasen<br />att medföra allt överensstämma med de följande.</center><br />';
$lang[23]='<center><form name="db_pwd" action="smf_import.php" method="GET">skriv in databas lösnordet;<input name="pwd" size="20" /><br />'."\n".'<br />."\n".<strong>snälla <input type="submit" name="confirm" value="yes" size="20" /> för att fortsätta</strong><input type="hidden" name="act" value="init_setup" /></form></center>';
$lang[24]='<center><u><strong><font size="4" face="Arial">Iscensätta 3: Importerande trackern's medlemmar</font></strong></u></center><br />';
$lang[25]='<center>Nu har databasen blivit installerat ordentligt det är tid till att starta importering av trackern's medlemmar,<br />Det kan ta lång tid ifall du har stort medlemsantal i databasen, var snäll vänta medans det håller på<br />Scriptet gjorde det det skulle göra :)!<br /><br /><strong>snälla <a href="'.$_SERVER['PHP_SELF'].'?act=member_import&amp;confirm=yes">Tryck här</a> för att fortsätta</center>';
$lang[26]='<center><u><strong><font size="4" face="Arial">ledsen</font></strong></u></center><br />';
$lang[27]='<center>Ledsen, det är menat att detta ska bli använt en gång , sen överge script och eftersom du redan har använt denna fil så har den blivit låst!</center>';
$lang[28]='<center><br /><strong><font color="#FF0000"><br />';
$lang[29]='</strong></font> Forum kontona skapades precis som dom ska, snälla <a href="'.$_SERVER['PHP_SELF'].'?act=import_forum&amp;confirm=no">Tryck här</a> för att fortsätta</center>';
$lang[30]='<center><u><strong><font size="4" face="Arial">Iscensätta 4: Importerande forum utsende & medelande</font></strong></u></center><br />';
$lang[31]='<center>Detta är sista steget i foruminstallationen, detta kommer sätta in alla BTI Forum till SMF,<br />dom kommer hamna i en ny kategori kallad "My BTI import",<br />snälla <a href="'.$_SERVER['PHP_SELF'].'?act=import_forum&amp;confirm=yes">Tryck här</a> för att fortsätta</center>';
$lang[32]='<center><u><strong><font size="4" face="Arial">Importering färdig</font></strong></u></center><br />';
$lang[33]='<center><font face="Arial" size="2">Please <a target="_new" href="smf/index.php?action=login">logga in till din nya smf forum</a> använd dina torrent login detaljer för att logga in<br />the <strong>Admin Panel</strong> sen välj <strong>Forum Underhåll</strong> och kör<br /><strong>hitta några fel och fixa dom.</strong> efterföljd av <strong>räkna om alla forum<br />och statistik.</strong> städa i importeringen och så vidare.<br /><br /><strong><font color="#0000FF">sen så borde din SMF forum vara färdig att användas!</font></strong></font></center>';
$lang[34]='<center><u><strong><font size="4" face="Arial" color="#FF0000">FEL!</font></strong></u></center><br />'."\n".'<br />'."\n".'<center><font face="Arial" size="3">Du skrev in fel lösnord eller så är du inte ägaren av tracker'n!<br />'."\n".'notera att ditt IP har blivit loggad.</font></center>';
$lang[35]='</body>'."\n".'</html>'."\n";
$lang[36]='<center>kan inte skriva till:<br /><br /><b>';
$lang[37]='</b><br /><br />snälla var säker på att filen är skrivbar kör sedan skriptet igen.</center>';
$lang[38]='<center><br /><font color="red" size="4"><b>Tillgång Nekad</b></font></center>';
?>