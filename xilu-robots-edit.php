<?php
/**
 * Plugin Name: Xilu Robots.txt Editor
 * Plugin URI: https://github.com/alazy21/xilu-robots-editor
 * Description: 編輯網站上 Robots.txt 檔案
 * Author: Stan Hsiao
 * Version: 1.0.0
 * Author URI: http://stanhsiao.tw/
 * Text Domain: xilu-robots-editor
 * Domain Path: /languages
 */

define( 'XILU_ROBOTS_EDITOR', 'xilu-robots-editor');
define( 'XILU_ROBOTS_EDITOR_URL', plugin_dir_url( __FILE__ ) );
define( 'XILU_ROBOTS_EDITOR_PATH', plugin_dir_path( __FILE__ ) );
define( 'XILU_ROBOTS_EDITOR_BASENAME', plugin_basename(__FILE__) );


// 於外掛頁面新增項目連結
if ( !function_exists('xilu_robots_editor_plugin_settings_link') ) {
    add_filter( 'plugin_action_links_'.XILU_ROBOTS_EDITOR_BASENAME, 'xilu_robots_editor_plugin_settings_link' );
    function xilu_robots_editor_plugin_settings_link( $links ) { 
        $settings_link = '<a href="'.esc_url( get_admin_url(null, 'tools.php?page='.XILU_ROBOTS_EDITOR) ).'">'.__( 'Edit', 'xilu-robots-editor' ).'</a>';
        array_unshift( $links, $settings_link ); 
        return $links; 
    }    
}


// 掛上多語言
if ( !function_exists('xilu_robots_editor_load_textdomain') ) {
    add_action( 'init', 'xilu_robots_editor_load_textdomain', 0 );
    function xilu_robots_editor_load_textdomain() {
        load_plugin_textdomain( 'xilu-robots-editor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
    } 
}


// 工具(tools.php)內子選單
if ( !function_exists('xilu_robots_editor_plugin_menu') ) {
    add_action( 'admin_menu', 'xilu_robots_editor_plugin_menu' );
    function xilu_robots_editor_plugin_menu() {
        add_submenu_page( 'tools.php', __( 'Robots.txt Editor', 'xilu-robots-editor' ), 'Xilu Robots.txt Editor', 'manage_options', XILU_ROBOTS_EDITOR, 'xilu_robots_editor_page' );
    }
}


// 主頁面
if ( !function_exists('xilu_robots_editor_page') ) {
    function xilu_robots_editor_page() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'xilu-robots-editor' ) );
        }

        $file = get_home_path()."robots.txt";
        if( !is_file( $file ) ) {
            $file = XILU_ROBOTS_EDITOR_PATH.'robots.txt'; // 查無檔案，讀取範本檔案內容
        }
        $myfile = fopen( $file, "r") or die("Unable to open file!");
        @$val = fread( $myfile, filesize($file) );
        fclose( $myfile );
        
        include_once 'include/xilu-robots-editor_page.php';
    }
}


// 載入 JavaScript & CSS 檔案
if ( !function_exists('xilu_robots_editor_enqueue_scripts') ) {
    add_action('admin_enqueue_scripts', 'xilu_robots_editor_enqueue_scripts');
    function xilu_robots_editor_enqueue_scripts( $hook ) {

        global $plugin_page;
        if( $plugin_page == XILU_ROBOTS_EDITOR ):
            // 啟用 WordPress 內建程式碼編輯器 ( CodeMirror )
            $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
            wp_localize_script('jquery', 'cm_settings', $cm_settings);
            wp_enqueue_script('wp-theme-plugin-editor');
            wp_enqueue_style('wp-codemirror');

            // 載入外掛 JavaScript & CSS 檔案
            wp_enqueue_style( 'xilu-robots-editor', XILU_ROBOTS_EDITOR_URL.'css/xilu-robots-editor.css' );
            wp_enqueue_script('xilu-robots-editor', XILU_ROBOTS_EDITOR_URL.'js/xilu-robots-edit.js', array(), false, true );
        endif;
    }
}


// Ajax 存檔
if ( !function_exists('xilu_robots_editor_save_file') ) {
    add_action('wp_ajax_save_robots_file', 'xilu_robots_editor_save_file');
    function xilu_robots_editor_save_file() {
        if( $_SERVER['REQUEST_METHOD'] == "POST" || !wp_verify_nonce( $_REQUEST['robots'] ) ) {
            $file = get_home_path()."robots.txt";
            file_put_contents( $file, trim( $_REQUEST['robots']) );
            $data['css'] = 'notice-success';
            $data['mesg'] = __( 'The file has been edited.', 'xilu-robots-editor' );
        } else {
            $data['css'] = 'notice-error';
            $data['mesg'] = __( 'An unexpected error occurred!', 'xilu-robots-editor' );
        }
        echo wp_json_encode( $data );
        wp_die();
    }
}
