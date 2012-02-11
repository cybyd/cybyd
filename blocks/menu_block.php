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

global $CURUSER, $FORUMLINK, $language;

   print("<table class=\"lista\" width=\"100%\" cellspacing=\"0\">\n<tr><td class=\"blocklist\" align=\"center\"><a class=\"menu\" href=\"index.php\">".$language["MNU_INDEX"]."</a></td></tr>\n");

   if ($CURUSER["view_torrents"]=="yes")
      {
      print("<tr><td class=\"blocklist\" align=\"center\"><a class=\"menu\" href=\"index.php?page=torrents\">".$language["MNU_TORRENT"]."</a></td></tr>\n");
      print("<tr><td class=\"blocklist\" align=\"center\"><a class=\"menu\" href=\"index.php?page=extra-stats\">".$language["MNU_STATS"]."</a></td></tr>\n");
      }
   if ($CURUSER["can_upload"]=="yes")
      print("<tr><td class=\"blocklist\" align=\"center\"><a class=\"menu\" href=\"index.php?page=upload\">".$language["MNU_UPLOAD"]."</a></td></tr>\n");
   if ($CURUSER["view_users"]=="yes")
   {
      print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php?page=users\">".$language["MNU_MEMBERS"]."</a></td></tr>\n");
// Staff Page - Petr1fied / start / http://www.btiteam.org/smf/index.php?topic=19541.msg109523#msg109523
      print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php?page=staff\">".$language["STAFF"]."</a></td></tr>\n");
// Staff Page - Petr1fied / end
   }
   if ($CURUSER["view_news"]=="yes")
      print("<tr><td class=\"blocklist\" align=\"center\"><a class=\"menu\" href=\"index.php?page=viewnews\">".$language["MNU_NEWS"]."</a></td></tr>\n");
   if ($CURUSER["view_forum"]=="yes")
      {
        if ($FORUMLINK=="" || $FORUMLINK=="internal" || substr($FORUMLINK,0,3)=="smf" || $FORUMLINK=="ipb")
           print("<tr><td class=\"blocklist\" align=\"center\"><a class=\"menu\" href=\"index.php?page=forum\">".$language["MNU_FORUM"]."</a></td></tr>\n");
        else
            print("<tr><td class=\"blocklist\" align=\"center\"><a class=\"menu\" href=\"".$FORUMLINK."\">".$language["MNU_FORUM"]."</a></td></tr>\n");
      }
   if ($CURUSER["uid"]==1 || !$CURUSER)
      print("<tr><td class=\"blocklist\" align=\"center\"><a class=\"menu\" href=\"index.php?page=login\">".$language["LOGIN"]."</a></td></tr>\n</table>\n");
   else
       print("<tr><td class=\"blocklist\" align=\"center\"><a class=\"menu\" href=\"logout.php\">".$language["LOGOUT"]."</a></td></tr>\n</table>\n");

?>
