<?php

// CyBerFuN.ro & xList.ro

// xList .::. xDNS
// http://xDNS.ro/
// http://xLIST.ro/
// Modified By cybernet2u

/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2012  Btiteam
//
//    This file is part of xbtit.
//
// Redistribution and use in source and binary forms, with or without modification,
// are permitted provided that the following conditions are met:
//
//   1. Redistributions of source code must retain the above copyright notice,
//      this list of conditions and the following disclaimer.
//   2. Redistributions in binary form must reproduce the above copyright notice,
//      this list of conditions and the following disclaimer in the documentation
//      and/or other materials provided with the distribution.
//   3. The name of the author may not be used to endorse or promote products
//      derived from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED
// WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
// MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
// IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
// TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
// PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
// LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
// EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
////////////////////////////////////////////////////////////////////////////////////

$dbfile="upgrade/v141_to_v2.sql";

// declaration of variables
$INSTALLPATH = dirname(__FILE__);
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : 'welcome');
$allowed_actions = array('welcome','reqcheck','settings','sql_import','save_mysql','finished');
if (!in_array($action, $allowed_actions))
    $action = 'welcome';
define("BTIT_INSTALL", TRUE);

if (isset($_SERVER['PHP_SELF']))
   $_SERVER['PHP_SELF']=htmlspecialchars($_SERVER['PHP_SELF']);
$cur_script=$_SERVER['PHP_SELF'];

require_once("include/xbtit_version.php");
global $tracker_version, $tracker_revision;

// getting globals
$GLOBALS["btit-tracker"]         = "xbtit";
$GLOBALS["current_btit_version"] = $tracker_version . " (r".$tracker_revision.")";
$GLOBALS["btit_installer"]       = "xbtit Upgrade ::";

// getting needed files
load_lang_file();

// starting main page
echo ("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
echo ("<html xmlns=\"http://www.w3.org/1999/xhtml\">");
echo ("<head>");
echo ("<meta http-equiv=\"content-type\" content=\"text/html; charset=".(isset($install_lang["charset"])?$install_lang["charset"]:"ISO-8859-1")."\" />");
echo ("<title>".$GLOBALS["btit_installer"]."&nbsp;".$GLOBALS["current_btit_version"]."</title>");
echo ("<link rel=\"stylesheet\" href=\"style/xbtit_default/main.css\" type=\"text/css\" />");
echo ("</head>");
echo ("<body>");
echo ("<div id=\"main\" />");
echo ("<center><div id=\"logo\" />");
echo ("<table width=\"750\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" />");
echo ("<tr><td class=\"tracker_logo\" valign=\"top\"></td></tr>");
echo ("</table></div></center>");
// now we can add the different pages for the installer

// Getting wished install language
function load_lang_file()
{
    global $install_lang;

    $GLOBALS["find_install_lang"] = array();

    // Make sure the languages directory actually exists.
    if (file_exists(dirname(__FILE__) . '/language/install_lang/'))
    {
        // Find all the "Install" language files in the directory.
        $dir = dir(dirname(__FILE__) . '/language/install_lang/');
        while ($entry = $dir->read())
        {
            if (substr($entry, 0, 8) == 'install.' && substr($entry, -4) == '.php')
                $GLOBALS["find_install_lang"][$entry] = ucfirst(substr($entry, 8, strlen($entry) - 12));
        }
        $dir->close();
    }

    // Didn't find any, show an error message!
    if (empty($GLOBALS["find_install_lang"]))
    {
        step ("Installation ERROR!","ERROR!","*");
        echo ("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
        echo ("<html xmlns=\"http://www.w3.org/1999/xhtml\">");
        echo ("<head>");
        echo ("<meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\" />");
        echo ("<title>".$GLOBALS["btit_installer"]."&nbsp;".$GLOBALS["current_btit_version"]." - Language Error</title>");
        echo ("<link rel=\"stylesheet\" href=\"style/xbtit_default/main.css\" type=\"text/css\" />");
        echo ("</head>");
        echo ("<body style=\"font-family: sans-serif;\"><div style=\"width: 600px;\">");
        echo ("<p>A critical language error has occurred.</p>");
        echo ("<p>This installer was unable to find the installer's language file or files.  They should be found under:</p>");
        echo ("<div style=\"margin: 1ex; font-family: monospace; font-weight: bold;\">/language/install_lang/</div>");
        echo ("<p>In some cases, FTP clients do not properly upload files with this many folders.  Please double check to make sure you <span style=\"font-weight: 600;\">have uploaded all the files in the distribution</span>.</p>");
        echo ("<p>If you continue to get this error message, feel free to <a href=\"http://www.btiteam.org/smf/index.php/\">look to us for support</a>.</p>");
        echo ("</div>");
        die;
    }

    // Override the language file?
    if (isset($_GET["lang_file"]))
        $_SESSION["install_lang"] = $_GET["lang_file"];
    elseif (isset($GLOBALS["HTTP_GET_VARS"]["lang_file"]))
        $_SESSION["install_lang"] = $GLOBALS["HTTP_GET_VARS"]["lang_file"];
    // If no language is selected, use English as the default
    else $_SESSION["install_lang"] = "install.english.php";

    // Make sure it exists, if it doesn't reset it.
    if (!isset($_SESSION["install_lang"]) || !file_exists(dirname(__FILE__) . '/language/install_lang/' . $_SESSION["install_lang"]))
    {
        // Use the first one...
        list ($_SESSION["install_lang"]) = array_keys($GLOBALS["find_install_lang"]);

        // If we have english and some other language, use the other language.  We Americans hate english :P.
        if ($_SESSION["install_lang"] == "install.english.php" && count($GLOBALS["find_install_lang"]) > 1)
            list ($_SESSION["install_lang"]) = array_keys($GLOBALS["find_install_lang"]);
    }

    // And now include the actual language file itself.
    require_once(dirname(__FILE__) . '/language/install_lang/' . $_SESSION["install_lang"]);
}

function language_list()
         {

         global $TABLE_PREFIX;

         $ret = array();
         $res = mysql_query("SELECT * FROM {$TABLE_PREFIX}language ORDER BY language");

         while ($row = mysql_fetch_assoc($res))
             $ret[] = $row;

         unset($row);
         mysql_free_result($res);

         return $ret;
}

function style_list()
         {

         global $TABLE_PREFIX;

         $ret = array();
         $res = mysql_query("SELECT * FROM {$TABLE_PREFIX}style ORDER BY id");

         while ($row = mysql_fetch_assoc($res))
             $ret[] = $row;

         unset($row);
         mysql_free_result($res);

         return $ret;
}

//starting functions for the install
// Starting page at every step
function step ($text = '', $stepname = '', $stepnumber = '') {
    ////////// top block  //////////
    echo ("<div><table class=\"lista\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" align=\"center\">");
    echo ("<tr><td class=\"block\" height=\"20px\" style=\"padding: 5px;\">");
    echo ("<center><b>".$text."</b><div align=\"right\">" . $stepname . "&nbsp;(" . $stepnumber . "/5)</div></center>");
    echo ("</td></tr></table></div>");
    ////////// main block //////////
    echo ("<table class=\"lista\" cellspacing=\"0\" cellpadding=\"10\" width=\"90%\" align=\"center\">");
    echo ("<tr><td style=\"padding: 10px;\" class=\"lista\">");
  }

// check if the installation is not locked
if (file_exists(dirname(__FILE__)."/install.lock"))
{
    step ("Installation Error!","ERROR!","*");
    echo ("<p>For security reasons, this installer is locked!<br>Please (via FTP) remove or change the 'install.lock' file before continue.</p>");
    die; 
}

// main page -> step zero
if ($action == 'welcome')
{
    step ($install_lang["welcome_header"],$install_lang["step"]."&nbsp;".$install_lang["welcome_header"],"*");
    echo ("<p align=\"center\" style=\"color: red\">REMEMBER TO BACKUP FIRST!!!</p>");
    echo ("<p align=\"center\">".$install_lang["welcome"]."</p>");
    
    // Show a language selection...
    if (count($GLOBALS["find_install_lang"]) > 1)
    {
        echo '
                <div style="padding-bottom: 2ex; text-align: ', empty($install_lang["lang_rtl"]) ? 'right' : 'left', ';">
                    <form action="', $_SERVER['PHP_SELF'], '" method="get">
                        ', $install_lang["installer_language"], '&nbsp;<select id="installer_language" name="lang_file" onchange="location.href = \'', $_SERVER['PHP_SELF'], '?lang_file=\' + this.options[this.selectedIndex].value;">';

        foreach ($GLOBALS["find_install_lang"] as $lang => $name)
            echo '
                            <option', isset($_SESSION["install_lang"]) && $_SESSION["install_lang"] == $lang ? ' selected="selected"' : '', ' value="', $lang, '">', $name, '</option>';

        echo '
                        </select>

                        <noscript><input type="submit" value="', $install_lang["installer_language_set"], '" /></noscript>
                    </form>
                </div>';
    }
    // listing the 777 files
    echo ("".$install_lang["list_chmod"]."");
    echo ("<ul>");
    echo ("<li>./include/settings.php</li>");
    echo ("<li>./cache/</li>");
    echo ("<li>./torrents/</li>");
    echo ("<li>./badwords.txt</li>");
    echo ("</ul>");

    echo ("".$install_lang["system_req"]."");
    // changelog
    echo ("<p>".$install_lang["view_log"]."&nbsp;<a href=\"changelog.txt\" target=\"_blank\">".$install_lang["here"]."</a></p>");
    echo ("<div align=\"right\"><input type=\"button\" class=\"button\" name=\"continue\" value=\"".$install_lang["start"]."\" onclick=\"javascript:document.location.href='$cur_script?lang_file=".$_SESSION["install_lang"]."&amp;action=reqcheck'\" /></div>");
}

// requirements check
elseif ($action == 'reqcheck') {
    step ($install_lang["requirements_check"],$install_lang["step"]."&nbsp;".$install_lang["reqcheck"],"1");

// check cache folder
if (file_exists(dirname(__FILE__)."/cache"))
  {
  if (is_writable(dirname(__FILE__)."/cache"))
        $cache=$install_lang["write_succes"];
  else
        $cache=$install_lang["write_fail"]."&nbsp;&nbsp;&nbsp;".$install_lang["can_continue"];
  }
else
  $cache=$install_lang["write_file_not_found"];
// check torrents folder
if (file_exists(dirname(__FILE__)."/torrents"))
  {
  if (is_writable(dirname(__FILE__)."/torrents"))
        $torrents=$install_lang["write_succes"];
  else
        $torrents=$install_lang["write_fail"]."&nbsp;&nbsp;&nbsp;".$install_lang["can_continue"];
  }
else
  $torrents=$install_lang["write_file_not_found"];
// check badwords.txt
if (file_exists(dirname(__FILE__)."/badwords.txt"))
  {
  if (is_writable(dirname(__FILE__)."/badwords.txt"))
        $badwords=$install_lang["write_succes"];
  else
        $badwords=$install_lang["write_fail"]."&nbsp;&nbsp;&nbsp;".$install_lang["can_continue"];
  }
else
  $badwords=$install_lang["write_file_not_found"];
// check include/settings.php
if (file_exists(dirname(__FILE__)."/include/settings.php"))
  {
  if (is_writable(dirname(__FILE__)."/include/settings.php"))
        $settings=$install_lang["write_succes"];
  else
        $settings=$install_lang["write_fail"]."&nbsp;".$install_lang["not_continue_settings"];
  }
else
  $settings=$install_lang["write_file_not_found"]."&nbsp;".$install_lang["not_continue_settings2"];

if ((bool)ini_get('allow_url_fopen')===true)
   $allow_url_fopen=$install_lang["allow_url_fopen_ON"];
else
   $allow_url_fopen=$install_lang["allow_url_fopen_OFF"]."&nbsp;&nbsp;&nbsp;".$install_lang["can_continue"];
  
    echo ("<h2>".$install_lang["requirements_check"]."</h2>");
    echo ("<table width=\"100%\" cellpadding=\"4\" cellspacing=\"4\" border=\"0\" style=\"margin-bottom: 2ex;\">");
    echo ("<tr><td width=\"40%\" valign=\"top\">".$install_lang["cache_folder"].":</td><td>".$cache."</td></tr>");
    echo ("<tr><td width=\"40%\" valign=\"top\">".$install_lang["torrents_folder"].":</td><td>".$torrents."</td></tr>");
    echo ("<tr><td width=\"40%\" valign=\"top\">".$install_lang["badwords_file"].":</td><td>".$badwords."</td></tr>");
    echo ("<tr><td width=\"40%\" valign=\"top\">".$install_lang["settings.php"].":</td><td>".$settings."</td></tr>");
    echo ("<tr><td width=\"40%\" valign=\"top\">".$install_lang["allow_url_fopen"].":</td><td>".$allow_url_fopen."</td></tr>");
    echo ("</table>");
    // don't continue if this file doesn't exists
    if (file_exists(dirname(__FILE__)."/include/settings.php"))
        {
        if (is_writable(dirname(__FILE__)."/include/settings.php"))
            echo ("<div align=\"right\"><input type=\"button\" class=\"button\" name=\"continue\" value=\"".$install_lang["next"]."\" onclick=\"javascript:document.location.href='$cur_script?lang_file=".$_SESSION["install_lang"]."&amp;action=settings'\" /></div>");
        }

}

// setting up the tracker
elseif ($action == 'settings') {
    step ($install_lang["settings"],$install_lang["step"]."&nbsp;".$install_lang["settings"],"2");
    
    // getting host info.
    $db_server = @ini_get('mysql.default_host') or $db_server = 'localhost';
    $db_user = isset($_POST['ftp_username']) ? $_POST['ftp_username'] : @ini_get('mysql.default_user');
    $db_name = isset($_POST['ftp_username']) ? $_POST['ftp_username'] : @ini_get('mysql.default_user');
    $db_passwd = @ini_get('mysql.default_password');
    $db_name = empty($db_name) ? 'xbtit' : $db_name;
    
    echo ("<form action=\"".$_SERVER['PHP_SELF']."?lang_file=".$_SESSION["install_lang"]."&amp;action=save_mysql\" method=\"post\">");
    echo ("<h2>".$install_lang["mysql_settings"]."</h2><h3>".$install_lang["mysql_settings_info"]."</h3>");
    echo ("<table width=\"100%\" cellpadding=\"4\" cellspacing=\"4\" border=\"0\" style=\"margin-bottom: 2ex;\">");
    echo ("<tr><td width=\"20%\" valign=\"top\">".$install_lang["mysql_settings_server"].":</td><td><input type=\"text\" name=\"db_server\" id=\"db_server_input\" value=\"".$db_server."\" size=\"30\" /></td></tr>");
    echo ("<tr><td valign=\"top\">".$install_lang["mysql_settings_username"].":</td><td><input type=\"text\" name=\"db_user\" id=\"db_user_input\" value=\"".$db_user."\" size=\"30\" /></td></tr>");
    echo ("<tr><td valign=\"top\">".$install_lang["mysql_settings_password"].":</td><td><input type=\"password\" name=\"db_passwd\" id=\"db_passwd_input\" value=\"".$db_passwd."\" size=\"30\" /></td></tr>");
    echo ("<tr><td valign=\"top\">".$install_lang["mysql_settings_database"].":</td><td><input type=\"text\" name=\"db_name\" id=\"db_name_input\" value=\"".$db_name."\" size=\"30\" /></td></tr>");
    echo ("<tr><td valign=\"top\">".$install_lang["mysql_settings_prefix"].":</td><td><input type=\"text\" name=\"db_prefix\" id=\"db_prefix_input\" value=\"xbtit_\" size=\"30\" /></td></tr></table>");
    echo ("<div align=\"right\"><input type=\"submit\" value=\"". $install_lang["next"]."\" /></div></form>");
}
// saving the database connection data
elseif ($action == 'save_mysql'){

if (empty($_POST["db_server"]) || empty($_POST["db_user"]) || empty($_POST["db_passwd"]) || empty($_POST["db_name"])){
    step ($install_lang["mysqlcheck"],$install_lang["step"]."&nbsp;".$install_lang["mysqlcheck_step"],"2");
    echo ($install_lang["no_leave_blank"]);
    die;
}
// check settings.php file
if (file_exists(dirname(__FILE__)."/include/settings.php"))
  {
  if (is_writable(dirname(__FILE__)."/include/settings.php"))
     {
     $fd = fopen("include/settings.php", "w");
     $foutput = "<?php\n\n";
     $foutput.= "\$dbhost = \"".$_POST["db_server"]."\";\n";
     $foutput.= "\$dbuser = \"".$_POST["db_user"]."\";\n";
     $foutput.= "\$dbpass = \"".$_POST["db_passwd"]."\";\n";
     $foutput.= "\$database = \"".$_POST["db_name"]."\";\n";
     $foutput.= "\$TABLE_PREFIX = \"".$_POST["db_prefix"]."\";\n";
     $foutput.= "\n?>";
     fwrite($fd,$foutput);
     fclose($fd);
     step ($install_lang["mysqlcheck"],$install_lang["step"]."&nbsp;".$install_lang["mysqlcheck_step"],"2");
     echo ($install_lang["mysql_settings"]."&nbsp;".$install_lang["saved"]);
     echo ("<div align=\"right\"><input type=\"button\" class=\"button\" name=\"continue\" value=\"".$install_lang["next"]."\" onclick=\"javascript:document.location.href='$cur_script?lang_file=".$_SESSION["install_lang"]."&amp;action=sql_import'\" /></div>");
     }
  else
    echo ($install_lang["file_not_writeable"]);
  }
else
echo ($install_lang["file_not_exists"]);
}

// checking the database connection
elseif ($action == 'sql_import') {
    step ($install_lang["mysql_import"],$install_lang["step"]."&nbsp;".$install_lang["mysql_import_step"],"3");

    // Make sure it works.
    require(dirname(__FILE__) . '/include/settings.php');

    // Attempt a connection.
    $db_connection = @mysql_connect($dbhost, $dbuser, $dbpass);
    

    // Still no connection?  Big fat error message :P.
    if (!$db_connection)
    {
        echo '
                <div class="error_message">
                    <div style="color: red;">', $install_lang['mysql_fail'], '</div>

                    <div style="margin: 2.5ex; font-family: monospace;"><b>', mysql_error() , '</b></div>

                    <a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $install_lang['error_message_click'], '</a> ', $install_lang['error_message_try_again'], '
                </div>';
        die;
    }


    // Okay, now let's try to connect...
    if (!mysql_select_db($database, $db_connection))
    {
        echo '
                <div class="error_message">
                    <div style="color: red;">', sprintf($install_lang['error_mysql_database'], $database), '</div>
                    <br />
                    <a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $install_lang['error_message_click'], '</a> ', $install_lang['error_message_try_again'], '
                </div>';

        die;
    }

    // check if some basic table are present in current selected db
    $request_tables=array("{$TABLE_PREFIX}blocks", "{$TABLE_PREFIX}namemap", "{$TABLE_PREFIX}summary", "{$TABLE_PREFIX}forums","{$TABLE_PREFIX}language", "{$TABLE_PREFIX}style", "{$TABLE_PREFIX}users", "{$TABLE_PREFIX}users_level");
    for ($i=0;$i<count($request_tables);$i++)
      {
        $rt=mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$request_tables[$i]."'"));
        if ($rt==0) // table not found!
                {
                    echo '
                            <div class="error_message">
                                <div style="color: red;">Table '.$request_tables[$i].' seems to be missed!</div>
                                <br />
                                <a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $install_lang['error_message_click'], '</a> ', $install_lang['error_message_try_again'], '
                            </div>';

                    die;
                }
    }
    $replaces = array(
        'btit_' => $TABLE_PREFIX,
    );
    foreach ($install_lang as $key => $value)
    {
        if (substr($key, 0, 8) == 'default_')
            $replaces['{$' . $key . '}'] = addslashes($value);
    }

    if (isset($replaces['{$default_reserved_names}']))
       $replaces['{$default_reserved_names}'] = strtr($replaces['{$default_reserved_names}'], array('\\\\n' => '\\n'));

    // If the UTF-8 setting was enabled, add it to the table definitions.
    if (isset($_POST['utf8']))
        $replaces[') TYPE=MyISAM;'] = ') TYPE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

    // Read in the SQL.  Turn this on and that off... internationalize... etc.
    $sql_lines = explode("\n", strtr(implode(' ', file(dirname(__FILE__) . '/'.$dbfile)), $replaces));

    // Execute the SQL.
    $current_statement = '';
    $failures = array();
    $exists = array();
    foreach ($sql_lines as $count => $line)
    {
        // No comments allowed!
        if (substr(trim($line), 0, 1) != '#' && substr(trim($line), 0, 3) != '---')
            $current_statement .= "\n" . rtrim($line);

        // Is this the end of the query string?
        if (empty($current_statement) || (preg_match('~;[\s]*$~s', $line) == 0 && $count != count($sql_lines)))
            continue;

        // Does this table already exist?  If so, don't insert more data into it!
        if (preg_match('~^\s*INSERT INTO ([^\s\n\r]+?)~', $current_statement, $match) != 0 && in_array($match[1], $exists))
        {
            $current_statement = '';
            continue;
        }

        if (mysql_query($current_statement) === false)
        {
            // Error 1050: Table already exists!
            if (mysql_errno($db_connection) === 1050 && preg_match('~^\s*CREATE TABLE ([^\s\n\r]+?)~', $current_statement, $match) == 1)
                $exists[] = $match[1];
            else
                $failures[$count] = mysql_error();
        }

        $current_statement = '';
    }
    if (count($exists)>0 || count($failures)>0)
     {
     $error="";
     foreach($failures as $err_line=>$err_msg)
        $error.="Error on line $err_line: \"$err_msg\"<br />\n";
        echo '
                <div class="error_message">
                    <div style="color: red;">', $error, '</div>
                    <br />
                    <a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $install_lang['error_message_click'], '</a> ', $install_lang['error_message_try_again'], '
                </div>';

        die;

     }
     echo (str_replace("database.sql",$dbfile,$install_lang["database_saved"]));
     echo ("<div align=\"right\"><input type=\"button\" class=\"button\" name=\"continue\" value=\"".$install_lang["next"]."\" onclick=\"javascript:document.location.href='$cur_script?lang_file=".$_SESSION["install_lang"]."&amp;action=finished'\" /></div>");
}

// finished
elseif ($action == 'finished') {
    step ($install_lang["finished"],$install_lang["step"]."&nbsp;".$install_lang["finished_step"],"*");
    echo ("<h2>".$install_lang["succes_upgrade1"]."</h2>");
    if(!@rename("install.unlock", "install.lock"))
        echo ($install_lang["succes_upgrade2b"]);
    else
        echo ($install_lang["succes_upgrade2a"]);
    echo ("<br /><br />");
    echo ($install_lang["succes_upgrade3"]);
    echo ("<br />");
    echo ("<p>BTITeam</p>");
    echo ("<div align=\"center\"><a href=\"index.php\" target=\"_self\">".$install_lang["go_to_tracker"]."</a>");
}
echo ("</td>\n</tr>\n</table>");
echo ("</div>");
echo ("</body>");
echo ("</html>");
?>
