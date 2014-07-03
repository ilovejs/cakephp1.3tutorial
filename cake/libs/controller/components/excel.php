<?php

	class ExcelComponent extends Object
	{
		/**
		 * Import the data from an excel file
		 * @params:
		 *		$filePath 	= the folder to xls files
		 *		$headers 	= the first line contains the header
		 * @return:
		 *		an array with the data imported
		 * */
		function importData($filePath, $headers = true)
		{
			set_time_limit(240);    //4minutes
			ini_set('memory_limit', '64M');
			App::import('Vendor', 'Spreadsheet_Excel_Reader', array('file' => 'reader.php'));
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('CP1251');
			$data->read($filePath);
			
			$result = array();
			$count = 0;
			
			for ($i = (($headers)?2:1); $i <= $data->sheets[0]['numRows']; $i++) 
			{
				for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) 
				{
					try
					{
						if($headers)
							$result[$count][$data->sheets[0]['cells'][1][$j]] = $data->sheets[0]['cells'][$i][$j];
						else
							$result[$count][] = $data->sheets[0]['cells'][$i][$j];
					}catch(Exception $e)
					{}
				}
				$count++;
			}
			return $result;
		}
		
		function writeData($fileName, $data)
		{
			App::import('Vendor', 'Spreadsheet_Excel_Reader', array('file' => 'reader.php'));
		}
		
		function xlsBOF() 
		{
			echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
			return;
		}

		function xlsEOF() 
		{
			echo pack("ss", 0x0A, 0x00);
			return;
		}

		function xlsWriteNumber($Row, $Col, $Value) 
		{
			echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
			echo pack("d", $Value);
			return;
		}

		function xlsWriteLabel($Row, $Col, $Value ) 
		{
			//debug($Value);
			$L = strlen($Value);
			echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
			echo $Value;
			return;
		} 
		
		function export($data, $model)
		{
			//debug($data);
			// XLS Data Cell
			$this->xlsBOF();
			
			//HEADER
			$col=0;
			foreach($data[0] as $idx=>$header)
			{
				foreach($header as $txt=>$value)
				{
					$this->xlsWriteLabel(0,$col,$txt);
					$col++;
				}
			}
			
			$col = 0;
			$row = 1;
			
			foreach($data as $record)
			{
				$col = 0;
				foreach($record[$model] as $header=>$value)
				{
					$this->xlsWriteLabel($row,$col,$value);
					$col++;
				}
				$row++;
			}
			
			$this->xlsEOF();
			exit();
		}
	}
?>
