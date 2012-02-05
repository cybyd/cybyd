<script type="text/javascript">
function form_control()
  {
    if (document.getElementById('title').value.length==0)
      {
      var title=document.createElement('span');
      title.innerHTML='<tag:language.ERR_NO_TITLE />';
      alert(title.innerHTML);
      document.getElementById('title').focus();
      return false;
      }

   return true;
  }
</script>
<if:ADD_EDIT>
<div align="center">
  <form action="<tag:news.action />" name="news" method="post" onsubmit="return form_control()">
    <table border="0" class="lista" width="100%">
      <tr>
    <td align="center" colspan="2" class="header"><tag:language.NEWS_INSERT />:<input type="hidden" name="action" value="<tag:news.hidden_action />"/><input type="hidden" name="id" value="<tag:news.hidden_id />"/></td>
      </tr>
      <tr>
    <td align="left" class="lista" style="font-size:10pt"><tag:language.NEWS_TITLE /></td>
    <td align="left" class="lista"><input type="text" name="title" id="title" size="40" maxlength="40" value="<tag:news.news_title />"/></td>
      </tr>
      <tr>
    <td align="left" class="lista" valign="top" style="font-size:10pt"><tag:language.NEWS_DESCRIPTION /></td>
    <td align="left" class="lista"><tag:news.bbcode /></td>
      </tr>
      <tr>
    <td align="center" class="header" colspan="2"><input type="submit" class="btn" name="conferma" value="<tag:language.FRM_CONFIRM />" />&nbsp;&nbsp;&nbsp;<input type="submit" class="btn" name="conferma" value="<tag:language.FRM_CANCEL />" /></td>
      </tr>
    </table>
  </form>
</div>
</if:ADD_EDIT>
<if:VIEW>
<table cellpadding="4" cellspacing="1" border="0" width="100%" bgcolor="#000000" style="font-family:Verdana;font-size:11px">
  <loop:news_model>
  <if:EDIT_DEL>
  <tr>
    <td class=header align=center>
  <if:EDIT_NEWS>
    <a href="<tag:news_model[].add />"><img border=0 alt="<tag:language.ADD />" src="images/n_add.png" /></a>&nbsp;&nbsp;&nbsp;<a href="<tag:news_model[].edit />"><img border=0 alt="<tag:language.EDIT />" src="images/n_edit.png" /></a>
  </if:EDIT_NEWS>
  <if:DELETE_NEWS>
    &nbsp;&nbsp;&nbsp;<a onclick=\"return confirm('". str_replace("'","\'",<tag:language.DELETE_CONFIRM />)."')\" href="<tag:news_model[].delete />"><img border=0 alt="<tag:language.DELETE />" src="images/n_delete.png" /></a></td></tr>
  </if:DELETE_NEWS>
  </if:EDIT_DEL>

    <tr>
      <td class="header" align="center"><tag:language.POSTED_BY />: <tag:news_model[].username /><br><tag:language.POSTED_DATE />: <tag:news_model[].date />
      </td>
    </tr>
    <tr>
      <td class="lista" align="center"><b><tag:language.TITLE />: <tag:news_model[].title /></b><br><br>
    <table style="border-top:1px" solid gray;width:100%;font-family:Verdana;font-size:10px'>
      <tr>
        <td><tag:news_model[].news /></td>
      </tr>
    </table>
      </td>
    </tr>
</loop:news_model>
</table>
</if:VIEW>
<if:NO_NEWS>
  <center><tag:language.NO_NEWS />...<br />
    <if:EDIT_NEWS>
      <br /><a href="<tag:news_add />"><img border=0 alt="<tag:language.ADD />" src="images/new.gif" /></a><br /></center>
      </if:EDIT_NEWS>
</if:NO_NEWS>