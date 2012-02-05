<script type="text/javascript">
function ShowHide(id,id1) {
    obj = document.getElementsByTagName("div");
    if (obj[id].style.display == 'block'){
     obj[id].style.display = 'none';
     obj[id1].style.display = 'block';
    }
    else {
     obj[id].style.display = 'block';
     obj[id1].style.display = 'none';
    }
}

function windowunder(link)
{
  window.opener.document.location=link;
  window.close();
}
</script>
    <div align="center">
      <table width="100%" class="lista" border="0" cellspacing="5" cellpadding="5">
        <tr>
          <td align="right" class="header"><tag:language.FILE />
          <if:MOD>
          <tag:mod_task />
          </if:MOD>
          </td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.filename /></td>
        </tr>
        <tr>
          <td align="right" class="header"><tag:language.TORRENT /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><a href="download.php?id=<tag:torrent.info_hash />&amp;f=<tag:torrent.filename />.torrent"><tag:torrent.filename /></a></td>
        </tr>
        <tr>
          <td align="right" class="header"><tag:language.INFO_HASH /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.info_hash /></td>
        </tr>
        <tr>
          <td align="right" class="header" valign="top"><tag:language.DESCRIPTION /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.description /></td>
        </tr>
        <tr>
          <td align="right" class="header"><tag:language.CATEGORY_FULL /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.cat_name /></td>
        </tr>
        <tr>
          <td align="right" class="header"><tag:language.RATING /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.rating /></td>
        </tr>
        <tr>
          <td align="right" class="header"><tag:language.SIZE /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.size /></td>
        </tr>
        <if:DISPLAY_FILES>
        <tr>
        <td align="right" class="header" valign="top"><a name="expand" href="#expand" onclick="javascript:ShowHide('files','msgfile');"><tag:language.SHOW_HIDE /></a></td>
        <td align="left" class="lista">
        <div style="display:none" id="files">
          <table class="lista">
            <tr>
              <td align="center" class="header"><tag:language.FILE /></td>
              <td align="center" class="header"><tag:language.SIZE /></td>
            </tr>
            <loop:files>
            <tr>
              <td align="center" class="lista" style="text-align:left;" valign="top"><tag:files[].filename /></td>
              <td align="center" class="lista" style="text-align:left;" valign="top"><tag:files[].size /></td>
            </tr>
            </loop:files>
          </table>
        </div>
        <div style="display:block" id="msgfile" align="left"><tag:torrent.numfiles /></div>
        </td>
        </tr>
        </if:DISPLAY_FILES>
        <tr>
          <td align="right" class="header"><tag:language.ADDED /></td>
          <td class="lista" style="text-align:left;" valign="top"><tag:torrent.date /></td>
        </tr>
        <if:SHOW_UPLOADER>
        <tr>
          <td align="right" class="header"><tag:language.UPLOADER /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.uploader /></td>
        </tr>
        </if:SHOW_UPLOADER>
        <if:NOT_XBTT>
        <tr>
          <td align="right" class="header"><tag:language.SPEED /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.speed /></td>
        </tr>
        </if:NOT_XBTT>
        <tr>
          <td align="right" class="header"><tag:language.DOWNLOADED /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.downloaded /></td>
        </tr>
        <tr>
          <td align="right" class="header"><tag:language.PEERS /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.seeds />, <tag:torrent.leechers /> = <tag:torrent.peers /></td>
        </tr>
        <if:EXTERNAL>
        <tr>
          <td valign="middle" align="right" class="header"><tag:torrent.update_url /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.announce_url /></td>
        </tr>
        <tr>
          <td valign="middle" align="right" class="header"><tag:language.LAST_UPDATE /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.lastupdate /> (<tag:torrent.lastsuccess />)</td>
        </tr>
        </if:EXTERNAL>
      </table>
      <a name="comments" />
      <br />
      <br />
      <table width="100%" class="lista">
        <if:INSERT_COMMENT>
        <tr>
          <td align="center" colspan="3">
             <a href="index.php?page=comment&amp;id=<tag:torrent.info_hash />&amp;usern=<tag:current_username />"><tag:language.NEW_COMMENT /></a>
          </td>
        </tr>
        </if:INSERT_COMMENT>
        <if:NO_COMMENTS>
        <tr>
          <td colspan="3" class="lista" align="center"><tag:language.NO_COMMENTS /></td>
        </tr>
        <else:NO_COMMENTS>
        <loop:comments>
        <tr>
          <td class="header"><tag:comments[].user /></td>
          <td class="header"><tag:comments[].date /></td>
          <td class="header" align="right"><tag:comments[].delete /></td>
        </tr>
        <tr>
          <td colspan="3" class="lista" align="center" style="text-align:left;" valign="top"><tag:comments[].comment /></td>
        </tr>
        </loop:comments>
        </if:NO_COMMENTS>
      </table>
    </div>
    <br />
    <br />
    <div align="center">
      <tag:torrent_footer />
    </div>