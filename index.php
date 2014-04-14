<?php
@ob_start( 'ob_gzhandler' );
/***
 * GPLv3 Philip Kahn.
 * Full license: 
 * https://github.com/tigerhawkvok/Phylogeny/blob/master/LICENSE.md
 *
 * If you found this page using a search engine, view the full page
 * at http://phylogeny.revealedsingularity.net
 *
 * Source code available at 
 * https://github.com/tigerhawkvok/Phylogeny
 ***/

$update_date = "2013/05/31";
$update_content = "Revised Paraves after Godefroidt et al. 2013 and several others"; //Note "Updated" is placed before text.

?>
<!doctype html>

<html   xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="google-site-verification" content="z-BjpgjZM_BEJH1p9_LBr9BAlh_LhXm6QtczF8XrxiE" />
    <title>Compiled Family-Level Extant Phylogeny</title>
    <script type='text/javascript'>
      var counter=1;
      function showHide()
      {
      counter++;
      var e = counter % 2;
      if(e==0) document.getElementById('searchbox').style.right='-25em';
      else document.getElementById('searchbox').style.right='0';
      }
    </script>
<!-- Begin PHP function block.  Rest of body resumes after Line 351 -->
<?php
  include('tools/toolkit.inc');
  include('tools/styledec.inc');
?>

    <link title="Phylogeny" href="<?php echo $siteurl; ?>/R2S_OpenSearch_Phylogeny.xml" type="application/opensearchdescription+xml" rel="search"/>
 
<?php
$nop=0;
$ncl=0;
$cc=1;
$extinct="<img src='images/extinct.png' alt='Extinct' class='edagger' />";
$linkout_closeanchor=" $newwindow class='lo_anchor' > <img src='images/linkout.png' alt='Outgoing Link' class='linkout' /></a>";
$htest1 = "$extinct " . $_REQUEST['h'];
$htest2 = "? " . $_REQUEST['h'];
$backclass = "";


////////////////////////////////////////////

function tester($group)
{
 global $htest1,$htest2;
 $htest3 = "? " . $htest1;
 $tagged = strpos($group,"<tt>"); 
 $para = strpos($group,"*");
 if ($para !== false)
  {
   $para2 = strpos($group,"<sup>");
   if($para2 !== false) $para = $para2;
   $group = substr($group,0,$para);
  }
 if( $tagged !== FALSE) $group = substr($group,0,$tagged);
 if ($group == $_REQUEST['h'] || $group == $htest1 || $group == $htest2 || $group == $htest3) 
   {
    return true;
    break;
   }
 else return false;
}

///////////////////////////////////////////

function openphylo($ingroup)
  {
    global $nop;
    if ($ingroup=="(Unnamed)") $ingroup="<span class='noname'>$ingroup</span>";
    if(tester($ingroup)) 
      {
        $ingroup = "<span class='highlight'>$ingroup</span>";
        $backclass = " found";
      }
    echo "
	  <table class='phylo'>
	    <tr class='phyloline'><td colspan='2'></td></tr> <!--Skip a line-->
	    <tr class='phyloline'>
	      <td class='phylo1 $backclass'>
		<img src='images/phylooutbar.png' alt='' style='vertical-align:middle;width:12px;height:3px;' /> $ingroup
		<!--Begin Subclade $nop--> ";
    $nop++;
  }

/////////////////////////////////////////////

function closephylo($outgroup)
  {
    global $ncl,$nop;
    $nop--;
    if(tester($outgroup)) 
      {
	$outgroup = "<span class='highlight'>$outgroup</span>";
        $backclass = " found";
      }

    echo "
	       <!--End Subclade $nop-->
	     </td>
	   </tr>
	   <tr class='close'>
	     <td class='close $backclass'>
	       &nbsp;&nbsp;&nbsp; $outgroup
	     </td>
	   </tr>
	 </table> ";
  }

////////////////////////////////////////////

function singlephylo($ingroup)
  {
    if ($ingroup=="(Unnamed)") $ingroup="<span class='noname'>$ingroup</span>";
    if(tester($ingroup)) $ingroup = "<span class='highlight'>$ingroup</span>";
    echo "
	  <table class='phylo'>
	    <tr class='phyloline'><td colspan='2'></td></tr> <!--Skip a line-->
	    <tr class='close'>
	      <td class='close $backclass'>
		&nbsp;&nbsp;&nbsp; $ingroup
	      </td>
	    </tr>
	  </table> ";
  }

/////////////////////////////////////

function buildoutgroup($outgroup)
  {
    global $ncl, $nop;
    $nop--;
    if ($outgroup=="(Unnamed)") $outgroup="<span class='noname'>$outgroup</span>";
    if(tester($outgroup)) {
	$outgroup = "<span class='highlight'>$outgroup</span>";
        $backclass = " found";
      }
    echo "
	  <!--End Subclade $nop-->
        </td>
       </tr>
      <tr class='phyloline'>
       <td class='phylo1 $backclass'>
        <img src='images/phylooutbar.png' alt='' style='vertical-align:middle;width:12px;height:3px;' /> $outgroup";
  }

///////////////////////////////////////////

function buildendoutgroup($outgroup)
  {
global $nop;
$nop--;
if ($outgroup=="(Unnamed)") $outgroup="<span class='noname'>$outgroup</span>";
    if(tester($outgroup)) { 
	$outgroup = "<span class='highlight'>$outgroup</span>";
        $backclass = " found";
      }
echo "
<!--End Subclade $nop-->
  </td>
</tr>
<tr class='close'>
<td class='close $backclass'>
   &nbsp;&nbsp;&nbsp; $outgroup";
}

//////////////////////////////////////////

function singleoutgroup($ingroup)
  {
    if ($ingroup=="(Unnamed)") $ingroup="<span class='noname'>$ingroup</span>";
    if(tester($ingroup)) {
        $ingroup = "<span class='highlight'>$ingroup</span>";
        $backclass = " found";
      }
    echo "
      <table class='phylo'>
	<tr class='phyloline'><td colspan='2'></td></tr> <!--Skip a line-->
	<tr class='close'>
	  <td class='close $backclass'>
	    &nbsp;&nbsp;&nbsp; $ingroup";
  }

/////////////////////////////////////

function closeoutgroup()
  {
    echo "</td></tr></table>";
  }

////////////////////////////////////////////

function requestcheck()
  {
    global $cc;
    $retval = false;
    $n=0;
    while ($n <= $cc)
      {
	if ($_REQUEST["$n"]!="") $retval=true;
	$n++;
      }
    if ($retval==true)
      {
        $n=0;
        $good=0;
        $rstring="?";
	while ($n <= $cc)
	  {
	    if ($_REQUEST["$n"]!="")
	      {
		if ($good==0) $rstring .= "$n=t";
		else $rstring .= "&amp;$n=t";
		$good++;
	      }
	    $n++;
	  }
	$retval=$rstring;
      }
    return $retval;
  }

////////////////////////////////////////////

function requestcheckblank($skip)
  {
    global $cc;
    $retval = false;
    $n=0;
    while ($n <= $cc)
      {
        if ($_REQUEST["$n"]!="") $retval=true;
        $n++;
      }
    if ($retval==true)
      {
        $n=0;
        $good=0;
        $rstring="?";
        while ($n <= $cc)
          {
	    if ($_REQUEST["$n"]!="" && $n!=$skip)
	      {
		if ($good==0) $rstring=$rstring . "$n=t";
		else $rstring=$rstring . "&amp;$n=t";
		$good++;
	      }
            $n++;
          }
        $retval=$rstring;
      }
    return $retval;
  }

/////////////////////////////////////////////////

function cladecollapse($title,$cc2)
  {
  if(tester($title)) {
	$title = "<span class='highlight'>$title</span>";
        $backclass = " found";
      }
    if ($_REQUEST['subpages']!='true')
      {
        if ($_REQUEST["$cc2"]!="")
          {
	    // When open ...
	    if (requestcheck()!=false)
	      {
                // If other ones ...
                $title="<a href='" . requestcheckblank($cc2) . "#a$cc2' id='a$cc2'>$title</a>";
              }
            else
              {
                // If no other ones ...
                $title="<a href='?reset=t#a$cc2' id='a$cc2'>$title</a>";
              }
          }
        else
          {
            //when closed
            if (requestcheck()!=false)
              {
                // If other ones ...
                $title="<a href='" . requestcheck()  .  "&amp;$cc2=t#a$cc2' id='a$cc2'>$title</a>";
              }
            else
              {
                $title="<a href='?$cc2=t#a$cc2' id='a$cc2'>$title</a>";
              }
          }
      }
    else // if we are requesting subpages ...
      {
        if ($_REQUEST["$cc2"]!="")
          {
            // When open ...
            $ccold=$cc2-1;
            $title="<a href='?$ccold=t#a$ccold'>$title</a>";
          }
        else
          {
            //when closed
            $title="<a href='?$cc2=t#a$cc2' id='a$cc2'>$title</a>";
          }
      }
    return $title;
  }

/////////////////////////////////////////////////

function expandAll($clade_counter)
{

  $iter = 0;
  while ($iter < $clade_counter)
    {
      $_REQUEST["$iter"] = "t";
      $iter++;
    }

}

/////////////////////////////////////////////////

/******** Popout Subpages.  In development. *********

function spcheck($ccn,$trees)
  {
    if ($_REQUEST['$ccn']!="" && $_REQUEST['subpages']=='true') 
      {
        echo "<form method='post' action='?d=t&subpages=true'><input type='hidden' value='" . $trees . "'> <input type='submit' value='Pop Out current node'></form>";
      }
    else if ($_REQUEST['$ccn']!="" && $_REQUEST['subpages']!='true') echo $trees;
    else echo "";
}

///////////////////////////////////////////////

function getChildTrees()
  {
    // Core of popout code.  Need to select just the child trees of a node.
    $uri=$_SERVER["REQUEST_URI"];
    // get all child trees, pass into url with subpage = true to hide others
    // hide others by checking request and echoing "" for nonrequested
  }

*********** End page popout ******************/

global $phylostitch;
$phylostitch=requestcheck();

?>
  </head>
  <body>
    <div class='container'>
      <?php include('tools/headlinks.inc'); ?>
      <div class='header'>
        <!-- Header Stuff -->
	<h1>Compiled Family-Level Extant Phylogeny</h1>
      </div>
      <div class='content'>
	<p>&nbsp;</p>
	<p>

	  This phylogeny is based off work done for the UC Museum of
	  Paleontology in 2008. A work in progress, the objective is
	  to complete the phylogeny to "family" for all extant groups,
	  to order for extinct lineages.  This is a multi-year task,
	  and too big for one person.  If you have a minute,
	  please <a href='assist.php'>consider contributing to the
	  project</a>. <strong>Even just common name tags for nodes are very helpful!</strong>

	</p>
	<div style='width:20em;' class='noticebox'>
	  <p>
	    <strong>Last Updated</strong>: <?php echo $update_date; ?>.<br/>
	    Updated <?php echo $update_content; ?>.
	  </p>
	</div>
	<div style='font-size:9pt;'>
	  <p>
	    <strong>Systematics for this phylogeny:</strong> The
	    following phylogeny consists of a well-accepted phylogenetic
	    layout of animals, going until extant families.  Low-level
	    (i.e., more specific) nodes have common names first, with
	    taxonomic names in parenthesis for ease of the general
	    public.  Placement of a node is based on a composite by the
	    sources cited at the bottom, with the most recent
	    publication taking precedence.  The only non-peer reviewed
	    source is the "Tree of Life" project, which itself cites its
	    phylogenies; however, due to its dynamical nature, it is the
	    last resource used.
	  </p>  
	  <p>
	    A resolved phylogeny, even if speculative, is given priority
	    to a phylogeny which contains polytomies.
	  </p>
	  <p>
	    <strong>How to use and read:</strong> To recollapse a
	    portion of the tree, just click on it again.  A "?" in front
	    of a name indicates that its phylogenetic placement is
	    uncertain, and a dagger (<?php echo $extinct; ?>) indicates
	    that the node and all its descendants are extinct.  A "*"
	    indicates that the node is known to be paraphyletic, and
	    "**" indicates that an uncertain number of its child nodes
	    are known to be paraphyletic (though the main node is not).
	    Three stars (***) indicate both of these conditions.
	  </p>
	  <p>
	    Searching for a node highlights its child nodes in a
	    box. Making this more general is a work in progress.
	  </p>
	  <p>
	    <strong>Linking:</strong> Since this project is constantly
	    expanding, links to a particular node may not be stable in
	    the long run. For stable links, use the search function to
	    get to a node you want, and use the resulting permalink.
	  </p>
	</div>
	<p>&nbsp;</p>
	
	<!-- Print Layout Warning -->
	<?php
	   function curPageURL() {
	     $pageURL = 'http';
	     if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	     $pageURL .= "://";
	     if ($_SERVER["SERVER_PORT"] != "80") {
	       $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	     } else {
	       $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	     }
	     return $pageURL;
	   }

	   if ($_REQUEST['st']=='print') {
	?>
	  <p class='error' style='color:red;'>Please note that to print this page properly you must print the page background.</p>
	<?php } ?>
	<!-- End Warning -->


	<!-- IMPORTANT: CLADE COLLAPSE COUNTER. For various functions and allows "Expand All" link. -->
	<?php 
	   $cc=75;  /// Number of collapses.  MUST be manually entered, and >= true collapse number.
/* $expandall="$siteurl/?";
	   $i_expcounter = 1;
	   while ($i_expcounter <= $cc)
	     {
	       $expandall = $expandall . $i_expcounter . "=t&amp;";
	       $i_expcounter++;
	       }*/
           $printlink = curPageURL();
           $printlink=str_replace('&','&amp;',$printlink);
	   echo "<!-- " . strpos($printlink,'?') . "-->";
	   if (strpos($printlink,'?') == "")
             {
               $printlink = $printlink . "?st=print";
	     }
           else if (substr($printlink,-5) == "&amp;")
             {
	       $printlink = $printlink . "st=print";
             }
           else $printlink = $printlink . "&amp;st=print";

if ($_REQUEST['expand']=="TRUE")
  {
    expandAll($cc);
  }
	?>
	<!-- End -->

	<p style='text-align:right;'>
	  Quicklinks: 
	  <a href='<?php echo $siteurl; ?>'>Collapse All</a>
	  | 
	  <a href='?expand=TRUE'>Expand All</a> 
	  |
	  <?php if ($_REQUEST['st']=='print') { 
	    $page=curPageURL();
	    $pageurllen=strlen($page);
	    $pageurllen=$pageurllen - 9;
	    $page=substr($page,0,$pageurllen);
	    $page=str_replace('&','&amp;',$page);
	  ?>
	  <a href='<?php echo $page; ?>'>Standard View</a>
	  <?php }
	  else { ?>
	  <a href='<?php echo $printlink; ?>'>Print View</a>
	  <?php } ?>
	</p>
	<div id='searchbox'>
	<form action='search.php?q=search' method='post'>
	  <div>
	    <input type='button' onclick='showHide()'
	    value='Show/Hide'/>
	    <br/>
	    <label for='search'>Search Phylogeny: </label>
	    <input type='text' name='search' id='search' />&nbsp;
	    <input type='submit' value='Search' />
	  </div>
	</form>
	<?php if($_REQUEST['h']) { ?>
	<p style='text-align:right;'>
	  <label for='permalink'>Permalink: </label>
	  <?php
	     $permaurl = $siteurl . "/search.php?permalink=" . str_replace(" ","+",$_REQUEST['h']);
	     $shorturl = @file_get_contents("http://is.gd/api.php?longurl=$permaurl");
	     $styleoverride = "";
	     if(@strpos($shorturl,"http://is.gd") !== false && $_REQUEST['fullurl']!="TRUE") $p_url_out = $shorturl;
	     else 
	      {
	       $p_url_out = $permaurl;
	      }
	     ?>
	  <input type='text' readonly='readonly' name='permalink' id='permalink' value='<?php echo $p_url_out; ?>' onclick='select()' />
	       <br/><span style='font-size:8pt;'><strong>Full
	  Permalink (for embedding on your site)</strong>:<br/> <?php echo $permaurl; ?>
	</p>
	<?php } ?>
	</div> <!-- Close Searchbox -->

	<p style='vertical-align:bottom'>
	  Metazoa / Coelomata
	</p>

<!--Begin PHP constructed phylogenetic table. Manual content resumes approximately 100 lines from the bottom.-->

<?php
/*
TOP_ROOT_BREAK
*/
openphylo("Deuterosomes");
openphylo("Chordata");
openphylo("Vertebrata");
   openphylo("Jawed Vertebrates (Gnathostomata)");
      openphylo("Bony Vertebrates (Osteichthyes)");
         openphylo("Fleshy-finned Vertebrates (Sarcopterygii)");
            openphylo(cladecollapse("Tetrapoda",1));
	    if ($_REQUEST['1']!="")
	    {
	      openphylo("(Unnamed)");
	      openphylo("(Unnamed)");
	      openphylo("(Unnamed)");
	      openphylo("Crown-group tetrapods");
               openphylo(cladecollapse("Amniota",2));
	       if ($_REQUEST['2']!="")
	       {
                  openphylo(cladecollapse("Sauropsida<tt>reptile,reptilia</tt>",3));
                  if ($_REQUEST['3']!="")
                  {
                    openphylo("Diapsida");
                      openphylo("Neodiapsida");
                        openphylo("Sauria");
                    //Begin untabbed
		    openphylo("Archosauromorpha");
                    openphylo(cladecollapse("Archosauria",4));
		    if ($_REQUEST['4']!="")
		    {
                      openphylo("Avesuchia");
                      //
                      openphylo("Ornithodira");
                        openphylo(cladecollapse("Dinosauria",5));
                        if ($_REQUEST['5']!="")
                        {
                          openphylo(cladecollapse("Saurischia",6));
			  if ($_REQUEST['6']!="")
			  {
                            openphylo(cladecollapse("Theropoda",7));
			    if ($_REQUEST['7']!="")
			    {
			      openphylo("Tetanurae");
			        openphylo("Avetheropoda / Neotetanurae");
                              	  openphylo("Coelurosauria<tt>feathers</tt>");
				    openphylo("(Unnamed)");
                               	     openphylo("Maniraptora<tt>raptors</tt>");
				     openphylo("(Unnamed)"); // Alvarezsauridae 
				     openphylo("Paraves");
				       openphylo("Eumaniraptora<tt>Deinonychosauria</tt>");
				       openphylo("(Unnamed)");
                                         openphylo(cladecollapse("Avialae<tt>birds</tt>",8));
				         if ($_REQUEST['8']!="")
				       	 {
					 openphylo("(Unnamed)");
                                           openphylo("Aves");
					     openphylo("Pygostylia");
					       openphylo("Ornithothoraces");
					         openphylo("Ornithomorpha");
					           openphylo("Ornithurae");
						     openphylo("Carinatae");
						       openphylo("Neornithes");
						         openphylo(cladecollapse("Neognathae",9));
							 if ($_REQUEST['9']!="")
							 {
						           openphylo("Neoaves**");
							     openphylo("'Land Birds'");
							       openphylo("(Unnamed)");
							         openphylo("(Unnamed)");
								   openphylo("(Unnamed)");
								     openphylo("(Unnamed)");
								       openphylo("Passeriformes");
								         openphylo("Oscines");
                                                                         closephylo("Suboscines");
                                                                       closephylo("Psittaciformes");
                                                                     buildendoutgroup("Falconiformes");
                                                                        singlephylo("Falconidae");
                                                                     closeoutgroup();
                                                                   buildendoutgroup("Cariamae");
                                                                     openphylo("$extinct Phorusrhacidae<tt>terror bird</tt>");
                                                                     closephylo("Seriemas (Cariamidae)");
                                                                   closeoutgroup();
                                                                 buildendoutgroup("(Unnamed)");
                                                                   openphylo("(Unnamed)");
                                                                     openphylo("(Unnamed)");
                                                                       openphylo("(Unnamed)");
                                                                         openphylo("(Unnamed)");
                                                                           openphylo("Coraciiformes");
                                                                             openphylo("Classical coraciiformes*");
                                                                             closephylo("Piciformes");
                                                                           closephylo("Bucerotiformes");
                                                                         closephylo("Trogoniformes");
                                                                       closephylo("Cuckoo roller (Leptosomatidae)");
                                                                     buildendoutgroup("(Unnamed)");
                                                                       openphylo("Coliiformes");
                                                                       closephylo("Stirigiformes");
                                                                     closeoutgroup();
                                                                   buildendoutgroup("(Unnamed)");
                                                                     openphylo("Accipitriformes");
                                                                     closephylo("New world vultures (Cathartidae)");
                                                                   closeoutgroup();
                                                                 closeoutgroup();
							       closephylo("Charadriiformes");
							     buildendoutgroup("(Unnamed)");
                                                               openphylo("(Unnamed)");
                                                                 openphylo("(Unnamed)");
								   openphylo("'Water birds'");
								     openphylo("(Unnamed)");
								       openphylo("(Unnamed)");
								         openphylo("Procellariiformes");
								          buildendoutgroup("Sphenisciformes");
                                                                            singleoutgroup("Penguins (Spheniscidae)");
                                                                          closeoutgroup();
                                                                        buildendoutgroup("Pelecaniiformes");
								          openphylo("Classical pelecaniiformes*");
								          closephylo("Ciconiiformes*");
                                                                        closeoutgroup();
                                                                      closephylo("Gaviiformes");
                                                                    closephylo("Musophagiformes");
								  buildendoutgroup("(Unnamed)");
                                                                    openphylo("(Unnamed)");
								      openphylo("Gruiformes");
                                                                      closephylo("Cuculiformes");
								    closephylo("Otididae");
								  closeoutgroup();
                                                                buildendoutgroup("Opisthocomiformes");
                                                                  singlephylo("Hoatzin (Opisthocomidae)");
                                                                closeoutgroup();
                                                              buildendoutgroup("(Unnamed)");
							        openphylo("(Unnamed)");
                                                                  openphylo("Caprimulgiformes");
							            openphylo("(Unnamed)");
                                                                      openphylo("Apodiformes");
                                                                      closephylo("Owlet-nightjars (Aegothelidae)");
								    closephylo("Classical caprimulgiformes*");
                                                                  buildendoutgroup("(Unnamed)");
                                                                    openphylo("Sunbittern (Eurypygidae)");
                                                                    closephylo("Kagu (Rhynochetidae)");
                                                                  closeoutgroup();
                                                                buildendoutgroup("(Unnamed)");
                                                                  openphylo("(Unnamed)");
                                                                    openphylo("(Unnamed)");
                                                                      openphylo("(Unnamed)");
							                openphylo("Columbiformes");
                                                                        closephylo("Mesites (Mesitornithidae)");
                                                                      buildendoutgroup("Pteroclidiformes");
                                                                        singlephylo("Sandgrouse (Pteroclididae)");
                                                                      closeoutgroup();
                                                                    closephylo("Tropicbirds (Phaethontidae)");
							          buildendoutgroup("(Unnamed)");
								    openphylo("Phoenicopteriformes");
								    closephylo("Podicepidiformes");
                                                                  closeoutgroup();
                                                                closeoutgroup();
							      closeoutgroup();
							    closeoutgroup();
                                                             //End Neoaves [accidental overindent]
							  buildendoutgroup("Galloanserae");
							    openphylo("Galliformes");
							      openphylo("Incomplete");
							      closephylo("Incomplete");
							    buildendoutgroup("Anseriformes");
							      openphylo("Incomplete");
							      closephylo("Incomplete");
							    closeoutgroup();
							  closeoutgroup();
							 }
						         buildendoutgroup("Palaeognathae<tt>paleognath,ratite</tt>");
						           openphylo("(Unnamed)");
                                                             openphylo("(Unnamed)");
                                                               openphylo("(Unnamed)");
							         openphylo("(Unnamed)");
							           openphylo("(Unnamed)");
								     openphylo("Cassowaries (Casuariidae)");
								     closephylo("Emu (Dromaiidae)");
							           closephylo("Kiwis (Apteryigidae)");
							         closephylo("$extinct Dinornithidae");
							       buildendoutgroup("Tinamformes");
							         openphylo("Aridland tinamous (Nothurinae)");
							         closephylo("Forest tinamous (Tinaminae)");
							       closeoutgroup();
							     closephylo("Rheas (Rheidae)");
							   buildendoutgroup("(Unnamed)");
							     openphylo("(Unnamed)");
							       openphylo("$extinct <em>Struthiolithus</em>");
							       closephylo("$extinct Aepyornithidae");
							     closephylo("Ostriches (Struthionidae)");
							   closeoutgroup();
						         closeoutgroup();
						       closephylo("$extinct <em>Ichthyornis</em>");
						     closephylo("$extinct <em>Hesperornis</em>");
						   closephylo("$extinct <em>Patagopteryx</em>");
						 buildendoutgroup("$extinct Enantiornithes");
                                                   openphylo("$extinct Euenantiornithes");
                                                     openphylo("$extinct <em>Sinornis</em>");
                                                     closephylo("$extinct <em>Gobipteryx</em>");
                                                   buildendoutgroup("(Unnamed)");
                                                     openphylo("$extinct <em>Noguerornis</em>");
                                                     closephylo("$extinct <em>Iberomesornis</em>");
                                                   closeoutgroup();
                                                 closeoutgroup();
					       closephylo("$extinct Confusiusornithidae");
					     closephylo("$extinct <em>Archaeopteryx</em>");
					     closephylo("$extinct <em>Anchiornis</em>");
                                           closephylo("$extinct <em>Aurornis</em>");
				       	 }
					   buildendoutgroup("$extinct Troodontidae");
					     openphylo("$extinct (Unnamed)");
					       openphylo("$extinct <em>Sinovenator</em>");
					       closephylo("$extinct <em>Troodon</em>");
					     closephylo("$extinct <em>Jinfengopteryx</em>");
					   closeoutgroup();
					 buildendoutgroup("$extinct Dromaeosauridae");
 					   openphylo("$extinct (Unnamed)");
 					     openphylo("$extinct (Unnamed)");
 					       openphylo("$extinct Eudromaeosauria");
					         openphylo("$extinct Dromaeosaurinae");
						   openphylo("$extinct <em>Dromaeosaurus</em>");
						   closephylo("$extinct <em>Utahraptor</em>");
						 buildendoutgroup("$extinct Velociraptorinae");
						   openphylo("$extinct (Unnamed)");
						     openphylo("$extinct (Unnamed)");
						       openphylo("$extinct (Unnamed)");
						         openphylo("$extinct <em>Velociraptor</em>");
						         closephylo("$extinct <em>Deinonychus</em>");
						       closephylo("$extinct <em>Saurornitholestes</em>");
						     closephylo("$extinct <em>Tsaagan</em>");
						   closephylo("$extinct <em>Bambiraptor</em>");
						 closeoutgroup();
					       closephylo("$extinct Microraptorinae");
					     buildendoutgroup("$extinct Unenlagiinae");
					       openphylo("$extinct <em>Unenlagia</em>");
					       closephylo("$extinct <em>Austroraptor</em>");
					     closeoutgroup();
					   closephylo("$extinct <em>Mahakala</em>");
					 closeoutgroup();
					 buildendoutgroup("$extinct (Unnamed)");
					   openphylo("$extinct <em>Epidendrosaurus</em>");
					   closephylo("$extinct <em>Epidexipteryx</em>");
					 closeoutgroup();
				       buildendoutgroup("$extinct (Unnamed)");
				         openphylo("$extinct Oviraptorosauria");
					 closephylo("$extinct Therizinosauroidea");
				       closeoutgroup();
                                      buildendoutgroup("$extinct (Unnamed)");
                                      openphylo("$extinct Alvarezsauria");
                                        openphylo("$extinct Alvarezsauridae");
                                          openphylo("$extinct Parvicursorinae");
                                            openphylo("$extinct Mononykini");
                                              openphylo("$extinct <em>Mononykus</em>");
                                              closephylo("$extinct <em>Shuvuuia</em>");
                                            closephylo("$extinct Ceratonykini");
                                          closephylo("$extinct <em>Alvarezsaurus</em>");
                                        buildendoutgroup("$extinct Patagonykinae");
                                          openphylo("$extinct <em>Patagonykus</em>");
                                          closephylo("$extinct <em>Bonapartenykus</em>");
                                        closeoutgroup();
                               	      closephylo("$extinct Ornithomimosauria");
                                      closeoutgroup();
				      closephylo("$extinct Tyrannosauroidea");
				    closephylo("$extinct Compsognathidae");
                              	  buildendoutgroup("$extinct Carnosauria");
				    openphylo("(Unnamed)");
				      openphylo("$extinct Allosauridae");
				      closephylo("$extinct Sinraptoridae");
				    closephylo("$extinct Carcharodontosauridae");
				  closeoutgroup();
			        buildendoutgroup("(Unnamed)");
				  openphylo("$extinct Spinosauroidea");
				  closephylo("$extinct Szechuanosaurus");
				closeoutgroup();
			      closephylo("$extinct Ceratosauria");
			    }
                            buildendoutgroup(cladecollapse("$extinct Sauropodomorpha",10));
			    if ($_REQUEST['10']!="")
			    {
			    //Sauropodomomorpha
			      openphylo("(Unnamed)");
			        openphylo("$extinct Anchisauria");
			          openphylo("$extinct Sauropoda");
			            openphylo("$extinct Eusauropoda");
				      openphylo("$extinct Neosauropoda");
				        openphylo("$extinct Macronaria");
				          openphylo("$extinct Camarasauromorpha");
				            openphylo("(Unnamed)");
					      openphylo("$extinct Titanosauriformes");
					        openphylo("$extinct Titanosauria");
					          openphylo("(Unnamed)");
					            openphylo("$extinct Saltasauridae");
					            closephylo("$extinct Titanosauridae");
					          closephylo("$extinct Malawisaurus");
					        closephylo("$extinct Phuwiangosaurus");
					      buildendoutgroup("$extinct Brachiosauridae");
					        openphylo("$extinct Brachiosaurus");
					        closephylo("$extinct Girraffatitan");
					      closeoutgroup();
					    closephylo("$extinct Camarasauridae");
				          closephylo("$extinct Jobaria");
				        buildendoutgroup("$extinct Diplodocoidea");
				          openphylo("$extinct Flagellicaudata");
				            openphylo("$extinct Dicraeosauridae");
					    closephylo("$extinct Diplodocidae");
				          buildendoutgroup("(Unnamed)");
                                            openphylo("$extinct Nigersaurus");
                                            closephylo("$extinct Rebbachisaurus");
                                          closeoutgroup();
				        closeoutgroup();
				      closephylo("$extinct Mamenchisauridae");
				    closephylo("$extinct Cetiosauridae");
				  buildendoutgroup("(Unnamed)");
				    openphylo("$extinct Anchisauridae");
				    buildoutgroup("$extinct Melanorosaurus");
				    closephylo("$extinct Aardonyx");
				  closeoutgroup();
			        buildendoutgroup("$extinct Prosauropoda");
			          openphylo("$extinct Plateosauridae");
				  closephylo("$extinct Massospondylidae");
			        closeoutgroup();
			      closephylo("$extinct Saturnalia");
			    }
			    closeoutgroup();
			 }
                         buildendoutgroup(cladecollapse("$extinct Ornithischia",11));
			 if ($_REQUEST['11']!="")
			 {
			   openphylo("$extinct Cerapoda");
			     openphylo("$extinct Ornithopoda");
			       openphylo("$extinct Euornithopoda");
			         openphylo("(Unnamed)");
				   openphylo("$extinct Hadrosauridae<tt>duck,billed,duck-billed</tt>");
				   buildoutgroup("$extinct Iguanodon");
				   closephylo("$extinct Ouranosaurus");
				 closephylo("$extinct Hypsilophodontidae");
			       closephylo("$extinct Heterodontosauridae");
			     buildendoutgroup("$extinct Marginocephalia");
			       openphylo("$extinct Pachycephalosauria<tt>pachycephalosaurus</tt>");
			       closephylo("$extinct Ceratopsia<tt>triceratops,styracosaurus,protoceratops</tt>");
			     closeoutgroup();
			   buildendoutgroup("$extinct Thyreophora");
			     openphylo("$extinct Stegosauria<tt>stegosaurus</tt>");
			     closephylo("$extinct Ankylosauria<tt>armor,club</tt>");
			   closeoutgroup();
			 }
			 closeoutgroup();
                        }
			//build collapse here
                        buildendoutgroup(cladecollapse("$extinct Pterosauria",12));
                        if ($_REQUEST['12']!="")
                        {
			  openphylo("(Unnamed)");
			    openphylo("(Unnamed)");
			      openphylo("(Unnamed)");
			        openphylo("(Unnamed)");
				  openphylo("$extinct Pterodactyloidea");
				    openphylo("$extinct Lophocratia");
				      openphylo("$extinct Ctenochasmatoidea");
				        openphylo("(Unnamed)");
					  openphylo("$extinct Pterodactylus");
					  buildoutgroup("$extinct Lonchodectidae");
					  closephylo("$extinct Ctenochasmatidae");
					closephylo("$extinct Cycnorhamphus");
				      buildoutgroup("$extinct Azhdarchoidea");
				        openphylo("(Unnamed)");
					  openphylo("$extinct Tupuxvara");
					  closephylo("$extinct Azhdarchidae");
					closephylo("$extinct Tapejaridae");
				      buildendoutgroup("$extinct Dsungaripteroidea");
				        openphylo("$extinct Germanodactylus");
					closephylo("$extinct Dsungaripteridae");
				      closeoutgroup();
				    buildendoutgroup("$extinct Ornithocheiroidea");
				      openphylo("$extinct Euornithocheiroidea");
				        openphylo("(Unnamed)");
					  openphylo("$extinct Pteranodontidae");
					  closephylo("$extinct Nyctosaurus");
					closephylo("$extinct Ornithocheiridae");
				      closephylo("$extinct Istiodactylidae");
				    closeoutgroup();
				  buildendoutgroup("(Unnamed)");
				    openphylo("$extinct Scaphognathidae");
				    closephylo("$extinct Rhamphorhynchinae");
				  closeoutgroup();
				closephylo("$extinct Campylognathoididae");
			      closephylo("$extinct Anurognathidae");
			    closephylo("$extinct Dimorphodontidae");
			  closephylo("$extinct Preondactylus");
                        }
			closeoutgroup();
		     buildendoutgroup("Crurotarsi");
		     openphylo("(Unnamed)");
                      openphylo(cladecollapse("Crocodylomorpha",13));
                      if ($_REQUEST['13']!="")
                      {
                        openphylo("Crocodylia");
                          openphylo("Mesoeucrocodylia");
                            openphylo("Metasuchia");
                              openphylo("Neosuchia");
                                openphylo("Eusuchia");
                                  openphylo("(Unnamed)");
                                    openphylo("Crocodiles (Crocodylidae)");
                                    closephylo("Gharial and False Gharial (Gavialidae)");
                                  closephylo("Alligators (Alligatoridae)");
                                closephylo("$extinct Bernissartia");
                              buildendoutgroup("(Unnamed)");
                                openphylo("$extinct Goniopholidiae");
                                closephylo("$extinct Dyrosauridae");
                              closeoutgroup();
                            closephylo("$extinct Notosuchidae");
                          closephylo("$extinct Thalattosuchia");
                        closephylo("$extinct Sphenosuchidae");
                     }
		      buildendoutgroup("(Unnamed)");
		        openphylo("$extinct Rauisuchia *");
		        buildoutgroup("$extinct Ornithosuchidae");
			closephylo("$extinct Stagonolepididae");
		     closeoutgroup();
		      closephylo("$extinct Phytosauria");
                     closeoutgroup();
                      // Indenting error
                      closephylo("$extinct Euparkeria");
		      }
		    closephylo("$extinct Prolacertiformes");
		    buildendoutgroup(cladecollapse("Lepidosauromorpha",14));
		    if ($_REQUEST['14']!="")
                    {
                     openphylo(cladecollapse("Lepidosauria",15));
                      if ($_REQUEST['15']!="")
                      {
                         openphylo("Squamata<tt>lizards,snakes</tt>");
                            openphylo(cladecollapse("Scleroglossa",16));
			    if ($_REQUEST['16']!="")
			    {
                               openphylo("Autarchoglossa");
                                 openphylo("Scincomorpha");
                                   openphylo("Lacertoidea");
                                     openphylo("Lacertiformes");
                                       openphylo("Teioidea");
                                         openphylo("Gymnopthalmidae");
                                         closephylo("Teiidae");
                                       closephylo("Larcertidae");
                                     closephylo("Xantusiidae");
                                   buildendoutgroup("Scincoidea");
                                     openphylo("(Unnamed)");
                                       openphylo("Cordylidae");
                                       closephylo("Gerrhosauridae");
                                     closephylo("Scincidae");
                                   closeoutgroup();
                                 buildendoutgroup("Anguimorpha");
                                   openphylo("(Unnamed)");
                                     openphylo("(Unnamed)");
                                       openphylo("Anguidae");
                                       closephylo("Xenosauridae");
                                     closephylo("Shinisauridae");
				   buildoutgroup(" $extinct Mosasauridae");
                                   buildendoutgroup("Varanoidea");
                                     openphylo("(Unnamed)");
                                       openphylo("Monitor Lizards (Varanidae)");
                                       closephylo("Lanthanotidae");
                                     closephylo("Gila Monsters (Helodermatidae)");
                                   closeoutgroup();
                                 closeoutgroup();
                               buildoutgroup(cladecollapse("? Serpentes<tt>snakes</tt>",17));
			       if ($_REQUEST['17']!="")
			       {
                                 openphylo("(Unnamed)");
			           openphylo("Alethinophidea");
			             openphylo("(Unnamed)");
				       openphylo("Macrostomata");
				         //openphylo("(Unnamed)");
				           openphylo("(Unnamed)");
				             openphylo("Caenophidia");
					       openphylo("Colubroidea");
                                                 openphylo("(Unnamed)");
                                                  openphylo("(Unnamed)");
                                                   openphylo("(Unnamed)");
					            openphylo("(Unnamed)");
                                                      openphylo("(Unnamed)");
                                                        openphylo("(Unnamed)");
                                                          openphylo("Atractaspididae");
                                                          closephylo("Boodontidae");
                                                        closephylo("Elapidae");
					              closephylo("Colubridae *");
                                                    closephylo("Homalopsidae");
					           closephylo("Viperidae");
                                                  closephylo("Pareatidae");
                                                 closephylo("Xenodermatidae");
					       closephylo("Acrochordidae");
                                             buildendoutgroup("(Unnamed)");
                                               openphylo("(Unnamed)");
                                                 openphylo("(Unnamed)");
                                                   openphylo("Boidae");
                                                   closephylo("Calabariidae");
                                                 closephylo("Bolyeriidae");
                                               buildoutgroup("(Unnamed)");
                                                 openphylo("(Unnamed)");
                                                   openphylo("Pythonidae");
				                   closephylo("Loxocemidae");
				                 closephylo("Xenopeltidae");
				               closephylo("Uropeltidae");
                                             closeoutgroup();
					   closephylo("Xenophidiidae");
                                       buildendoutgroup("(Unnamed)");
				         openphylo("Aniliidae");
				  	 closephylo("Tropidophiidae");
                                       closeoutgroup();
				     closephylo("Anomochilidae");
				   closephylo("Anomalepididae");
			         buildendoutgroup("Scolecophidea");
			           openphylo("Leptotyphlopidae");
				   closephylo("Typhlopidae");
			         closeoutgroup();

			       }
                               buildoutgroup("? Amphisbaenia");
                               closephylo("Geckos (Gekkonidae)");
			    }
                            buildendoutgroup(cladecollapse("Iguania",18));
			    if ($_REQUEST['18']!="")
			    {
			      openphylo("Iguanidae");
			        openphylo("Corytophaninae");
				buildoutgroup("Crotaphytinae");
				buildoutgroup("Hoplocercinae");
				buildoutgroup("Iguaninae");
				buildoutgroup("Oplurinae");
				buildoutgroup("Phyrnosomatinae");
				buildoutgroup("Polychrotinae");
				closephylo("Tropidurinae<sup>*</sup>");
			      buildendoutgroup("Acrodonta");
			        openphylo("Agamidae<sup>*</sup>");
				closephylo("Chamaeleonidae");
			      closeoutgroup();
			    }
			    closeoutgroup();
                         buildendoutgroup("Rhynchocephalia");
                           singlephylo("Tuatara (Sphenodontidae)");
                         closeoutgroup();
                       }
		       buildendoutgroup(cladecollapse("$extinct Sauropterygia<tt>marine reptile</tt>",19));
		       if ($_REQUEST['19']!="")
		       {
                         openphylo("$extinct Eosauropterygia");
                           openphylo("$extinct Eusauropterygia");
                             openphylo("$extinct Pistosauroidea");
                               openphylo("$extinct Pistosauria");
		                 openphylo("$extinct Plesiosauria");
  			           openphylo("$extinct Pliosauroidea");
                                     openphylo("$extinct Pliosauridae");
                                     closephylo("$extinct Rhomaleosauridae");
	  		           buildendoutgroup("$extinct Plesiosauroidea");
                                     openphylo("$extinct Euplesiosauria");
                                       openphylo("$extinct Cryptocleidoidea");
                                         openphylo("$extinct Tricleidia");
                                           openphylo("$extinct Polycotylidae");
                                           closephylo("$extinct Cimoliasauridae");
                                         closephylo("$extinct Cryptoclididae");
                                       closephylo("$extinct Elasmosauridae");
                                     closephylo("$extinct Plesiosauridae");
                                   closeoutgroup();
                                 closephylo("$extinct Pistosauridae");
                               closephylo("$extinct Corosaurus");
			     closephylo("$extinct Nothosauria");
			   closephylo("$extinct Pachypleurosauria");
			 buildoutgroup("? $extinct Placodontia");
			 closephylo("? $extinct Thalattosauriformes");
		       }
		       closeoutgroup();
		     }
                     closeoutgroup();
                    closephylo("? $extinct Icthyosauria");
                   closephylo("$extinct Araeoscelidia");
                    buildendoutgroup("? Anapsida");
                    //end untabbed
		      openphylo("Parareptilae");
		        openphylo("(Unnamed)");
		          openphylo("Procolophonia");
			      openphylo("(Unnamed)");
			        openphylo(cladecollapse("? Testudines<tt>turtle,tortoise</tt>",20));
				if ($_REQUEST['20']!="")
				  {
		                  openphylo("Casichelydia");
				    openphylo("Cryptodira");
				      openphylo("Testudinoidea");
				        openphylo("Testudinoidae");
					  openphylo("Testudinidae");
					  closephylo("Bataguridae");
					closephylo("Emydidae");
				      buildoutgroup("Trionychoidae");
				        openphylo("Trionychidae");
					closephylo("Carettochelyidae");
				      buildoutgroup("Kinosternoidae");
				        openphylo("Dermatemydidae");
					closephylo("Kinosternidae");
				      buildoutgroup("Chelonioidea");
				        openphylo("Cheloniidae");
					closephylo("Dermochelidae");
				      closephylo("Cheyldridae");
				    buildendoutgroup("Pluerodira");
				      openphylo("(Unnamed)");
				        openphylo("Pelomedusidae");
					closephylo("Podocnemidae");
				      closephylo("Chelidae");
				    closeoutgroup();
				  closephylo("$extinct Proganochelydae");
				}
			       closephylo("$extinct Pareiasauridae"); //closing testudines
			     closephylo("$extinct Procolophonidae");
			   closephylo("$extinct Bolosauridae");
			 closephylo("$extinct Millerettidae");
		       closephylo("$extinct Mesosauridae");
		       closeoutgroup(); // close sauropsida
                  }
                  buildendoutgroup(cladecollapse("Synapsida",21));
		  if ($_REQUEST['21']!="")
		  {
		    openphylo("(Unnamed)");
		    openphylo("(Unnamed)");
		    openphylo("(Unnamed)");
		    openphylo("(Unnamed)");
		    openphylo("Therapsida");
		    openphylo("(Unnamed)");
		    openphylo("(Unnamed)");
		    openphylo("Theriodontia");
		    openphylo("(Unnamed)");
                     openphylo("Cynodontia");
                       openphylo(cladecollapse("Mammalia",22));
                       if ($_REQUEST['22']!="")
                       {
                         openphylo("Theria");
                           openphylo(cladecollapse("Eutheria<tt>epitheria</tt>",23));
                           if ($_REQUEST['23']!="")
                            {
                           openphylo("Placentalia");
                             openphylo(cladecollapse("Boreoeutheria",24));
			     if ($_REQUEST['24']!="")
			     {
                                 openphylo(cladecollapse("Laurasiatheria",25));
				 if ($_REQUEST['25']!="")
				 {
                                   openphylo("(Unnamed)");
                                     openphylo("Ferungulata");
                                       openphylo(cladecollapse("Cetartiodactyla",26));
                                       if ($_REQUEST['26']!="")
                                       {      
                                         openphylo("Selenodontia");
                                           openphylo("Neoselenodontia");
                                             openphylo("Tylopoda");
                                               openphylo(cladecollapse("Cetacea",27));
                                               if ($_REQUEST['27']!="")
                                               {
                                               //Cetacea
                                                 openphylo("Archaeoceti");
                                                   openphylo("(Unnamed)");
                                                     openphylo("(Unnamed)");
                                                       openphylo("(Unnamed)");
                                                        openphylo("(Unnamed)");
                                                           openphylo("(Unnamed)");
                                                            openphylo("(Unnamed)");
                                                               openphylo("Autoceta");
                                                                 openphylo("Mysticeti");
                                                                  openphylo("(Unnamed)");
                                                                    openphylo("(Unnamed)");
                                                                      openphylo("Balaenopteridae");
                                                                      closephylo("Eschrichtidae");
                                                                    closephylo("Neobalaenidae");
                                                                  closephylo("Balaenidae");
                                                                 buildendoutgroup("Odontoceti");
                                                                   openphylo("(Unnamed)"); 
                                                                     openphylo("Delphinida");
                                                                       openphylo("(Unnamed)");
                                                                         openphylo("Delphinoidea");
                                                                           openphylo("(Unnamed)");
                                                                             openphylo("Delphinidae");
                                                                             closephylo("Phocoenidae");
                                                                           closephylo("Monodontidae");
                                                                         closephylo("Iniidae");
                                                                       buildendoutgroup("Platanistoidea");
                                                                         singlephylo("Platanistidae");
                                                                       closeoutgroup();
                                                                     buildendoutgroup("Ziphoidea");
                                                                       singlephylo("Ziphiidae");
                                                                     closeoutgroup();
                                                                   buildendoutgroup("Physeteroidea");
                                                                     singlephylo("Physeteridae");
                                                                   closeoutgroup();
                                                                 closeoutgroup();
                                                               closephylo("$extinct Basilosauridae");
                                                             closephylo("$extinct Dorudon");
                                                           closephylo("$extinct Protocetus");
                                                         closephylo("$extinct Cross Whale");
                                                       closephylo("$extinct Remingtonocetus");
                                                     closephylo("$extinct Ambulocetus");
                                                   closephylo("$extinct Pakicetus");
                                                closephylo("$extinct Nalacetus");
                                               }
                                               closephylo("Hippos (Hippopotamidae)");
                                             buildendoutgroup("Ruminantia");
                                               openphylo("Pecora");
                                                 openphylo("Cervoidea");
                                                   openphylo("(Unnamed)");
                                                     openphylo("(Unnamed)");
                                                       openphylo("Deer (Cervidae)");
                                                       closephylo("Musk Deer (Moschidae)");
                                                     closephylo("Cows, antelope, etc. (Bovidae)");
                                                   closephylo("Giraffes (Giraffidae)");
                                                 closephylo("Pronghorn (Antilocapridae)");
                                               closephylo("Chevrotains (Tragulidae)");
                                             closeoutgroup();
                                           closephylo("Camels, Lama, and alpacas (Camelidae)");
                                         buildendoutgroup("Suina");
                                           openphylo("Pigs (Suidae)");
                                           closephylo("Peccaries (Tayassuidae)");
                                         closeoutgroup();
                                       }
                                       buildendoutgroup("(Unnamed)");
                                         openphylo("(Unnamed)");
                                           openphylo(cladecollapse("Carnivora",28));
					   if ($_REQUEST['28']!="")
					   {
					   //Carnivora
					     openphylo("Caniformia<tt>dogs</tt>");
					       openphylo("Arctoidea<tt>bears</tt>");
					         openphylo("(Unnamed)");
						   openphylo("Musteloidea");
						     openphylo("(Unnamed)");
                                                       openphylo("(Unnamed)");
						         openphylo("Racoons (Procyonidae)");
						         closephylo("Weasels and Otters (Mustelidae)");
                                                       closephylo("Skunks (Mephitidae)");
						     closephylo("Red Panda (Ailuridae)");
						   buildendoutgroup("Pinnipedia");
						     openphylo("Otarioidea");
						       openphylo("Sea Lions (Otariidae)");
						       closephylo("Walruses (Odobenidae)");
						     closephylo("Seals (Phocidae)");
						   closeoutgroup();
						 closephylo("Ursidae<tt>bears</tt>");
					       closephylo("Canidae<tt>dogs</tt>");
					     buildendoutgroup("Feliformia<tt>cats</tt>");
                                               openphylo("(Unnamed)");
					         openphylo("(Unnamed)");
					           openphylo("(Unnamed)");
                                                     openphylo("(Unnamed)");
						       openphylo("Mongooses and meerkats (Herpestidae)");
                                                       closephylo("Madagascar mongooses (Eupleridae)");
						     closephylo("Hyenas (Hyaenidae)");
					           closephylo("Civets (Vivveridae)");
					         closephylo("Felidae<tt>cats</tt>");
                                               closephylo("African Palm Civet (Nandiniidae)");
					     closeoutgroup();
					   }
                                           buildendoutgroup("Pholidota");
                                             singlephylo("Pangolins (Manidae)");
                                           closeoutgroup();
                                         buildendoutgroup(cladecollapse("Perissodactyla",29));
                                         if ($_REQUEST['29']!="")
                                         {
                                         //Perissodactyls
                                           openphylo("Lophodontomorpha");
                                             openphylo("Hippomorpha");
                                               singlephylo("Equidae<tt>horse</tt>");
                                             buildendoutgroup("Tapirmorpha");
                                               openphylo("Ceratomorpha");
                                                 openphylo("Rhinoceros (Rhinocerotidae)");
                                                 closephylo("Tapiridae");
                                               closephylo("$extinct Chalicotheriidae");
                                             closeoutgroup();
                                           closephylo("? $extinct Brontotheroidea");
                                         }
                                         closeoutgroup();
                                       closeoutgroup();
                                     buildendoutgroup(cladecollapse("Chiroptera<tt>bats</tt>",30));
                                     if ($_REQUEST['30']!="")
                                     {
                                     //Bats
                                       openphylo("Microchiroptera");
                                         openphylo("Yangchiroptera");
                                          openphylo("(Unnamed)");
                                           buildoutgroup("(Unnamed)");
                                             openphylo("(Unnamed)");
                                               openphylo("(Unnamed)");
                                                 openphylo("Leaf-nosed bats (Phyllostomidae)");
                                                 closephylo("Naked-backed bats (Mormoopidae)");
                                               closephylo("Fishing bats (Noctilionidae)");
                                             closephylo("Short-tailed bats (Mystacinidae)");
                                           buildoutgroup("(Unnamed)");
                                             openphylo("(Unnamed)");
                                               openphylo("(Unnamed)");
                                                 openphylo("Funnel-eared bats (Natalidae)");
                                                 closephylo("Smoky bats (Furipteridae)");
                                               closephylo("Disk-winged bats (Thyropteridae)");
                                             closephylo("Sucker-footed bat (Myzopodidae)");
                                           buildoutgroup("(Unnamed)");
                                             openphylo("Free-tailed bats (Molossidae)");
                                             closephylo("Vespertilionidae");
                                           closephylo("Sac-winged bats (Emballonuridae)");
                                         buildendoutgroup("Yinchiroptera");
                                           openphylo("(Unnamed)");
                                             openphylo("(Unnamed)");
                                               openphylo("(Unnamed)");
                                                 openphylo("Horseshoe bats (Rhinolophidae)");
                                                 closephylo("Leaf-nosed and roundleaf bats (Hipposideridae)");
                                               closephylo("False vampire bats (Megadermatidae)");
                                             closephylo("Slit-faced bats (Nycteridae)");
                                           buildendoutgroup("(Unnamed)");
                                             openphylo("Hog-nosed bat (Craseonycteridae)");
                                             closephylo("Mouse-tailed bats (Rhinopomatidae)");
                                           closeoutgroup();
                                         closeoutgroup();
                                       buildendoutgroup("Megachiroptera");
                                         singlephylo("Fruit Bats (Pteropodidae)");
                                       closeoutgroup();
                                     }
                                     closeoutgroup();
                                   buildendoutgroup(cladecollapse("Lipotyphla",31));
                                   if ($_REQUEST['31']!="")
                                   {
                                   // Insectivores
                                     openphylo("Soricomorpha");
                                       openphylo("(Unnamed)");
                                         openphylo("Erinaceomorpha");
                                           singlephylo("Hedgehogs and Gymnures (Erinaceidae)");
                                         closephylo("Shrews (Soricidae)");
                                       closephylo("Moles, shew-moles, and desmans (Talpidae)");
                                     closephylo("Solenodons (Solenodontidae)");
                                   }
                                   closeoutgroup();
				 }
                                 buildendoutgroup(cladecollapse("Euarchontoglires",32));
				 if ($_REQUEST['32']!="")
				 {
                                   openphylo("Archonta");
                                     openphylo("(Unnamed)");
                                       openphylo("Scandentia");
                                         openphylo("Tree-shrews (Tupaiidae)");
                                         closephylo("Pen-tailed tree-shrews (Ptilocercidae)");
                                       buildendoutgroup("Dermoptera");
                                         singlephylo("Colugos / \"Flying Lemurs\" (Cynocephalidae)");
                                       closeoutgroup();
                                     buildendoutgroup(cladecollapse("Primates",33));
                                     if ($_REQUEST['33']!="")
                                     {
                                     //Primates
                                       openphylo("(Unnamed)");
                                         openphylo("Haplorhini");
                                           openphylo("(Unnamed)");
                                             openphylo("Simiiformes<tt>monkey,monkeys,anthropoidea,simians</tt>");
                                               openphylo(cladecollapse("Catarrhini",34));
                                               if($_REQUEST['34']!="")
                                               {
                                                 openphylo("Hominoidea<tt>apes</tt>");
                                                   openphylo("Great Apes (Hominidae)");
                                                     openphylo("Homininae");
                                                       openphylo("(Unnamed)");
                                                         openphylo(cladecollapse("Homonini",35));
                                                         if($_REQUEST['35']!="")
                                                         {
                                                           openphylo("(Unnamed)");
                                                             openphylo("(Unnamed)");
                                                               openphylo("(Unnamed)");
                                                                 openphylo("Homo<tt>humans</tt>");
                                                                   openphylo("(Unnamed)");
                                                                     openphylo("(Unnamed)");
                                                                       openphylo("(Unnamed)");
                                                                         openphylo("Modern Humans (H. sapiens)<tt>homo</tt>");
                                                                           //Find authority: h. idaltu, h. floresiensis
                                                                         closephylo("$extinct Neanderthals (H. neanderthalensis)<tt>homo</tt>");
                                                                       closephylo("$extinct H. ergaster<tt>homo</tt>");
                                                                     closephylo("$extinct H. rudolfensis<tt>homo</tt>");
                                                                   closephylo("$extinct H. habilis<tt>homo</tt>");
                                                                 buildendoutgroup("(Unnamed)");
                                                                   openphylo("$extinct Kenyanthropus");
                                                                   closephylo("$extinct Paranthropus");
                                                                 closeoutgroup();
                                                               closephylo("$extinct Australopithecus");
                                                             closephylo("$extinct Praeanthropus");
                                                           closephylo("$extinct Ardipithecus");
                                                         closephylo("Chimps and Bonobos (Pan)<tt>troglodytes</tt>");
                                                       }
                                                       closephylo("Gorillas (Gorilla)");
                                                     closephylo("Orang-utans (Ponginae)<tt>orangutan</tt>");
                                                   closephylo("Gibbons (Hylobatidae)");
                                                 closephylo("Old World monkeys (Cercopithecoidea)");
                                               }
                                               buildendoutgroup("New World monkeys (Platyrrhini)");
                                                 openphylo("(Unnamed)");
                                                   openphylo("(Unnamed)");
                                                     openphylo("Pitheciidae");
                                                     closephylo("Atelidae");
                                                   closephylo("Cebidae");
                                                 closephylo("Aotidae");
                                               closeoutgroup();
                                             buildendoutgroup("Tarsiiformes");
                                               singlephylo("Tarsiers (Tarsiidae)");
                                             closeoutgroup();
                                           closephylo("$extinct Omomyidae");
                                         buildendoutgroup("Strepsirrhini");
                                           openphylo("(Unnamed)");
                                             openphylo("Lemuriformes");
                                               openphylo("Lemuroidea");
                                                 openphylo("(Unnamed)");
                                                   openphylo("Lemurs (Lemuridae)");
                                                   closephylo("Sportive lemurs (Lepilemuridae)");
                                                 closephylo("Indriidae");
                                               buildendoutgroup("Cheirogaleoidea");
                                                 singlephylo("Dwarf and mouse lemurs (Cheirogaleoidae)");
                                               closeoutgroup();
                                             buildoutgroup("Lorisiformes");
                                               openphylo("Lorises (Lorisidae)");
                                               closephylo("Galagos (Galagidae)");
                                             closephylo("? Aye-aye (Daubentoniidae)");
                                           closephylo("$extinct Adapiformes");
                                         closeoutgroup();
                                       closephylo("$extinct Plesiadapiformes");
                                     }
                                     closeoutgroup();
                                   buildendoutgroup(cladecollapse("Glires",36));
                                   if ($_REQUEST['36']!="")
                                   {
                                     openphylo(cladecollapse("Rodentia",37));
                                     if ($_REQUEST['37']!="")
                                     {
                                     //Rodents
                                       openphylo("(Unnamed)");
				         openphylo("(Unnamed)");
					   openphylo("Myodonta");
					     openphylo("(Unnamed)");
					       openphylo("Mice and Rats (Muridae)");
					       closephylo("Mole Rats (Spalacidae)");
					     closephylo("Jerboas (Dipodidae)");
					   buildendoutgroup("(Unnamed)");
					     openphylo("(Unnamed)");
					       openphylo("Geomyoidea");
					         openphylo("Pocket gophers (Geomyidae)");
						 closephylo("Pocket mice (Heteromyidae)");
					       closephylo("Beavers (Castoridae)");
					     buildendoutgroup("Anomoluromorpha");
					       openphylo("Springhare (Pedetidae)");
					       closephylo("Scaly-tailed flying squirrels (Anomoluridae)");
					     closeoutgroup();
					   closeoutgroup();
					 buildendoutgroup("Ctenohystrica");
					   openphylo("Hystricognathi");
					     openphylo("(Unnamed)");
					       openphylo("(Unnamed)");
					         openphylo("Phiomorpha");
						   openphylo("Cane rats (Thryonomyidae)");
						   closephylo("Dassie Rat (Petromuridae)");
						 closephylo("Dune mole rats (Bathyergidae)");
					       buildendoutgroup("(Unnamed)");
					         openphylo("Caviomorpha");
						   openphylo("(Unnamed)");
						     openphylo("New-world porcupines (Erethizontidae)");
						     closephylo("Chinchillas (Chinchillidae)");
						   closephylo("Guinea Pigs, Capybaras, etc. (Caviidae)");
						 closephylo("Spiny-tailed rat (Echimyidae)");
					       closeoutgroup();
					     closephylo("Long-tailed porcupine (Hystricidae)");
					   closephylo("Gundi (Ctenodactylidae)");
					 closeoutgroup();
                                       buildendoutgroup("Sciurimorpha");
				         openphylo("(Unnamed)");
				           openphylo("Squirrels (Sciuridae)");
					   closephylo("Mountain beavers (Aplodontidae)");
					 closephylo("Dormice (Gliridae)");
				       closeoutgroup();
                                     }
                                     buildendoutgroup("Rabbits (Lagomorpha)");
                                       openphylo("Rabbits and Hares (Leporidae)");
                                       closephylo("Pikas (Ochotonidae)");
                                     closeoutgroup();
                                   }
                                   closeoutgroup();
				 }
                                 closeoutgroup();
				}
                             buildendoutgroup("Atlantogenata");
///////////////////Begin inside Atlantogenata
                             openphylo(cladecollapse("Afrotheria",38));
			     if ($_REQUEST['38']!="")
			     {
                               openphylo("Paenungulata");
                                 openphylo("Tethytheria");
                                   openphylo("Hyracoidea");
                                     singlephylo("Hyraxes (Procaviidae)");
                                   buildendoutgroup("Sirenia");
                                     openphylo("Manatees (Trichechidae)");
                                     closephylo("Dugong (Dugongidae)");
                                   closeoutgroup();
                                 buildendoutgroup("Proboscidea");
                                   openphylo("Elephantiformes");
                                     singleoutgroup("Elephantoidea");
                                       openphylo("Euelephantoidea");
                                         openphylo("(Unnamed)");
                                           openphylo("Elephants (Elephantidae)<tt>mammoths</tt>");
                                           closephylo("$extinct Gomphotheriidae");
                                         closephylo("$extinct Ambelodontidae");
                                       closephylo("$extinct Mastodons (Mammutidea)");
                                     closeoutgroup();
                                   closephylo("$extinct Deinotheriidae");
                                 closeoutgroup();
                               buildendoutgroup("(Unnamed)");
			         openphylo("(Unnamed)");
				   openphylo("Tenrecoidea");
				     singleoutgroup("Afrosoricida");
				       openphylo("Tenrecs (Tenrecidae)");
				       closephylo("Golden Moles (Chrysochloridae)");
				     closeoutgroup();
				   buildendoutgroup("Tubulidentata");
				     singlephylo("Aardvarks (Orycteropodidae)");
				   closeoutgroup();
				 buildendoutgroup("Macroscelidea");
				   singlephylo("Elephant Shrews (Macroscelididae)");
				 closeoutgroup();
			       closeoutgroup();
			     }
                             buildendoutgroup(cladecollapse("Xenarthra",39));
                             if ($_REQUEST['39']!="")
                             {
                               openphylo("Armadillos (Cingulata)");
                                 openphylo("(Unnamed)");
                                   openphylo("(Unnamed)");
                                     openphylo("$extinct Glyptodontidae");
                                     closephylo("$extinct Pampatheriidae");
                                   closephylo("Extant Armadillos (Dasypodidae)");
                                 closephylo("$extinct Peltephilidae");
                               buildendoutgroup("Pilosa");
                                 openphylo("Sloths (Phyllophaga)<tt>Folivora</tt>");
                                   openphylo("Folivora");
                                     openphylo("(Unnamed)");
                                       openphylo("(Unnamed)");
                                         openphylo("$extinct Megatheriidae");
                                         closephylo("$extinct Nothrotheriidae");
                                       closephylo("Two-toed sloths (Megalonychidae)");
                                     buildendoutgroup("(Unnamed)");
                                       openphylo("$extinct Scelidotheriidae");
                                       closephylo("$extinct Mylodontidae");
                                     closeoutgroup();
                                   closephylo("Three-toed sloths (Bradypodidae)");
                                 buildendoutgroup("Anteaters (Vermilingua)");
                                   openphylo("True Anteaters (Myrmecophagidae)");
                                   closephylo("Silky Anteater (Cyclopedidae)");
                                 closeoutgroup();
                               closeoutgroup();
                             }
                             closeoutgroup();
////////////// End Inside Unnamed
                             closeoutgroup(); // Unnamed
                            closephylo("$extinct Eomaia");
                           }
                           buildendoutgroup(cladecollapse("Metatheria",40));
			   if ($_REQUEST['40']!="")
			   {
                          openphylo("Marsupialia<tt>marsupial</tt>");
                            openphylo("Ameridelphia");
                              openphylo("Didelphimorphia");
                                singleoutgroup("Didelphoidea");
                                  singlephylo("Opossums (Didelphidae)");
                                closeoutgroup();
                              buildendoutgroup("Paucituberculata");
                                singlephylo("Shew Opossums (Caenolestidae)");
                              closeoutgroup();
                            buildendoutgroup(cladecollapse("Australidelphia",41));
                            if($_REQUEST['41']!="")
                            {
                              openphylo("(Unnamed)");
                                openphylo("Dasyuromorphia");
                                  openphylo("Tasmanian Devil and relatives (Dasyuridae)");
                                  buildoutgroup("Numbat (Myrmecobiidae)");
                                  closephylo("$extinct Tasmanian Wolf (Thylacinidae)");
                                buildoutgroup("Peramelemorphia");
                                  singleoutgroup("Perameloidea");
                                    openphylo("Bandicoots (Peramelidae)");
                                    closephylo("Bilbies (Thylacomyidae)");
                                  closeoutgroup();
                                buildoutgroup("Notoryctemorphia");
                                  singlephylo("Marsupial moles (Notoryctidae)");
                                buildendoutgroup("Microbiotheria");
                                  singlephylo("Monito del Monte (Microbiotheriidae)");
                                closeoutgroup();
                              buildendoutgroup("Diprotodontia");
                                openphylo("(Unnamed)");
                                  openphylo("Phalangeriformes<sup>*</sup>");
                                    openphylo("Feather-tailed glider and possum (Acrobatidae)");
                                    buildoutgroup("Pygmy possums (Burramyidae)");
                                    buildoutgroup("Ringtail possums (Pseudocheiridae)");
                                    buildoutgroup("Petauridae");
                                    closephylo("Possums (Phalangeridae)");
                                  buildoutgroup("Vombatiformes");
                                    openphylo("Wombats (Vombatidae)");
                                    closephylo("Koalas (Phascolarctidae)");
                                  closephylo("Noolbenger (Tarsipedidae)");
                                buildendoutgroup("Macropodiformes");
                                  openphylo("(Unnamed)");
                                    openphylo("Musky rat-kangaroo (Hypsiprymnodontidae)");
                                    closephylo("Potoroos and bettongs (Potoroidae)");
                                  closephylo("Kangaroos and wallabies (Macropodidae)");
                                closeoutgroup();
                              closeoutgroup();
                            }
                            closeoutgroup();
                          closephylo("$extinct Deltatheroida");
			   }
                           closeoutgroup();
			 
                         buildendoutgroup("Prototheria<tt>monotremata,monotreme</tt>");
                           openphylo("Platypus (Ornithorynchidae)");
                           closephylo("Echidnas (Tachyglossidae)");
                         closeoutgroup();
			 }
                       closephylo("$extinct Tritheledontidae");
                     closephylo("$extinct Therocephalia");
		     //
		     closephylo("$extinct Gogonopsia<tt>gorgonopsid,gorgonopsids,gorgonopsida</tt>");
		     closephylo("$extinct Dicynodontia");
		     closephylo("$extinct Dinocephalia");
		     closephylo("$extinct Biarmosuchia");
		     closephylo("$extinct Sphenacodontidae");
		     closephylo("$extinct Edaphosauridae<tt>dimetrodon,pelycosaur</tt>");
		     closephylo("$extinct Ophiacodontidae");
		     closephylo("$extinct Varanopseidae");
		     buildendoutgroup("(Unnamed)");
		       openphylo("$extinct Eothyrididae");
		       closephylo("$extinct Caseidae");
		     closeoutgroup();
		  }
                  closeoutgroup();
		}
               // ROO\T_JUMP 1
               buildendoutgroup(cladecollapse("Lissamphibia<tt>amphibians</tt>",42));
	       if ($_REQUEST['42']!="")
	       {
                 openphylo("Salientia");
                 //begin untabbed
                 openphylo("Batrachia");
                   openphylo(cladecollapse("Frogs (Anura)",43));
		   if ($_REQUEST['43']!="")
		   {
		   //Frogs
		     openphylo("Leiopelmatanura");
		       openphylo("Bombinanura");
		         openphylo("Discoglossanura");
			   openphylo("Pipanura");
			     openphylo(cladecollapse("Neobatrachia",44));
			     if ($_REQUEST['44']!="")
			     {
			     //neobatrachia
			       openphylo("Ranoidea");
			         openphylo("(Unnamed)");
				   openphylo("Ranidae<sup>*</sup>");
				   buildoutgroup("Rhacophoridae");
				   closephylo("Mantellidae");
				 buildoutgroup("Arthroleptidae");
				 buildoutgroup("Dendrobatidae");
				 buildoutgroup("Hemisotidae");
				 buildoutgroup("Microhylidae");
				 buildoutgroup("Hyperoliidae");
				 closephylo("Petropedetidae");
			       buildoutgroup("(Unnamed)");
			         openphylo("Myobatrachidae");
				 closephylo("Sooglossidae");
			       buildoutgroup("(Unnamed)");
			         openphylo("(Unnamed)");
				   openphylo("Allophrynidae");
				   closephylo("Centrolenidae");
				 buildendoutgroup("(Unnamed)");
				   openphylo("Hylidae<sup>*</sup>");
				   closephylo("Pseudidae");
				 closeoutgroup();
			       buildoutgroup("Brachycephalidea");
			       buildoutgroup("Bufonidae");
			       buildoutgroup("Helophyrnidae");
			       buildoutgroup("Leptodactylidae<sup>*</sup>");
			       closephylo("Rhinodermatidae");
			     }
			     buildendoutgroup("Mesobatrachia");
			       openphylo("Pelobatoidea");
			         openphylo("(Unnamed)");
				   openphylo("Pelodytidae");
				   closephylo("Pelobatidae");
				 closephylo("Megophryidae");
			       buildendoutgroup("Pipoidea");
			         openphylo("Pipidae");
				 closephylo("Rhinophrynidae");
			       closeoutgroup();
			     closeoutgroup();
			   closephylo("Discoglossidae");
			 closephylo("Bombinatoridae");
		       closephylo("Leiopelmatidae");
		     closephylo("Ascaphidae");
		   }
                   buildendoutgroup(cladecollapse("Salamanders (Urodela)",45));
		   if ($_REQUEST['45']!="")
		   {
		   //Salamanders
		     openphylo("Neocaudata");
		       openphylo("Salamandroidea");
		         openphylo("(Unnamed)");
			   openphylo("(Unnamed)");
			     openphylo("(Unnamed)");
			       openphylo("(Unnamed)");
			         openphylo("Ambystomatidae");
				 closephylo("Dicamptodontidae");
			       closephylo("Salamandridae");
			     closephylo("Proteidae");
			   closephylo("Rhyacotritonidae");
			 buildendoutgroup("(Unnamed)");
			   openphylo("Amphiumidae");
			   closephylo("Plethodontidae");
			 closeoutgroup();
		       buildendoutgroup("Cryptobranchoidea");
		         openphylo("Cryptobranchidae");
			 closephylo("Hynobiidae");
		       closeoutgroup();
		     closephylo("Sirenidae");
		   }
		   closeoutgroup();
                 buildendoutgroup(cladecollapse("Cacaelians (Gymnophiona)",46));
		 if ($_REQUEST['46']!="")
		 {
		 //gymnophiona
		   openphylo("(Unnamed)");
		     openphylo("(Unnamed)");
		       openphylo("Scolecomorphidae");
		       buildoutgroup("Caeciliidae<sup>*</sup>");
		       closephylo("Typhlonectidae");
		     buildendoutgroup("(Unnamed)");
		       openphylo("Ichtyophiidae");
		       closephylo("Uraeotyphlidae");
		     closeoutgroup();
		   closephylo("Rhinaterematidae");
		 }
		 closeoutgroup();
               // end uncorrected tabs
                closephylo("$extinct Albanerpetontidae");
	       }
               closeoutgroup();
	      closephylo("$extinct <em>Tulerpeton</em>");
		      closephylo("$extinct <em>Ichthyostega</em>");
		      closephylo("$extinct <em>Acanthostega</em>");
	    buildendoutgroup("(Unnamed)"); // closing tetrapods
		      openphylo("$extinct <em>Ventastega</em>");
		      closephylo("$extinct <em>Metaxygnathus</em>");
	    closeoutgroup();
	     }
	
            closephylo("Lungfishes (Dipnoi)");
         buildendoutgroup(cladecollapse("Ray-finned Fishes (Actinopterygii)",47));
	 if ($_REQUEST['47']!="")
	 {
           openphylo("Neopterygii");
             openphylo("Teleostei<sup>*</sup>");
               openphylo("Elopocephala");
                 openphylo("Clupeocephala");
                   openphylo("Euteleostei");
                     openphylo("Neognathi");
                     closephylo("Salmon and relatives (Salmoniformes)");
                   closephylo("Otocephala");
                 closephylo("Eels and relatives (Elopomorpha)");
               closephylo("Osteoglossomorpha");
             closephylo("Pachycormidae");
           closephylo("Cheirolepididae");
	 }
         closeoutgroup();
      buildendoutgroup("Cartilaginous 'Fishes' (Chondrichthyes)");
        openphylo("Sharks and Rays (Neoselachii)");
        closephylo("Holocephali");
      closeoutgroup();
   closephylo("Jawless 'Fishes' (Agnatha)");
closephylo("Tunicata");
closephylo("Sea stars, urchins, and cucumbers (Echinodermata)");
buildendoutgroup("Protostomes");
   openphylo("Ecdysozoa");
     openphylo("Arthropoda");
       openphylo(cladecollapse("Hexapoda",48));
       if ($_REQUEST['48']!="")
       {
         openphylo("(Unnamed)");
           openphylo("(Unnamed)");
             openphylo("Insecta (Entognatha)<tt>insects</tt>");
               openphylo("Dicondylia");
                 openphylo("Pterygota");
                   openphylo("(Unnamed)");
                     openphylo(cladecollapse("Neoptera",49));
                     if ($_REQUEST['49']!="")
                     {
                       openphylo("Endopterygota");
                         openphylo("(Unnamed)");
                           openphylo("Coleoptera<tt>beetle,beetles</tt>");
                           buildendoutgroup("Neuropterida");
                             openphylo("Lacewings (Neuroptera)");
                             buildoutgroup("Dobsonflies (Megaloptera)");
                             closephylo("Snakeflies (Raphidioptera)");
                           closeoutgroup();
                         buildoutgroup("(Unnamed)");
                           openphylo("Hymenoptera<tt>bee,ant,wasp</tt>");
                           buildendoutgroup("(Unnamed)");
                             openphylo("Amphiesmenoptera");
                               openphylo("Lepidoptera<tt>butterfly,butterflies,moth</tt>");
                               closephylo("Caddisflies (Trichoptera)");
                             buildendoutgroup("Antliophora");
                               openphylo("(Unnamed)");
                                 openphylo("Fleas (Siphonaptera)");
                                 closephylo("Scorpionflies (Mecoptera)*");
                               closephylo("Flies (Diptera)");
                             closeoutgroup();
                           closeoutgroup();
                         closephylo("Twisted-wing parasites (Strepsiptera)");
                       buildoutgroup("Paraneoptera");
                         openphylo("Condylognatha");
                           openphylo("Hemiptera");
                           closephylo("Thrips (Thysanoptera)");
                         buildendoutgroup("Psocodea");
                           openphylo("Bark lice (Psocoptera)*");
                           closephylo("Lice (Phthiraptera)");
                         closeoutgroup();
		       //extra indent from collapsed node
                         buildendoutgroup("Polyneoptera");
                           openphylo("Dictyoptera");
                             openphylo("(Unnamed)");
                               openphylo("Termites (Isoptera)");
                               closephylo("Cockroaches (Blattodea)");
                             closephylo("Mantids (Mantodea)");
			   buildendoutgroup("Anartioptera");
			      openphylo("Polyplecoptera");
			       singleoutgroup("Plecopterida");
				 openphylo("Mystroptera");
				   openphylo("Zoroptera");
				     singlephylo("Zorotypidae");
				   closephylo("Webspinners (Embiidina)");
				 closephylo("Stoneflies (Plecoptera)");
			       closeoutgroup();
  			     buildendoutgroup("Polyorthoptera");
			       openphylo("Dermapterida");
				 singlephylo("Earwigs (Dermaptera)");
			       buildendoutgroup("Orthopterida");
			         openphylo("Notopterodea");
				   singleoutgroup("Notoptera");
				     openphylo("Rock Crawlers (Grylloblattodea)");
				       singlephylo("Grylloblattidae");
				     buildendoutgroup("Gladiators (Mantophasmatodea)");
				       singlephylo("Mantophasmatidae");
				     closeoutgroup();
				   closeoutgroup();
			         buildendoutgroup("Panorthoptera");//
			           openphylo("Holophasmatodea");
			             singlephylo("Walking Sticks (Phasmatodea)");
			           closephylo("Grasshoppers, Crickets, and Katydids (Orthoptera)");
			         closeoutgroup();
			       closeoutgroup();
			     closeoutgroup();
			   closeoutgroup();
			   //extra indent from collapsed node
                       closeoutgroup();
                     }
                     closephylo("Dragonflies (Odonata)");
                   closephylo("Mayflies (Ephemeroptera)");
                 closephylo("Silverfish (Zygentoma)");
               closephylo("Bristletails (Archaeognatha)");
             closephylo("Diplura");
           closephylo("Springtails (Collembola)");
         closephylo("Protura");
       }
       buildoutgroup("Chelicerata");
         openphylo("Arachnida<tt>spiders,scorpions</tt>");
         closephylo("Xiphosura");
       buildoutgroup("Myriapoda<tt>centipedes,millipedes</tt>");
       buildoutgroup("Crustacea<tt>crustacean,shrimp,lobster,crab</tt>");
       closephylo("$extinct Trilobitomorpha");
     closephylo("Roundworms (Nematoda)");
   buildendoutgroup("Lophotrochozoa");
     openphylo("(Unnamed)");
       openphylo("Snails, clams, squid, etc (Mollusca)<tt>octopus,oyster</tt>");
       closephylo("Segmented worms (Annelida)");
     closephylo("Flatworms and relatives (Platyzoa)");
   closeoutgroup();
/*
END_PHYLO_BREAK
*/
?>
<!--End PHP constructed table-->

    <p>&nbsp;</p>
    <p class='footer' style='display:inline;'>
      Phylogeny after </p>
    <ul class='cite-list'>
      <li>
	Agnolin, F et al. 2012 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1016/j.cretres.2011.11.014'>10.1016/j.cretres.2011.11.014</a></span>
      </li>
      <li>
  Arillo, A and Engel, MS 2006 <span class='cite-source'>Amer. Museum Novitates #3539</span><a href='http://digitallibrary.amnh.org/dspace/bitstream/2246/5817/1/N3539.pdf' <?php echo $linkout_closeanchor; ?>
      </li>
      <li>
	Arnason, U et al. 2002 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1073/pnas.102164299'>10.1073/pnas.102164299</a></span>
      </li>
      <li>
	Bell Jr., GL and Polcyn, MJ
	2005. <span class='cite-source'>Netherlands Journal of	Geosciences 84&mdash;3 177&mdash;194	<a href='http://www.njgonline.nl/publish/articles/000253/article.pdf' <?php echo $linkout_closeanchor; ?>
      </li>
      <li>
        Benton, M 2005 <span class='cite-source'>ISBN: 0-632-05637-1</span>
      </li>
      <li>
	Bitsch, C and Bitsch, J 2000. <span class='cite-source'>Journal: Zool. Scripta 29(2), 131&mdash;156</span>
      </li>
      <li>
        Chiappe, LM 2002 <span class='cite-source'>ISBN: 0-520-20094-2</span>
      </li>
      <li>
        Carpenter, K. 1997 <span class='cite-source'>ISBN: 0121552101</span>
      </li>
      <li>
	Choiniere, J et al. 2010 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1126/science.1182143'>10.1126/science.1182143</a></span>
      </li>
      <li>
	Churakov, G et al. 2009 <span class='cite-source'>DOI <a href='http://dx.doi.org/10.1101/gr.090647.108'>10.1101/gr.090647.108</a></span>
      </li>
      <li>
	Flynn, JJ et al. 1998 <span class='cite-source'>DOI <a href='http://dx.doi.org/10.1006/mpev.1998.0504'>10.1006/mpev.1998.0504</a></span>
      </li>
      <li>
	Feldhamer GA et al. 2007 <span class='cite-source'>ISBN: 0801886953</span>
      </li>
      <li>
	Gatesy, J et al. 2001 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1016/S0169-5347(01)02236-4'>10.1016/S0169-5347(01)02236-4</a></span>
      </li>
      <li>
	Godefroidt, P et al. 2013 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1038/nature12168'>10.1038/nature12168</a></span>
      </li>
      <li>
	Grellet-Tinner, G 2006 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1080/03115510608619350' <?php echo $newwindow; ?>>10.1080/03115510608619350</a></span>
      </li>
      <li>
	Hackett, S et al. 2008 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1126/science.1157704' <?php echo $newwindow; ?>>10.1126/science.1157704</a></span>
      </li>
      <li>
        Harris, JD 2006 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1017/S1477201906001805' <?php echo $newwindow; ?>>10.1017/S1477201906001805</a></span>
      </li>
      <li>
	Higdon, JW et al. 2007 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1186/1471-2148-7-216'>10.1186/1471-2148-7-216</a></span>
      </li>
      <li>
        Holtz, TR 1998 <span class='cite-source'>ISSN: 0871-5424</span> <a href='http://www.mnhn.ul.pt/geologia/gaia/1.pdf' <?php echo $linkout_closeanchor; ?>
      </li>
      <li>
        Huchon, D et al. 2002 <span class='cite-source'>Journal: Molecular Biology and Evolution</span> <a href='http://mbe.oxfordjournals.org/cgi/content/full/19/7/1053' <?php echo $linkout_closeanchor; ?>
      </li>
      <li>
	Kristensen, NP 1999 <span class='cite-source'>Journal: Eur. J. Entomol. 96(3), 237&mdash;253</span>
      </li>
      <li>
	Lyal, CH 1985 <span class='cite-source'>Journal: Syst. Entomol. 10, 145&mdash;165</span>
      </li>
      <li>
	Matthee, CA et al. 2001 <span class='cite-source'>Journal: Systematic Biology</span> <a href='http://www.jstor.org/stable/3070929' <?php echo $linkout_closeanchor; ?>
      </li>
      <li>
	Milinkovitch, MC et al 1994 <!-- <span class='cite-source'>DOI <a href='http://dx.doi.org/10.1006/mpev.1994.1006'>10.1006/mpev.1994.1006</a></span>  -->
      </li>
      <li>
	Ouvrard, D et al. 2000 <span class='cite-source'>Journal: Mol. Phylogenet. Evol. 16, 403&mdash;417</span>
      </li>
      <li>
        Padian, K 2001 <span class='cite-source'>Journal: American Zoologist</span> <a href='http://www.jstor.org/stable/3884487' <?php echo $linkout_closeanchor; ?>
      </li>
      <li>
	Pough et al. 2004 <span class='cite-source'>ISBN: 0-13-100849-8</span>
      </li>
      <li>
	Roca, AL et al. 2004 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1038/nature02597'>10.1038/nature02597</a></span>
      </li>
      <li>
	Ronquist, F et al. 1999 <span class='cite-source'>Journal: Zool. Scripta 28, 13&mdash;50</span>
      </li>
      <li>
	Schultz, JW and Regier, JC 2000 <span class='cite-source'>Journal: Proc. R. Soc. Biol. Sci. B 267(1447), 1011&mdash;1019</span>
      </li>
      <li>
	Sorensen, JT et al. 1995 <span class='cite-source'>Journal: Pan-Pacific Entomol. 71, 31&mdash;60</span>
      </li>
      <li>
	Strait, DS and Gine, FE 2004 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1016/j.jhevol.2004.08.008'>10.1016/j.jhevol.2004.08.008</a></span>
      </li>
      <li>
	Szalay, FS et al. 1993 <!-- Methatherian Phylogeny -->
      </li>
      <li>
        Taylor, MP 2009 <span class='cite-source'>Journal: Journal of Vertebrate Paleontology</span> <a href='http://www.miketaylor.org.uk/dino/pubs/taylor2009/Taylor2009-brachiosaurus-and-giraffatitan.pdf' <?php echo $linkout_closeanchor; ?>
      </li>
      <li>
	Turner, AH et al. 2012 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1206/748.1'>10.1206/748.1</a></span>
      </li>
      <li>
	Unwin, DM in Mazin, J-M and Buffetaut, E 2003 <span class='cite-source'>ISBN: 1862391432</span>
      </li>
      <li>
	Vilhelsen, L 2000 <span class='cite-source'>Journal: Zoomorphology 119, 185&mdash;221</span>
      </li>
      <li>
	von Dohlen, CD and Moran, NA 1995 <span class='cite-source'>Journal: J. Mol. Evol. 41, 211&mdash;223</span>
      </li>
      <li>
	Weishampel, DA et al. 1990 <span class='cite-source'>ISBN: 0520067274</span>
      </li>
      <li>
	Wiens, JJ et al. 2008 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1080/10635150802166053'>10.1080/10635150802166053</a></span>
      </li>
      <li>
	Yates, AM et al. 2009 <span class='cite-source'>DOI: <a href='http://dx.doi.org/10.1098/rspb.2009.1440'>10.1098/rspb.2009.1440</a></span>
      </li>
      <li class='last'>
	the Tree of Life <a href='http://www.tolweb.org' <?php echo $linkout_closeanchor; ?>
      </li>
    </ul>
  </div> <!-- Closed content -->
<?php include('tools/footerlinks.inc'); ?>
</div><!-- Close container-->
</body>
</html>
