<?php

/**
 * Plugin Name: Visual Website Editor
 * Plugin URI: http://www.tidioelements.com
 * Description: Visual Website Editor for WordPress powered by Tidio Elements
 * Version: 1.2
 * Author: Tidio Ltd.
 * Author URI: http://www.tidioelements.com
 * License: GPL2
 */
 
if(!class_exists('TidioPluginsScheme')){
	 require "classes/TidioPluginsScheme.php";
}
 
class TidioVisualEditor {
	
	private $scriptUrl = '//www.tidioelements.com/redirect/';
	
	private $pageId = '';
	
	private $projectPublicKey;
	
	private $parseMode = false;
		
	public function __construct() {
				
		add_action('admin_menu', array($this, 'addAdminMenuLink'));
		
		add_action('wp_enqueue_scripts', array($this, 'enqueueScript'));

		add_action('deactivate_'.plugin_basename(__FILE__), array($this, 'uninstall'));	
		
		//
				
		$this->parseStart();
		
	}

	public function __destruct(){
		
		if($this->projectPublicKey && !is_admin()){
			$this->parseEnd();
		}
		
	}
	
	// Parse
	
	private function parseStart(){

		// if this is site page, and integrator options dosen't exsist

		if(!is_admin() && !get_option('tidio-elements-project-data')){	
			$this->parseMode = true;
		}
		
		if($this->parseMode){
									
			$this->projectPublicKey = get_option('tidio-visual-public-key');
			
			if($this->projectPublicKey){
				
				if(!class_exists('TidioElementsParser'))
					require 'classes/TidioElementsParser.php';
				
				TidioElementsParser::start($this->projectPublicKey);
				
			}
			
		}
	}
	
	private function parseEnd(){
		
		if($this->parseMode){
			TidioElementsParser::end();
		}
		
	}

	// Uninstall
	
	public function uninstall(){
		delete_option('tidio-visual-public-key');
		delete_option('tidio-visual-private-key');
		TidioPluginsScheme::removePlugin('visual-editor');
	}
	
	// Menu Positions
	
	public function addAdminMenuLink(){
		
        $optionPage = add_menu_page(
			'Visual Editor', 'Visual Editor', 'manage_options', 'tidio-visual-editor', array($this, 'addAdminPage'), plugins_url(basename(__DIR__) . '/media/img/icon.png')
        );
        $this->pageId = $optionPage;
		
	}
	
    public function addAdminPage() {
        // Set class property
        $dir = plugin_dir_path(__FILE__);
        include $dir . 'options.php';
    }

	
	// Enqueue Script
	
	public function enqueueScript(){
		
		/*
				
		$iCanUseThisPlugin = TidioPluginsScheme::usePlugin('visual-editor');
		
		if(!$iCanUseThisPlugin){
			
			return false;
			
		}
		
		//
				
		$tidioVisualPublicKey = get_option('tidio-visual-public-key');		
				
        if (!empty($tidioVisualPublicKey)){
						
            wp_enqueue_script('tidio-integrator', $this->scriptUrl.$tidioVisualPublicKey.'.js', array(), '1.0', false);
			
		}
		
		*/

	}
	
	//
	
	public function ajaxResponse($status = true, $value = null){
		
		echo json_encode(array(
			'status' => $status,
			'value' => $value
		));	
		
		exit;
			
	}
}

$TidioVisualEditor = new TidioVisualEditor();



