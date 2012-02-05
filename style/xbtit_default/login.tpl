<script type="text/javascript">
function form_control()
  {
    if (document.getElementById('want_username').value.length==0)
      {
      var want_username=document.createElement('span');
      want_username.innerHTML='<tag:language.INSERT_USERNAME />';
      alert(want_username.innerHTML);
      document.getElementById('want_username').focus();
      return false;
      }

    if (document.getElementById('want_password').value == "")
      {
      var want_password=document.createElement('span');
      want_password.innerHTML='<tag:language.INSERT_PASSWORD />';
      alert(want_password.innerHTML);
      document.getElementById('want_password').focus();
      return false;
      }

   return true;
  }
</script>
<form method="post" onsubmit="return form_control()" action="<tag:login.action />">
  <table align="center" class="lista" border="0" cellpadding="4" cellspacing="1">
    <if:FALSE_USER>
    <tr>
      <td align="center" class="lista" colspan="2"><span style="color:#FF0000;"><tag:login_username_incorrect /></span></td>
    </tr>
    </if:FALSE_USER>
    <if:FALSE_PASSWORD>
    <tr>
      <td align="center" class="lista" colspan="2"><span style="color:#FF0000;"><tag:login_password_incorrect /></span></td>
    </tr>
    </if:FALSE_PASSWORD>
    <tr>
      <td align="right" class="header"><tag:language.USER_NAME />:</td>
      <td class="lista"><input type="text" size="40" name="uid" id="want_username" value="<tag:login.username />" maxlength="40" /></td>
    </tr>
    <tr>
      <td align="right" class="header"><tag:language.USER_PWD />:</td>
      <td class="lista"><input type="password" size="40" name="pwd" id="want_password" maxlength="40" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" class="btn" value="<tag:language.FRM_CONFIRM />" /></td>
    </tr>
    <tr>
      <td colspan="2" class="blocklist" align="center"><tag:language.NEED_COOKIES /></td>
    </tr>
  </table>
</form>
<div align="center" class="lista">
  <a href="<tag:login.create />"><tag:language.ACCOUNT_CREATE /></a>&nbsp;&nbsp;&nbsp;<a href="<tag:login.recover />"><tag:language.RECOVER_PWD /></a>
</div>