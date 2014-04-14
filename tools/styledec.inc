		  <?php
		     if ($_REQUEST['st']=="")
		     {
			echo "<link rel='stylesheet' title='Phylogeny Site (Default)' href='layout/main.css' media='screen' />
			<link rel='alternate stylesheet' title='Print Layout' href='layout/print.css' media='print' />";
			/* echo "@import 'layout/main.css';"; */
		     }
		     else if ($_REQUEST['st']=="print")
		     {
		        echo "<link rel='stylesheet' title='Print Layout' href='layout/print.css' />
			<link rel='alternate stylesheet' title='Print Layout' href='layout/print.css' media='print' />
			<link rel='alternate stylesheet' title='Phylogeny Site (Default)' href='layout/main.css' media='screen' />";
			/* echo "@import 'layout/print.css';"; */
		     }
		     else if ($_REQUEST['st']=="alt")
		     {
		        echo "<style type='text/css'> 
			@import 'layout/alt.css';
			</style>";
		     }
		     else if ($_REQUEST['st']=="bland")
		     {
		        echo"<!--No CSS Here-->";
		     }
		     else 
		     {
		        echo "<link rel='stylesheet' title='Phylogeny Site (Default)' href='layout/main.css' media='screen' />
			<link rel='alternate stylesheet' title='Print Layout' href='layout/print.css' media='print' />";
			/* echo "@import 'layout/main.css';"; */
		     }
		 ?>
