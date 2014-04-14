<?php
ob_start( 'ob_gzhandler' );
?>

<!DOCTYPE
 html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html   xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>Phylogeny Coding</title>
    <link rel='stylesheet' title='Phylogeny Site (Default)' href='layout/main.css' media='screen' />
  </head>
  <body>
    <div class='container'>
      <?php 
	 include('tools/toolkit.inc');
	 include('tools/headlinks.inc');
	 ?>
      <h1>Phylogeny Coding (API)</h1>
      
      <p>
	The source code of this site is written in PHP, and the main
	phylogeny is displayed as a series of nested tables with a
	repeating background to achieve the phylogeny bars.  <span
	class='code'>&lt;table&gt;</span>s are used instead of <span
	class='code'>&lt;div&gt;</span> to make forced, repeated
	positions and the alignment of various polytomies slightly
	easier.  
      </p>
      <p>
        Reading this documentation works best with the <a href='source.php'>source code</a> open.
      </p>
      <p><br/></p>
      <div style='width:30em;' class='noticebox'>
        <p>

	  The page also accepts a form of "tagging" for the search
	  function.  Inside the function, and inside the quotes, you
	  can use the (X)HTML tag <span class='code'>&lt;tt&gt;</span>
	  to insert tags for the search engine to read.

	</p>
	<p>

	  For example, <span class='code'>
	  openphylo("Felidae&lt;tt&gt;cat,tiger,lion&lt;/tt&gt;") </span> will
	  display "Felidae" as normal on the main page, but searches
	  for "cat", "tiger", or "lion" will still return results.
	</p>
	<p>
	  Using CSS, the contents of
	  the <span class='code'>&lt;tt&gt;</span> tag are hidden, but
	  they are still visible to the search engine and will
	  influence search results.

	</p>
      </div>
      <p>

        To write code for the page, there are three main
        constructive elements (functions):

      </p>	
      <code>
        openphylo("NODE");
      </code>
      <p>
        This element opens a table, spaces it, and places a line out
        with the name NODE affixed. While <strong>openphylo()</strong> does NOT need
        any child elements, since the table is left open, subgroups
        can be added before using the next element:
      </p>
      <code>
        closephylo("NODE");
      </code>
      <p>
        This element uses an angled line to represent a closed /
        uncontinuable outgroup, consisting of one group or
        representative named NODE. This element closes the table
        opened up with <strong>openphylo()</strong>.
      </p>
      <p>
        However, there are many cases where there is a dichotomous
        branching that you want to have its own outgroup.  For that,
        the following function is used:
      </p>
      <code>
        buildendoutgroup("NODE");
      </code>
      <p>
        Which functions exactly like <strong>openphylo()</strong> but has the symbolic
        display of <strong>closeoutgroup()</strong>.  It is used ONLY in conjuction
        with its companion function
      </p>
      <code>
        closeoutgroup();
      </code>
      <p>
        Which closes the elements used for <strong>buildendoutgroup()</strong>.  Note
        that <strong>closeoutgroup()</strong> takes no parameters.
      </p>
      <p>
        You can see all of these in the following image:
      </p>
      <div style='width:450px;margin: 0 auto;padding:5px;text-align:center;'>
        <img src='images/example1.png' alt='Example 1' style='border: 1px solid black; padding: 5px;'/>
        <p style='font-size:10pt;'>
          Here, (1) is a <strong>buildendoutgroup()</strong> (note the
          angled symbol), (2) is an <strong>openphylo()</strong> with
          no child elements, and (3) and (4) are both
          <strong>closephylo()</strong> elements. (1) is closed with a
          <strong>closeoutgroup()</strong> function after the
          <strong>closephylo()</strong> function is called for
          Holocephali.
        </p>
      </div>
      <p>
        The remaining edge cases are one of the following elements:
      </p>
      <code>
        singlephylo("NODE");<br/>
	singleoutgroup("NODE");
      </code>
      <p>
        Both of these elements only apply when the parent node has a
        single child, and thus using <strong>closephylo()</strong>
        would add a superfluous line. <strong>singlephylo()</strong>
        generally occurs when the single child is a relatively precise
        node, such as family or genus (IE, <em>Sphenodon</em>) and
        thus closes the <span class='code'>&lt;table&gt;</span> tag.
        <strong>singleoutgroup()</strong> is very similar, but does
        not close the <span class='code'>&lt;table&gt;</span> element,
        so it can have nested items below
        it. <strong>singlephylo()</strong>, by contrast, can have no
        child elements.  Note this means that
        <strong>singleoutgroup()</strong> then requires a
        <strong>closeoutgroup()</strong> to end the element.
      </p>
      <p>
        In the unfortunate but unavoidable case of unresolved phylogenies (polytomies), just insert (after <strong>openphylo()</strong> but before <strong>closephylo()</strong>):
      </p>
      <code>
        buildoutgroup("NODE");
      </code>
      <p>
        <strong>buildoutgroup()</strong> can take child elements, such as <a href='http://is.gd/3eIVx'>Phoenicopteriformes</a> (as of 2009-09-13).
      </p>
      <p>
        To build a collapsable node, <em>inside of the
        argument for the appropriate element</em>, in place of the node name, use the following
        function:
      </p>
      <code>
        cladecollapse("NODE",NUMBER);
      </code>
      <p>
        Where NODE is the node name as usual, but NUMBER is the
        numeric identifier of the collapsed region.  When submitting, please
        provide the nummber of the collapse (it is easier for
        bookkeeping to keep them in order through the page), the first
        node inside, and the last node inside.  These are enclosed in
        the following code block manually when I accept your
        submission:
      </p>
      <code>
        /*** Note Anapsida has a single child in this example, so we use singleoutgroup() here ***/ <br/>
        singleoutgroup(cladecollapse("? Turtles &amp; Tortises (Testudines)",17));<br/>
        &nbsp;if ($_REQUEST['17']!="") <br/>
	&nbsp;{<br/>
	&nbsp;&nbsp;&nbsp;//Items inside the collapse; turtle phylogeny in this case.<br/>
	&nbsp;}<br/>
	closeoutgroup();
      </code>
      <p>
        Finally, the variable $extinct is used to place the dagger to
        indicate an extinct lineage.  If you use a node that has no
        organizational name, please use "(Unnamed)" (including case)
        to label it.  This will ensure it is displayed properly.
      </p>
      <?php include('tools/footerlinks.inc'); ?>
    </div>
  </body>
</html>
