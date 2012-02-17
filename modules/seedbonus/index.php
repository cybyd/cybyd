<?php
ob_start();
  if ($CURUSER["uid"] > 1)
    {
  if (!isset($CURUSER)) global $CURUSER;
$r=do_sqlquery("SELECT seedbonus FROM {$TABLE_PREFIX}users WHERE id=$CURUSER[uid]");
$cc=number_format(mysql_result($r,0,"seedbonus"),2);
echo "<br><center><h1>".$language["BONUS_INFO1"]."$cc).<br>".$language["BONUS_INFO2"]."</h1></center>";  
?>
  <table class=lista width="474" align="center">
  <tr>
    <td class=header align=center width="26"><?php echo $language["OPTION"] ?></td>
    <td class=header align=center width="319"><?php echo $language["WHAT_ABOUT"] ?></td>
    <td class=header align=center width="41"><?php echo $language["POINTS"] ?></td>
    <td class=header align=center width="62"><?php echo $language["EXCHANGE"] ?> </td>
  </tr>
<?php
  $uid=$CURUSER['uid'];
  $r=do_sqlquery("SELECT * from {$TABLE_PREFIX}users where id=$uid");
  $c=mysql_result($r,0,"seedbonus");
  $r=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}bonus");
  while($row = mysql_fetch_array($r)){
    if($c<$row['points']) { $enb="disabled"; }
    echo "<form action=seedbonus_exchange.php?id=".$row['id']." method=post><tr>
          <td class=lista align=center><b>".$row['name']."</b></td>
          <td class=lista><b>".$row['gb']."".$language["GB_UPLOAD"]."</b></td>
          <td class=lista align=center>".$row['points']."</td>
          <td class=lista><input type=submit name=submit value=\"".$language["EXCHANGE"]."!\" $enb></td>
          </tr></form>";
  }
   if($c<$GLOBALS["price_vip"]) { $anc="disabled"; }
   ?>
  <form action=seedbonus_exchange.php?id=vip method=post><tr>
    <td class=lista align=center><b>4</b></td>
<?php echo "<td class=lista><b>".$language["UP_TO_VIP"]."</b></td>"; ?>
<?php echo "<td class=lista align=center>".$GLOBALS["price_vip"]."</td>"; ?>
<?php echo "<td class=lista><input type=submit name=submit value=\"".$language["EXCHANGE"]."!\" $anc"; ?> ></td>
  </tr></form>
<?php
$res=do_sqlquery("SELECT custom_title, id, username FROM {$TABLE_PREFIX}users WHERE id=$uid");
$row=mysql_fetch_array($res);
if($c>=$GLOBALS["price_ct"]) {
   echo "<tr>\n<td class=header align=center colspan=5><b>".$language["CHANGE_CUSTOM_TITLE"].$GLOBALS["price_ct"].")</b></td></tr>\n";
   if (!$row["custom_title"])
        $title = "<i>".$language["NO_CUSTOM_TITLE"]."</i>";
   else
        $title = unesc($row["custom_title"]);
   echo "<tr>\n<td class=lista>".$language["CUSTOM_TITLE"]."</td>\n<td class=lista colspan=5>".$title."</td></tr>\n";
   if (!$row["custom_title"])
        $custom = "";
   else
        $custom = $row["custom_title"];
   echo "<tr>\n<td class=lista align=center><b>5</b></td>\n<td class=lista colspan=2>";
   echo "<form method=post action=title2.php?action=changetitle>";
   echo "<input type=text name=title size=50 maxlength=50 value=\"".unesc($custom)."\"></td>";
   echo "<td class=lista align=center><input type=\"submit\" value=\"".$language["EXCHANGE"]."!\">";
   echo "</form>";
   echo "</td></tr>\n";
}
if($c<$GLOBALS["price_ct"]) {
    echo "<tr>\n<td class=lista align=center colspan=5><b>".$language["CHANGE_CUSTOM_TITLE"].$GLOBALS["price_ct"].")</b></td></tr>\n";
    echo "<tr>\n<td class=lista align=center colspan=5><b>".$language["NEED_MORE_POINTS"]."</b></td></tr>\n";
}
if($c>=$GLOBALS["price_name"]) {
   echo "<tr>\n<td class=header align=center colspan=5><b>".$language["CHANGE_USERNAME"].$GLOBALS["price_name"].")</b></td></tr>\n";
   $title = unesc($row["username"]);
   echo "<tr>\n<td class=lista>".$language["MEMBER"]."</td>\n<td class=lista colspan=5>".$title."</td></tr>\n";
   $name = $row["username"];
   echo "<tr>\n<td class=lista align=center><b>6</b></td>\n<td class=lista colspan=2>";
   echo "<form method=post action=username.php?action=changename>";
   echo "<input type=text name=name size=50 maxlength=50 value=\"".unesc($name)."\"></td>";
   echo "<td class=lista align=center><input type=\"submit\" value=\"".$language["EXCHANGE"]."!\">";
   echo "</form>";
   echo "</td></tr>\n";
}
if($c<$GLOBALS["price_name"]) {
    echo "<tr>\n<td class=lista align=center colspan=5><b>".$language["CHANGE_USERNAME"].$GLOBALS["price_name"].")</b></td></tr>\n";
    echo "<tr>\n<td class=lista align=center colspan=5><b>".$language["NEED_MORE_POINTS"]."</b></td></tr>\n";
}
?>
</form>
  <?php
//echo "</form>";
//echo $row['points'];
echo "<td class=lista colspan=5><center><h1>".$language["BONUS_INFO3"].$GLOBALS["bonus"].$language["BONUS_INFO4"]."</h1></center></td>";
echo "</table>";
}
else
    print("<div align=\"center\">\n
           <br />".$language["ERR_PERM_DENIED"]."</div>");
    block_end();
$module_out=ob_get_contents();
ob_end_clean();
?>