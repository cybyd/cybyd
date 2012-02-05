<?php
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

global $CURUSER, $user, $USERLANG, $FORUMLINK, $db_prefix,$btit_settings, $ipb_prefix;

require_once(load_language("lang_account.php"));

         block_begin("".BLOCK_USER."");

         if (!$CURUSER || $CURUSER["id"]==1)
            {
            // guest-anonymous, login require
            ?>
            <form action="index.php?page=login" name="login" method="post">
            <table class="lista" border="0" align="center" width="100%">
            <tr><td style="text-align:center;" align="center" class="poller"><?php echo $language["USER_NAME"]?>:</td></tr><tr><td class="poller" style="text-align:center;" align="center"><input type="text" size="9" name="uid" value="<?php $user ?>" maxlength="40" /></td></tr>
            <tr><td style="text-align:center;" align="center" class="poller"><?php echo $language["USER_PWD"]?>:</td></tr><tr><td class="poller" style="text-align:center;" align="center"><input type="password" size="9" name="pwd" maxlength="40" /></td></tr>
            <tr><td colspan="2" class="poller" style="text-align:center;" align="center"><input type="submit" value="<?php echo $language["FRM_LOGIN"]?>" /></td></tr>
            <tr><td class="lista" style="text-align:center;" align="center"><a class="user" href="index.php?page=signup"><?php echo $language["ACCOUNT_CREATE"]?></a></td></tr><tr><td class="lista" style="text-align:center;" align="center"><a class="user" href="index.php?page=recover"><?php echo $language["RECOVER_PWD"]?></a></td></tr>
            </table>
            </form>
            <?php
            }
         else
             {
             // user information
             $style=style_list();
             $langue=language_list();
             print("\n<form name=\"jump\" method=\"post\" action=\"index.php\">\n<table class=\"poller\" width=\"100%\" cellspacing=\"0\">\n<tr><td align=\"center\">".$language["USER_NAME"].":  " .unesc($CURUSER["username"])."</td></tr>\n");
             print("<tr><td align=\"center\">".$language["USER_LEVEL"].": ".$CURUSER["level"]."</td></tr>\n");
             if(substr($FORUMLINK,0,3)=="smf")
                 $resmail=get_result("SELECT `unread".(($FORUMLINK=="smf")?"M":"_m")."essages` `ur` FROM `{$db_prefix}members` WHERE ".(($FORUMLINK=="smf")?"`ID_MEMBER`":"`id_member`")."=".$CURUSER["smf_fid"],true,$btit_settings['cache_duration']);
             elseif($FORUMLINK=="ipb")
                 $resmail=get_result("SELECT `msg_count_new` `ur` FROM `{$ipb_prefix}members` WHERE `member_id`=".$CURUSER["ipb_fid"],true,$btit_settings['cache_duration']);
             else
                 $resmail=get_result("SELECT COUNT(*) as ur FROM {$TABLE_PREFIX}messages WHERE readed='no' AND receiver=$CURUSER[uid]",true,$btit_settings['cache_duration']);
             if ($resmail && count($resmail)>0)
                {
                 $mail=$resmail[0];
                 if ($mail['ur']>0)
                    print("<tr><td align=\"center\"><a class=\"user\" href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."&amp;do=pm&amp;action=list\">".$language["MAILBOX"]."</a> (<font color=\"#FF0000\"><b>".$mail['ur']."</b></font>)</td></tr>\n");
                 else
                     print("<tr><td align=\"center\"><a class=\"user\" href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."&amp;do=pm&amp;action=list\">".$language["MAILBOX"]."</a></td></tr>\n");
                }
             else
                 print("<tr><td align=\"center\">".$language["NO_MAIL"]."</td></tr>");
             print("<tr><td align=\"center\">");
             include("include/offset.php");
             print($language["USER_LASTACCESS"].":<br />".date("d/m/Y H:i:s",$CURUSER["lastconnect"]-$offset));
             print("</td></tr>\n<tr><td align=\"center\">");
             print($language["USER_STYLE"].":<br />\n<select name=\"style\" size=\"1\" onchange=\"location=document.jump.style.options[document.jump.style.selectedIndex].value\">");
             foreach($style as $a)
                            {
                            print("<option ");
                            if ($a["id"]==$CURUSER["style"])
                               print("selected=\"selected\"");
                            print(" value=\"account_change.php?style=".$a["id"]."&amp;returnto=".urlencode($_SERVER['REQUEST_URI'])."\">".$a["style"]."</option>");
                            }
             print("</select>");
             print("</td></tr>\n<tr><td align=\"center\">");
             print($language["USER_LANGUE"].":<br />\n<select name=\"langue\" size=\"1\" onchange=\"location=document.jump.langue.options[document.jump.langue.selectedIndex].value\">");
             foreach($langue as $a)
                            {
                            print("<option ");
                            if ($a["id"]==$CURUSER["language"])
                               print("selected=\"selected\"");
                            print(" value=\"account_change.php?langue=".$a["id"]."&amp;returnto=".urlencode($_SERVER['REQUEST_URI'])."\">".$a["language"]."</option>");
                            }
             print("</select>");
             print("</td>\n</tr>\n");
             print("\n<tr><td align=\"center\"><a class=\"user\" href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."\">".$language["USER_CP"]."</a></td></tr>\n");
             if ($CURUSER["admin_access"]=="yes")
                print("\n<tr><td align=\"center\"><a class=\"user\" href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."\">".$language["MNU_ADMINCP"]."</a></td></tr>\n");

             print("</table>\n</form>");
             }
         block_end();
?>