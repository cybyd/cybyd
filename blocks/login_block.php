<?php

// CyBerFuN.ro & xList.ro

// xList .::. Login Block
// http://tracker.cyberfun.ro/
// http://www.cyberfun.ro/
// http://xlist.ro/
// Modified By CyBerNe7

require_once(load_language("lang_account.php"));
block_begin("".BLOCK_USER."");
?>
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
<form method="post" onsubmit="return form_control()" action="index.php?page=login">
    <table align="center" class="lista" border="0" cellpadding="10">
      <tr>
<td align="right" class="header"><?php echo $language["USER_NAME"]?>:</td><td class="lista"><input type="text" size="40" name="uid" id="want_username" value="<?php $user ?>" maxlength="40" /></td>
</tr>
    <tr>
<td align="right" class="header"><?php echo $language["USER_PWD"]?>:</td><td class="lista"><input type="password" size="40" name="pwd" id="want_password" maxlength="40" /></td>
</tr>
    <tr>
<td colspan="2" class="header" align="center"><input type="submit" class="btn" value="<?php echo $language["FRM_LOGIN"]?>" /></td>
      </tr>
    <tr>
<td colspan="2" class="header" align="center"><?php echo $language["NEED_COOKIES"]?></td>
</tr>
<tr>
<td colspan="2" class="header" align="center"><a href="index.php?page=signup"><?php echo $language["ACCOUNT_CREATE"]?></a>&nbsp;&nbsp;&nbsp;<a href="index.php?page=recover"><?php echo $language["RECOVER_PWD"]?></a></td>
</tr>
    </table>
    </form>
<?php
block_end();
?>
