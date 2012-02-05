<div align="center">
  <form action="<tag:torrent.link />" method="post" name="edit">
    <table class="lista">
      <tr>
        <td align="right" class="header"><tag:language.FILE /></td>
        <td class="lista"><input type="text" name="name" value="<tag:torrent.filename />" size="60" /></td>
      </tr>
      <tr>
        <td align="right" class="header"><tag:language.INFO_HASH /></td>
        <td class="lista"><tag:torrent.info_hash /></td>
      </tr>
      <tr>
        <td align="right" class="header"><tag:language.DESCRIPTION /></td>
        <td class="lista"><tag:torrent.description /></td>
      </tr>
      <tr>
        <td class="header" ><tag:language.CATEGORY_FULL /></td>
        <td class="lista"><tag:torrent.cat_combo /></td>
      </tr>
      <tr>
        <td align=right class="header"><tag:language.SIZE /></td>
        <td class="lista" ><tag:torrent.size /></td>
      </tr>
      <tr>
        <td align=right class="header"><tag:language.ADDED /></td>
        <td class="lista" ><tag:torrent.date /></td>
      </tr>
      <tr>
        <td align=right class="header"><tag:language.DOWNLOADED /></td>
        <td class="lista" ><tag:torrent.complete /></td>
      </tr>
      <tr>
        <td align=right class="header"><tag:language.PEERS /></td>
        <td class="lista" ><tag:torrent.peers /></td>
      </tr>
    </table>
    <input type="hidden" name="info_hash" value="<tag:torrent.info_hash />" />
    <table>
      <td align="right">
            <input type="submit" class="btn" value="<tag:language.FRM_CONFIRM />" name="action" />
      </td>
      <td align="left">
            <input type="submit" class="btn" value="<tag:language.FRM_CANCEL />" name="action" />
      </td>
    </table>
  </form>
</div>