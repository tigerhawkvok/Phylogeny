<?php
ob_start( 'ob_gzhandler' );
?>
<!DOCTYPE
 html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html   xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>Phylogeny Search</title>
    <link rel='stylesheet' title='Phylogeny Site (Default)' href='layout/main.css' media='screen' />
<?php

include('tools/toolkit.inc');

$file_name = 'index_source.txt';
$file_dir='http://phylogeny.revealedsingularity.net/';
//$file_loc = $file_dir . $file_name;
$file_loc=$file_name;

if($_REQUEST['search']!="") $_POST['search']=$_REQUEST['search'];

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/* Start timer */
$time_start = microtime_float();

function array_find_key($needle, $haystack)
{
   $i=0;
   foreach ($haystack as $item)
   {
      if (@strpos(strtolower($item), strtolower($needle)) !== FALSE)
      {
         if(@strpos($item,"//")===false && @strpos($item,"\/*")===false)
           {
             $result = array($item,$i);
             return $result;
             break;
            }
      }
     $i++;
   }
   return false;
}

function arrayify ($file)
{
 $contents = file_get_contents($file);
 $doc_array=array();
 $pos = strpos($contents,"\n");
 while($pos !== false)
  {
    $pos = strpos($contents,"\n");
    if ($pos !== false)
      {
        $doc_array[] = substr($contents,0,$pos);
        $contents = substr($contents,$pos+1);
      }
  }
 $top = array_find_key("TOP_ROOT_BREAK",$doc_array);
 $bottom = array_find_key("END_PHYLO_BREAK",$doc_array);
 $nodecount=$bottom[1]-$top[1];
 echo "\n\n<!-- FINDERSTRING bottom-line: '" . $bottom[1] . "' and top: '" . $top[1] . "' for " . $nodecount . " total nodes-->\n\n";
 array_splice($doc_array,$bottom[1]);
 $doc_array=array_slice($doc_array, $top[1]);
 return $doc_array;

}

function handle_search($searchstring, $document)
{
  $param = array_find_key($searchstring,$document);
  if ($param !== FALSE)
   {
     $counter = 0;
     $bcounter = 0;
     $trail = array();
     //echo "<br/><br/>Found in line " . $param[1] . " size " . sizeof($document) . "<br/><br/>";
     $trim_end = array_slice($document,0,$param[1]+1);
     $from_bottom = array_reverse($trim_end);
     foreach ($from_bottom as $line)
      {
        if (strpos($line,"}") !==FALSE) 
          {
            if ($bcounter<1) 
              {
                $bcounter=1;
	        $counter=0;
	      }
	    else $bcounter++;
          }
        if (strpos($line,"{") !==FALSE) $bcounter--;
        if ($bcounter < 0) { /**** Begin bcounter ***/ 
        if (strpos($line,"close") !==FALSE)
          {
            $counter--;
          }
        if (strpos($line,"open") !==FALSE || strpos($line,"build") !==FALSE || strpos($line,"singleout") !==FALSE)
          {
            $counter++;
          }
        if ($counter > 0)
          {
            if (strpos($line,"cladecollapse") !==FALSE)
              {
                $num_start = strpos($line,"\",")+2; //" For visual formatting
                $n=5; // Accomodates an absurd 10k collapses
                while($n > 0)
                  {
                    $c_id = substr($line,$num_start,$n);
                    if (is_numeric($c_id)) $n=0;
                    $n--;
                  }
                $trail[] = $c_id;
               // echo $line . "<br/>";
                //$counter = 0;
              }
          }
          } /*** End bcounter ***/
        if (strpos($line,"TOP_ROOT_BREAK") !==FALSE)
          {
            return $trail;
            break;
          }
        if (strpos($line,"ROOT_JUMP") !==FALSE)
          {
            $jumpnum_s = strpos($line, "ROOT_JUMP");
            $n=5;
            while ($n>0)
              {
                $sub = substr($line,$jumpnum_s+9,$n);
                if (is_numeric($sub)) $n=0;
                $n--;
              }
            $trail[] = $sub;
            return $trail;
            break;
          }
      }
     return $trail;
     break;
   }
 return false;
}


function parse_link($key,$document,$linktrail)
{
 $openstring = "<a href='";
 $close_tagstring = "</a>";
 $end_tagstring = "'>";
 global $siteurl;
 
 $res_start = strpos($document[$key],"(\"") +2; //"
 $qextinct = strpos($document[$key],"\$extinct ");
 if ($qextinct !== FALSE) $res_start = $res_start+9;
 if (strpos($document[$key],"?") !== FALSE) $res_start = $res_start + 2;
 $res_end = strpos($document[$key],"\","); //"
 if ($res_end === FALSE) $res_end = strpos($document[$key],"\")"); //"
 $para = strpos($document[$key],"*");
 if ($para !== false)
  {
   $para2 = strpos($document[$key],"<sup>");
   if($para2 !== false) $para = $para2;
   $res_end = $para;
  }
 $tag_end = strpos($document[$key],"<tt>");
 if($tag_end !== FALSE && $tag_end < $res_end) $res_end = $tag_end;

 $length = $res_end - $res_start;

 $res_output = substr($document[$key],$res_start,$length);
 $res_output_l = $res_output;

 $res_output_l = str_replace("&amp;","&amp;amp;",$res_output_l);
 $res_output_l = str_replace(" ","+",$res_output_l);

 $linkstring = "$siteurl/?"; //index.php
 foreach ($linktrail as $node)
 {
  $linkstring .= $node . "=t&amp;";
 }
 $linkstring .= "h=$res_output_l&amp;#a" . $linktrail[0];
 $final = $openstring . $linkstring . $end_tagstring . $res_output . $close_tagstring;

 return $final;
}


function parse_link_head($key,$document,$linktrail)
{
 $res_start = strpos($document[$key],"(\"") +2; //"
 $qextinct = strpos($document[$key],"\$extinct ");
 if ($qextinct !== FALSE) $res_start = $res_start+9;
 if (strpos($document[$key],"?") !== FALSE) $res_start = $res_start + 2;
 $res_end = strpos($document[$key],"\","); //"
 if ($res_end === FALSE) $res_end = strpos($document[$key],"\")"); //"
 $tag_end = strpos($document[$key],"<tt>");
 if($tag_end !== FALSE) $res_end = $tag_end;

 $length = $res_end - $res_start;

 $res_output = substr($document[$key],$res_start,$length);
 $res_output_l = $res_output;
 $res_output_l = str_replace("&amp;","&amp;amp;",$res_output_l);
 $res_output_l = str_replace(" ","+",$res_output_l);
 $linkstring = "$siteurl/index.php?";
 foreach ($linktrail as $node)
 {
  $linkstring .= $node . "=t&amp;";
 }
 $linkstring .= "h=$res_output_l&amp;#a" . $linktrail[0];


 return $linkstring;
}


function searcher($searchstring,$file)
{
 $doc_array=arrayify($file);
 $rcount = 0;
 $result = array_find_key($searchstring,$doc_array);
 if ($result === false) 
  {
    $todaystring = intval(date("Ymd"));
    if (file_exists("sourced/$todaystring")===false)
      {
        echo "<!-- Refreshed index -->";
        copy('index.php','index_source.txt'); //maybe manual index refresh?
        copy('dummy',"sourced/$todaystring");
        $doc_array=arrayify($file);
        $result = array_find_key($searchstring,$doc_array);
      }
  }
 if ($result === false)
  {
    echo "<p>Search '$searchstring' not found. Please try again.  Consider <a href='assist.php'>helping me add this node to the project</a>.</p>";
    return false;
    break;
  }
 echo "<p>You searched for '$searchstring'. Any of these nodes what you're looking for?</p>\n<p>";
 $doc_array_tmp = $doc_array;
 while($result !== false)
  {
    $trail=handle_search($result[0],$doc_array);
    $linkstring=parse_link($result[1],$doc_array,$trail);
    $rcount++;
    echo "$rcount ) $linkstring <br/>\n";
    $doc_array_tmp = array_slice($doc_array,$result[1]+1);
    if (sizeof($doc_array_tmp)>1)
     {
      $result = array_find_key($searchstring,$doc_array_tmp);
      $result = array_find_key($result[0],$doc_array);
     }
    else $result = false;
  }
 $plural = "matches";
 if ($rcount == 1) $plural = "match";
 echo "<br/><br/>\n <strong>$rcount possible $plural found.</strong>";
 echo "  You will be taken to the closest parent collapsable node, and your result will be highlighted.</p>";
}


function permalinker($searchstring,$file)
{
 $doc_array=arrayify($file);
 $rcount = 0;
 
 $realsearch = $searchstring;

 $poly = strpos($searchstring,"*");
 $poly2 = strpos($searchstring,"<sup>*</sup>");
 if ($poly2 !== false) $poly = $poly2;
 if ($poly !== false) $realsearch = substr($searchstring,0,$poly);
 $w_common = strpos($realsearch," (");
 if ($w_common !== false)
    {
      $realsearch = substr($realsearch,$w_common,strpos($realsearch,")"));
    }

 $result = array_find_key($realsearch,$doc_array);
 
 if ($result === false)
  {
    echo "  </head>
  <body>
    <div class='container'>
      <p>Permalink not found. Your term may have been replaced in the literature, or a typo may have been corrected. Try another query.</p>";
    return false;
    break;
  }
 /*
The true result should always be the first result, as deeper nodes are more specific ones.
Otherwise, the results will have to be looped until a precise equality is reached.
  */
 $trail=handle_search($result[0],$doc_array); 
 $linkstring=parse_link_head($result[1],$doc_array,$trail);
 echo "<meta http-equiv='refresh' content='0;url=$linkstring' />
  </head>
  <body>
    <div class='container'>
      <p>Node found &mdash; loading ... </p>";
}

?>
      <?php
	 if($_REQUEST['permalink']!="")
	  {
	    $permalink = str_replace("+", " ", $_REQUEST['permalink']);
	    $test=permalinker($permalink,$file_loc);
	  }
	 if($_REQUEST['permalink']=="" || $test === false)
          {
	   if($_REQUEST['permalink']=="")
	   { ?>
  </head>
  <body>
    <div class='container'>
	   <?php }
		 include ('tools/headlinks.inc');
	 ?>
      <h1>Phylogeny Search</h1>
      <p>
	<br/>

	You can search here for any of the nodes or common names
	listed in this phylogeny.

      </p>
      <hr/>
      <?php
	 if($_REQUEST['q']=='search')
	  {
	   searcher($_POST['search'],$file_loc);
	   $time_end = microtime_float();
	   $time = round($time_end - $time_start,4);
	   echo "<p class='footer' style='background:#f0f7f9;'>
		 If you didn't find what you want, check if your word is an irregular plural, 
		 such as 'wallaby' to 'wallabies'. If you are unsure of your spelling, try a 
		 shorter sub-phrase, such as 'cimo' instead of 'Cimoliasauridae'.<br/>
		 Search completed in $time seconds.</p>";
	  }
	 ?>
      <p>&nbsp;</p>
      <form action='?q=search' method='post' style='text-align:center;'>
	<p>
	  <label for='search'>Search: </label>
	  <input type='text' id='search' name='search' />
	  <input type='submit' value='Search'/>
	</p>
      </form>
      <?php
	 include('tools/footerlinks.inc');
	 } /* end permalink block */ 
	     ?>
    </div>
  </body>
</html>
