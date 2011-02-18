<?php

class SearchResults extends Model{

	function search_split_terms($terms){
		$terms = preg_replace("/\"(.*?)\"/e", "self::search_transform_term('\$1')", $terms);
		$terms = preg_split("/\s+|,/", $terms);

		$out = array();

		foreach ($terms as $term) {

			$term = preg_replace("/\{WHITESPACE-([0-9]+)\}/e", "chr(\$1)", $term);
			$term = preg_replace("/\{COMMA\}/", ",", $term);

			$out[] = $term;
		}

		return $out;
	}

	function search_transform_term($term){
		$term = preg_replace("/(\s)/e", "'{WHITESPACE-'.ord('\$1').'}'", $term);
		$term = preg_replace("/,/", "{COMMA}", $term);
		return $term;
	}

	function search_escape_rlike($string){
		return preg_replace("/([.\[\]*^\$])/", '\\\$1', $string);
	}

	function search_db_escape_terms($terms){
		$out = array();
		foreach ($terms as $term) {
			$out[] = '[[:<:]]'.addslashes($this->search_escape_rlike($term)).'[[:>:]]';
		}
		return $out;
	}

	function search_perform($terms,$field,$table,$status=false,$activity=false){
		$terms = $this->search_split_terms($terms);
		$terms_db = $this->search_db_escape_terms($terms);
		$terms_rx = $this->search_rx_escape_terms($terms);

		$parts = array();
		foreach ($terms_db as $term_db) {
			$parts[] = "(".$field." RLIKE '$term_db')";
		}
		
		if(isset($status)){
			switch($status){
				case 1:
					$parts[] = "status = 'Alive'";
					break;
				case 2:
					$parts[] = "status = 'Dead'";
					break;
				case 3:
					$parts[] = "status = 'Banned'";
					break;
				case 4:
					$parts[] = "status = 'Taking a Break'";				
					break;
				case 5:
					break;
			}
		}

		if(isset($activity)){
			switch($activity){
				case 1:
					$maxtime = time() - 600;
					$parts[] = "(lastonline >= '$maxtime')";
					break;
				case 2:
					$maxtime = time() - 3600;
					$parts[] = "(lastonline >= '$maxtime')";
					break;
				case 3:
					$maxtime = time() - 86400;
					$parts[] = "(lastonline >= '$maxtime')";
					break;
				case 4:
					$maxtime = time() - 604800;
					$parts[] = "(lastonline >= '$maxtime')";				
					break;
				case 5:
					break;
			}
		}
		
		$parts = implode(' AND ', $parts);
		
		$rows = array();
		$result = $this->db->query("SELECT * FROM $table WHERE $parts")->rows();
						
		foreach($result as $key => $row) {
		
			$row['score'] = 0;

			foreach ($terms_rx as $term_rx) {
				$row['score'] += preg_match_all("/$term_rx/i", $row['username'], $null);
			}

			$rows[] = $row;
					
		}
				
		uasort($rows, array($this, 'search_sort_results'));

		return $rows;
	}

	function search_rx_escape_terms($terms){
		$out = array();
		foreach ($terms as $term) {
			$out[] = '\b'.preg_quote($term, '/').'\b';
		}
		return $out;
	}

	function search_sort_results($a, $b){
		$ax = $a['score'];
		$bx = $b['score'];

		if ($ax == $bx) { return 0; }
		return ($ax > $bx) ? -1 : 1;
	}

	function search_html_escape_terms($terms){
		$out = array();

		foreach ($terms as $term) {
			if (preg_match("/\s|,/", $term)) {
				$out[] = '"'.htmlspecialchars($term).'"';
			}else {
				$out[] = htmlspecialchars($term);
			}
		}

		return $out;
	}

	function search_pretty_terms($terms_html){

		if (count($terms_html) == 1) {
			return array_pop($terms_html);
		}

		$last = array_pop($terms_html);

		return implode(', ', $terms_html)." and $last";
	}
	
}