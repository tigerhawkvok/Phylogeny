<?php

// if they mark they're uploading now


if($_REQUEST['q']=='submit')
  {
    // Move files
    if(file_exists($_POST['dir'])===false) mkdir($_POST['dir']);
    $i=1;
    $success=0;
    $fail=0;
    if ($_POST['type'] == 'Image') $filetypes = 'PNG, JPG, BMP, SVG, AI, PSD, or GIF';
    if ($_POST['type'] == 'Document') $filetypes = 'DOC, DOCX, TXT, ODT, XPS, or PDF';
    while ($i<=1)
      {
        $name="image-$i";
	$target=$_POST['dir'] . "/" . $_FILES[$name]['name'];
	$ext=strtolower(substr($_FILES[$name]['name'],-3));
	if (($ext=='png') || ($ext=='jpg') || ($ext=='jpeg') || ($ext=='bmp') || ($ext=='gif') || ($ext=='svg') || ($ext=='.ai') || ($ext=='psd') || ($ext == 'txt') || ($ext == 'odt') || ($ext == 'doc') || ($ext == 'ocx') ||  ($ext == 'xps') || ($ext == 'pdf')) 
	  {
            if(move_uploaded_file($_FILES[$name]['tmp_name'], $target)) $success++;
	    else $fail++;
	  } 
	 else 
	  {
	  echo "<p class='error'><strong>ERROR:</strong> File ". $_FILES[$name]['name'] . " is an unknown file type.  Please use only $filetypes files.</p>";
	  $fail++;
	  }
	 $i++;
      }
      global $upload,$success, $fail;
      $upload=$_POST['dir'];
      if ($fail > 0) echo "<p><strong>ERROR</strong>: $fail files failed to upload.</p>";
    
  }
else {

//create random directory for file housing
$rand=rand(1,256)*rand(1,256);
$server_loc_temp="contribute_temp/$rand";

while (file_exists($server_loc_temp))
  {
    $rand=rand(1,getrandmax())*$rand(1,getrandmax());
    $server_loc_temp="contribute_temp/$rand";
  }

$haveimage=1; // change: from $_POST['num']

if ($haveimage == '') 
   {
     echo "<p><strong>ERROR</strong>: No images number entered. Showing 4 fields.</p>";
     $haveimage=4;
   }

   $count=$haveimage;
    if ($_POST['type'] == 'Image') $filetypes = 'PNG, JPG, BMP, SVG, AI, PSD, or GIF';
    if ($_POST['type'] == 'Document') $filetypes = 'DOC, DOCX, TXT, ODT, XPS, or PDF';
    echo "<p>Upload file here.  Max file size: 2 MB.  $filetypes accepted.</p>
          <input type='hidden' name='MAX_FILE_SIZE' value='2000000' />
	  <input type='hidden' name='dir' value='$server_loc_temp' />";
    echo "<ul style='list-style:none;'> \n";
    $i=1;
    while ($i<=$haveimage)
      {
        echo "<label for='image-$i'>File $i:</label> \n";
	echo "<input name='image-$i' type='file' /> <br/>"; // Browser form
	$i++;
      }
    echo "</ul> \n";
  


} // end pre-upload
// clear out contents

//rmdir($server_loc_temp);



?>