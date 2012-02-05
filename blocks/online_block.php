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

global $CURUSER,$btit_settings;
if (!$CURUSER || $CURUSER["view_users"]=="no")
   {
    // do nothing
   }
else
    {

     //block_begin("Online Users");
     print("\n<table class=\"lista\" width=\"100%\">\n");

     $u_online=array();
     $group=array();
     $u_online=get_result("SELECT * FROM {$TABLE_PREFIX}online ol",true,$btit_settings['cache_duration']);

     $total_online=count($u_online);
     $uo=array();
     foreach($u_online as $id=>$users_online)
        {
            if (isset($group[unesc(ucfirst($users_online["user_group"]))]))
               $group[unesc(ucfirst($users_online["user_group"]))]++;
            else
               $group[unesc(ucfirst($users_online["user_group"]))]=1;
            if ($users_online["user_id"]>1)
                $uo[]="<a class=\"online\" href=\"index.php?page=userdetails&amp;id=".$users_online["user_id"]."\" title=\"".unesc(ucfirst($users_online["location"]))."\">".
                       unesc($users_online["prefixcolor"]).unesc($users_online["user_name"]).unesc($users_online["suffixcolor"])."</a>";

     }

     print("<tr><td class=\"header\" align=\"center\" width=\"85%\">".$language["GROUP"]."</td><td class=\"header\" align=\"center\" width=\"15%\">".$language["NUMBER_SHORT"]."</td></tr>\n");

     foreach($group as $gname=>$gnumber)
        {
          print("<tr>\n");
          print("<td class=\"blocklist\" align=\"left\">$gname</td><td class=\"blocklist\" align=\"right\">$gnumber</td>\n");
          print("</tr>\n");
      }

     print("<tr><td class=\"blocklist\" align=\"left\">Total</td><td class=\"blocklist\" align=\"right\">$total_online</td>\n</tr>\n");
     print("<tr><td colspan=\"2\" class=\"blocklist\">".$language["REGISTERED"].": ".implode(", ",$uo)."</td>\n</tr>\n");


     //print($print. $gueststr . ($guest_num>0 && $regusers>0?" ".$language["WORD_AND"]." ":"") . ($regusers>0?"$regusers ".($regusers>1?$language["MEMBERS"]:$language["MEMBER"])."): ":")") . $users ."\n</td></tr>");
     block_end();
     print("</table>\n");
} // end if user can view
?>