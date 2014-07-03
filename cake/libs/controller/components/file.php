<?php

	class FileComponent extends Object
	{
		var $fh;
		
		/**
		 * uploads files to the server
		 * @params:
		 *		$folder 	= the folder to upload the files e.g. 'img/files'
		 *		$formdata 	= the array containing the form files
		 * 		$permitted 	= array containing the permitted download extensions
		 *		$itemId 	= id of the item (optional) will create a new sub folder
		 * @return:
		 *		will return an array with the success of each file upload
		 */
		function uploadFiles($folder, $formdata, $permitted = null, $itemId = null) 
		{
			// setup dir names absolute and relative
			$folder_url = WWW_ROOT.$folder;
			$rel_url = $folder;
			
			// create the folder if it does not exist
			if(!is_dir($folder_url)) {
				mkdir($folder_url);
			}
				
			// if itemId is set create an item folder
			if($itemId) {
				// set new absolute folder
				$folder_url = WWW_ROOT.$folder.'/'.$itemId; 
				// set new relative folder
				$rel_url = $folder.'/'.$itemId;
				// create directory
				if(!is_dir($folder_url)) {
					mkdir($folder_url);
				}
			}
			
			// list of permitted file types, this is only images but documents can be added
			//$permitted = array('image/gif','image/jpeg','image/pjpeg','image/png');
			
			$file = $formdata;
			//$file['tmp_name'] = $file['tmp_name'].'.tmp';
			// loop through and deal with the files
			foreach($formdata as $file) 
			{
				
				// replace spaces with underscores
				$filename = str_replace(' ', '_', $file['name']);
				// assume filetype is false
				
				if($permitted != null)
				{
					$typeOK = false;
					// check filetype is ok
					foreach($permitted as $type) 
					{
						if($type == $file['type']) 
						{
							$typeOK = true;
							break;
						}
					}
				}else
				{
					$typeOK = true;
				}
				// if file type ok upload the file
				if($typeOK) {
					// switch based on error code
					switch($file['error']) {
						case 0:
							// check filename already exists
							if(!file_exists($folder_url.'/'.$filename)) {
								// create full filename
								
								$full_url = $folder_url.'/'.$filename;
								$url = $rel_url.'/'.$filename;
								
								// upload the file
								$success = move_uploaded_file($file['tmp_name'], $full_url);
							} else {
								// create unique filename and upload file
								ini_set('date.timezone', 'Europe/London');
								$now = date('Y-m-d-His');
								$full_url = $folder_url.'/'.$now.$filename;
								$url = $rel_url.'/'.$now.$filename;
								$success = move_uploaded_file($file['tmp_name'], $full_url);
							}
							// if upload was successful
							if($success) {
								// save the url of the file
								$result['urls'][] = $full_url;//$url;
							} else {
								$result['errors'][] = "Error uploading $filename. Please try again.";
							}
							break;
						case 3:
							// an error occured
							$result['errors'][] = "Error uploading $filename. Please try again.";
							break;
						default:
							// an error occured
							$result['errors'][] = "System error uploading $filename. Contact webmaster.";
							break;
					}
				} elseif($file['error'] == 4) {
					// no file was selected for upload
					$result['nofiles'][] = "No file Selected";
				} else {
					// unacceptable file type
					$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
				}
			}
			return $result;
		}
		
		function folderContent($path)
		{
			/*if(stristr(strtolower($_SERVER['SERVER_SOFTWARE']),"win"))
				$path = str_replace('\\','/',$path);
			else
				$path = str_replace('/','\\',$path);
			
			debug($path);*/
			$myDirectory = opendir($path);
			
			$dirArray = array();
			
			// get each entry
			while($entryName = readdir($myDirectory)) 
			{
				if($entryName != '.' && $entryName != '..' && $entryName != '.svn')
				{
					$info = pathinfo($path.'/'.$entryName);
					$stat = stat($path.'/'.$entryName);
					
					$obj = array
					(
						'baseName'=>$info['basename'],
						'extension'=>$info['extension'],
						'fileName'=>$info['filename'],
						'atime' =>date('d-m-Y H:i:s',$stat['atime']),
						'mtime'=> date('d-m-Y H:i:s',$stat['mtime'])
					);
					
					$dirArray[$info['basename']] = $obj;
				}
			}

			// close directory
			closedir($myDirectory);
			sort($dirArray);
			return $dirArray;

		}
		
		function folderClear($path)
		{
			$myDirectory = opendir($path);

			// get each entry
			while($entryName = readdir($myDirectory)) {
				if($entryName != '.' && $entryName != '..' && $entryName != '.svn')
					unlink($path.$entryName);
			}

			// close directory
			closedir($myDirectory);

		}
		
		/*
		 * types
		 * a = append
		 * w = write
		 * r = read
		 * */
		function open($fileName, $type = 'a')
		{
			$this->fh = fopen($fileName, $type) or die("can't open file");
		}
		
		function write($text)
		{
			if($this->fh)
				fwrite($this->fh, $text);
			else
				die('File object not opened.');
		}
		
		function AppendLn($text)
		{
			if($this->fh)
				fwrite($this->fh, "$text\n");
			else
				die('File object not opened.');
		}
		
		function writeContent($fileName, $content, $type = 'w')
		{
			$this->open($fileName,$type);
			$this->write($content);
			$this->close();
		}
		
		function content($fileName)
		{
			return file_get_contents($fileName, FILE_USE_INCLUDE_PATH);
		}
		
		function close()
		{
			fclose($this->fh);
		}
	}
?>
