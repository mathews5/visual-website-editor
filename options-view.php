<div class="wrap">
	
    <a href="http://www.tidioelements.com/?utm_source=wordpress&utm_medium=cpc&utm_campaign=plugin_inside" id="tidio-top-logo" target="_blank"></a>
    
    <h2>Visual Website Editor for WordPress</h2>
    
    <?php if(!$view['compatibilityPlugin']): ?>
    <div class="alert alert-info" style="margin: 10px 0px 15px;">We're sorry, this plugin is not compatible with other Tidio Elements plugins - that is why it cannot be displayed on your site. To take advantage of all the possibilities our platform offers, please install <a href="http://wordpress.org/plugins/tidio-elements-integrator/" target="_blank" style="font-weight: bold;">Tidio Elements Integrator</a> plugin or uninstall the other plugins.</div>    
    <?php endif; ?>
    
    <!-- -->
        
 	<?php if($view['mode']==='OK'): ?>
    
    <p>Click "Go to Visual Editor" to continue to your Website's visual editor.</p>
    
    <a class="button button-primary" href="<?php echo $view['editorUrl'] ?>" target="_blank">Go to Visual Editor</a>
            
    <?php endif; ?>
    
    <?php if($view['mode']==='ERR_INVALID_URL'): ?>
    
    <p>Sorry <strong>Visual Website Editor</strong> doesn't support localhost sites.</p>
    
    <?php endif; ?>
    
</div>
