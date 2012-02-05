
<script type="text/javascript">

function checkExtension()
{

    // for mac/linux, else assume windows
    if (navigator.appVersion.indexOf('Mac') != -1 || navigator.appVersion.indexOf('Linux') != -1)
        var fileSplit = '/';
    else
        var fileSplit = '\\';

    var fileType      = '.torrent';
    var fileName      = document.getElementById('torrent').value; // current value
    var extension     = fileName.substr(fileName.lastIndexOf('.'), fileName.length);

    if (extension!=fileType)
      {
       alert('<tag:language.ERR_PARSER />');
       return false;
    }

    return true;
}

function CheckForm()
{
  // file extension
  if (checkExtension()==false)
     return false;

  var cat=document.getElementsByName('category')[0];
  // categories
  if (cat.value=='0')
    {
    alert('<tag:language.WRITE_CATEGORY />');
    cat.focus();
    return false;
    }

  var desc=document.getElementsByName('info')[0];

  // description
  if (desc.value.length==0)
    {
    alert('<tag:language.EMPTY_DESCRIPTION />');
    desc.focus();
    return false;
    }


  // all filled...
  return true;
}

</script>
<center><tag:language.INSERT_DATA /><br /><br /><tag:language.ANNOUNCE_URL /><br /><b><tag:upload.announces /></b><br /></center>
<form name="upload" method="post" onsubmit="return CheckForm();" action="index.php?page=upload" enctype="multipart/form-data">
<input type="hidden" name="user_id" size="50" value="" />
  <table class="lista" border="0" width="96%" cellspacing="1" cellpadding="2">
    <tr>
      <td class="header"><tag:language.TORRENT_FILE /></td>
      <td class="lista" align="left"><input type="file" id="torrent" name="torrent" /></td>
    </tr>
    <tr>
      <td class="header" ><tag:language.CATEGORY_FULL /></td>
      <td class="lista" align="left"><tag:upload_categories_combo /></td>
    </tr>
    <tr>
      <td class="header" ><tag:language.FILE_NAME /></td>
      <td class="lista" align="left"><input type="text" name="filename" size="50" maxlength="200" /></td>
    </tr>
    <tr>
      <td class="header" valign="top"><tag:language.DESCRIPTION /></td>
      <td class="lista" ><tag:textbbcode /></td>
    </tr>
    <tr>
      <td class="header"><tag:language.TORRENT_ANONYMOUS /></td>
      <td class="lista">&nbsp;&nbsp;<tag:language.NO /><input type="radio" name="anonymous" value="false" checked="checked" />&nbsp;&nbsp;<tag:language.YES /><input type="radio" name="anonymous" value="true" /></td>
    </tr>
    <tr>
      <td class="header" align="right"><input type="submit" class="btn" value="<tag:language.FRM_SEND />" /></td>
      <td class="header" align="left"><input type="reset" class="btn" value="<tag:language.FRM_RESET />" /></td>
    </tr>
  </table>
</form>

