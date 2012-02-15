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

function disable_button(state)
{
 document.getElementById('ty').disabled=(state=='1'?true:false);
}

at=new sack();

function ShowUpdate()
{
  var mytext=at.response + '';
  var myout=mytext.split('|');
  document.getElementById('thanks_div').style.display='block';
  document.getElementById('loading').style.display='none';
  document.getElementById('thanks_div').innerHTML = myout[0]; //at.response;
  disable_button(myout[1]);
}

function thank_you(ia)
{
  disable_button('1');
  at.resetData();
  at.onLoading=show_wait;
  at.requestFile='thanks.php';
  at.setVar('infohash',"'"+ia+"'");
  at.setVar('thanks',1);
  at.onCompletion = ShowUpdate;
  at.runAJAX();
}

function ShowThank(ia)
{
  at.resetData();
  at.onLoading=show_wait;
  at.requestFile='thanks.php';
  at.setVar('infohash',"'"+ia+"'");
  at.onCompletion = ShowUpdate;
  at.runAJAX();
}

function show_wait()
{
  document.getElementById('thanks_div').style.display='none';
  document.getElementById('loading').style.display='block';
}
</script>
<script type="text/javascript" src="jscript/prototype.js"></script>
<script type="text/javascript" src="jscript/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="jscript/lightbox.js"></script>
<link rel="stylesheet" href="jscript/lightbox.css" type="text/css" media="screen" />
    <div align="center">
      <table width="100%" class="lista" border="0" cellspacing="5" cellpadding="5">
        <tr>
          <td align="right" class="header"><tag:language.TORRENT />
          <if:MOD>
          <tag:mod_task />
          </if:MOD>
          </td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><a href="download.php?id=<tag:torrent.info_hash />&amp;f=<tag:torrent.filename />.torrent"><tag:torrent.filename /></a></td>
        </tr>
        <tr>
          <td align="right" class="header"><tag:language.INFO_HASH /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.info_hash /></td>
        </tr>
	<tr>
	<td align="right" class="header" valign="top"><tag:language.THANKS_USERS /></td>
          <td class="lista" align="center">
              <form action="thanks.php" method="post" onsubmit="return false">
              <div id="thanks_div" name="thanks_div" style="display:block;"></div>
              <input type="button" id="ty" disabled="disabled" value="<tag:language.THANKS_YOU />" onclick="thank_you('<tag:torrent.info_hash />')" />
              </form>
              <script type="text/javascript">ShowThank('<tag:torrent.info_hash />');</script>
          </td>
        </tr>
<if:IMAGEIS>
        <tr>
          <td align="right" class="header" valign="top"><tag:language.IMAGE /></td>
          <td class="lista" align="center"><a href="<tag:uploaddir /><tag:torrent.image />" title="<tag:torrent.filename /> Poster" rel="lightbox"><img src="<tag:uploaddir /><tag:torrent.image />" width=<tag:width />></a></td>
        </tr>
</if:IMAGEIS>
        <tr>
          <td align="right" class="header" valign="top"><tag:language.DESCRIPTION /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.description /></td>
        </tr>
        <tr>
          <td align="right" class="header"><tag:language.CATEGORY_FULL /></td>
          <td class="lista" align="center" style="text-align:left;" valign="top"><tag:torrent.cat_name /></td>
        </tr>
<if:has_screenshot>
      <tr>
      <td align="right" class="header" valign="top"><tag:language.SCREEN /></td>
      <td class="lista">
      <table class="lista" border="0" cellspacing="0" cellpadding="0">
        <if:SCREENIS1>
          <td class="lista" align="center"><a href="<tag:uploaddir /><tag:torrent.screen1 />" title="<tag:torrent.filename /> #1 screenshot" rel="lightbox[screen]"><img src="thumbnail.php?size=150&path=<tag:uploaddir /><tag:torrent.screen1 />"></a></td>
        </if:SCREENIS1>
        <if:SCREENIS2>
          <td class="lista" align="center"><a href="<tag:uploaddir /><tag:torrent.screen2 />" title="<tag:torrent.filename /> #2 screenshot" rel="lightbox[screen]"><img src="thumbnail.php?size=150&path=<tag:uploaddir /><tag:torrent.screen2 />"></a></td>
        </if:SCREENIS2>
        <if:SCREENIS3>
          <td class="lista" align="center"><a href="<tag:uploaddir /><tag:torrent.screen3 />" title="<tag:torrent.filename /> #3 screenshot" rel="lightbox[screen]"><img src="thumbnail.php?size=150&path=<tag:uploaddir /><tag:torrent.screen3 />"></a></td>
        </if:SCREENIS3>
      </table>
      </td>
      </tr>
</if:has_screenshot>
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
TeST
