<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Send_mail {
    function __construct() { 
        $this->CI =& get_instance();
		//library
		//$this->CI->load->library('email/ElasticEmailClient'); 
    }
	
   
   function sendMail($subject = '', $content = '', $contactList = '', $type = '') {
    	if(empty($contactList)){
    		return -1;
    	}
    
    	$params = array();
    	$params['content'] = $content;
        $params['subject'] = $subject;
        $params['from_name'] = 'KARE by Karam';
    	$params['from_email'] = 'karam@karam.in';
	//	print_r($params);print_r($contactList);die("123");
		$send = $this->karanMail($params, $contactList);
    	return $send;
    }
    
	
	function karanMail($params, $contactList){
		
			/*$params['to_email'] = array();
		foreach($contactList as $key => $value){
			$params['to_email'][] = array('email' => $value);
		}*/
		$to_email = $contactList;
		$html = $params['content'];
		$text = $params['subject'];
		$bcc_emails = array("varun@flashonmind.com");
		$url = 'https://api.elasticemail.com/v2/email/send';
		$api_key = '5b7829cb-d3b6-4a58-a337-7aa15183c8cf';
		try{
				$post = array(
				'from' => $params['from_email'],
				'fromName' => $params['from_name'],
				'apikey' => $api_key,
				'subject' => $params['subject'],
				//'to' => (count($params['to_email']) === 0) ? null : join(';', $params['to_email']),
				'to' => $to_email,
				'msgBcc' =>  join(';',$bcc_emails),
				'bodyHtml' => $html,
				'bodyText' => $text,
				'isTransactional' => false);
				
				$ch = curl_init();
				curl_setopt_array($ch, array(
					CURLOPT_URL => $url,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $post,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_HEADER => false,
					CURLOPT_SSL_VERIFYPEER => false
				));
				
				$result=curl_exec ($ch);
				curl_close ($ch);
				return $result;	
		}
		catch(Exception $ex){
			echo $ex->getMessage();
		}
	}
	
	
	/////////////////////////////////////////////////////////
	//XXXXXXXXXXXXXXXXXXXXXX  MAILS XXXXXXXXXXXXXXXX
	/////////////////////////////////////////////////////////
        
	
	function send_register($email_to = NULL, $email_title = NULL, $data = NULL, $template = NULL, $attachment = '', $bcc_email = ''){
		//$resultMail = $this->CI->send_mail->send_register($email_to,$email_title,$email_check,$user_data);
		
		
		if (empty($email_to) || empty($data) || empty($template))
		{
			return FALSE;
		}
		
	
		
		$message = $this->CI->load->view($template, $data, TRUE);
		
	
	    $subject = $email_title;
	    $contactList = $email_to;
	    $resultMail = $this->sendMail($subject, $message, $contactList, 1);
		return $resultMail;
	}
	
	/*function forgotPassword($brandInfo = ''){
		$data['urlArray'] = $this->CI->config->item('MOBILE_URLS');
		$data['brandInfo'] = $brandInfo; 
		$data['resetLink'] = $this->CI->config->item('RESET_BRAND_PASSWORD').$brandInfo['resetPswd'];
		$subject = 'Reset Password Request';
		$content = $this->CI->load->view('mailTemplate/brands/forgotPassword', $data, TRUE);
		$contactList = array($brandInfo['email']);
		$resultMail = $this->sendMail($subject, $content, $contactList, 2);
	}*/
	
}
?>