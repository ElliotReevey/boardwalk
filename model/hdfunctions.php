<?php

	class HdFunctions extends Model{
					
		function ticketStatus($status){

			switch ($status) {
			    case 0:
			        $actual = gettext("Open");
			        break;
			    case 1:
			        $actual = gettext("Processing");
			        break;
			    case 2:
			        $actual = gettext("Closed");
			        break;
			}	
		
			return $actual;			

		}
		
	
	}