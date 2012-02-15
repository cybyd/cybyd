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
if (!$CURUSER || $CURUSER["view_torrents"]=="no")
   {
    // do nothing
   }
else
    {
  global $BASEURL, $STYLEPATH, $dblist, $XBTT_USE,$btit_settings;

  block_begin(LAST_TORRENTS);

  ?>
  <table cellpadding="4" cellspacing="1" width="100%">
  <?php
// Gold/Silver Torrent v 1.2 by Losmi / start
// f.gold as gold,
// Gold/Silver Torrent v 1.2 by Losmi / end
  if ($XBTT_USE)
     $sql = "SELECT f.info_hash as hash, f.gold as gold, f.seeds+ifnull(x.seeders,0) as seeds , f.leechers + ifnull(x.leechers,0) as leechers, dlbytes AS dwned, format(f.finished+ifnull(x.completed,0),0) as finished, filename, url, info, UNIX_TIMESTAMP(data) AS added, c.image, c.name AS cname, category AS catid, size, external, uploader FROM {$TABLE_PREFIX}files as f LEFT JOIN xbt_files x ON f.bin_hash=x.info_hash LEFT JOIN {$TABLE_PREFIX}categories as c ON c.id = f.category WHERE f.leechers + ifnull(x.leechers,0) + f.seeds+ifnull(x.seeders,0) > 0 ORDER BY data DESC LIMIT " . $GLOBALS["block_last10limit"];
  else
     $sql = "SELECT info_hash as hash, f.gold as gold, seeds, leechers, dlbytes AS dwned, format(finished,0) as finished, filename, url, info, UNIX_TIMESTAMP(data) AS added, c.image, c.name AS cname, category AS catid, size, external, uploader FROM {$TABLE_PREFIX}files as f LEFT JOIN {$TABLE_PREFIX}categories as c ON c.id = f.category WHERE leechers + seeds > 0 ORDER BY data DESC LIMIT " . $GLOBALS["block_last10limit"];

     $row = get_result($sql, true, $btit_settings['cache_duration']);
  ?>
  <tr>
      <td align="center" width="20" class="header">&nbsp;<?php echo $language["DOWN"]; ?>&nbsp;</td>
    <td align="center" width="55%" class="header">&nbsp;<?php echo $language["TORRENT_FILE"]; ?>&nbsp;</td>
    <td align="center" width="45" class="header">&nbsp;<?php echo $language["CATEGORY"]; ?>&nbsp;</td>
<?php
if (max(0,$CURUSER["WT"])>0)
    print("<td align=\"center\" width=\"20\" class=\"header\">&nbsp".$language["WT"]."&nbsp;</td>");
?>
    <td align="center" width="85" class="header">&nbsp;<?php echo $language["ADDED"]; ?>&nbsp;</td>
    <td align="center" width="60" class="header">&nbsp;<?php echo $language["SIZE"]; ?>&nbsp;</td>
    <td align="center" width="30" class="header">&nbsp;<?php echo $language["SHORT_S"]; ?>&nbsp;</td>
    <td align="center" width="30" class="header">&nbsp;<?php echo $language["SHORT_L"]; ?>&nbsp;</td>
    <td align="center" width="40" class="header">&nbsp;<?php echo $language["SHORT_C"]; ?>&nbsp;</td>
  </tr>
  <?php

  if ($row)
  {
      foreach ($row as $id=>$data)
      {
      echo "<tr>";
          if ( strlen($data["hash"]) > 0 )
          {
      echo "\n\t<td align=\"center\" class=\"lista\" width=\"20\" style=\"text-align: center;\">";
      echo "<a class=\"lasttor\" href=\"download.php?id=".$data["hash"]."&amp;f=" . rawurlencode($data["filename"]) . ".torrent\"><img src='images/torrent.gif' border='0' alt='".$language["DOWNLOAD_TORRENT"]."' title='".$language["DOWNLOAD_TORRENT"]."' /></a>";
      echo "</td>";

       $data["filename"]=unesc($data["filename"]);
       $filename=cut_string($data["filename"],intval($btit_settings["cut_name"]));
// Gold/Silver Torrent v 1.2 by Losmi / start
     $silver_picture='';
     $gold_picture ='';
     $res = get_result("SELECT * FROM {$TABLE_PREFIX}gold  WHERE id='1'", true, $btit_settings['cache_duration']);
            foreach ($res as $key=>$value)
            {
                $silver_picture = $value["silver_picture"];
                $gold_picture = $value["gold_picture"];
            }
        $gold ='';
        if($data['gold'] == 1)
        {
        $gold = '<img src="gold/'.$silver_picture.'" alt="silver" align="right"/>';
        }
        if($data['gold'] == 2)
        {
        $gold = '<img src="gold/'.$gold_picture.'" alt="gold" align="right"/>';
        }
// Gold/Silver Torrent v 1.2 by Losmi / end

       if ($GLOBALS["usepopup"])
          echo "\n\t<td width=\"55%\" class=\"lista\" style=\"padding-left:10px;\"><a class=\"lasttor\" href=\"javascript:popdetails('index.php?page=torrent-details&amp;id=" . $data['hash'] . "');\" title=\"" . $language["VIEW_DETAILS"] . ": " . $data["filename"] . "\">" . $filename . "</a>".$gold.($data["external"]=="no"?"":" (<span style=\"color:red\">EXT</span>)")."</td>";
       else
          echo "\n\t<td width=\"55%\" class=\"lista\" style=\"padding-left:10px;\"><a class=\"lasttor\" href=\"index.php?page=torrent-details&amp;id=" . $data['hash'] . "\" title=\"" . $language["VIEW_DETAILS"]. ": " . $data["filename"] . "\">" . $filename . "</a>".$gold.($data["external"]=="no"?"":" (<span style=\"color:red\">EXT</span>)")."</td>";
       echo "\n\t<td align=\"center\" class=\"lista\" width=\"45\" style=\"text-align: center;\"><a class=\"lasttor\" href=\"index.php?page=torrents&amp;category=$data[catid]\">" . image_or_link( ($data["image"] == "" ? "" : "$STYLEPATH/images/categories/" . $data["image"]), "", $data["cname"]) . "</a></td>";

    //waitingtime
    // only if current user is limited by WT
    if (max(0,$CURUSER["WT"])>0)
        {
          $wait=0;
          //$resuser=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}users WHERE id=".$CURUSER["uid"]);
          //$rowuser=mysql_fetch_array($resuser);
          if (max(0, $CURUSER['downloaded']) > 0) $ratio = number_format($CURUSER['uploaded'] / $CURUSER['downloaded'], 2);
          else $ratio=0.0;
          //$res2 =do_sqlquery("SELECT * FROM {$TABLE_PREFIX}files WHERE info_hash='".$data["hash"]."'");
          //$added=mysql_fetch_array($res2);
          $vz = $data['added']; //sql_timestamp_to_unix_timestamp($data["data"]);
          $timer = floor((time() - $vz) / 3600);
          if($ratio<1.0 && $CURUSER['uid']!=$data["uploader"]){
              $wait=$CURUSER["WT"];
          }
          $wait -=$timer;
          if ($wait<=0)$wait=0;

          echo "\n\t<td align=\"center\" width=\"20\" class=\"lista\" style=\"text-align: center;\">".$wait." h</td>";
        }
    //end waitingtime

             echo "\n\t<td nowrap=\"nowrap\" class=\"lista\" align=\"center\" width=\"85\" style=\"text-align: center;\">" . get_elapsed_time($data["added"]) . " ago</td>";
             echo "\n\t<td nowrap=\"nowrap\" class=\"lista\" align=\"center\" width=\"60\" style=\"text-align: center;\">" . makesize($data["size"]) . "</td>";

           if ( $data["external"] == "no" )
            {
              if ($GLOBALS["usepopup"])
                {
                echo "\n\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\" style=\"text-align: center;\"><a class=\"lasttor\" href=\"javascript:poppeer('index.php?page=peers&amp;id=".$data["hash"]."');\" title=\"".$language["PEERS_DETAILS"]."\">" . $data["seeds"] . "</a></td>\n";
                echo "\n\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\" style=\"text-align: center;\"><a class=\"lasttor\" href=\"javascript:poppeer('index.php?page=peers&amp;id=".$data["hash"]."');\" title=\"".$language["PEERS_DETAILS"]."\">" .$data["leechers"] . "</a></td>\n";
                if ($data["finished"]>0)
                   echo "\n\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\"><a class=\"lasttor\" href=\"javascript:poppeer('index.php?page=torrent_history&amp;id=".$data["hash"]."');\" title=\"History - ".$data["filename"]."\">" . $data["finished"] . "</a></td>";
                else
                    echo "\n\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\">---</td>";

                }
              else
                {
                echo "\n\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\" style=\"text-align: center;\"><a class=\"lasttor\" href=\"index.php?page=peers&amp;id=".$data["hash"]."\" title=\"".$language["PEERS_DETAILS"]."\">" . $data["seeds"] . "</a></td>\n";
                echo "\n\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\" style=\"text-align: center;\"><a class=\"lasttor\" href=\"index.php?page=peers&amp;id=".$data["hash"]."\" title=\"".$language["PEERS_DETAILS"]."\">" .$data["leechers"] . "</a></td>\n";
                if ($data["finished"]>0)
                   echo "\n\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\"><a class=\"lasttor\" href=\"index.php?page=torrent_history&amp;id=".$data["hash"]."\" title=\"History - ".$data["filename"]."\">" . $data["finished"] . "</a></td>";
                else
                    echo "\n\t<td align=\"center\" class=\"lista\">---</td>";

                }
            }
           else
             {
               // linkcolor
               echo "\n\t<td align=\"center\" width=\"30\" class=\"".linkcolor($data["seeds"])."\" style=\"text-align: center;\">" . $data["seeds"] . "</td>";
               echo "\n\t<td align=\"center\" width=\"30\" class=\"".linkcolor($data["leechers"])."\" style=\"text-align: center;\">" .$data["leechers"] . "</td>";
               if ($data["finished"]>0)
                  echo "\n\t<td align=\"center\" width=\"40\" class=\"lista\" style=\"text-align: center;\">" . $data["finished"] . "</td>";
               else
                   echo "\n\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\">---</td>";

        }
           echo "</tr>\n";
           }
      }
  }
  else
  {
    echo "\n<tr><td class=\"lista\" colspan=\"9\" align=\"center\" style=\"text-align: center;\">" . $language["NO_TORRENTS"] . "</td></tr>";
  }

  print("\n</table>");

  block_end();

} // end if user can view
?>
