<?php
/*
Plugin Name: ECCK Functionality Plugin, DO NOT REMOVE!!!
Plugin URI: http://www.finchproservices.com
Description: All custom functionality that has been added to this site that should NOT go in the functions.php or other theme files.
Author: Nate Finch
Author URI: http://www.finchproservices.com
Version: 1.0.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

/*
 * Plugin Contents
 *
1.  Load Lato and Roboto Google fonts
2.  Genesis Connect for Woocommerce
3.  Adding Korean Won to Gravity Form
4.  Enqueue scripts
5.  Enqueue Excerpt
6.  Newsroom shortcode
7.  Reduce the size of the PDF downloaded
8.  Removing Unnecessary User Profile Options
9.  Ability to edit orders from Processing
10. Hides the "Update User" button for ECCK Office Members
11. Send emails to Custom Order Field Email Meta
12. Send Email to Person In Charge When Orders Are Approved
13. Our Memebers Shortcode
14. Add a new Login Logo
15. Filter Invoices and Receipts for PDF file names
16. Adds Custom Meta Box for Slider Hyperlink
17. Remove Update Nags
18. Un-Sanatize Users so UserNames can include "&" and ","
19. Add front page stylings to wp_head
*/



//* Load Lato and Roboto Google fonts
add_action( 'wp_enqueue_scripts', 'n8f_load_google_fonts' );
function n8f_load_google_fonts() {
    wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,700|Roboto:300,700', array(), CHILD_THEME_VERSION );
}


/**
 * Genesis Connect for Woocommerce
 */
add_theme_support( 'genesis-connect-woocommerce' );

/**
 * Adding Korean Won to Gravity Form
 */
add_filter( 'gform_currencies', 'update_currency' );
function update_currency( $currencies ) {
    $currencies['WON'] = array(
        'name'               => __( 'Korean Won', 'gravityforms' ),
        'symbol_left'        => '&#8361;',
        'symbol_right'       => '',
        'symbol_padding'     => ' ',
        'thousand_separator' => ',',
        'decimal_separator'  => '.',
        'decimals'           => 2
    );
    return $currencies;
}

/**
 * Enqueue scripts
 */
function wptuts_scripts_with_jquery() {
    // Register the script like this for a plugin:
    wp_register_script( 'custom-script', plugins_url( '/js/custom-script.js', __FILE__ ), array( 'jquery' ) );
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'custom-script' );

}
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_with_jquery' );


/**
 * Enqueue Excerpt
 */
function custom_excerpt_length( $length ) {
    return 15;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/**
 * Newsroom shortcode
 */
function get_ecck_newsroom_posts() {
    $args = array(
        'category_name'         => 'all-news',
        'orderby'               => 'post_date',
        'order'                 => 'DESC',
        'posts_per_page'        => '10',
    );

    // Variable
    $output = '';

    //Call the new WP_Query
    $ecck_posts = new WP_Query($args);
    $output .= '<div class="panel-group first" id="accordion">';

    if ( $ecck_posts->have_posts() ) {
        $count = 0;
        while ( $ecck_posts->have_posts() ) {
            $ecck_posts->the_post();
            $the_permalink = get_the_permalink();
            $the_title = get_the_title();
            if ( strlen( $the_title ) > 60 ) {
                $the_title = substr( $the_title, 0, 60 ) . '...';
            }
            
//            $count++;
//            $id = rand(1, 99999999) * $count;
            $output .= '<div id="eccknewsaccordian" class="panel panel-default ecck-accordian-newsroom-border" >';
            $output .= '<div class="panel-heading" id="eccknewsaccordian">';
            $output .= '<a class="accordion-toggle active" href="'.$the_permalink.'">';
            $output .= '<h4 id="eccknewstitle" class="panel-title">';

//            $output .= '<span class="fa fa-plus-circle pull-right"></span>';

            $output .= '<span class="newsroom-date">'.get_the_date().' || </span><span class="newsroom-title">'.$the_title.'</span>';
            $output .= '</h4>';
            $output .= '</a>';
//            $output .= '<div id="article-'.$id.'" class="panel-collapse collapse">';
//            $output .= '<div class="panel-body" id="eccknewsaccordianbody">';
//            $output .= get_the_excerpt();
//            $output .= '</div>';
//            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
    } else {
        //no posts found
    }
    $output .= '</div>';
    $output .= '<div class="two-fifths">';
    ////kw_sc_logo_carousel('ad-space');
    $output .= '</div>';
    wp_reset_postdata();

    return $output;
}
add_shortcode( 'eccknewsroom', 'get_ecck_newsroom_posts');


//Reduce the size of the PDF downloaded
define("DOMPDF_ENABLE_FONTSUBSETTING", true);



/*
 * Removing Unnecessary User Profile Options
 -------------------------------------------*/
// Remove Peronal Option from Profile Admin
// http://wordpress.stackexchange.com/questions/49643/remove-personal-options-section-from-profile
// removes the `profile.php` admin color scheme options
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

function rdm_profile_subject_start() {
    ob_start( 'rdm_remove_personal_options' );
}
function rdm_profile_subject_end() {
    ob_end_flush();
}
add_action( 'admin_head-profile.php', 'rdm_profile_subject_start' );
add_action( 'admin_footer-profile.php', 'rdm_profile_subject_end' );


add_action('admin_head', 'my_custom_fonts');

//Hides Social and Yoast SEO tables and fields
function my_custom_fonts() {
    echo '<style>
    .user-googleplus-wrap,
    .user-twitter-wrap,
    .user-facebook-wrap,
    h3#wordpress-seo,
    form#your-profile h3#wordpress-seo + table,
    li#field_1_16.gfield.ecck-admin-only.field_sublabel_below.field_description_above,
    div#s2id_autogen4.select2-container.wc-customer-search.enhanced,
    li.administrator,
    a.button.tips.export_to_csv,
    .user-rich-editing-wrap, 
    .user-comment-shortcuts-wrap,
    .show-admin-bar.user-admin-bar-front-wrap,
    #your-profile > h2:first-of-type,
    #your-profile > h2:nth-of-type(4),
    #your-profile > h3:nth-of-type(2),
    #your-profile > h3:nth-of-type(2) + table.form-table,
    #your-profile > h3:nth-of-type(3),
    #your-profile > h3:nth-of-type(3) + table.form-table,
    .user-nickname-wrap,
    .user-description-wrap,
    .user-profile-picture {
        display: none !important;
    }
    
    /*MailPoet*/
    form#wysija-add > .form-table > tbody > tr:nth-of-type(7),
    form#wysija-add > .form-table > tbody > tr:nth-of-type(8),
    form#wysija-add > .form-table > tbody > tr:nth-of-type(10),
    form#wysija-add > .form-table > tbody > tr:nth-of-type(11) {
        display: none !important;
    }
  </style>';
}

/* Remove Genesis Profile Options*/

add_action( 'admin_init', 'custom_remove_user_profile_fields' );

function custom_remove_user_profile_fields(){
    remove_action( 'show_user_profile', 'genesis_user_options_fields' );
    remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
    remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
    remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
    remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
    remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
    remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
    remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );
}
/*
 * End Removing Unnecessary User Profile Options
 -------------------------------------------*/




// Ability to edit orders from Processing
// https://nicolamustone.com/2015/05/14/how-to-edit-processing-orders/#more-627
add_filter( 'wc_order_is_editable', 'ecck_make_processing_orders_editable', 10, 2 );
function ecck_make_processing_orders_editable( $is_editable, $order ) {
    if ( $order->get_status() == 'processing' && current_user_can('administrator')) {
        $is_editable = true;
    }
    return $is_editable;
}


//Hides the "Update User" button for ECCK Office Members
add_action('admin_head', 'ecckoffice_remove_user_save');

function ecckoffice_remove_user_save() {
    if (current_user_can('ecck_office')) {
        echo '<style>
        input#submit.button.button-primary {
                display: none;
            }
        </style>';
    }
}



//Send emails to Custom Order Field Email Meta

add_filter( 'woocommerce_email_recipient_customer_processing_order', 'ecck_email_recipient_processing_function', 10, 2);

function ecck_email_recipient_processing_function($recipient, $object) {

    $order_id = $object->post->ID;

    $additional_recipiants = get_post_meta( $order_id, '_wc_acof_5', true );

    $recipient = $recipient . ', ' . $additional_recipiants;

    return $recipient;
}

add_filter( 'woocommerce_email_recipient_customer_completed_order', 'ecck_email_recipient_completed_function', 10, 2);

function ecck_email_recipient_completed_function($recipient, $object) {

    $order_id = $object->post->ID;

    $additional_recipiants = get_post_meta( $order_id, '_wc_acof_5', true );

    $recipient = $recipient . ', ' . $additional_recipiants;

    return $recipient;
}

// End Send emails to Custom Order Field Email Meta

/*
 *  Send Email to Person In Charge When Orders Are Approved
 *---------------------------------------*/

add_action( 'woocommerce_order_status_approved', 'ecck_email_upon_approval' );

function ecck_email_upon_approval( $order_id ) {

    $person_in_charge = get_post_meta( $order_id, '_wc_acof_2', true );

    $send_email_to = 'herena.oh@ecck.eu';



    switch ( $person_in_charge ) {

        case 'herenaoh':
            $send_email_to = 'herena.oh@ecck.eu';
            break;
        case 'haewonjang':
            $send_email_to = 'haewon.jang@ecck.eu';
            break;
        case 'hyokyungsuh':
            $send_email_to = 'hyokyung.suh@ecck.eu';
            break;
        case 'in-seungkay':
            $send_email_to = 'inseung.kay@ecck.eu';
            break;
        case 'ansookpark':
            $send_email_to = 'ansook.park@ecck.eu';
            break;
        case 'jiyunchoi':
            $send_email_to = 'jiyun.choi@ecck.eu';
            break;
        case 'seonghwanjeon':
            $send_email_to = 'seonghwan.jeon@ecck.eu';
            break;
        case 'sven-erikbatenburg':
            $send_email_to = 'sven.batenburg@ecck.eu';
            break;
        case 'youngshinahn':
            $send_email_to = 'youngshin.ahn@ecck.eu';
            break;
        case 'jeongjinlim':
            $send_email_to = 'jeongjin.lim@ecck.eu';
            break;
        case 'hyemihwang':
            $send_email_to = 'hyemi.hwang@ecck.eu';
            break;
        case 'seohyungkim':
            $send_email_to = 'seohyung.kim@ecck.eu';
            break;
        case 'chaheekim':
            $send_email_to = 'chahee.kim@ecck.eu';
            break;
        default:
            $send_email_to = 'herena.oh@ecck.eu';
            break;
    }

    $to      = $send_email_to;
    $subject = 'You have an approval to process';
    $message = 'Please process the approval for Order #' . $order_id . '. Thank you!';
    $headers = 'From: ECCK F&A <noreply@ecck.eu>' . "\r\n";
    wp_mail( $to, $subject, $message, $headers );
}



//Our Memebers Shortcode
add_shortcode('our_members', 'our_people_func');

function our_people_func($param) {
    ob_start();
    $args = array(
        'role' => 'ecck_member',
        'number' => 99999999);

    if (isset($_GET['char'])) {
        $args['search'] = esc_attr($_GET['char']) . '*';
        $args['search_columns'] = array('user_login', 'user_nicename');
    }

    if (isset($_GET['co-n'])) {
        $args['meta_key'] = 'company_name';
        $args['meta_value'] = esc_attr($_GET['co-n']);
        $args['meta_compare'] = 'like';
    }

    $user_query = new WP_User_Query($args);
    $all_users = $user_query->get_results();
    // count the number of users found in the query
    $total_users = $all_users ? count($all_users) : 1;
    // grab the current page number and set to 1 if no page number is set
    $page = isset($_GET['pno']) ? $_GET['pno'] : 1;
    // how many users to show per page
    $users_per_page = isset($param['count']) ? $param['count'] : 20;
    if (isset($_GET['char']) || isset($_GET['co-n'])) {
        $users_per_page = 100;
    }
    // calculate the total number of pages.
    $total_pages = 1;
    $offset = $users_per_page * ($page - 1);
    $total_pages = ceil($total_users / $users_per_page);

    // main user query
    $args = array(
        'role' => 'ecck_member',
        'orderby' => 'display_name',
        'number' => $users_per_page,
        'offset' => $offset
    );

    if (isset($_GET['char'])) {
        $args['search'] = esc_attr($_GET['char']) . '*';
        $args['search_columns'] = array('user_login', 'user_nicename');
    }

    if (isset($_GET['co-n'])) {
        $args['meta_key'] = 'company_name';
        $args['meta_value'] = esc_attr($_GET['co-n']);
        $args['meta_compare'] = 'like';
    }

    $user_query = new WP_User_Query($args);
    $users = $user_query->get_results();
    ?>
    <ul class="users_filter">
        <li>
            <a class="our-members-letters" href="/our-members/"> # </a>
        </li>
        <?php foreach (range('A', 'Z') as $char): ?>
            <li>
                <a class="our-members-letters <?php if (isset($_GET['char']) && $_GET['char'] == $char): ?>active<?php endif; ?>" href="<?php the_permalink(); ?>?char=<?php echo $char; ?>"><?php echo $char; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <form method="get" class="input-group filter_company_name">
        <input type="text" placeholder="Search" value="<?php echo isset($_GET['co-n']) ? $_GET['co-n'] : ''; ?>" name="co-n" />
        <span class="search-span input-group-btn"><button class="search-btn btn btn-primary btn-lg" type="submit" name="submit"><i class="fa fa-search"></i></button></span>
    </form>
    <?php
    if (!empty($users)):
        ?>
        <ul class="our_people row">
            <?php
            foreach ($users as $user):
                ?>
                <li class="col-md-6">
                    <div class="img col-md-5">
                        <?php if (get_user_meta($user->ID, 'company_logo', true)): ?>
                            <img class="ecck_company_logo" src="<?php echo get_user_meta($user->ID, 'company_logo', true); ?>"/>
                        <?php else: ?>
                            <img style="width: 100px;height: 100px;" src="<?php echo get_stylesheet_directory_uri(); ?>/images/no-image.png"/>
                        <?php endif; ?>
                    </div>
                    <div class="description col-md-7">
                        <?php if (get_field('company_name', 'user_' . $user->ID)): ?>
                            <span class="company"><?php echo get_field('company_name', 'user_' . $user->ID); ?></span>
                        <?php endif; ?>
                        <?php if (get_field('company_website', 'user_' . $user->ID)): ?>
                            <a href="<?php echo 'http://' . get_field('company_website', 'user_' . $user->ID); ?>" target="_blank"><?php echo get_field('company_website', 'user_' . $user->ID); ?></a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php
            endforeach;
            ?>
        </ul>
        <?php
        // grab the current query parameters
        $query_string = $_SERVER['QUERY_STRING'];
        $base = get_permalink() . '?' . remove_query_arg('pno', $query_string) . '%_%';

        echo paginate_links(array(
            //'base' => $base, // the base URL, including query arg
            //'format' => '&pno=%#%', // this defines the query parameter that will be used, in this case "p"
            //'prev_text' => __('&laquo; Previous'), // text for previous page
            //'next_text' => __('Next &raquo;'), // text for next page
            //'total' => $total_pages, // the total number of pages we have
            //'current' => $page, // the current page
            //'end_size' => 1,
            //'mid_size' => 5,
        ));
    else:
        ?>
        <p class="no_results"><?php _e('No results found!'); ?></p>
        <?php
    endif;
    return ob_get_clean();
}





/*
 *  Add a new Login Logo
 *---------------------------------------*/

function my_login_logo() { ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/ecck-square-login-logo.png);
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );



/*
    Filter Invoices and Receipts for PDF file names
*/

add_filter( 'wpo_wcpdf_filename', 'wpo_wcpdf_receipt_filename', 10, 4 );
function wpo_wcpdf_receipt_filename( $filename, $template_type, $order_ids, $context ) {
    // only change name for single invoice exports
    if ($template_type == 'invoice' && count($order_ids) == 1) {
        // get order
        $order_id = $order_ids[0];
        $order = wc_get_order( $order_id );
        // check order status
        if ($order->status == 'completed') {
            $invoice_string = _n( 'invoice', 'invoices', count($order_ids), 'wpo_wcpdf' );
            $filename = str_replace($invoice_string, 'receipt', $filename);
        }
    }

    return $filename;
}

add_filter( 'wpo_wcpdf_filename', 'wpo_wcpdf_packingslip_to_invoice_filename', 10, 4 );
function wpo_wcpdf_packingslip_to_invoice_filename( $filename, $template_type, $order_ids, $context ) {
    $invoice_string = _n( 'packing-slip', 'packing-slips', count($order_ids), 'wpo_wcpdf' );
    $new_prefix = _n( 'invoice', 'invoices', count($order_ids), 'wpo_wcpdf' );
    $new_filename = str_replace($invoice_string, $new_prefix, $filename);

    return $new_filename;
}

/*
 * Adds Custom Meta Box for Slider Hyperlink
 -------------------------------------------*/

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function ecck_add_meta_box() {

    $screens = array( 'slide' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'ecck_sectionid',
            __( 'Slide hyperlink ', 'ecck_textdomain' ),
            'ecck_meta_box_callback',
            $screen,
            'side',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'ecck_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function ecck_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'ecck_save_meta_box_data', 'ecck_meta_box_nonce' );

    /*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
    $value = get_post_meta( $post->ID, '_ecck_slide_hyperlink', true );

    echo '<label for="ecck_new_field">';
    _e( 'Add the page you want to link to here:', 'ecck_textdomain' );
    echo '</label> ';
    echo '<input type="text" id="ecck_new_field" name="ecck_new_field" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
    function ecck_save_meta_box_data( $post_id ) {

        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset( $_POST['ecck_meta_box_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['ecck_meta_box_nonce'], 'ecck_save_meta_box_data' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Make sure that it is set.
        if ( ! isset( $_POST['ecck_new_field'] ) ) {
            return;
        }

        // Sanitize user input.
        $my_data = sanitize_text_field( $_POST['ecck_new_field'] );

        // Update the meta field in the database.
        update_post_meta( $post_id, '_ecck_slide_hyperlink', $my_data );
    }
    add_action( 'save_post', 'ecck_save_meta_box_data' );

/*
 * End Custom Meta Box for Slider Hyperlink
 -------------------------------------------*/


//Remove Update Nags
function hide_update_notice_to_all_but_admin_users()
{
    if (!current_user_can('update_core')) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }
}
add_action( 'admin_head', 'hide_update_notice_to_all_but_admin_users', 1 );







//Un-Sanatize Users so UserNames can include "&" and ","

function ecck_do_not_sanitize_user($user, $raw_user, $strict) {
    return $raw_user;
}
add_filter('sanitize_user', 'ecck_do_not_sanitize_user', 10, 3);



//Change Registration Email

// Redefine user notification function
if ( !function_exists('wp_new_user_notification') ) {

    function wp_new_user_notification( $user_id, $deprecated = null, $notify = '' ) {
        if ( $deprecated !== null ) {
            _deprecated_argument( __FUNCTION__, '4.3.1' );
        }

        global $wpdb, $wp_hasher;
        $user = get_userdata( $user_id );

        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

        $message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s'), $user->user_email) . "\r\n";

        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);

        if ( 'admin' === $notify || empty( $notify ) ) {
            return;
        }

        // Generate something random for a password reset key.
        $key = wp_generate_password( 20, false );

        /** This action is documented in wp-login.php */
        do_action( 'retrieve_password_key', $user->user_login, $key );

        // Now insert the key, hashed, into the DB.
        if ( empty( $wp_hasher ) ) {
            require_once ABSPATH . WPINC . '/class-phpass.php';
            $wp_hasher = new PasswordHash( 8, true );
        }
        $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
        $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );

        $message = sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
        $message .= __('To set your password, visit the following address:') . "\r\n\r\n";
        $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";

        $message .= wp_login_url() . "\r\n\r\n";
        $message .= sprintf( __('If you have any problems, please contact us at %s.'), get_option('admin_email') ) . "\r\n\r\n";
        $message .= __('Adios!') . "\r\n\r\n";

        wp_mail($user->user_email, sprintf(__('[%s] username and password info'), $blogname), $message);
    }
}


//Add front page stylings to wp_head

//add_action('wp_head', 'new_styles_for_home_container_box');

function new_styles_for_home_container_box() {
    if ( is_front_page() ) {
        echo '
        <style>
            div.container.box {
                max-width: 100%;
            }
            
            #eccknewsroom {
                padding: 0 10%;
            }
            
            #ecckevents {
                padding: 0 10%;
            }
        </style>';
    }
}