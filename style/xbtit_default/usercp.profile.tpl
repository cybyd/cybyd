<form name="utente" method="post" action="<tag:profile.frm_action />">
  <table width="100%" border="0" class="lista">
    <tr>
      <td align="left" class="header"><tag:language.USER_NAME />:</td>
      <td align="left" class="lista"><tag:profile.username /></td>
  <if:AVATAR>
      <td class="lista" align="center" valign="top" rowspan="3"><tag:profile.avatar /></td>
  </if:AVATAR>
    </tr>
    <tr>
      <td align="left" class="header"><tag:language.AVATAR_URL /></td>
      <td align="left" class="lista"><input type="text" size="40" name="avatar" maxlength="100" value="<tag:profile.avatar_field />"/></td>
    </tr>
    <tr>
      <td align="left" class="header"><tag:language.USER_EMAIL />:</td>
      <td align="left" class="lista"><input type="text" size="30" name="email" maxlength="50" value="<tag:profile.email />"/></td>
    </tr>
  <if:USER_VALIDATION>
    <tr>
      <td align="left" class="header"></td>
      <td align="left" class="lista" colspan="2"><tag:language.REVERIFY_MSG /></td>
    </tr>
  </if:USER_VALIDATION>
    <tr>
      <td align="left" class="header"><tag:language.USER_LANGUE />:</td>
      <td align="left" class="lista" colspan="2"><select name="language"><tag:lang.language_combo /></select></td>
    </tr>
    <tr>
      <td align="left" class="header"><tag:language.USER_STYLE />:</td>
      <td align="left" class="lista" colspan="2"><select name="style"><tag:style.style_combo /></select></td>
    </tr>
    <tr>
      <td align="left" class="header"><tag:language.PEER_COUNTRY />:</td>
      <td align="left" class="lista" colspan="2"><select name="flag"><option value="0">--</option><tag:flag.flag_combo /></select></td>
    </tr>
    <tr>
      <td align="left" class="header"><tag:language.TIMEZONE />:</td>
      <td align="left" class="lista" colspan="2"><select name="timezone"><tag:tz.tz_combo /></select></td>
    </tr>
  <if:INTERNAL_FORUM>
    <tr>
      <td align="left" class="header"><tag:language.TOPICS_PER_PAGE />:</td>
      <td align="left" class="lista" colspan="2"><input type="text" size="3" name="topicsperpage" maxlength="3" value="<tag:profile.topicsperpage />"/></td>
    </tr>
    <tr>
      <td align="left" class="header"><tag:language.POSTS_PER_PAGE />:</td>
      <td align="left" class="lista" colspan="2"><input type="text" size="3" name="postsperpage" maxlength="3" value="<tag:profile.postsperpage />"/></td>
    </tr>
  </if:INTERNAL_FORUM>
    <tr>
      <td align="left" class="header"><tag:language.TORRENTS_PER_PAGE />:</td>
      <td align="left" class="lista" colspan="2"><input type="text" size="3" name="torrentsperpage" maxlength="3" value="<tag:profile.torrentsperpage />"/></td>
    </tr>
    <!-- Password confirmation required to update user record -->
    <tr>
        <td align="left" class="header"><tag:language.USER_PWD />: </td>
        <td align="left" class="lista" colspan="2"><input type="password" size="40" name="passconf" value=""/><tag:language.MUST_ENTER_PASSWORD /></td>
    </tr>
    <!-- Password confirmation required to update user record -->
    <tr>
      <td align="center" class="header" colspan="3">
    <table align="center" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center"><input type="submit" class="btn" name="confirm" value="<tag:language.FRM_CONFIRM />" /></td>
        <td align="center"><input type="button" class="btn" name="confirm" onclick="javascript:window.open('<tag:profile.frm_cancel />','_self');" value="<tag:language.FRM_CANCEL />" /></td>
      </tr>
    </table>
      </td>
    </tr>
  </table>
</form>