<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Excelsheet {
		function __construct() {
			$CI =& get_instance();
		}
		
		
		function subAssetView($param){
			print_r($param);die;
			
		}
		
		function exportToCsv($data = '', $filename = ''){
			// file name for download
			$filename .= ".xls";
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: application/vnd.ms-excel");
			
			if(is_array($data) && count($data)>0){
				//column name
							echo implode("\t", array_keys($data[0])) . "\r\n";
				//values in columns
				foreach($data as $key => $value){
					if($value < 0){
						continue;
					}
					echo implode("\t", array_values($value)) . "\r\n";
				}
			} else{
				echo 'no entries';
			}
		}
		
		
	}	