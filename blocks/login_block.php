<?php

// CyBerFuN.ro & xList.ro

// xList .::. Login Block
// http://tracker.cyberfun.ro/
// http://www.cyberfun.ro/
// http://xlist.ro/
// Modified By cybernet2u

require_once(load_language("lang_account.php"));
global $btit_settings;
?>
<table width="100%" align="center" border="0" cellspacing="1" cellpadding="4">
<td width="100%" align="center" valign="top">&nbsp;&nbsp;<b><?php echo $btit_settings["name"];?> Login</b>
<script type="text/javascript">
function form_control()
  {
    if (document.getElementById('want_username').value.length==0)
      {
      var want_username=document.createElement('span');
      want_username.innerHTML='<?php echo $language["INSERT_USERNAME"]?>';
      alert(want_username.innerHTML);
      document.getElementById('want_username').focus();
      return false;
      }

    if (document.getElementById('want_password').value == "")
      {
      var want_password=document.createElement('span');
      want_password.innerHTML='<?php echo $language["INSERT_PASSWORD"]?>';
      alert(want_password.innerHTML);
      document.getElementById('want_password').focus();
      return false;
      }

   return true;
  }
</script>
<form method="post" onsubmit="return form_control()" action="index.php?page=login&amp;returnto=index.php">
  <table align="center" class="lista" border="0" cellpadding="4" cellspacing="1">

    
    
    <tr>
      <td align="right"><?php echo $language["USER_NAME"]?>:</td>
      <td><input type="text" size="40" name="uid" id="want_username" maxlength="40" /></td>
    </tr>
    <tr>
      <td align="right"><?php echo $language["USER_PWD"]?>:</td>
      <td><input type="password" size="40" name="pwd" id="want_password" maxlength="40" /></td>
    </tr>

    <tr>
      <td colspan="2" align="center"><input type="submit" class="btn" value="<?php echo $language["LOGIN"]?>" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo $language["NEED_COOKIES"]?></td>
    </tr>
  </table>
</form>
</td>
</table>
