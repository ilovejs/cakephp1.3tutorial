<?php

	class GzipComponent extends Object
	{
		
			function zipContent($data)
			{
				// At the beginning of each page call these two functions
				ob_start();
				ob_implicit_flush(0);

				// Then do everything you want to do on the page
				echo $data;

				// Call this function to output everything as gzipped content.
				$this->print_gzipped_page();
			}
			
			// Include this function on your pages
			function print_gzipped_page() 
			{
				

					
					$contents = ob_get_contents();
					ob_end_clean();
					header('Content-Encoding:gzip');
					print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
					$size = strlen($contents);
					$contents = gzcompress($contents, 9);
					$contents = substr($contents, 0, $size);
					print($contents);
					exit();
				
			}
	}
