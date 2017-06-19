<?php
/*
  Plugin Name: Make AddQuickTags supports ACF Option page !
  Plugin URI:
  Description: This dead simple plugin makes AddQuickTags supports working with ACF Option page, It's add all custom Quick Tags in Text mod  of ACF's WYSISYG Editor
  Version: 1.0.0
  Author: CuongDC
  Author URI: cuongdcdev@gmail.com
  License: GPLv2
 */


function c_is_admin_page(){
    // check if is Admin page
    global $pagenow;
    return ($pagenow == "admin.php" ) ? true : false; 
}


function c_addQuickTags_supports_ACF() {
    if( !c_is_admin_page() ) return; //if not ACF option page, simply do nothing 
    
    
    global $current_screen;
    $addQuickTagOptionString = "rmnlQuicktagSettings";
    $options = get_option($addQuickTagOptionString);

    if (empty($options['buttons'])) {
        $options['buttons'] = '';
    }

    $options['buttons'] = apply_filters('addquicktag_buttons', $options['buttons']);
    // hook for filter options
    $options = apply_filters('addquicktag_options', $options);

    if (!$options) {
        return NULL;
    }

    if (1 < count($options['buttons'])) {
        // sort array by order value
        $tmp = array();
        foreach ((array) $options['buttons'] as $order) {
            if (isset($order['order'])) {
                $tmp[] = $order['order'];
            } else {
                $tmp[] = 0;
            }
        }
        array_multisort($tmp, SORT_ASC, $options['buttons']);
    }
    
    
    ?>
    <script type="text/javascript">
       (function($){
           $(document).ready(function(){
                var c_addquicktag_tags = <?php echo json_encode($options); ?>,
                    c_addquicktag_post_type = <?php echo json_encode($current_screen->id); ?>;

                console.log(c_addquicktag_tags["buttons"].length);
                for( var i = 0 ; i < c_addquicktag_tags["buttons"].length ; i++  ){
                var button = c_addquicktag_tags["buttons"][i];    
                QTags.addButton( 
                        button["attachment"],
                        button["text"],
                        button["start"],
                        button["end"],
                        button["access"] ,
                        button["title"]
                    );
                }
           });
           
           

       })(jQuery);
    </script>
        
<?php } 




add_action("admin_footer" , "c_addQuickTags_supports_ACF");

