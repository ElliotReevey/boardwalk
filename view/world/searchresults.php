<h1><?=sprintf(gettext("%s"),$search_type)?></h1>

<h3><?=sprintf(gettext("Search results for \"%s\""),$search_term)?></h3>

<ul>
<?php
	if($results) {
		foreach($results as $key => $value){
			echo "<li><a href='#'>".$value['username']."</a></li>";
		
		}
	} else {
		echo "<li>".sprintf(gettext("No %s found, <a href='%sworld/search/'>try again >></a>"),$result_type,$site_url)."</li>";	
	}
?>
</ul>

