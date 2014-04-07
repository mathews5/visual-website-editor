<?php
/**
 * Gets option value.
 */
$option_name = 'tidio-visual-public-key';
$tidio_public_key = get_option($option_name, '');
$auth_key = SECURE_AUTH_KEY;
$tidio_access_key = md5($auth_key);
$site_url = get_option('siteurl');
?>
<style>
    .wrap{
        max-width: 500px;
    }
    #tidio-visual-editor-button{
        width: 180px;
        text-align: center;
        background-position: 12px center;
        background-repeat: no-repeat;
    }
    #tidio-visual-editor-button.ajax{
        background-image: url('<?php echo plugins_url(basename(__DIR__) . '/img/ajax-loader.gif'); ?>');
    }
</style>
<script>
    jQuery(document).ready(function($) {

        //check if isset public key, if not get new one
        var $link = $('#tidio-visual-editor-button');
        $link.addClass('ajax');
        var public_key = $link.data('public-key');
        if (public_key == '') {
            //no public key, lets make some ajax jobs
            var tidio_access_key = $link.data('tidio-access-key');
            var site_url = $link.data('site-url');
            var url = "http://visual-editor.tidioelements.com/editor-visual/accessProject";
            $.get(url, {
                'key': tidio_access_key,
                'url': site_url,
                'cms': 'wordpress'
            }).done(function(data) {
                if (typeof data.value.public_key != 'undefined') {
                    var public_key = data.value.public_key;
                    $link.animate({
                        'opacity': 1
                    });
                    var data = {
                        action: 'tidio_visual_set_key',
                        key: public_key
                    };
                    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                    $.post(ajaxurl, data, function(response) {
                    });

                    $link.data('public-key', public_key);
                    $link.removeClass('ajax');
                }
            });
        } else {
            $link.animate({
                'opacity': 1
            });
            $link.removeClass('ajax');
        }

        function redirect_to_editor() {
            var $link = $('#tidio-visual-editor-button');
            var public_key = $link.data('public-key');
            var access_key = $link.data('tidio-access-key');
            var url = "http://visual-editor.tidioelements.com/editor-visual/" + public_key + "?key=" + access_key + '&cms=wordpress';
            window.open(url, '_blank');
            $link.removeClass('ajax');
        }
        $('#tidio-visual-editor-button').click(function() {
            var $link = $(this)
            $link.addClass('ajax');
            redirect_to_editor();
        });
    });
</script>
<div class="wrap">
    <h2>Visual Website Editor for WordPress</h2> 

    <?php
    if (isset($msg)):
        ?>
        <div id="setting-error-settings_updated" class="updated settings-error"> 
            <p><strong><?php echo $msg; ?></strong></p>
        </div>
        <?php
    endif;
    ?>
    <p>
        Click "Go to Visual Editor" to continue to your Website's visual editor.
    </p>
    <a id="tidio-visual-editor-button" class="button button-primary" href="javascript:void();"
       data-tidio-access-key="<?php echo $tidio_access_key; ?>"
       data-public-key="<?php echo $tidio_public_key; ?>"
       data-site-url="<?php echo $site_url; ?>"
       style="opacity:0"
       >Go to Visual Editor</a>
</div>