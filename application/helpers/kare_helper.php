<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('set_radio_state')){
	
	//this will help radio boxes to select it self
	function set_radio_state($var,$val){
		if(isset($var)){
			if ($var==$val){
				echo 'checked="checked"';
			}
		}
	}
}


if ( ! function_exists('set_checkbox_state')){
	
	//this will help radio boxes to select it self
	function set_checkbox_state($var,$val){
		if(isset($var)){
			if ($var==$val){
				echo 'checked="checked"';
			}
		}
	}

}


if ( ! function_exists('set_option_state')){
	
	//this will help radio boxes to select it self
	function set_option_state($var,$val){
		if(isset($var)){
			if ($var==$val){
				echo 'selected="selected"';
			}
		}
	}

}

if (!function_exists('draw_tree')) {
    /**
     * @return Void
     */
    function draw_tree($edit_id='',$from=''){
        $CI  =& get_instance();
	    $model=$CI->load->model('ProductCategory_model');

		// show_tree(null,$edit_id,$from);
		// die;
		
			$required  = ($from!='')? 'required="required"' : '';
			echo "<select class='form-control category-list' name='cat_parentid' id='cat_parentid' {$required}>
				 <option value=''>No Parent</option>";
			show_tree(null,$edit_id, $from);
			echo "</select>";
		
    }
}


if(!function_exists('show_tree')){
	
	function show_tree($pid=null,$edit_id='',$from=''){
		// get base categories
		$CI  =& get_instance();
		static  $child_level;
		$padding='';
		if(!$pid){
		     $pid=0;
             $child_level=0;   			 
		}else{
			$child_level++;
		}
		
		$padd = "&nbsp;&nbsp;";
		if($child_level==0){
			$padding = "&nbsp;&nbsp;&nbsp;&nbsp;";
		}elseif($child_level > 0){
			for($i = 0; $i<=$child_level; $i++){
				$arrayPad[] = $padd;
			}
		}
		$padding = implode('',$arrayPad);

	    $node='';
		
		$childs=$CI->ProductCategory_model->get_categories($pid);

		if($childs){
			if($from=='' && $clientView==''){
		        foreach($childs as $child){
					$selected = ($child['id'] == $edit_id)? 'selected' : '';
			        echo $node ="<option {$selected} value='{$child['id']}' class='level$child_level'>".$padding.$child['cat_name']."</option>";
			        echo show_tree($child['id'],$edit_id, $from='');
				}
	            $child_level--;
				//return show_tree($child['id']);
			}else if($from!='' && $clientView==''){
				echo $child_level.'<br />'.$padding;
				foreach($childs as $child){
					$resChild 	= $CI->ProductCategory_model->has_child($child['id']);
					$disabled 	= ($resChild)? 'disabled="disabled"': '';
					$color 		= (!$resChild)? 'style="color:blue"': '';
					$selected 	= ($child['id'] == $edit_id)? 'selected' : '';
			        echo $node 	= "<option {$color} {$selected} {$disabled} value='{$child['id']}' class='level$child_level'>".$padding.$child['cat_name']."</option>";
			        echo show_tree($child['id'],$edit_id, $from);
				}
	            $child_level--;
			}
	    }else{
			//echo "shakti";
           $child_level--;		
		   return;
		}
	}
}

if(!function_exists('do_upload')){
	function do_upload($file_name, $folder_path){
		
		$config=array();
		$confirm_code	=	md5(uniqid(rand()));
		$ext 			=	pathinfo($_FILES[$file_name]['name'], PATHINFO_EXTENSION);
		$fileName		=	$confirm_code.'.'.strtolower($ext);

		check_directory($folder_path);
		
		$config['upload_path'] 		= $folder_path;
		$config['allowed_types']    = 'gif|jpg|png|jpeg|pdf|xls|txt';
		$config['file_name']       	= $fileName;
		
		$CI  =& get_instance();
		$CI->load->library('upload');
		$CI->upload->initialize($config);
		
		if ( ! $CI->upload->do_upload($file_name)){
			return $error = array('error' => $CI->upload->display_errors());
		}else{
			$extn = strtolower(trim($ext));
			if ($extn =='jpg' || $extn =='png' || $extn =='jpeg' || $extn =='gif') {
				/* Resize the Image*/
				$result = resize_image($fileName, $folder_path);
				if(is_array($result)){
					return $result;
				}
			}

			$image_return =  str_replace('./','FCPATH/',$folder_path);
			$image_path_return =  $image_return.$fileName;
			
			return array('upload_data' => $CI->upload->data(), 'file_path' => $image_path_return);
		}
	}
}

	/*
		* check_directory
		* check for directory if it exists in the specified place.
		* if does not exist then create the specific directory.
		* Shakti Singh
	*/
if(!function_exists('check_directory')){
	function check_directory($new_path){
		if(is_dir($new_path)) {
			return true;
        } else {
			$old_umask = umask(0);
			mkdir($new_path, 0777);
			umask($old_umask);
			return true;
		}
	}
}

if(!function_exists('delete_file')){
    function delete_file($file){
		$file_path = str_replace('FCPATH/', './',$file);
		if (file_exists($file_path)) {
			unlink(str_replace('FCPATH', '.', $file));
		}
		return true;
	}
}

if(!function_exists('resize_image')){
	function resize_image($imgName,$filePath){
		
		if(file_exists($filePath.$imgName)){
			
			$image_info = getimagesize($filePath.$imgName);
			$image_width = $image_info[0];
			$image_height = $image_info[1];
			if(($image_width >800 && $image_height >800) && ($image_width > $image_height)){
				$max_width = '800';
				$max_height = '600';
			}elseif(($image_width >800 && $image_height >800) && ($image_width < $image_height)){
				$max_width = '600';
				$max_height = '800';
			}elseif(($image_width >800 && $image_height >800) && ($image_width == $image_height)){
				$max_width = '800';
				$max_height = '800';
			}
	
			$configs['image_library']	= 'gd2';
			$configs['source_image'] 	= $filePath.$imgName;
			$configs['maintain_ratio'] 	= TRUE;
			$configs['width']         	= $image_width;
			$configs['height']       	= $image_height;
			
			$CI  =& get_instance();
			$CI->load->library('image_lib');
			$CI->image_lib->initialize($configs);

			if ( ! $CI->image_lib->resize()){
					$CI->image_lib->clear();
					return array('error'=>$CI->image_lib->display_errors());
				}else{
					$CI->image_lib->clear();
					return $imgName;
				}
		}else{
			return array('error'=>'Image Location not found');
		}
	}
}

if (!function_exists('setMessages')) {
    /**
     * @param string $msg
     * @param string $type
     */
    function setMessages($msg = '', $type = '')
    {
        $CI =& get_instance();
        if($type == 'error'){
			 $CI->session->set_flashdata('msg',"<p class='alert alert-danger capital'><strong>SYNTAX ERROR! :</strong> ".trim($msg)."</p>");
		}else{
			 $CI->session->set_flashdata('msg',"<p class='alert alert-success  capital'>".$msg."</p>");
		}
    }
}
?>