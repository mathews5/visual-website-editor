<?php

class VisualEditorOptions {
	
	private $apiHost = 'http://visual-editor.tidioelements.com/';
	
	private $siteUrl;
	
	private $siteIsValid = true;
	
	public function __construct(){
		
		$this->siteUrl = get_option('siteurl');
		
		$this->siteIsValid = $this->checkSiteIsValid();
		
	}
	
	public function getPublicKey(){
		
		if(!$this->siteIsValid)
			
			return '';
			
		//

		$publicKey = get_option('tidio-visual-public-key');

		if(!empty($publicKey))
			
			return $publicKey;
			
		//
		
		$apiData = $this->getContentData($this->apiHost.'editor-visual/accessProject?'.http_build_query(array(
			'key' => $this->getPrivateKey(),
			'url' => $this->siteUrl,
			'cms' => 'wordpress'
		)));
		
		$apiData = json_decode($apiData, true);
		
		if(!$apiData['status'])
		
			return false;
		
		$apiData = $apiData['value'];
		
		update_option('tidio-visual-public-key', $apiData['public_key']);
			
		return $apiData['public_key'];
	}
	
	public function getPrivateKey(){
		
		if(!$this->siteIsValid)
			
			return '';
		
		//
		
		$privateKey = get_option('tidio-visual-private-key');
		
		if(!empty($privateKey))
			
			return $privateKey;
			
		//
		
		$privateKey = md5(SECURE_AUTH_KEY);
		
		update_option('tidio-visual-private-key', $privateKey);
		
		return $privateKey;
		
	}
	
	public function getEditorUrl(){
				
		return $this->apiHost.'editor-visual/'.$this->getPublicKey().'?key='.$this->getPrivateKey().'&platform=wordpress';
				
	}
	
	public function siteIsValid(){
		
		return $this->siteIsValid;
		
	}
	
	private function checkSiteIsValid(){
		
		$urlParse = parse_url($this->siteUrl);
		
		if(empty($urlParse['host']))
			
			return false;
					
		if(in_array($urlParse['host'], array('127.0.0.1', 'localhost')))
			
			return false;
		
		//
			
		return true;
		
	}

	private function getContentData($url, $urlGet = null){
		
		if($urlGet && is_array($urlGet)){
			
			$url = $url.'?'.http_build_query($urlGet);
			
		}
		
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
	
		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;
		
	}
	
}