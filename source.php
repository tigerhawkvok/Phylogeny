<html>
  <head><title>Source Code Retrieval</title>
<?php
if(copy('index.php','index_source.txt'))
  { ?>
    <meta http-equiv="refresh" content="3;url=http://phylogeny.revealedsingularity.net/index_source.txt" />
    </head>
    <body><p>Please wait ... if you do not redirect to the source in 5 seconds, click <a href='http://phylogeny.revealedsingularity.net/index_source.txt'>here</a>.</body>
<?php
  }
else
  { ?>
    </head>
    <body>
      <p>
        There was an error retreiving the source. Please refresh and try again. If the problem persists, please <a href='mailto:pkahn@revealedsingularity.net'>contact the webmaster.</a>
	</p>
	</body>
<?php
  }
?>
</html>
    