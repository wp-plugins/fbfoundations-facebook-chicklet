<?php
/**
Plugin Name: Facebook Chicklet
Plugin URI: http://staynalive.com/facebookchicklet
Description: Adds a Facebook Fan Count Chicklet to your WordPress blog.
Author: Jesse Stay
Version: .1
Author URI: http://staynalive.com/
Text Domain: fbchicklet

=== RELEASE NOTES ===
2009-10-26 - v0.1 - Initial Release
*/

/**
 * Facebook Chicklet Plugin Class
 */

$path = __FILE__;
if (!$path){$path=$_SERVER['PHP_SELF'];}
$current_directory = dirname($path);
$current_directory = str_replace('\\','/',$current_directory);
$current_directory = explode('/',$current_directory);
$current_directory = end($current_directory);
if(empty($current_directory) || !$current_directory)
	$current_directory = 'fbchicklet';
define('FBCHICKLET_FOLDER', $current_directory);
define('FBPATH', '/wp-content/plugins/'.FBCHICKLET_FOLDER.'/');

class FBChicklet {

    /**
     * Initalize the plugin by registering the hooks
     */
    function __construct() {

        // Load localization domain
        load_plugin_textdomain( 'fbchicklet', false, dirname(plugin_basename(__FILE__)) . '/languages' );

        // Register hooks
        add_action( 'admin_menu', array(&$this, 'register_settings_page') );
        add_action( 'admin_init', array(&$this, 'add_settings') );
	add_action('wp_head', array(&$this, 'js_header') );

        // register short code
        add_shortcode('fbchicklet', array(&$this, 'shortcode_handler'));

        $plugin = plugin_basename(__FILE__);
        add_filter("plugin_action_links_$plugin", array(&$this, 'add_action_links'));
 	//add_action('wp_enqueue_scripts',array(&$this, 'fbchicklet_sack'));

	// register page info
	// register_setting( 'fbchicklet', 'fbchicklet-page' );

    }

	function js_header() {
		wp_print_scripts( array('sack') );
  		// Define custom JavaScript function
		?>
<script type="text/javascript">
//<![CDATA[
function store_page_info( num_fans, page_url )
{
	var mysack = new sack( 
       		"<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );    

  	mysack.execute = 1;
  	mysack.method = 'POST';
  	mysack.setVar( "action", "store_page_info" );
  	mysack.setVar( "num_fans", num_fans );
  	mysack.setVar( "page_url", page_url );
  	mysack.onError = function() { console.error('Ajax error in posting num_fans' )};
  	mysack.runAJAX();

  	return true;
}
//]]>
</script>
		<?php
	}



    /**
     * Register the settings page
     */
    function register_settings_page() {
        add_options_page( __('FBFoundations Facebook Chicklet', 'fbchicklet'), __('Facebok Chicklet', 'fbchicklet'), 8, 'fbchicklet', array(&$this, 'settings_page') );
    }

    /**
     * add options
     */
    function add_settings() {
        // Register options
        register_setting( 'fbchicklet', 'fbchicklet-style');
    }



    /**
     * hook to add action links
     * @param <type> $links
     * @return <type>
     */
    function add_action_links( $links ) {
        // Add a link to this plugin's settings page
        $settings_link = '<a href="options-general.php?page=fbchicklet">' . __("Settings", 'fbchicklet') . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Adds Footer links. Based on http://striderweb.com/nerdaphernalia/2008/06/give-your-wordpress-plugin-credit/
     */
    function add_footer_links() {
        $plugin_data = get_plugin_data( __FILE__ );
        printf('%1$s ' . __("plugin", 'fbchicklet') .' | ' . __("Version", 'fbchicklet') . ' %2$s | '. __('by', 'fbchicklet') . ' %3$s<br />', $plugin_data['Title'], $plugin_data['Version'], $plugin_data['Author']);
    }

    /**
     * Dipslay the Settings page
     */
    function settings_page() {
?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2><?php _e( 'Facebook Chicklet Settings', 'fbshare' ); ?></h2>

		<p>To implement the Chicklet, just place "&lt;?php if (function_exists('fbchicklet_button')) echo fbchicklet_button(); ?&gt;" (minus the quotes) anywhere in your blog's theme files.</p>

            <form id="smer_form" method="post" action="options.php">
                <?php settings_fields('fbchicklet'); ?>
                <?php $options = get_option('fbchicklet-style'); ?>
                <?php $options['page_id'] = ($options['page_id'] == "") ? "12327140265" : $options['page_id'];?>
                <?php $options['text-color'] = ($options['text-color'] == "") ? "#59564f" : $options['text-color'];?>
                <?php $options['width'] = ($options['width'] == "") ? "88px" : $options['width'];?>
                <?php $options['height'] = ($options['height'] == "")? "26px":$options['height'];?>
                <?php $options['background-main'] = ($options['background-main'] == "")? "#94bfbf":$options['background-main'];?>
                <?php $options['top-left-border-main'] = ($options['top-left-border-main'] == "")? "#cefdfd":$options['top-left-border-main'];?>
                <?php $options['bottom-right-border-main'] = ($options['bottom-right-border-main'] == "")? "#5f8586":$options['bottom-right-border-main'];?>
                <?php $options['background-inner'] = ($options['background-inner'] == "")? "#cefdfd":$options['background-inner'];?>
                <?php $options['top-left-border-inner'] = ($options['top-left-border-inner'] == "")? "#8a8a8a":$options['top-left-border-inner'];?>
                <?php $options['bottom-right-border-inner'] = ($options['bottom-right-border-inner'] == "")? "#fefffe":$options['bottom-right-border-inner'];?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Facebook Page ID', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[page_id]" value="<?php echo $options['page_id']; ?>" /></label></p>
                            <p><?php _e("This is the ID of your Facebook Page.  This is used to determine the fan count and the URL for your Page.  To get the ID, just click on the main image in the upper-left of your Facebook Page, and the ID will be the part of the URL after the 'id='", 'fbchicklet');?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Text Color', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[text-color]" value="<?php echo $options['text-color']; ?>" /></label></p>
                            <p><?php _e("The color of the text for the chicklet.", 'fbchicklet');?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Width', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[width]" value="<?php echo $options['width']; ?>" /></label></p>
                            <p><?php _e("The width (in pixels, including 'px') of the chicklet.", 'fbchicklet');?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Height', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[height]" value="<?php echo $options['height']; ?>" /></label></p>
                            <p><?php _e("The height (in pixels, including 'px') of the chicklet.", 'fbchicklet');?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Main Chicklet Background', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[background-main]" value="<?php echo $options['background-main']; ?>" /></label></p>
                            <p><?php _e("The main background color of the chicklet.", 'fbchicklet');?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Main Chicklet Top Left Border Color', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[top-left-border-main]" value="<?php echo $options['top-left-border-main']; ?>" /></label></p>
                            <p><?php _e("The top and left border color of the chicklet.", 'fbchicklet');?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Main Chicklet Bottom Right Border Color', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[bottom-right-border-main]" value="<?php echo $options['bottom-right-border-main']; ?>" /></label></p>
                            <p><?php _e("The bottom and right border color of the chicklet.", 'fbchicklet');?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Inner Chicklet Background', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[background-inner]" value="<?php echo $options['background-inner']; ?>" /></label></p>
                            <p><?php _e("The inner background color of the chicklet.", 'fbchicklet');?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Inner Chicklet Top Left Border Color', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[top-left-border-inner]" value="<?php echo $options['top-left-border-inner']; ?>" /></label></p>
                            <p><?php _e("The top and left border color of the inside of the chicklet.", 'fbchicklet');?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Inner Chicklet Bottom Right Border Color', 'fbchicklet' ); ?></th>
                        <td>
                            <p><label><input type="text" name="fbchicklet-style[bottom-right-border-inner]" value="<?php echo $options['bottom-right-border-inner']; ?>" /></label></p>
                            <p><?php _e("The bottom and right border color of the inside of the chicklet.", 'fbchicklet');?></p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="fbchicklet-submit" class="button-primary" value="<?php _e('Save Changes', 'fbchicklet') ?>" />
                </p>
            </form>
        </div>
<?php
        // Display credits in Footer
        add_action( 'in_admin_footer', array(&$this, 'add_footer_links'));
    }

    /**
     * Short code handler
     * @param <type> $attr
     * @param <type> $content 
     */
    function shortcode_handler($attr, $content) {
        return fbchicklet_button(false);
    }

    // PHP4 compatibility
    function FBChicklet() {
            $this->__construct();
    }
}

// Start this plugin once all other plugins are fully loaded
add_action( 'init', 'FBChicklet' ); function FBChicklet() { global $FBChicklet; $FBChicklet= new FBChicklet(); }

/**
 * Template function to add the fbchicklet button
 */
function fbchicklet_button($display = true) {
        $options = get_option('fbchicklet-style');
        $page = get_option('fbchicklet-page');

	// set defaults
	$options['page_id'] = ($options['page_id'] == "") ? "12327140265" : $options['page_id'];
	$options['text-color'] = ($options['text-color'] == "") ? "#59564f" : $options['text-color'];
	$options['width'] = ($options['width'] == "") ? "88px" : $options['width'];
	$options['background-main'] = ($options['background-main'] == "")? "#94bfbf":$options['background-main'];
	$options['top-left-border-main'] = ($options['top-left-border-main'] == "")? "#cefdfd":$options['top-left-border-main'];
	$options['bottom-right-border-main'] = ($options['bottom-right-border-main'] == "")? "#5f8586":$options['bottom-right-border-main'];
	$options['background-inner'] = ($options['background-inner'] == "")? "#cefdfd":$options['background-inner'];
	$options['top-left-border-inner'] = ($options['top-left-border-inner'] == "")? "#8a8a8a":$options['top-left-border-inner'];
	$options['bottom-right-border-inner'] = ($options['bottom-right-border-inner'] == "")? "#fefffe":$options['bottom-right-border-inner'];

	$fan_count = ($page['num_fans']) ? $page['num_fans'] : '...';
	$page_url = ($page['url']) ? $page['url'] : 'http://facebook.com/stay';
	$fql = "SELECT fan_count, page_url FROM page WHERE page_id=".$options['page_id'];

    	$output = '<div id="fanBoxChicklet" style="width:'.$options['width'].';height:17px;overflow:auto;background-color:'.$options['background-main'].';border-top: 1px solid '.$options['top-left-border-main'].';border-left: 1px solid '.$options['top-left-border-main'].';border-right: 1px solid '.$options['bottom-right-border-main'].';border-bottom: 1px solid '.$options['bottom-right-border-main'].'; font: 11px/normal monospace, courier new, sans-serif;color:'.$options['text-color'].';margin: 0;padding: 0;text-align:right">                                                                           
<p id="quantity" style="width:auto;height:13px;min-width:40px;background-color:'.$options['background-inner'].';border-top: 1px solid '.$options['top-left-border-inner'].';border-left: 1px solid '.$options['top-left-border-inner'].';border-right: 1px solid '.$options['bottom-right-border-inner'].';border-bottom: 1px solid '.$options['bottom-right-border-inner'].';padding: 2px;float: left; text-align: center;overflow: hidden;margin:1px 5px 0 0;padding:0">'.$fan_count.'</p>    
<p class="readerCaption" style="width:auto;float: left;text-align: center;vertical-align: middle;margin: 2px 0 0 0;padding: 0">
<a href="'.$page_url.'" id="feedCountLink" target="_blank" style="color:'.$options['text-color'].';text-decoration:none;margin:0;padding:0">Fans</a>
</p>                                                                                                
</div>                                                                                              
<div id="fanBoxBy" style="width:'.$options['width'].';height:9px;font: 9px/normal monospace, courier new, sans-serif;color:'.$options['text-color'].'">
ON FACEBOOK
</div>  
<script type="text/javascript">
jQuery("#header").ready(function ($) {
        FB.Facebook.apiClient.fql_query("'.$fql.'",             
                function(rows) {        
                        $("#fanBoxChicklet #quantity").html(rows[0].fan_count);
			store_page_info(rows[0].fan_count,rows[0].page_url);
                }
        );
});
</script>
';
    
    if ($display) {
        echo $output;
    } else {
        return $output;
    }
}

add_action('wp_ajax_store_page_info', 'store_page_info');
add_action('wp_ajax_nopriv_store_page_info', 'store_page_info');

function store_page_info() {

        $page = get_option('fbchicklet-page');

	if ($page) {
		update_option('fbchicklet-page',array('page_url' => $_POST['page_url'], 'num_fans' => $_POST['num_fans']), 'Page information options');
	}
	else {	
		add_option('fbchicklet-page',array('page_url' => $_POST['page_url'], 'num_fans' => $_POST['num_fans']), 'Page information options');
	}

}

?>
