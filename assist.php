<?php
ob_start( 'ob_gzhandler' );
$fr_headers  = 'MIME-Version: 1.0' . "\r\n";
$fr_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
?>

<!DOCTYPE
  html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

  <html   xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
                                                 <head>
                                                 <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
                                                 <title>Submit Phylogeny</title>
                                                 <link rel='stylesheet' title='Phylogeny Site (Default)' href='layout/main.css' media='screen' />
                                                 </head>
                                                 <body>
                                                 <div class='container'>
<?php 
                                                 include('tools/toolkit.inc');
include('tools/headlinks.inc');
?>
<h1>Phylogeny Contribution</h1>
      
<p>
You can use the form on this page to contribute new or revised
	phylogenies.  Please select a phylogeny submission type and a
	citation type.  The citability is what keeps this useful to
	everyone involved, so please check your citations!  If you
	wish to provide code, you should probably <a
	href='coding.php'>check the API documentation</a>.
  </p>
  <p>
  You can also now <a href='https://github.com/tigerhawkvok/Phylogeny'>directly contribute on GitHub!</a>
  </p>
  <p>
	Please be sure at least one group already online is included
	as an outgroup to your contribution.  If you wish to contribute
	search tags, it is simplest to paste a block of code from the 
	<a href='source.php' <?php echo $newwindow; ?>>source code</a> 
and insert tags directly as mentioned in the documentation.
  </p>
<hr/>
<?php
if ($_REQUEST['q'] == 'select')
  {
?>
    <!-- Display selected sumbission types -->
      <form id='submitter' method='post'  <?php if (($_POST['type'] == 'Image') || ($_POST['type'] == 'Document')) echo "enctype='multipart/form-data'"; ?> action='?q=submit'>
    <div id='information'>
	  <!-- Submission field -->
<?php 
	     if (($_POST['type'] == 'Image') || ($_POST['type'] == 'Document')) include('tools/upload_tools.inc'); 
	     else {
?>
         <label for='subtext'>Text Phylogeny:</label><br/>
         <textarea rows='30' cols='100' id='subtext' name='subtext'></textarea><br/>
<?php
       }
    $labelval = $_POST['cite'];
    if ($labelval == "") $labelval = 'Longform';
    if ($labelval == 'Longform') $labelval .= ' Citation (with ISBN if applicable):';
    else $labelval .= ':';
    if($labelval == 'DOI') $value="value='doi:'";
    else if ($labelval == 'URI') $value="value='http://'";
    else $value="";
?>
	  <label for='citation'><?php echo $labelval; ?></label>
	  <input type='text' id='citation' name='citation' <?php echo $value; ?>/>
	  <br/><br/>
	  <label for='contributer'>Name:</label>
	  <input type='text' name='contributer' id='contributer'/>
	  <br/>
	  <label for='email'>Email:</label>
	  <input type='email' name='email' id='email' />
	  <br/><br/>
	  <input type='hidden' name='type' value='<?php echo $_POST['type']; ?>'/>
	  <input type='submit' value='Submit'/>
    </div>
    </form>
<?php
  }
else if ($_REQUEST['q'] == 'suggest')
  { 
    echo "<p>
	       No project is ever perfect, and suggestions are always
	       appreciated! Just let me know any ideas / feature
	       requests / etc, and I'll take a look and get back to
	       you.
	     </p>
	     <form action='?q=suggsub' method='post'>
	       <div>
		 <label for='subtext'>Suggestions:</label><br/>
		 <textarea rows='30' cols='100' id='suggtext' name='suggtext'></textarea><br/>
		 <label for='name'>Name:</label><input type='text' name='name' id='name'/><br/>
		 <label for='email'>Email:</label><input type='text' name='email' id='email'/><br/>
		 <input type='submit' value='Send'/>
	       </div>
	     </form>";
  }
else if ($_REQUEST['q'] == 'suggsub')
  {
    if ($_POST['email'] == "" || strpos($_POST['email'],'@') == "" || $_POST['name'] == "" || $_POST['suggtext'] == "")
      {
        $fail++;
        echo "<p class='error'>ERROR: Not all fields were filled out. Please use your browser's back button and try again.</p>";
      }
    else
      {
        $webmaster="tigerhawkvok@gmail.com"; //Temporary
        $from=$_POST['email'];
        $name=$_POST['email'];
        $fr_headers .= "From: blackhole@revealedsingularity.net\r\nReply-To: $from";
        $subject='Phylogeny suggestion';
        $body=$_POST['suggtext'];
        if(mail($webmaster,$subject,$body,$fr_headers)) echo "<p>Thanks for your suggestion!</p>";
        else echo "<p class='error'>ERROR: There was a problem sending your suggestion.  Please go back and try again.</p>";
      }
  }
else if ($_REQUEST['q'] == 'submit')
  {
?>
<?php if (($_POST['type'] == 'Image') || ($_POST['type'] == 'Document'))
  {
    include('tools/upload_tools.inc'); 
    $infostring = "File location: http://phylogeny.revealedsingularity.net/" . $_POST['dir'] . ".  File type: " . $_POST['type'];
  }
  else $infostring= "<pre>" . $_POST['subtext'] . "</pre>";
?>
  <!-- Submission handling, messages -->
<?php
     if ($_POST['email'] == "" || strpos($_POST['email'],'@') == "" || $_POST['contributer'] == "")
       {
         $fail++;
         echo "<p class='error'>ERROR: Your contact information is invalid.</p>";
       }
  if (strlen($_POST['citation']) < 9)
    {
      $fail++;
      echo "<p class='error'>ERROR: Your citation information is invalid.</p>";
    }
  if ($infostring == "")
    {
      $fail++;
      echo "<p class='error'>ERROR: Your phylogeny was empty.</p>";
    }
  if ($fail == 0 ) {
    $webmaster="tigerhawkvok@gmail.com"; //Temporary
    $from=$_POST['email'];
    $fr_headers .= "From: blackhole@revealedsingularity.net\r\nReply-To: $from";
    $subject='Phylogeny contribution';
    $author=$_POST['contributer'];
    $message = "Contribution from " . $author . " at " . $from . ". Citation: " . $_POST['citation'] . "<br/><br/>\n" . $infostring . "<br/><br/>That is all.";
    if (mail($webmaster,$subject,$message,$fr_headers))
      { ?>
        <p>Thanks for contributing!  I'll get back to you as soon as possible.  You can <a href='http://phylogeny.revealedsingularity.net'>click here to go back to the main site</a>.</p>
<?php 
      }
    else echo "<p class='error'>ERROR: There was a problem with your submission.  Please try again.  If the problem persists, please <a href='mailto:pkahn@revealedsingularity.net'>contact the webmaster</a>.</p>";
  }
  else echo "<p>Please use your browser's back button and try again.</p>";
  }
else { ?>
  <div class='noticebox' style='width:20em;'>
	<p>If you only have a little time to contribute, consider working on tags for the various families, such as common names. Its quick, easy, and helpful, but would take a long time for just a few people!</p>
  </div>
  <form id='submitter' method='post' action='?q=select'>
	<!-- Form items: DOI / URI / Citation (Longform); Image / Textual phylogeny ; contact -->
                                                                                  <p>Submission type:</p>
	<div id='subtype'>
  <input type='radio' name='type' value='Image' />Image<br/>
  <input type='radio' name='type' value='Document' />Document<br/>
  <input type='radio' name='type' value='Text' />Text or Code<br/>
	</div>
	<p>Citation type:</p>
	<div id='citation'>
  <input type='radio' name='cite' value='DOI' />DOI<br/>
  <input type='radio' name='cite' value='URI' />URI<br/>
  <input type='radio' name='cite' value='Longform' />Longform<br/><br/>
	</div>
	<p>
  <input type='submit' value='Begin' />
	</p>
  </form>
  <p>
	Just have a suggestion? <a href='?q=suggest'>Just click here</a>.
    </p>
<?php } ?>
<p class='footer'>This site is happy to acknowledge the contributions of the following people:</p>
  <ul id='contribute'>
	<li class='last'>
  <a href='mailto:kcolwell@physics.ucsd.edu'>K Colwell</a> (Sauropterygia, Hexapoda)
	</li>
  </ul>
<?php    include ('tools/footerlinks.inc');
?>
  </div>
  </body>
  </html>
