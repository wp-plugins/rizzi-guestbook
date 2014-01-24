<?php

//Set default options.
add_option($opt_vgb_items_per_pg, 10);
add_option($opt_vgb_max_upload_siz, 50);
add_option($opt_vgb_no_anon_signers, false);
add_option($opt_vgb_show_browsers, true);
add_option($opt_vgb_show_flags, true);
add_option($opt_vgb_style, "Default");
add_option($opt_vgb_show_cred_link, false);
add_option($opt_vgb_digg_pagination, false);

/*
 * Tell WP about the Admin page
 */
add_action('admin_menu', 'vgb_add_admin_page', 99);
function vgb_add_admin_page()
{ 
    add_options_page('Rizzi-Guestbook Options', 'Rizzi-Guestbook', 'administrator', "rizzi-guestbook", 'vgb_admin_page');
}

/**
  * Link to Settings on Plugins page 
  */
add_filter('plugin_action_links', 'vgb_add_plugin_links', 10, 2);
function vgb_add_plugin_links($links, $file)
{
    if( dirname(plugin_basename( __FILE__ )) == dirname($file) )
        $links[] = '<a href="options-general.php?page=' . "wp-vipergb" .'">' . __('Settings',WPVGB_DOMAIN) . '</a>';
    return $links;
}


/*
 * Output the Admin page
 */
function vgb_admin_page()
{
    global $vgb_name, $vgb_homepage, $vgb_version;
    global $opt_vgb_page, $opt_vgb_style, $opt_vgb_reverse, $opt_vgb_allow_upload;
    global $opt_vgb_items_per_pg, $opt_vgb_max_upload_siz;
	global $opt_vgb_no_anon_signers;
    global $opt_vgb_show_browsers, $opt_vgb_show_flags, $opt_vgb_show_cred_link;
    global $opt_vgb_hidesponsor, $opt_vgb_digg_pagination;
    ?>
    <div class="wrap">
      <?php
      if( isset($_POST['opts_updated']) )
      {
          update_option( $opt_vgb_page, $_POST[$opt_vgb_page] );
          update_option( $opt_vgb_style, $_POST[$opt_vgb_style] );
          update_option( $opt_vgb_items_per_pg, $_POST[$opt_vgb_items_per_pg] );
          update_option( $opt_vgb_reverse, $_POST[$opt_vgb_reverse] );
          update_option( $opt_vgb_allow_upload, $_POST[$opt_vgb_allow_upload] );
          update_option( $opt_vgb_max_upload_siz, $_POST[$opt_vgb_max_upload_siz] );
		  update_option( $opt_vgb_no_anon_signers, $_POST[$opt_vgb_no_anon_signers] );
          update_option( $opt_vgb_show_browsers, $_POST[$opt_vgb_show_browsers] );
          update_option( $opt_vgb_show_flags, $_POST[$opt_vgb_show_flags] );
          update_option( $opt_vgb_show_cred_link, $_POST[$opt_vgb_show_cred_link] );
		  update_option( $opt_vgb_digg_pagination, $_POST[$opt_vgb_digg_pagination] );
          ?><div class="updated"><p><strong><?php _e('Options saved.', WPVGB_DOMAIN ); ?></strong></p></div><?php
      }
      if( isset($_REQUEST[$opt_vgb_hidesponsor]) )
          update_option($opt_vgb_hidesponsor, $_REQUEST[$opt_vgb_hidesponsor]);
      ?>
      <h2 style="clear: none">
         <?php _e('Rizzi-Guestbook Options', WPVGB_DOMAIN) ?>
         <?php if( get_option($opt_vgb_page) ): ?>
         <span style="font-size:12px;"> <a href="edit-comments.php?p=<?php echo get_option($opt_vgb_page)?>"><?php _e('Manage Entries', WPVGB_DOMAIN) ?> &raquo;</a></span>
         <?php endif;?>
      </h2>
      
      <?php _e('
<hr><center>
<table border="0">
   <tr><td>
<h2>To help improve this plugin, click the donate button.</h2>
   </td><td><br />
<!-- PayPal Button -->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank"><input type="hidden" name="cmd" value="_donations" />
<input type="hidden" name="business" value="PUVC5R8CCHQAN" />
<input type="hidden" name="lc" value="US" />
<input type="hidden" name="item_name" value="JamRizzi" />
<input type="hidden" name="currency_code" value="USD" />
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted" />
<input type="image" alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" />
<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" border="0" /></form>
<!-- End PayPal Button -->
</td></tr></table>
</center>
<hr>
<br />
To add a Guestbook to your blog, simply create a new page, select it in the first combobox below, and click "Save."', WPVGB_DOMAIN) ?><br /><br />
        
      <?php if(!get_option($opt_vgb_hidesponsor)): ?>
        <!-- Sponsorship message *was* here, until Automattic demanded they be removed from all plugins - see http://gregsplugins.com/lib/2011/11/26/automattic-bullies/ -->
      <?php endif; ?>
      <hr />
      
      <h4><?php _e('Main Settings', WPVGB_DOMAIN) ?>:</h4>
      <form name="formOptions" method="post" action="">
        <?php _e('Guestbook Page', WPVGB_DOMAIN) ?>:
        <select style="width:150px;" name="<?php echo $opt_vgb_page?>">
          <?php
            $pages = get_pages(array('post_status'=>'publish,private'));  
            $vgb_page = get_option($opt_vgb_page);
            echo '<option value="0" selected>&lt;None&gt;</option>';
            foreach($pages as $page)
               echo '<option value="'.$page->ID.'"'. ($page->ID==$vgb_page?' selected':'').'>'.$page->post_title.'</option>'."\n";
          ?>
        </select><br />
        
        <h4><?php _e('Extra Settings', WPVGB_DOMAIN)?>:</h4>
        <input type="text" size="3" name="<?php echo $opt_vgb_items_per_pg?>" value="<?php echo get_option($opt_vgb_items_per_pg) ?>" /> <?php _e('Entries Per Page', WPVGB_DOMAIN)?><br />
		<input type="checkbox" name="<?php echo $opt_vgb_digg_pagination?>" value="1" <?php echo get_option($opt_vgb_digg_pagination)?'checked="checked"':''?> /> <?php _e('Use Digg-style pagination', WPVGB_DOMAIN)?><br />
        <input type="checkbox" name="<?php echo $opt_vgb_reverse?>" value="1" <?php echo get_option($opt_vgb_reverse)?'checked="checked"':''?> /> <?php _e('Reverse Order (list from oldest to newest)', WPVGB_DOMAIN)?><br />
<!--ECU Code        <input type="checkbox" name="<?php echo $opt_vgb_allow_upload?>" value="1" <?php echo get_option($opt_vgb_allow_upload)?'checked="checked"':''?> /> <?php _e('Allow Image Uploads', WPVGB_DOMAIN)?><br /> -->
<!--ECU Code        <input type="text" size="3" name="<?php echo $opt_vgb_max_upload_siz?>" value="<?php echo get_option($opt_vgb_max_upload_siz) ?>" /> <?php _e('Max Image Filesize (kb)', WPVGB_DOMAIN)?><br /><br /> -->
        <input type="checkbox" name="<?php echo $opt_vgb_no_anon_signers?>" value="1" <?php echo get_option($opt_vgb_no_anon_signers)?'checked="checked"':''?> /> <?php _e('Don\'t allow anonymous signatures (aka only allow registered users)',WPVGB_DOMAIN)?><br />
        <input type="hidden" name="opts_updated" value="1" />
        <div class="submit"><input type="submit" name="Submit" value="<?php _e('Save',WPVGB_DOMAIN)?>" /></div>
      </form>
    
    <hr />  
There are several Guestbook options for Wordpress, but many of theme are
extremely complicated, not user friendly, and have incompatibility issues.
Rizzi-Guestbook is different.  The purpose for this plugin was to create a
Guestbook that is simple to administrate, easy to use, while still containing
the necessary functionality a Guestbook needs.
<p>
Rizzi-Guestbook is a modified and improved version of the popular WP-ViperGB plugin.
The plugin's listing page has been beautifully redesigned, unnecessary features have been
removed to increase simplicity, and mobile support has been added.
<p>
This plugin was redesigned by JamRizzi.  For more information, visit <a href="http://jamrizzi.x10.bz" target="_blank">jamrizzi.x10.bz</a>.


    <?php
}

?>