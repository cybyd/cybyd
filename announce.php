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

ignore_user_abort(1);

$GLOBALS["peer_id"] = "";
$summaryupdate = array();

$BASEPATH=dirname(__FILE__);
require("$BASEPATH/include/config.php");
require("$BASEPATH/include/common.php");

error_reporting(E_ALL ^ E_NOTICE);

// check if we are using standard php tracker or xbtt backend
// if using xbbt we will make a redirect to xbtt announce using
// informations given in config + pid if private
// thank you petr1fied for the code.
if ($XBTT_USE)
{

    function implode_with_keys($glue, $array) {
           $output = array();
           foreach( $array as $key => $item )
                   $output[] = $key . "=" . $item;

           return implode($glue, $output);
    }

    if (isset ($_GET["pid"])) {
       $pid = $_GET["pid"];
       if (strpos($pid , "?")!==false)
          $pid  = substr($pid , 0,strpos($pid , "?"));
       unset($_GET["pid"]);
    } else
       $pid = "";

    if (isset($_SERVER['QUERY_STRING']))
        $query_string = substr($_SERVER['QUERY_STRING'], strpos($_SERVER['QUERY_STRING'], "?")+1);
    else
        $query_string=implode_with_keys("&", $_GET);


    if ($pid!="") // private announce
    {
       header("Location: $XBTT_URL/$pid/announce?" . $query_string);
    }
    else // public
    {
    header("Location: $XBTT_URL/announce?" . $query_string);
    }

  exit;
}

// not using xbtt, let us go ;)

// Schedules an update to the table. It gets so much traffic
// that we do all our changes at once.
// When called, the column $column for the current info_hash is incremented
// by $value, or set to exactly $value if $abs is true.
function summaryAdd($column, $value, $abs = false)
{
    if (isset($GLOBALS["summaryupdate"][$column]))
    {
        if (!$abs)
            $GLOBALS["summaryupdate"][$column][0] += $value;
        else
            show_error("Tracker bug calling summaryAdd");
    }
    else
    {
        $GLOBALS["summaryupdate"][$column][0] = $value;
        $GLOBALS["summaryupdate"][$column][1] = $abs;
    }
}


// connect to db
if ($GLOBALS["persist"])
    $conres=mysql_pconnect($dbhost, $dbuser, $dbpass) or show_error("Tracker error - mysql_connect: " . mysql_error());
else
    $conres=mysql_connect($dbhost, $dbuser, $dbpass) or show_error("Tracker error - mysql_connect: " . mysql_error());

    mysql_select_db($database) or show_error("Tracker error - $database - ".mysql_error());

// connection is done ok

if (isset ($_GET["pid"]))
    $pid = $_GET["pid"];
else
    $pid = "";



if (strpos($pid, "?"))
{
  $tmp = substr($pid , strpos($pid , "?"));
  $pid  = substr($pid , 0,strpos($pid , "?"));
  $tmpname = substr($tmp, 1, strpos($tmp, "=")-1);
  $tmpvalue = substr($tmp, strpos($tmp, "=")+1);
  $_GET[$tmpname] = $tmpvalue;
}

// Many thanks to KktoMx for figuring out this head-ache causer,
// and to bideomex for showing me how to do it PROPERLY... :)
if (get_magic_quotes_gpc())
{
    $info_hash = bin2hex(stripslashes($_GET["info_hash"]));
    $peer_id = bin2hex(stripslashes($_GET["peer_id"]));
}
else
{
    $info_hash = bin2hex($_GET["info_hash"]);
    $peer_id = bin2hex($_GET["peer_id"]);
}

$iscompact=(isset($_GET["compact"])?$_GET["compact"]=='1':false);

// controll if client can handle gzip
if ($GZIP_ENABLED)
    {
    if (stristr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip") && extension_loaded('zlib') && ini_get("zlib.output_compression") == 0)
        {
        if (ini_get('output_handler')!='ob_gzhandler' && !$iscompact)
            {
            // only for non compact
            ob_start("ob_gzhandler");
            }
        else
            {
            ob_start();
            }
    }
    else
        {
        ob_start();
        }
}
// end gzip controll

header("Content-type: text/plain");
header("Pragma: no-cache");

// Error: no web browsers allowed
$agent = mysql_real_escape_string($_SERVER["HTTP_USER_AGENT"]);
// Deny access made with a browser...

if (preg_match("/^Mozilla|^Opera|^Links|^Lynx/i", $agent))
{
    header("HTTP/1.0 500 Bad Request");
    die("This a a bittorrent application and can't be loaded into a browser");
}

// check if al needed information is sent by the client
if (!isset($_GET["port"]) || !isset($_GET["downloaded"]) || !isset($_GET["uploaded"]) || !isset($_GET["left"]))
    show_error("Invalid information received from BitTorrent client");

$port = $_GET["port"];
$ip = getip();

// IP Banned ??
$nip = ip2long($ip);
$res = mysql_query("SELECT * FROM {$TABLE_PREFIX}bannedip WHERE $nip >= first AND $nip <= last") or error_log(__FILE__." - ".__LINE__);
if (mysql_num_rows($res) > 0)
 {
   show_error("You are not authorized to use this tracker (".$SITENAME.") -- Your IP address (".$ip.") is BANNED.");
   die();
}
// end banned IP


// only for internal tracked torrent!
$res_tor =mysql_query("SELECT UNIX_TIMESTAMP(data) as data, uploader FROM {$TABLE_PREFIX}files WHERE external='no' AND info_hash='".$info_hash."'");
if (mysql_num_rows($res_tor)==0)
   show_error("Torrent is not authorized for use on this tracker.");




$downloaded = (float)($_GET["downloaded"]);
$uploaded = (float)($_GET["uploaded"]);
$left = (float)($_GET["left"]);

// if private announce turned on
if ($PRIVATE_ANNOUNCE) {
$pid = AddSlashes(StripSlashes($pid));

// if PID empty string or not send by client
if ($pid=="" || !$pid)
   show_error("Please redownload the torrent. PID system is active and pid was not found in the torrent");
}

// PID turned on
if ($PRIVATE_ANNOUNCE) {
  $respid = mysql_query("SELECT u.*, level, can_download, WT FROM {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul on u.id_level=ul.id WHERE pid='".$pid."' LIMIT 1");
  if (!$respid || mysql_num_rows($respid)!=1)
     show_error("Invalid PID (private announce): $pid. Please redownload torrent from $BASEURL.");
  else
      {
      $rowpid=mysql_fetch_assoc($respid);
      if ($rowpid["can_download"]!="yes" && $PRIVATE_ANNOUNCE)
         show_error("Sorry your level ($rowpid[level]) is not allowed to download from $BASEURL.");
      //waittime
      elseif ($rowpid["WT"]>0) {
        $wait=0;
        if (intval($rowpid['downloaded'])>0)
           $ratio=number_format($rowpid['uploaded']/$rowpid['downloaded'],2);
        else
            $ratio=0.0;

        $added=mysql_fetch_assoc($res_tor);
        $vz = $added["data"];
        $timer = floor((time() - $vz) / 3600);
        if($ratio<1.0 && $rowpid['id']!=$added["uploader"]){
            $wait=$rowpid["WT"];
        }
        $wait -=$timer;
        if ($wait<=0)$wait=0;
        elseif($wait!=0 && $left!=0){show_error($rowpid["username"]." your Waiting Time = ".$wait." h");}
      }
      //end
  }
} else {
// PID turned off
   $respid = mysql_query("SELECT u.*, level, can_download, WT FROM {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul on u.id_level=ul.id WHERE u.cip='$ip' LIMIT 1");
   if (!$respid || mysql_num_rows($respid)!=1)
     // maybe it's guest with new query I must found at least guest user
    $respid = mysql_query("SELECT u.*, level, can_download, WT FROM {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul on u.id_level=ul.id WHERE u.id=1 LIMIT 1");
    if (!$respid || mysql_num_rows($respid)!=1)
      {
        // do nothing but tracker is misconfigured!!!
        // guest user not found...
      }
    else
      {
      $rowpid=mysql_fetch_assoc($respid);
      if ($rowpid["can_download"]!="yes")
         show_error("Sorry your level ($rowpid[level]) is not allowed to download from $BASEURL.");
      //waittime
      elseif ($rowpid["WT"]>0) {
        $wait=0;
        if (intval($rowpid['downloaded'])>0)
           $ratio=number_format($rowpid['uploaded']/$rowpid['downloaded'],2);
        else
            $ratio=0.0;

        $added=mysql_fetch_assoc($res_tor);
        $vz = $added["data"];
        $timer = floor((time() - $vz) / 3600);
        if($ratio<1.0 && $rowpid['id']!=$added["uploader"]){
            $wait=$rowpid["WT"];
        }
        $wait -=$timer;
        if ($wait<=0)
            $wait=0;
        elseif($wait!=0 && $left!=0)
            {show_error($rowpid["username"]." your Waiting Time = ".$wait." h");}
      }
      //end
    }
}


if (isset($_GET["event"]))
    $event = $_GET["event"];
else
    $event = "";

if (!isset($GLOBALS["ip_override"]))
    $GLOBALS["ip_override"] = true;

if (isset($_GET["numwant"]))
    if ($_GET["numwant"] < $GLOBALS["maxpeers"] && $_GET["numwant"] >= 0)
        $GLOBALS["maxpeers"]=$_GET["numwant"];

if (isset($_GET["trackerid"]))
{
    if (is_numeric($_GET["trackerid"]))
        $GLOBALS["trackerid"] = mysql_real_escape_string($_GET["trackerid"]);
}
if (!is_numeric($port) || !is_numeric($downloaded) || !is_numeric($uploaded) || !is_numeric($left))
    show_error("Invalid numerical field(s) from client");


/////////////////////////////////////////////////////
// Checks

// Upgrade holdover: check for unset directives
if (!isset($GLOBALS["countbytes"]))
    $GLOBALS["countbytes"] = true;


/* Returns true if the user is firewalled, NAT'd, or whatever.
 * The original tracker had its --nat_check parameter, so
 * here is my version.
 */
function isFireWalled($hash, $peerid, $ip, $port)
{

    // NAT checking off?
    if (!$GLOBALS["NAT"])
        return false;

    $protocol_name = 'BitTorrent protocol';
    $theError = "";
    // Hoping 10 seconds will be enough
    $fd = fsockopen($ip, $port, $errno, $theError, 10);
    if (!$fd)
        return true;

    fclose($fd);
    return false;
}


// Returns info on one peer
function getPeerInfo($user, $hash)
{

  global $TABLE_PREFIX;

    // If "trackerid" is set, let's try that
    if (isset($GLOBALS["trackerid"]))
    {
        $query = "SELECT peer_id,bytes,ip,port,status,lastupdate,sequence FROM {$TABLE_PREFIX}peers WHERE sequence=\"".$GLOBALS["trackerid"]."\" AND infohash=\"$hash\"";
        $results = mysql_query($query) or show_error("Tracker error: invalid torrent");
        $data = mysql_fetch_assoc($results);
        if (!$data || $data["peer_id"] != $user)
        {
            // Damn, but don't crash just yet.
            $query = "SELECT peer_id,bytes,ip,port,status,lastupdate,sequence from {$TABLE_PREFIX}peers where peer_id=\"$user\" AND infohash=\"$hash\"";
            $results = mysql_query($query) or show_error("Tracker error: invalid torrent");
            $data = mysql_fetch_assoc($results);
        }
    }
    else
    {
        $query = "SELECT peer_id,bytes,ip,port,status,lastupdate,sequence from {$TABLE_PREFIX}peers where peer_id=\"$user\" AND infohash=\"$hash\"";
        $results = mysql_query($query) or show_error("Tracker error: invalid torrent");
        $data = mysql_fetch_assoc($results);
    }

    if (!($data))
        return false;

    $GLOBALS["trackerid"] = $data["sequence"];
    return $data;
}

/////////////////////////////////////////////////////
// Any section of code might need to make a new peer, so this is a function here.
// I don't want to put it into funcsv2, even though it should, just for consistency's sake.

function start($info_hash, $ip, $port, $peer_id, $left, $downloaded=0, $uploaded=0, $upid="")
{
  global $BASEURL, $TABLE_PREFIX;

    if (isset($_GET["ip"]) && $GLOBALS["ip_override"])
    {
        // compact check: valid IP address:
        if ($_GET["ip"]!=long2ip(ip2long($_GET["ip"])))
            show_error("Invalid IP address. Must be standard dotted decimal (hostnames not allowed)");

        $ip = mysql_real_escape_string($_GET["ip"]);
    }
    else
        $ip = getip();

    $ip = mysql_real_escape_string($ip);
    $agent = mysql_real_escape_string($_SERVER["HTTP_USER_AGENT"]);
    $remotedns = gethostbyaddr($ip);

    if (isset($_GET["ip"])) $nuIP = $_GET["ip"];
      else $nuIP = "";
    if ($remotedns == $nuIP)
      $remotedns = "AA";
    else
        {
        $remotedns = strtoupper($remotedns);
        preg_match('/^(.+)\.([A-Z]{2,3})$/', $remotedns, $tldm);
    if (!empty($tldm[2]))
          $remotedns = mysql_real_escape_string($tldm[2]);
    else
      $remotedns = "AA";
      }

    if ($left == 0)
        $status = "seeder";
    else
        $status = "leecher";

    if (@isFireWalled($info_hash, $peer_id, $ip, $port))
        $nat = "Y";
    else
        $nat = "N";



    $compact = mysql_real_escape_string(str_pad(pack('Nn', ip2long($ip), $port),6));
    $peerid = mysql_real_escape_string('2:ip' . strlen($ip) . ':' . $ip . '7:peer id20:' . hex2bin($peer_id) . "4:porti{$port}e");
    $no_peerid = mysql_real_escape_string('2:ip' . strlen($ip) . ':' . $ip . "4:porti{$port}e");


    $results = @mysql_query("INSERT INTO {$TABLE_PREFIX}peers SET infohash=\"$info_hash\", peer_id=\"$peer_id\", port=\"$port\", ip=\"$ip\", lastupdate=UNIX_TIMESTAMP(), bytes=\"$left\", status=\"$status\", natuser=\"$nat\", client=\"$agent\", dns=\"$remotedns\", downloaded=$downloaded, uploaded=$uploaded, pid=\"$upid\"");


    // Special case: duplicated peer_id.
    if (!$results)
    {
        if (mysql_errno()==1062)
        {
            // Duplicate peer_id! Check IP address
            $peer = getPeerInfo($peer_id, $info_hash);
            if ($ip == $peer["ip"])
            {
                // Same IP address. Tolerate this error.
                return "WHERE natuser='N'";
            }
            // Different IP address. Assume they were disconnected, and alter the IP address.
            quickQuery("UPDATE {$TABLE_PREFIX}peers SET ip=\"$ip\", compact=\"$compact\", with_peerid=\"$peerid\", without_peerid=\"$no_peerid\" WHERE peer_id=\"$peer_id\"  AND infohash=\"$info_hash\"");
            return "WHERE natuser='N'";
        }
        error_log("BtiTracker: start: ".mysql_error());
        show_error("Tracker/database error. The details are in the error log.");
    }
    $GLOBALS["trackerid"] = mysql_insert_id();

    @mysql_query("UPDATE {$TABLE_PREFIX}peers SET sequence=\"".$GLOBALS["trackerid"]."\", compact=\"$compact\", with_peerid=\"$peerid\", without_peerid=\"$no_peerid\" WHERE peer_id=\"$peer_id\" AND infohash=\"$info_hash\"");

    if ($left == 0)
    {
        summaryAdd("seeds", 1);
        return "WHERE status=\"leecher\" AND natuser='N'";
    }
    else
    {
        summaryAdd("leechers", 1);
        return "WHERE natuser='N'";
    }
}

/// End of function start

// default for max peers with same pid/ip
if (!isset($GLOBALS["maxseeds"])) $GLOBALS["maxseeds"]=2;
if (!isset($GLOBALS["maxleech"])) $GLOBALS["maxleech"]=2;

// send random peers to client (direct print)
function sendRandomPeers($info_hash)
{

  global $TABLE_PREFIX;



    if ($GLOBALS["NAT"])
        $where="WHERE infohash=\"$info_hash\" AND natuser = 'N'";
    else
        $where="WHERE infohash=\"$info_hash\"";

    $query = "SELECT ".((isset($_GET["no_peer_id"]) && $_GET["no_peer_id"] == 1) ? "" : "peer_id,")."ip, port FROM {$TABLE_PREFIX}peers ".$where." ORDER BY RAND() LIMIT ".$GLOBALS["maxpeers"];

    echo "d";
    echo "8:intervali".$GLOBALS["report_interval"]."e";
    if (isset($GLOBALS["min_interval"]))
        echo "12:min intervali".$GLOBALS["min_interval"]."e";
    echo "5:peers";

    $result = @mysql_query($query);

    if (isset($_GET["compact"]) && $_GET["compact"] == '1')
      {
        $p='';
        while ($row = mysql_fetch_assoc($result))
            $p .= str_pad(pack("Nn", ip2long($row["ip"]), $row["port"]), 6);
        echo strlen($p).':'.$p;
    }
    else // no_peer_id or no feature supported
    {
        echo 'l';
        while ($row = mysql_fetch_assoc($result))
        {
            echo "d2:ip".strlen($row["ip"]).":".$row["ip"];
            if (isset($row["peer_id"]))
                echo "7:peer id20:".hex2bin($row["peer_id"]);
            echo "4:porti".$row["port"]."ee";
        }
        echo "e";
    }
    if (isset($GLOBALS["trackerid"]))
        echo "10:tracker id".strlen($GLOBALS["trackerid"]).":".$GLOBALS["trackerid"];
    echo "e";

    mysql_free_result($result);
}


// Deletes a peer from the system and performs all cleaning up
//
//  $assumepeer contains the result of getPeerInfo, or false
//  if we should grab it ourselves.
function killPeer($userid, $hash, $left, $assumepeer = false)
{

  global $TABLE_PREFIX;

    if (!$assumepeer)
    {
        $peer = getPeerInfo($userid, $hash);
        if (!$peer)
            return;
        if ($left != $peer["bytes"])
            $bytes = bcsub($peer["bytes"], $left);
        else
            $bytes = 0;
    }
    else
    {
        $bytes = 0;
        $peer = $assumepeer;
    }

    quickQuery("DELETE FROM {$TABLE_PREFIX}peers WHERE peer_id=\"$userid\" AND infohash=\"$hash\"");
    if (mysql_affected_rows() == 1)
    {
        if ($peer["status"] == "leecher")
            summaryAdd("leechers", -1);
        else
            summaryAdd("seeds", -1);
        if ($GLOBALS["countbytes"] && ((float)$bytes) > 0)
            summaryAdd("dlbytes",$bytes);
        if ($peer["bytes"] != 0 && $left == 0)
            summaryAdd("finished", 1);

        summaryAdd("lastcycle", "UNIX_TIMESTAMP()", true);
    }
}


// Transfers bytes from "left" to "dlbytes" when a peer reports in.
function collectBytes($peer, $hash, $left, $downloaded=0, $uploaded=0, $pid="")
{

  global $TABLE_PREFIX;

    $peerid=$peer["peer_id"];

    if (!$GLOBALS["countbytes"])
    {
        quickQuery("UPDATE {$TABLE_PREFIX}peers SET lastupdate=UNIX_TIMESTAMP(), downloaded=$downloaded, uploaded=$uploaded, pid=\"$pid\" where infohash=\"$hash\" AND " . (isset($GLOBALS["trackerid"]) ? "sequence=\"${GLOBALS["trackerid"]}\"" : "peer_id=\"$peerid\""));
        return;
    }
    $diff = bcsub($peer["bytes"], $left);
    quickQuery("UPDATE {$TABLE_PREFIX}peers set " . (($diff != 0) ? "bytes=\"$left\"," : ""). " lastupdate=UNIX_TIMESTAMP(), downloaded=$downloaded, uploaded=$uploaded, pid=\"$pid\" where infohash=\"$hash\" AND " . (isset($GLOBALS["trackerid"]) ? "sequence=\"".$GLOBALS["trackerid"]."\"" : "peer_id=\"$peerid\""));

    // Anti-negative clause
    if (((float)$diff) > 0)
        summaryAdd("dlbytes", $diff);
}

function runSpeed($info_hash, $delta)
{

  global $TABLE_PREFIX;

        //stick in our latest data before we calc it out
        quickQuery("INSERT IGNORE INTO {$TABLE_PREFIX}timestamps (info_hash, bytes, delta, sequence) SELECT '$info_hash' AS info_hash, dlbytes, UNIX_TIMESTAMP() - lastSpeedCycle, NULL FROM {$TABLE_PREFIX}files WHERE info_hash=\"$info_hash\"");

        // mysql blows sometimes so we have to read the data into php before updating it
        $results = mysql_query('SELECT (MAX(bytes)-MIN(bytes))/SUM(delta), COUNT(*), MIN(sequence) FROM '.$TABLE_PREFIX.'timestamps WHERE info_hash="'.$info_hash.'"' );
        $data = mysql_fetch_row($results);

        summaryAdd("speed", $data[0], true);
        summaryAdd("lastSpeedCycle", "UNIX_TIMESTAMP()", true);

        // if we have more than 20 drop the rest
        if ($data[1] == 21)
            quickQuery("DELETE FROM {$TABLE_PREFIX}timestamps WHERE info_hash=\"$info_hash\" AND sequence=${data[2]}");
        else if ($data[1] > 21)
        // This query requires MySQL 4.0.x, but should rarely be used.
        quickQuery ('DELETE FROM '.$TABLE_PREFIX.'timestamps WHERE info_hash="'.$info_hash.'" ORDER BY sequence LIMIT '.($data['1'] - 20));
}

//




// select how many users with same pid or ip
$results = mysql_query("SELECT status, count(status) FROM {$TABLE_PREFIX}peers WHERE ".($PRIVATE_ANNOUNCE?"pid=\"$pid\"":"ip=\"$ip\"")." AND infohash=\"$info_hash\" AND peer_id<>\"$peer_id\" GROUP BY status") or show_error("Tracker error: invalid torrent");
$status = array();

while ($resstat = mysql_fetch_row($results))
  $status[$resstat[0]]=$resstat[1];

if (!isset($status["leecher"]))
    $status["leecher"]=0;
if (!isset($status["seeder"]))
    $status["seeder"]=0;

if ($status["seeder"]>=$GLOBALS["maxseeds"] || $status["leecher"]>=$GLOBALS["maxleech"])
   show_error("Sorry max peers reached! Redownload torrent from $BASEURL");
// end select

unset($status);
mysql_free_result($results);

// UPDATE users ratio down/up for every event on every announce
// only with the difference between stored down/up and sended by client
if ($LIVESTATS)
  {
     // changed peer_id with pid/ip 485
     $resstat=mysql_query("SELECT uploaded, downloaded FROM {$TABLE_PREFIX}peers WHERE ".($PRIVATE_ANNOUNCE?"pid=\"$pid\"":"ip=\"$ip\"")." AND infohash=\"$info_hash\"");
     if ($resstat && mysql_num_rows($resstat)>0)
         {
         $livestat=mysql_fetch_assoc($resstat);
         // only if uploaded/downloaded are >= stored data in peer list
         //if ($uploaded>=$livestat["uploaded"])
               $newup=max(0,($uploaded-$livestat["uploaded"]));
         //else
         //      $newup=$uploaded;

         //if ($downloaded>=$livestat["downloaded"])
               $newdown=max(0,($downloaded-$livestat["downloaded"]));
         //else
         //      $newdown=$downloaded;
         // rev 485

         quickquery("UPDATE {$TABLE_PREFIX}users SET downloaded=IFNULL(downloaded,0)+$newdown, uploaded=IFNULL(uploaded,0)+$newup WHERE ".($PRIVATE_ANNOUNCE?"pid='$pid'":"cip='$ip'")."");
         }
       mysql_free_result($resstat);

       // begin history - also this is registered live or not
       if ($LOG_HISTORY)
         {
          $resu=mysql_query("SELECT id FROM {$TABLE_PREFIX}users WHERE ".($PRIVATE_ANNOUNCE?"pid='$pid'":"cip='$ip'") ." ORDER BY lastconnect DESC LIMIT 1");
          // if found at least one user should be 1
          if ($resu && mysql_num_rows($resu)==1)
            {
              $curuid=mysql_fetch_assoc($resu);
              quickQuery("UPDATE {$TABLE_PREFIX}history set uploaded=IFNULL(uploaded,0)+$newup, downloaded=IFNULL(downloaded,0)+$newdown WHERE uid=".$curuid["id"]." AND infohash='$info_hash'");
            }
          mysql_free_result($resu);
       }
       // end history    }
}



switch ($event)
{
    // client sent start
    case "started":

       $start = start($info_hash, $ip, $port, $peer_id, $left, $downloaded, $uploaded, $pid);

       sendRandomPeers($info_hash);

       // begin history
       if ($LOG_HISTORY)
         {
          $resu=mysql_query("SELECT id FROM {$TABLE_PREFIX}users WHERE ".($PRIVATE_ANNOUNCE?"pid='$pid'":"cip='$ip'") ." ORDER BY lastconnect DESC LIMIT 1");
          // if found at least one user should be 1
          if ($resu && mysql_num_rows($resu)==1)
            {
              $curuid=mysql_fetch_assoc($resu);
              quickQuery("UPDATE {$TABLE_PREFIX}history set active='yes',agent='".getagent($agent,$peer_id)."' WHERE uid=".$curuid["id"]." AND infohash='$info_hash'");
              // record is not present, create it (only if not seeder: original seeder don't exist in history table, other already exists)
              if (mysql_affected_rows()==0 && $left>0)
                 quickQuery("INSERT INTO {$TABLE_PREFIX}history (uid,infohash,active,agent) VALUES (".$curuid["id"].",'$info_hash','yes','".getagent($agent,$peer_id)."')");
            }
          mysql_free_result($resu);
       }
       // end history
    break;

    // client sent stop
    case "stopped":

       killPeer($peer_id, $info_hash, $left);

       sendRandomPeers($info_hash);

       // update user uploaded/downloaded
       if (!$LIVESTATS)
            @mysql_query("UPDATE {$TABLE_PREFIX}users SET uploaded=IFNULL(uploaded,0)+$uploaded, downloaded=IFNULL(downloaded,0)+$downloaded WHERE ".($PRIVATE_ANNOUNCE?"pid='$pid'":"cip='$ip'")." AND id>1 LIMIT 1");

       // begin history - if LIVESTAT, only the active/agent part
       if ($LOG_HISTORY)
         {
          $resu=mysql_query("SELECT id FROM {$TABLE_PREFIX}users WHERE ".($PRIVATE_ANNOUNCE?"pid='$pid'":"cip='$ip'") ." ORDER BY lastconnect DESC LIMIT 1");
          // if found at least one user should be 1
          if ($resu && mysql_num_rows($resu)==1)
            {
              $curuid=mysql_fetch_assoc($resu);
              quickQuery("UPDATE {$TABLE_PREFIX}history set active='no',".($LIVESTATS?"":" uploaded=IFNULL(uploaded,0)+$uploaded, downloaded=IFNULL(downloaded,0)+$downloaded,")." agent='".getagent($agent,$peer_id)."' WHERE uid=".$curuid["id"]." AND infohash='$info_hash'");
            }
          mysql_free_result($resu);
       }
       // end history    }
    break;

    // client sent complete
    case "completed":

        $peer_exists = getPeerInfo($peer_id, $info_hash);

        if (!is_array($peer_exists))
            start($info_hash, $ip, $port, $peer_id, $left, $downloaded, $uploaded, $pid);
        else
        {
            quickQuery("UPDATE {$TABLE_PREFIX}peers SET bytes=0, status=\"seeder\", lastupdate=UNIX_TIMESTAMP(), downloaded=$downloaded, uploaded=$uploaded, pid=\"$pid\" WHERE sequence=\"".$GLOBALS["trackerid"]."\" AND infohash=\"$info_hash\"");

            // Race check
            if (mysql_affected_rows() == 1)
              {
                summaryAdd("leechers", -1);
                summaryAdd("seeds", 1);
                summaryAdd("finished", 1);
                summaryAdd("lastcycle", "UNIX_TIMESTAMP()", true);
            }
            else
              collectBytes($peer_exists, $info_hash, $left, $downloaded, $uploaded, $pid);
        }


        sendRandomPeers($info_hash);

        // begin history
        if ($LOG_HISTORY)
          {
           $resu=mysql_query("SELECT id FROM {$TABLE_PREFIX}users WHERE ".($PRIVATE_ANNOUNCE?"pid='$pid'":"cip='$ip'") ." ORDER BY lastconnect DESC LIMIT 1");
           // if found at least one user should be 1
           if ($resu && mysql_num_rows($resu)==1)
             {
               $curuid=mysql_fetch_assoc($resu);
               // if user has already completed this torrent, mysql will give error because of unique index (uid+infohash)
               // upload/download will be updated on stop event...
               // record should already exist (created on stated event)
               quickQuery("UPDATE {$TABLE_PREFIX}history SET date=UNIX_TIMESTAMP(),active='yes',agent='".getagent($agent,$peer_id)."' WHERE uid=".$curuid["id"]." AND infohash='$info_hash'");
               // record is not present, create it
               if (mysql_affected_rows()==0)
                  quickQuery("INSERT INTO {$TABLE_PREFIX}history (uid,infohash,date,active,agent) VALUES (".$curuid["id"].",'$info_hash',UNIX_TIMESTAMP(),'yes','".getagent($agent,$peer_id)."')");

             }
           mysql_free_result($resu);
        }
        // end history
    break;

    // client sent no event
    case "":

        $peer_exists = getPeerInfo($peer_id, $info_hash);
        $where = "WHERE natuser='N'";

        if (!is_array($peer_exists))
            $where = start($info_hash, $ip, $port, $peer_id, $left, $downloaded, $uploaded, $pid);

        if ($peer_exists["bytes"] != 0 && $left == 0)
        {
            quickQuery("UPDATE {$TABLE_PREFIX}peers SET bytes=0, status=\"seeder\", lastupdate=UNIX_TIMESTAMP(), downloaded=$downloaded, uploaded=$uploaded, pid=\"$pid\" WHERE sequence=\"".$GLOBALS["trackerid"]."\" AND infohash=\"$info_hash\"");
            if (mysql_affected_rows() == 1)
            {
                summaryAdd("leechers", -1);
                summaryAdd("seeds", 1);
                summaryAdd("finished", 1);
                summaryAdd("lastcycle", "UNIX_TIMESTAMP()", true);
            }
            else
              collectBytes($peer_exists, $info_hash, $left, $downloaded, $uploaded, $pid);
        }
        else
          collectBytes($peer_exists, $info_hash, $left, $downloaded, $uploaded, $pid);


       sendRandomPeers($info_hash);

    break;

    // not valid event
    default:
        show_error("Invalid event from client.");

}


if ($GLOBALS["countbytes"])
{
    // Once every minute or so, we run the speed update checker.
    $query = @mysql_query("SELECT UNIX_TIMESTAMP() - lastSpeedCycle FROM {$TABLE_PREFIX}files WHERE info_hash=\"$info_hash\"");
    $results = mysql_fetch_row($query);
    if ($results[0] >= 120)
       @runSpeed($info_hash, $results[0]);
}


// Finally, it's time to do stuff to the summary table.
if (!empty($summaryupdate))
{
    $stuff = "";
    foreach ($summaryupdate as $column => $value)
    {
        $stuff .= ', '.$column. ($value[1] ? "=".$value[0] : "=IF(($column<" . abs($value[0])." AND ".$value[0]."<0),0,$column+".$value[0].")");
    }
    mysql_query("UPDATE {$TABLE_PREFIX}files SET ".substr($stuff, 1)." WHERE info_hash=\"$info_hash\"");
}

// generaly not needed, but
// just in case server don't close connection
mysql_close();

?>