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



if (!defined("IN_BTIT"))
      die("non direct access!");


$scriptname = htmlspecialchars($_SERVER["PHP_SELF"]."?page=torrents");
$addparam = "";


if ($XBTT_USE)
   {
    $tseeds="f.seeds+ifnull(x.seeders,0)";
    $tleechs="f.leechers+ifnull(x.leechers,0)";
    $tcompletes="f.finished+ifnull(x.completed,0)";
    $ttables="{$TABLE_PREFIX}files f LEFT JOIN xbt_files x ON x.info_hash=f.bin_hash";
   }
else
    {
    $tseeds="f.seeds";
    $tleechs="f.leechers";
    $tcompletes="f.finished";
    $ttables="{$TABLE_PREFIX}files f";
    }


if(!$CURUSER || $CURUSER["view_torrents"]!="yes")
{
    err_msg($language["ERROR"],$language["NOT_AUTHORIZED"]." ".$language["MNU_TORRENT"]."!<br />\n".$language["SORRY"]."...");
    stdfoot();
    exit();
}


if(isset($_GET["search"]))
{
    $trova = htmlspecialchars(str_replace ("+"," ",$_GET["search"]));
} else {
    $trova = "";
}
 
$category = (!isset($_GET["category"])?0:explode(";",$_GET["category"]));
// sanitize categories id
if (is_array($category))
    $category = array_map("intval",$category);
else
    $category = 0;

$combo_categories=categories( $category[0] );

(isset($_GET["active"]) && is_numeric($_GET["active"]) && $_GET["active"]>=0 && $_GET["active"]<=2) ? $active=intval($_GET["active"]) : $active=1;

if($active==0)
{
    $where = " WHERE 1=1";
    $addparam.="active=0";
} // active only
elseif($active==1){
    $where = " WHERE $tleechs+$tseeds > 0";
    $addparam.="active=1";
} // dead only
elseif($active==2){
    $where = " WHERE $tleechs+$tseeds = 0";
    $addparam.="active=2";
}

/* Rewrite, part 1: encode "WHERE" statement only. */

// selezione categoria
if ($category[0]>0) {
   $where .= " AND category IN (".implode(",",$category).")"; // . $_GET["category"];
   $addparam.="&amp;category=".implode(";",$category); // . $_GET["category"];
}


// Search
if (isset($_GET["search"])) {
   $testocercato = trim($_GET["search"]);
   $testocercato = explode(" ",$testocercato);
   if ($_GET["search"]!="")
      $search = "search=" . implode("+",$testocercato);
    for ($k=0; $k < count($testocercato); $k++) {
        $query_select .= " filename LIKE '%" . mysql_real_escape_string($testocercato[$k]) . "%'";
        if ($k<count($testocercato)-1)
           $query_select .= " AND ";
    }
    $where .= " AND " . $query_select;
}

// end search

// torrents count...

$res = get_result("SELECT COUNT(*) as torrents FROM $ttables $where",true,$btit_settings['cache_duration']);

$count = $res[0]["torrents"];
if (!isset($search)) $search = "";

if ($count>0) {
   if ($addparam != "") {
      if ($search != "")
         $addparam .= "&amp;" . $search . "&amp;";
      //else
          //$addparam .= "&";
   }
   else {
      if ($search != "")
         $addparam .=  $search . "&amp;";
      else
          $addparam .= ""; //$scriptname . "?";
      }

    $torrentperpage=intval($CURUSER["torrentsperpage"]);
    if ($torrentperpage==0)
        $torrentperpage=($ntorrents==0?15:$ntorrents);

    // getting order
    $order_param=3;
    if (isset($_GET["order"]))
       {
         $order_param=(int)$_GET["order"];
         switch ($order_param)
           {
           case 1:
                $order="cname";
                break;
           case 2:
                $order="filename";
                break;
           case 3:
                $order="data";
                break;
           case 4:
                $order="size";
                break;
           case 5:
                $order="seeds";
                break;
           case 6:
                $order="leechers";
                break;
           case 7:
                $order="finished";
                break;
           case 8:
                $order="dwned";
                break;
           case 9:
                $order="speed";
                break;
           default:
               $order="data";
               
         }

    }
    else
        $order="data";

    $qry_order=str_replace(array("leechers","seeds","finished"),array($tleechs,$tseeds, $tcompletes),$order);

    $by_param=2;
    if (isset($_GET["by"]))
      {
        $by_param=(int)$_GET["by"];
        $by=($by_param==1?"ASC":"DESC");
    }
    else
        $by="DESC";


    list($pagertop, $pagerbottom, $limit) = pager($torrentperpage, $count,  $scriptname."&amp;" . $addparam.(strlen($addparam)>0?"&amp;":"")."order=$order_param&amp;by=$by_param&amp;");

    // Do the query with the uploader nickname
    if ($SHOW_UPLOADER)
        $query = "SELECT f.info_hash as hash, $tseeds as seeds, $tleechs as leechers, $tcompletes as finished,  f.dlbytes as dwned , IFNULL(f.filename,'') AS filename, f.url, f.info, f.anonymous, f.speed, UNIX_TIMESTAMP( f.data ) as added, c.image, c.name as cname, f.category as catid, f.size, f.external, f.uploader as upname, u.username as uploader, prefixcolor, suffixcolor FROM $ttables LEFT JOIN {$TABLE_PREFIX}categories c ON c.id = f.category LEFT JOIN {$TABLE_PREFIX}users u ON u.id = f.uploader LEFT JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id $where ORDER BY $qry_order $by $limit";

    // Do the query without the uploader nickname
    else
        $query = "SELECT f.info_hash as hash, $tseeds as seeds, $tleechs as leechers, $tcompletes as finished,  f.dlbytes as dwned , IFNULL(f.filename,'') AS filename, f.url, f.info, f.speed, UNIX_TIMESTAMP( f.data ) as added, c.image, c.name as cname, f.category as catid, f.size, f.external, f.uploader FROM $ttables LEFT JOIN {$TABLE_PREFIX}categories c ON c.id = f.category $where ORDER BY $qry_order $by $limit";
    // End the queries
       $results = get_result($query,true,$btit_settings['cache_duration']);
}



if ($by=="ASC")
    $mark="&nbsp;&uarr;";
else
    $mark="&nbsp;&darr;";

// load language file
require(load_language("lang_torrents.php"));


$torrenttpl=new bTemplate();
$torrenttpl->set("language",$language);
$torrenttpl->set("torrent_script","index.php");
$torrenttpl->set("torrent_search",$trova);
$torrenttpl->set("torrent_categories_combo",$combo_categories);
$torrenttpl->set("torrent_selected_all",($active==0?"selected=\"selected\"":""));
$torrenttpl->set("torrent_selected_active",($active==1?"selected=\"selected\"":""));
$torrenttpl->set("torrent_selected_dead",($active==2?"selected=\"selected\"":""));

$torrenttpl->set("torrent_pagertop",$pagertop);
$torrenttpl->set("torrent_header_category","<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=1&amp;by=".($order=="cname" && $by=="ASC"?"2":"1")."\">".$language["CATEGORY"]."</a>".($order=="cname"?$mark:""));
$torrenttpl->set("torrent_header_filename","<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=2&amp;by=".($order=="filename" && $by=="ASC"?"2":"1")."\">".$language["FILE"]."</a>".($order=="filename"?$mark:""));
$torrenttpl->set("torrent_header_comments",$language["COMMENT"]);
$torrenttpl->set("torrent_header_rating",$language["RATING"]);
$torrenttpl->set("WT",intval($CURUSER["WT"])>0,TRUE);
$torrenttpl->set("torrent_header_waiting",$language["WT"]);
$torrenttpl->set("torrent_header_download",$language["DOWN"]);
$torrenttpl->set("torrent_header_added","<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=3&amp;by=".($order=="data" && $by=="ASC"?"2":"1")."\">".$language["ADDED"]."</a>".($order=="data"?$mark:""));
$torrenttpl->set("torrent_header_size","<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=4&amp;by=".($order=="size" && $by=="DESC"?"1":"2")."\">".$language["SIZE"]."</a>".($order=="size"?$mark:""));
$torrenttpl->set("uploader",$SHOW_UPLOADER,TRUE);
$torrenttpl->set("torrent_header_uploader",$language["UPLOADER"]);
$torrenttpl->set("torrent_header_seeds","<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=5&amp;by=".($order=="seeds" && $by=="DESC"?"1":"2")."\">".$language["SHORT_S"]."</a>".($order=="seeds"?$mark:""));
$torrenttpl->set("torrent_header_leechers","<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=6&amp;by=".($order=="leechers" && $by=="DESC"?"1":"2")."\">".$language["SHORT_L"]."</a>".($order=="leechers"?$mark:""));
$torrenttpl->set("torrent_header_complete","<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=7&amp;by=".($order=="finished" && $by=="ASC"?"2":"1")."\">".$language["SHORT_C"]."</a>".($order=="finished"?$mark:""));
$torrenttpl->set("torrent_header_downloaded","<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=8&amp;by=".($order=="dwned" && $by=="ASC"?"2":"1")."\">".$language["DOWNLOADED"]."</a>".($order=="dwned"?$mark:""));
$torrenttpl->set("torrent_header_speed","<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=9&amp;by=".($order=="speed" && $by=="ASC"?"2":"1")."\">".$language["SPEED"]."</a>".($order=="speed"?$mark:""));
$torrenttpl->set("torrent_header_average",$language["AVERAGE"]);
$torrenttpl->set("XBTT",$XBTT_USE,TRUE);
$torrenttpl->set("torrent_pagerbottom",$pagerbottom);


$torrents=array();
$i=0;

if ($count>0) {
  foreach ($results as $tid=>$data) {

   $torrenttpl->set("WT1",intval($CURUSER["WT"])>0,TRUE);
   $torrenttpl->set("uploader1",$SHOW_UPLOADER,TRUE);
   $torrenttpl->set("XBTT1",$XBTT_USE,TRUE);

   $data["filename"]=unesc($data["filename"]);
   $filename=cut_string($data["filename"],intval($btit_settings["cut_name"]));

   $torrents[$i]["category"]="<a href=\"index.php?page=torrents&amp;category=$data[catid]\">".image_or_link(($data["image"]==""?"":"$STYLEPATH/images/categories/" . $data["image"]),"",$data["cname"])."</a>";
   if ($GLOBALS["usepopup"])
       $torrents[$i]["filename"]="<a href=\"javascript:popdetails('index.php?page=torrent-details&amp;id=".$data["hash"]."');\" title=\"".$language["VIEW_DETAILS"].": ".($data["filename"]!=""?$filename:$data["hash"])."\">".$data["filename"]."</a>".($data["external"]=="no"?"":" (<span style=\"color:red\">EXT</span>)");
   else
       $torrents[$i]["filename"]="<a href=\"index.php?page=torrent-details&amp;id=".$data["hash"]."\" title=\"".$language["VIEW_DETAILS"].": ".$data["filename"]."\">".($data["filename"]!=""?$filename:$data["hash"])."</a>".($data["external"]=="no"?"":" (<span style=\"color:red\">EXT</span>)");

   // search for comments
   /*
   $commentres = get_result("SELECT COUNT(*) as comments FROM {$TABLE_PREFIX}comments WHERE info_hash='" . $data["hash"] . "'",true);
   $commentdata = $commentres[0];

    if ($commentdata["comments"]>0)
      {
       if ($GLOBALS["usepopup"])
           $torrents[$i]["comments"]="<a href=\"javascript:popdetails('index.php?page=torrent-details&amp;id=".$data["hash"]."#comments');\" title=\"".$language["VIEW_DETAILS"].": ".$data["filename"]."\">" . $commentdata["comments"] . "</a>";
       else
           $torrents[$i]["comments"]="<a href=\"index.php?page=torrent-details&amp;id=".$data["hash"]."#comments\" title=\"".$language["VIEW_DETAILS"].": ".$data["filename"]."\">".$commentdata["comments"]."</a>";
      }
   else
   */

   // commented out to lower unsed queries (standard template)
       $torrents[$i]["comments"]="---";

   // Rating
   /*
   $vres = get_result("SELECT sum(rating) as totrate, count(*) as votes FROM {$TABLE_PREFIX}ratings WHERE infohash = '" . $data["hash"] . "'",true);
   $vrow = $vres[0];
   if ($vrow && $vrow["votes"]>=1)
      {
      $totrate=round($vrow["totrate"]/$vrow["votes"],1);
      if ($totrate==5)
         $totrate="<img src=\"$STYLEURL/images/5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
      elseif ($totrate>4.4 && $totrate<5)
         $totrate="<img src=\"$STYLEURL/images/4.5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
      elseif ($totrate>3.9 && $totrate<4.5)
         $totrate="<img src=\"$STYLEURL/images/4.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
      elseif ($totrate>3.4 && $totrate<4)
         $totrate="<img src=\"$STYLEURL/images/3.5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
      elseif ($totrate>2.9 && $totrate<3.5)
         $totrate="<img src=\"$STYLEURL/images/3.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\"  alt=\"\" />";
      elseif ($totrate>2.4 && $totrate<3)
         $totrate="<img src=\"$STYLEURL/images/2.5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\"  alt=\"\" />";
      elseif ($totrate>1.9 && $totrate<2.5)
         $totrate="<img src=\"$STYLEURL/images/2.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\"  alt=\"\" />";
      elseif ($totrate>1.4 && $totrate<2)
         $totrate="<img src=\"$STYLEURL/images/1.5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\"  alt=\"\" />";
      else
         $totrate="<img src=$STYLEURL/images/1.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\"  alt=\"\" />";
      }
   else
       $totrate=$language["NA"];

   $torrents[$i]["rating"]="$totrate";
   */
   // end rating

   // commented out to lower unsed queries (standard template)
   $torrents[$i]["rating"]=$language["NA"];


   //waitingtime
   // display only if the curuser have some WT restriction
   if (intval($CURUSER["WT"])>0)
      {
      $wait=0;
      //$resuser=get_result("SELECT * FROM {$TABLE_PREFIX}users WHERE id=".$CURUSER["uid"],true,$CACHE_DURATION);
      //$rowuser=$resuser[0];
      $wait=0;
      if (intval($CURUSER['downloaded'])>0) $ratio=number_format($CURUSER['uploaded']/$CURUSER['downloaded'],2);
      else $ratio=0.0;
      $vz = $data["added"];
      $timer = floor((time() - $vz) / 3600);
      if($ratio<1.0 && $CURUSER['uid']!=$data["uploader"]){
          $wait=$CURUSER["WT"];
      }
      $wait -=$timer;

      if ($wait<=0)$wait=0;
     if (strlen($data["hash"]) > 0)
          $torrents[$i]["waiting"]=($wait>0?$wait." h":"---");
   //end waitingtime
   }
   else $torrents[$i]["waiting"]="";


   $torrents[$i]["download"]="<a href=\"download.php?id=".$data["hash"]."&amp;f=" . urlencode($data["filename"]) . ".torrent\">".image_or_link("images/download.gif","","torrent")."</a>\n";

   include("include/offset.php");
   $torrents[$i]["added"]=date("d/m/Y",$data["added"]-$offset); // data
   $torrents[$i]["size"]=makesize($data["size"]);

   //Uploaders nick details
   if ($SHOW_UPLOADER && $data["anonymous"] == "true")
    $torrents[$i]["uploader"]=$language["ANONYMOUS"];
   elseif ($SHOW_UPLOADER && $data["anonymous"] == "false")
    $torrents[$i]["uploader"]="<a href=\"index.php?page=userdetails&amp;id=" . $data["upname"] . "\">".StripSlashes($data['prefixcolor'].$data["uploader"].$data['suffixcolor'])."</a>";
  //Uploaders nick details

   if ($data["external"]=="no")
      {
       if ($GLOBALS["usepopup"])
         {
         $torrents[$i]["classe_seeds"]=linkcolor($data["seeds"]);
         $torrents[$i]["seeds"]="<a href=\"javascript:poppeer('index.php?page=peers&amp;id=".$data["hash"]."');\" title=\"".$language["PEERS_DETAILS"]."\">" . $data["seeds"] . "</a>";
         $torrents[$i]["classe_leechers"]=linkcolor($data["leechers"]);
         $torrents[$i]["leechers"]="<a href=\"javascript:poppeer('index.php?page=peers&amp;id=".$data["hash"]."');\" title=\"".$language["PEERS_DETAILS"]."\">" .$data["leechers"] . "</a>";
         if ($data["finished"]>0)
            $torrents[$i]["complete"]="<a href=\"javascript:poppeer('index.php?page=torrent_history&amp;id=".$data["hash"]."');\" title=\"History - ".$data["filename"]."\">" . $data["finished"] . "</a>";
         else
             $torrents[$i]["complete"]="---";
         }
       else
         {
         $torrents[$i]["classe_seeds"]=linkcolor($data["seeds"]);
         $torrents[$i]["seeds"]="<a href=\"index.php?page=peers&amp;id=".$data["hash"]."\" title=\"".$language["PEERS_DETAILS"]."\">" . $data["seeds"] . "</a>";
         $torrents[$i]["classe_leechers"]=linkcolor($data["leechers"]);
         $torrents[$i]["leechers"]="<a href=\"index.php?page=peers&amp;id=".$data["hash"]."\" title=\"".$language["PEERS_DETAILS"]."\">" .$data["leechers"] . "</a>";
         if ($data["finished"]>0)
            $torrents[$i]["complete"]="<a href=\"index.php?page=torrent_history&amp;id=".$data["hash"]."\" title=\"History - ".$data["filename"]."\">" . $data["finished"] . "</a>";
         else
             $torrents[$i]["complete"]="---";
         }
      }
   else
       {
       // linkcolor
       $torrents[$i]["classe_seeds"]=linkcolor($data["seeds"]);
       $torrents[$i]["seeds"]=$data["seeds"];
       $torrents[$i]["classe_leechers"]=linkcolor($data["leechers"]);
       $torrents[$i]["leechers"]=$data["leechers"];
       if ($data["finished"]>0)
          $torrents[$i]["complete"]=$data["finished"];
       else
           $torrents[$i]["complete"]="---";
   }
   if ($data["dwned"]>0)
      $torrents[$i]["downloaded"]=makesize($data["dwned"]);
   else
       $torrents[$i]["downloaded"]=$language["NA"];

   if (!$XBTT_USE)
     {
       if ($data["speed"] < 0 || $data["external"]=="yes") {
          $speed = $language["NA"];
       }
           else if ($data["speed"] > 2097152) {
                $speed = round($data["speed"]/1048576,2) . " MB/sec";
       }
           else {
                   $speed = round($data["speed"] / 1024, 2) . " KB/sec";
       }
   }
   $torrents[$i]["speed"]=$speed;

  // progress
  if ($data["external"]=="yes")
     $prgsf=$language["NA"];
  else {
       $id = $data['hash'];
       if ($XBTT_USE)
          $subres = get_result("SELECT sum(IFNULL(xfu.left,0)) as to_go, count(xfu.uid) as numpeers FROM xbt_files_users xfu INNER JOIN xbt_files xf ON xf.fid=xfu.fid WHERE xf.info_hash=UNHEX('$id') AND xfu.active=1",true,$btit_settings['cache_duration']);
       else
           $subres = get_result("SELECT sum(IFNULL(bytes,0)) as to_go, count(*) as numpeers FROM {$TABLE_PREFIX}peers where infohash='$id'",true,$btit_settings['cache_duration']);
       $subres2 = get_result("SELECT size FROM {$TABLE_PREFIX}files WHERE info_hash ='$id'",true,$btit_settings['cache_duration']);
       $torrent = $subres2[0];
       $subrow = $subres[0];
       $tmp=0+$subrow["numpeers"];
       if ($tmp>0) {
          $tsize=(0+$torrent["size"])*$tmp;
          $tbyte=0+$subrow["to_go"];
          $prgs=(($tsize-$tbyte)/$tsize) * 100; //100 * (1-($tbyte/$tsize));
          $prgsf=floor($prgs);
          }
       else
           $prgsf=0;
       $prgsf.="%";
  }
  $torrents[$i]["average"]=$prgsf;

  $i++;
  }
} // if count

// assign array to loop tag

$torrenttpl->set("torrents",$torrents);

?>