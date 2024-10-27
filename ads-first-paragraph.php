<?php
/**
 * @package Ads After First Paragraph
 * @version 1.0
 */
/*
  Plugin Name: Ads After First Paragraph.
  Description: Ads After First Paragraph is a Ads plugin that includes user Ads code to post or webpage after first paragraph with additional feature of sticky Ads bar in sidebars.
  Author: ifourtechnolab
  Version: 1.0
  Author URI: http://www.ifourtechnolab.com/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * Plugin Url.
 */
define('FI_ADD_URL', plugin_dir_url(__FILE__));

/**
 * Plugin Path.
 */
define('FI_ADD_PATH', plugin_dir_path(__FILE__));

/**
 * Ads after first paragraph Class.
 */
class Ads_after_first_paragraph {

    /**
     * Plugin Name.
     * @var string 
     */
    public $name;
    
    /**
     * Ads after first paragraph Setting menu Page Section
     * @var string 
     */
    public $section;
    
    /**
     * Ads after first paragraph Setting menu Page Configuration option group.
     * @var string 
     */
    public $option;

    /**
     * Ads after first paragraph Documentation link URL.
     */
    const FI_ADD_DOCUMENTATION = 'http://www.fiadd.ifourtechnolab.com/documentation/';
    
    /**
     * Apply All Hook for Ads after first paragraph initialize plugin.
     * @global type $wp_version
     */
    public function __construct() {
        global $wp_version;

        $this->name = 'Ads after first paragraph';
        $this->section = 'fiAddSection';
        $this->option = 'fiAddOptions';

        add_action('admin_menu', array($this, 'fiAdd_plugin_setup_menu'));
        add_action('wp_enqueue_scripts', array($this, 'fiAddLeftscript'));
        add_action("admin_init", array($this, "fiAdd_display_admin_panel_fields"));

        add_filter('the_content', array($this, 'add_code_inside_post_contents'), 999, 1);
        add_action('the_content', array($this, 'right_left_front_end'), 9, 1);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'first_ads_configuration_link'));
        
    }

    /**
     * Ads after first paragraph Right and Left ads added in content callback function.
     * @param mix $content
     * @return mix
     */
    public function right_left_front_end($content) {
        if (is_single()) {
            include(FI_ADD_PATH . "frontend-add.php");
        }
        return $content;
    }

    /**
     * Ads after first paragraph front-end scripts callback function.
     */
    public function fiAddLeftscript() {
        wp_enqueue_style('fiaddcss', FI_ADD_URL . 'assets/css/fiadd.css');
    }

    /**
     * Apply the_content hook for filter the_content data callback function.
     * @param string $content $content WordPress the_content
     * @return string Ads embed the_content.
     */
    public function add_code_inside_post_contents($content) {
        if (is_single()) {
            $ads_code = get_option('fiAdd_adsID');
            $keyword = "</p>";
            
            $position = strpos($content, $keyword) + strlen($keyword);
            
            if($position != 4){
                return substr_replace($content, PHP_EOL . $ads_code, $position , 0);
            }else{
                return $content .= $ads_code; 
            }
        }
        return $content;
    }

    /**
     * Ads after first paragraph setup menu item inside Admin sidebar callback function.
     * @global type $user_ID
     */
    public function fiAdd_plugin_setup_menu() {
        global $user_ID;
        $title = apply_filters('fiAdd_menu_title', $this->name);
        $capability = apply_filters('fiAdd_capability', 'edit_others_posts');
        $page = add_menu_page($title, $title, $capability, 'fiAdd', array($this, 'fiAdd_admin_page'), FI_ADD_URL . "assets/images/fiAdd_icon.png", 9502);
        add_action('load-' . $page, array($this, 'fiAdd_help_tab'));
    }

    /**
     * Ads after first paragraph Setting page section group and option group registration callback function.
     */
    public function fiAdd_display_admin_panel_fields() {
        add_settings_section($this->section, $this->name . " Settings", null, $this->option);

        add_settings_field("fiAdd_adsID", "Ads after first paragraph Code", array($this, "display_adsID_setting"), $this->option, $this->section);

        add_settings_field("fiAdd_adsLeftID", "Ads in Left sidebar", array($this, "display_adsLeftID_setting"), $this->option, $this->section);

        add_settings_field("fiAdd_adsRightID", "Ads in Right sidebar", array($this, "display_adsRightID_setting"), $this->option, $this->section);

        register_setting($this->section, "fiAdd_adsID");
        register_setting($this->section, "fiAdd_adsLeftID");
        register_setting($this->section, "fiAdd_adsRightID");
    }

    /**
     * Ads after first paragraph configuration link create in plugin manager list callback function.
     * @param array $links
     * @return array $links
     */
    public function first_ads_configuration_link($links) {
        $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=fiAdd')) . '">Configure</a>';
        return $links;
    }

    /**
     * Ads after first paragraph Setting section box generate for First Paragraph ads callback function.
     */
    public function display_adsID_setting() {
        ?>
        <textarea name="fiAdd_adsID" style="width: 100%;" rows="10">
            <?php echo htmlspecialchars(get_option('fiAdd_adsID'), ENT_QUOTES, 'UTF-8'); ?>
        </textarea>
        <label>Insert your advt. code to show it after first paragraph of post.</label>
        <?php
    }

    /**
     * Ads after first paragraph Setting section box generate for Left side panel ads callback function.
     */
    public function display_adsLeftID_setting() {
        ?>
        <textarea name="fiAdd_adsLeftID" style="width: 100%;" rows="10">
            <?php echo htmlspecialchars(get_option('fiAdd_adsLeftID'), ENT_QUOTES, 'UTF-8'); ?>
        </textarea>
        <label>Insert your advt. code to show it in left sidebar(size: 160*600)</label>
        <?php
    }

    /**
     * Ads after first paragraph Setting section box generate for Right side panel ads callback function.
     */
    public function display_adsRightID_setting() {
        ?>		
        <textarea name="fiAdd_adsRightID" style="width: 100%;" rows="10">
            <?php echo htmlspecialchars(get_option('fiAdd_adsRightID'), ENT_QUOTES, 'UTF-8'); ?>
        </textarea>
        <label>Insert your advt. code to show it in Right sidebar(size: 160*600)</label>
        <?php
    }

    /**
     * Ads after first paragraph Generate Setting page Admin Page callback function.
     */
    public function fiAdd_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo $this->name; ?></h1>
            <br>
            <p>
                Add Ads Javascript code from your Ads Account. 
                Your Ads will show after first paragraph of your post.
            </p>
            <form method="post" action="options.php">
                <?php
                settings_fields($this->section);
                do_settings_sections($this->option);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Ads after first paragraph Help tab in top of Screen inside the Setting page callback function.
     */
    public function fiAdd_help_tab() {
        get_current_screen()->add_help_tab(array(
            'id' => 'documentation',
            'title' => __('Documentation', 'fiAdd'),
            'content' => "<p><a href='".self::FI_ADD_DOCUMENTATION."' target='blank'>".$this->name."</a></p>"
            )
        );
    }

    /**
     * Ads after first paragraph deactivation callback function.
     */
    public function fiAdd_deactivation_hook() {
        if (function_exists('update_option')) {
            update_option('fiAdd_adsID', NULL);
            update_option('fiAdd_adsLeftID', NULL);
            update_option('fiAdd_adsRightID', NULL);
        }
    }

    /**
     * Ads after first paragraph uninstall callback function.
     */
    public function fiAdd_uninstall_hook() {
        if (current_user_can('delete_plugins')) {
            delete_option('fiAdd_adsID');
            delete_option('fiAdd_adsLeftID');
            delete_option('fiAdd_adsRightID');
        }
    }
}

$fiAdd = new Ads_after_first_paragraph();

register_deactivation_hook(__FILE__, array('Ads_after_first_paragraph', 'fiAdd_deactivation_hook'));

register_uninstall_hook(__FILE__, array('Ads_after_first_paragraph', 'fiAdd_uninstall_hook'));
