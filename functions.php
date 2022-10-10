<?php

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function my_theme_enqueue_styles()
{

    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . "/style.css");
    wp_enqueue_script('myjquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js');
    // wp_enqueue_script('jquery');
}

function custom_post_type()
{

    $labels = array(
        'name' => _x('Testimonials', 'Post Type General Name', 'twentythirteen'),
        'singular_name' => _x('Testimonials', 'Post Type Singular Name', 'twentythirteen'),
        'menu_name' => __('Testimonials', 'twentythirteen'),
        'parent_item_colon' => __('Parent Testimonial', 'twentythirteen'),
        'all_items' => __('All Testimonial', 'twentythirteen'),
        'view_item' => __('View Testimonial', 'twentythirteen'),
        'add_new_item' => __('Add New Testimonial', 'twentythirteen'),
        'add_new' => __('Add New', 'twentythirteen'),
        'edit_item' => __('Edit Testimonial', 'twentythirteen'),
        'update_item' => __('Update Testimonial', 'twentythirteen'),
        'search_items' => __('Search Testmonial', 'twentythirteen'),
        'not_found' => __('Not Found', 'twentythirteen'),
        'not_found_in_trash' => __('Not found in Trash', 'twentythirteen'),
    );

    $args = array(
        'label' => __('Testimonials', 'twentythirteen'),
        'description' => __('testmonial description', 'twentythirteen'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'show_in_rest' => true,

        'taxonomies' => array('category'),

    );

    register_post_type('testimonials', $args);
}

add_action('init', 'custom_post_type', 0);
add_shortcode('mypost', 'allpost');
function allpost($atts)
{

    if (isset($atts['catogory_id'])) {
        $cat = implode(',', $atts['catogory_id']);
        $args = array(
            'post_type' => 'testimonials',
            'post_status' => 'publish',
            'posts_per_page' => 6,
            'orderby' => 'title',
            'order' => 'ASC',
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
            'category__in' => $cat,

        );
    } else {
        $args = array(
            'post_type' => 'testimonials',
            'post_status' => 'publish',
            'posts_per_page' => 6,
            'orderby' => 'title',
            'order' => 'ASC',
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,

        );
    }

    $loop = new WP_Query($args);
    $html = '<div class="my-div">';

    while ($loop->have_posts()): $loop->the_post();
        $html .= '<p class="first"> <a href="' . get_the_permalink() . '"> <br>' . get_the_title() . ' </a><br> </p>';
        $html .= '<p class="second">' . get_the_content() . '<br> </p>';

    endwhile;

    $big = 999999999; // need an unlikely integer
    $html .= paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $loop->max_num_pages,
    ));

    wp_reset_postdata();
    $html .= '</div>';
    return $html;
}
add_action('wp_enqueue_scripts', 'my_themeall_enqueue_styles');

function my_themeall_enqueue_styles()
{
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('twentytwentytwo-style'));
}

function myallpost()
{
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'testimonials',
        'posts_per_page' => 6,
        'paged' => 1,

    );

    $the_query = new WP_Query($args);
    $loop = new WP_Query($args);
    $html = '<div class="my-div">';

    while ($loop->have_posts()): $loop->the_post();
        $mycont = get_the_content();
        $subst = substr($mycont, 0, 200);

        $html .= '<p class="one first"> <br>' . get_the_title() . '<br> </p>';
        $html .= '<p class="two">' . $subst . '<br> </p>';
        $html .= '<div class="self" id="self"> </div>';
    endwhile;

    wp_reset_postdata();
    $html .= '<button class="load-more btn btn-success" id="loadmore">Load More </button>';
    $html .= '</div>';
    return $html;

    wp_reset_postdata();
}
add_shortcode('viewpost', 'myallpost');

add_action('wp_head', 'my_action_javascript');
add_filter('wp_default_scripts', 'remove_jquery_migrate');

function my_action_javascript()
{

    ?>

    <script type="text/javascript">
        jQuery(document).ready(function() {

            var post_count = '<?php echo ceil(wp_count_posts('testimonials')->publish); ?>';
            var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
            var page = 2;
            var countbtn = 2;
            jQuery('#loadmore').click(function()
            {
                alert("hello");
                countbtn++;

                var data =
                {
                    'action': 'my_action',
                    'whatever': 1234,
                    'page': page

                };


                jQuery.post(ajaxurl, data, function(response)
                {
                    $('#self').append(response);
                    if (countbtn > 3) {
                        $('#loadmore').hide();
                    }

                    page = page + 1;

                });
            });
        });
    </script> <?php
}

add_action('wp_ajax_my_action', 'my_action');
add_action('wp_ajax_nopriv_my_action', 'my_action');

function my_action()
{

    $args = array
        (
        'post_type' => 'testimonials',
        'posts_per_page' => 6,
        'paged' => $_POST['page'],

    );

    $the_query = new WP_Query($args);

    while ($the_query->have_posts()) {
        if ($the_query->have_posts()) {
            $the_query->the_post();
            $mytitle = get_the_title();
            $substr = substr($mytitle, 0, 200);
            echo '<p class="first">' . $substr . '</p>';
            echo '<p>' . get_the_content() . '</p>';
        }
    }

    wp_reset_postdata();

    wp_die();
}

function learningWordPress_customize_register($wp_customize)
{

    $wp_customize->add_setting('lwp_btn_color', array(
        'default' => 'link',
        'transport' => 'refresh',
    ));

    $wp_customize->add_section('lwp_standard_colors', array(
        'title' => __('Footer Links'),
        'priority' => 30,
    ));

    $wp_customize->add_control('lwp_btn_color_control', array(
        'label' => __(' Instagram link', 'LearningWordPress'),
        'section' => 'lwp_standard_colors',
        'settings' => 'lwp_btn_color',
    ));

    $wp_customize->add_setting('fburl', array(
        'default' => 'facebook url',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('fb link', array(
        'label' => __('Facebook link', 'LearningWordPress'),
        'section' => 'lwp_standard_colors',
        'settings' => 'fburl',
    ));
    $wp_customize->add_setting('twitter', array(
        'default' => 'twitter url',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('twitter', array(
        'label' => __('twitter link', 'LearningWordPress'),
        'section' => 'lwp_standard_colors',
        'settings' => 'twitter',
    ));
    $wp_customize->add_setting('linkdin', array(
        'default' => 'lindin url',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('linkdin', array(
        'label' => __('link', 'LearningWordPress'),
        'section' => 'lwp_standard_colors',
        'settings' => 'linkdin',
    ));
}

add_action('customize_register', 'learningWordPress_customize_register');

function mysignupform()
{
    $content = '';
    $content .= '<div class="container-fluid">
                <div class="row">
                <div class="col-md-12">

                 <form  action="" method="post">
                 <input type="text" class="form-control form-control-lg" placeholder="Type your name here" name="firstname">
                 &nbsp;&nbsp;&nbsp;&nbsp;
                 <input type="text" class="form-control form-control-lg" placeholder="Enter your Last Name here" name="lastname">
                 &nbsp;&nbsp;&nbsp;&nbsp;

                 <input type="email" class="form-control form-control-lg " placeholder="Enter Your Email Here" name="email">
                 &nbsp;&nbsp;&nbsp;&nbsp;
                 <input type="text" class="form-control form-control-lg" placeholder="Enter your Phone Number here" name="phone">
                 &nbsp;&nbsp;&nbsp;&nbsp;
                 <input type="password" class="form-control form-control-lg " placeholder="Enter Your Password Here" name="password">

                 <button type="submit" class="btn  mt-4">Submit</button>
                 </form>
                 </div>
                 </div>
                 </div>';
    return $content;
}

add_shortcode('mysignup', 'mysignupform');

function insertuser()
{
    $name = $_POST['firstname'];

    $lastname = $_POST['lastname'];

    $email = $_POST['email'];

    $phone = $_POST['phone'];

    $password = $_POST['password'];

    global $wpdb;

    $sql = $wpdb->insert('contactform', array(
        "name" => $name,
        "lastname" => $lastname,
        "email" => $email,
        "phone" => $phone,
        "password" => $password,
    ));

    $result = wp_create_user($name, $password, $email);
    if (!is_wp_error($result)) {
        echo "user is created successfully";
        $to = "lalitarora.hestabit@gmail.com";
        wp_mail($to, 'done', 'doneisdone');
    }
}

insertuser();

function loginform()
{
    $content = '';
    $content .= '<div class="container-fluid">
                 <div class="row">
                 <div class="col-md-12">
                 <form  action="" method="post">

                 <input type="text" class="form-control form-control-lg " placeholder="Type your name here" name="myusername">

                 <input type="password" class="form-control form-control-lg mt-3" placeholder="Enter Your Password Here" name="mypassword">

                 <button type="submit" class="btn mt-4" name="loginbtn">Login</button>

                 </form>
                 </div>
                 </div>
                 </div>';

    return $content;
}
add_shortcode('mylogin', 'loginform');

function mycorrectlogin()
{
    if (isset($_POST['loginbtn'])) {

        global $wpdb;
        $username = $_POST['myusername'];
        $password = $_POST['mypassword'];

        $credentials = array(
            'user_login' => $username,
            'user_password' => $password,

        );

        $result = wp_signon($credentials);
        if (!is_wp_error($result)) {
            echo "successfully Login";
            
        }
    }
}
add_action('template_redirect', 'mycorrectlogin');

function textdomain_register_sidebars()
{

    /* Register first sidebar name Primary Sidebar */
    register_sidebar(
        array(
            'name' => __('Lalit Sidebar', 'textdomain'),
            'id' => 'sidebar-11',
            'description' => __('A short description of the sidebar.', 'textdomain'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}
add_action('widgets_init', 'textdomain_register_sidebars');

add_action('wp_footer', 'mysidebar');

function footer_php()
{
    get_footer();
}

add_action('wp_footer', 'footer_php');

add_action('admin_enqueue_scripts', 'admin_scripts');

function admin_scripts()
{

    wp_enqueue_script('my-js', get_stylesheet_directory_uri() . "/my.js");
    wp_enqueue_script('myjquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js');
}

class OWT_WP_Widget extends WP_Widget
{

    public function __construct()
    {

        parent::__construct(
            // Base ID of your widget
            'OWT_WP_Widget',

            // Widget name will appear in UI
            __('WPBeginner Widget', 'wpb_widget_domain'),

            // Widget description
            array('description' => __('Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain'))
        );
    }

    public function form($instance)
    {

        $question = !empty($instance['question']) ? $instance['question'] : array();
        $answer = !empty($instance['answer']) ? $instance['answer'] : array();
        //$one = !empty($instance['one']) ? $instance['one'] : "";

        print_r($instance);
        // ?>

                    <form method="post">
                        <div id="wrap">
                         <div class="my_box">
                                <?php

        foreach ($question as $key => $q_value) {?>
                                <div class="field_box"><input type="textbox" name="<?php echo $this->get_field_name('question'); ?>[<?php $key;?>]" id="<?php echo $this->get_field_id('question'); ?>" class="mywade " value="<?php echo $q_value; ?>"></div>



                                <div class="field_box"><textarea name="<?php echo $this->get_field_name('answer'); ?>[<?php $key;?>]" id="<?php echo $this->get_field_id('answer'); ?>" class="mywade "> <?php echo $answer[$key] ?></textarea>
                                </div>
                            </div>
                        <?php }?>
                            <div class="button_box"><input type="button" name="add_btn" value="Add More" onclick="add_more()"></div>

                            <input type="hidden" id="box_count" value="1">

                    </form>
            <?php

    }

    public function widget($args, $instance)
    {

        $html = '';
        $html .= ' <table>';
        $html .= ' <tr>';
        $question = $instance['question'];
        $answer = $instance['answer'];

        foreach ($question as $key => $value) {
            $html .= '<td>' . $value . '</td>';

        }
        $html .= '</tr>';
        $html .= '<tr>';
        foreach ($answer as $key => $value) {
            $html .= '
                                   <td>' . $value . '</td>';

        }
        $html .= '</tr>';
        $html .= '</table>';
        echo $html;

    }

    public function update($new_instance, $old_instance)
    {
        
        return $new_instance;
    }
}

function wpb_load_widget()
{
    register_widget('OWT_WP_Widget');
}
add_action('widgets_init', 'wpb_load_widget');
