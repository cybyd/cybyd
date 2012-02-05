<if:read>
<table class="lista" width="100%" align="center">
  <tr>
    <td class="header" align="center"><tag:language.FORUM_NAME /></td>
    <td class="header" align="center"><tag:language.FORUM_N_TOPICS /></td>
    <td class="header" align="center"><tag:language.FORUM_N_POSTS /></td>
    <td class="header" align="center"><tag:language.FORUM_MIN_READ /></td>
    <td class="header" align="center"><tag:language.FORUM_MIN_WRITE /></td>
    <td class="header" align="center"><tag:language.FORUM_MIN_CREATE /></td>
    <td class="header" align="center"><tag:language.EDIT /></td>
    <td class="header" align="center"><tag:language.DELETE /></td>
  </tr>
  <loop:forums>
  <tr>
    <td class="lista" <tag:forums[].td_padding />><tag:forums[].name /></td>
    <td class="lista" align="center"><tag:forums[].topiccount /></td>
    <td class="lista" align="center"><tag:forums[].postcount /></td>
    <td class="lista"><tag:forums[].readlevel /></td>
    <td class="lista"><tag:forums[].writelevel /></td>
    <td class="lista"><tag:forums[].createlevel /></td>
    <td class="lista" align="center"><tag:forums[].edit /></td>
    <td class="lista" align="center"><tag:forums[].delete /></td>
  </tr>
  </loop:forums>
</table>
<table width="100%" align="center">
  <tr>
    <td class="header" align="center">
    <tag:add_link />
    </td>
  </tr>
</table>
<else:read>
<form name="editforum" action="<tag:frm_action />" method="post">
  <table class="lista" align="center">
    <tr>
      <td class="header" align="center"><tag:language.NAME /></td>
      <td class="lista"><input type="text" name="name" value="<tag:forum.name />" size="40" maxlength="60" /></td>
    </tr>
    <tr>
      <td class="header" align="center"><tag:language.DESCRIPTION /></td>
      <td class="lista"><textarea name="description" rows="3" cols="50"><tag:forum.description /></textarea></td>
    </tr>
    <tr>
      <td class="header" align="center"><tag:language.FORUM_PARENT /></td>
      <td class="lista"><tag:forum.combo_parent /></td>
    </tr>
    <tr>
      <td class="header" align="center"><tag:language.FORUM_MIN_READ /></td>
      <td class="lista"><tag:forum.combo_min_read /></td>
    </tr>
    <tr>
      <td class="header" align="center"><tag:language.FORUM_MIN_WRITE /></td>
      <td class="lista"><tag:forum.combo_min_write /></td>
    </tr>
    <tr>
      <td class="header" align="center"><tag:language.FORUM_MIN_CREATE /></td>
      <td class="lista"><tag:forum.combo_min_create /></td>
    </tr>
    <tr>
      <td class="header" align="center" colspan="2">
      <input type="submit" class="btn" name="confirm" value="<tag:language.FRM_CONFIRM />" />
      <input type="submit" class="btn" name="confirm" value="<tag:language.FRM_CANCEL />" />
      </td>
    </tr>
  </table>
</form>
</if:read>