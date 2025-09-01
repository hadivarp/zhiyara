<?php
/**
 * Zhiyara Persian Restaurant Guide Theme Functions
 * A complete restaurant guide theme similar to Michelin Guide
 */

// Theme setup
function zhiyara_theme_setup() {
    // Add theme support for various features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('منوی اصلی', 'zhiyara'),
        'footer' => __('منوی فوتر', 'zhiyara'),
    ));
    
    // Set content width
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'zhiyara_theme_setup');

// Register Custom Post Types
function zhiyara_register_post_types() {
    
    // Restaurant Post Type
    register_post_type('restaurant', array(
        'labels' => array(
            'name' => __('رستوران‌ها', 'zhiyara'),
            'singular_name' => __('رستوران', 'zhiyara'),
            'menu_name' => __('رستوران‌ها', 'zhiyara'),
            'add_new' => __('رستوران جدید', 'zhiyara'),
            'add_new_item' => __('افزودن رستوران جدید', 'zhiyara'),
            'edit_item' => __('ویرایش رستوران', 'zhiyara'),
            'new_item' => __('رستوران جدید', 'zhiyara'),
            'view_item' => __('مشاهده رستوران', 'zhiyara'),
            'search_items' => __('جستجوی رستوران', 'zhiyara'),
            'not_found' => __('رستورانی یافت نشد', 'zhiyara'),
            'not_found_in_trash' => __('رستورانی در زباله‌دان یافت نشد', 'zhiyara'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-store',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'restaurant'),
        'show_in_rest' => true,
    ));
    
    // Restaurant Category Taxonomy
    register_taxonomy('restaurant_category', 'restaurant', array(
        'labels' => array(
            'name' => __('دسته‌بندی رستوران', 'zhiyara'),
            'singular_name' => __('دسته‌بندی', 'zhiyara'),
            'menu_name' => __('دسته‌بندی‌ها', 'zhiyara'),
            'add_new_item' => __('افزودن دسته‌بندی جدید', 'zhiyara'),
            'edit_item' => __('ویرایش دسته‌بندی', 'zhiyara'),
            'update_item' => __('به‌روزرسانی دسته‌بندی', 'zhiyara'),
            'search_items' => __('جستجوی دسته‌بندی', 'zhiyara'),
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'category'),
        'show_in_rest' => true,
    ));
    
    // Cuisine Type Taxonomy
    register_taxonomy('cuisine_type', 'restaurant', array(
        'labels' => array(
            'name' => __('نوع غذا', 'zhiyara'),
            'singular_name' => __('نوع غذا', 'zhiyara'),
            'menu_name' => __('انواع غذا', 'zhiyara'),
        ),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'cuisine'),
        'show_in_rest' => true,
    ));
    
    // Location Taxonomy
    register_taxonomy('restaurant_location', 'restaurant', array(
        'labels' => array(
            'name' => __('مکان', 'zhiyara'),
            'singular_name' => __('مکان', 'zhiyara'),
            'menu_name' => __('مکان‌ها', 'zhiyara'),
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'location'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'zhiyara_register_post_types');

// Add Custom Meta Boxes for Restaurant Details
function zhiyara_add_restaurant_meta_boxes() {
    add_meta_box(
        'restaurant_details',
        __('جزئیات رستوران', 'zhiyara'),
        'zhiyara_restaurant_details_callback',
        'restaurant',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'zhiyara_add_restaurant_meta_boxes');

// Restaurant Details Meta Box Callback
function zhiyara_restaurant_details_callback($post) {
    wp_nonce_field('zhiyara_restaurant_details', 'zhiyara_restaurant_details_nonce');
    
    $star_rating = get_post_meta($post->ID, '_restaurant_star_rating', true);
    $price_range = get_post_meta($post->ID, '_restaurant_price_range', true);
    $phone = get_post_meta($post->ID, '_restaurant_phone', true);
    $address = get_post_meta($post->ID, '_restaurant_address', true);
    $website = get_post_meta($post->ID, '_restaurant_website', true);
    $opening_hours = get_post_meta($post->ID, '_restaurant_opening_hours', true);
    $featured_dish = get_post_meta($post->ID, '_restaurant_featured_dish', true);
    $chef_name = get_post_meta($post->ID, '_restaurant_chef_name', true);
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="restaurant_star_rating"><?php _e('امتیاز ستاره (۱-۵)', 'zhiyara'); ?></label></th>
            <td>
                <select name="restaurant_star_rating" id="restaurant_star_rating">
                    <option value=""><?php _e('انتخاب امتیاز', 'zhiyara'); ?></option>
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php selected($star_rating, $i); ?>>
                            <?php echo str_repeat('⭐', $i) . ' (' . $i . ' ستاره)'; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_price_range"><?php _e('محدوده قیمت', 'zhiyara'); ?></label></th>
            <td>
                <select name="restaurant_price_range" id="restaurant_price_range">
                    <option value=""><?php _e('انتخاب محدوده قیمت', 'zhiyara'); ?></option>
                    <option value="1" <?php selected($price_range, '1'); ?>>$ - ارزان</option>
                    <option value="2" <?php selected($price_range, '2'); ?>>$$ - متوسط</option>
                    <option value="3" <?php selected($price_range, '3'); ?>>$$$ - گران</option>
                    <option value="4" <?php selected($price_range, '4'); ?>>$$$$ - بسیار گران</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_phone"><?php _e('شماره تلفن', 'zhiyara'); ?></label></th>
            <td><input type="text" name="restaurant_phone" id="restaurant_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="restaurant_address"><?php _e('آدرس', 'zhiyara'); ?></label></th>
            <td><textarea name="restaurant_address" id="restaurant_address" rows="3" class="large-text"><?php echo esc_textarea($address); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="restaurant_website"><?php _e('وب‌سایت', 'zhiyara'); ?></label></th>
            <td><input type="url" name="restaurant_website" id="restaurant_website" value="<?php echo esc_url($website); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="restaurant_opening_hours"><?php _e('ساعات کاری', 'zhiyara'); ?></label></th>
            <td><textarea name="restaurant_opening_hours" id="restaurant_opening_hours" rows="3" class="large-text"><?php echo esc_textarea($opening_hours); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="restaurant_featured_dish"><?php _e('غذای ویژه', 'zhiyara'); ?></label></th>
            <td><input type="text" name="restaurant_featured_dish" id="restaurant_featured_dish" value="<?php echo esc_attr($featured_dish); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="restaurant_chef_name"><?php _e('نام سرآشپز', 'zhiyara'); ?></label></th>
            <td><input type="text" name="restaurant_chef_name" id="restaurant_chef_name" value="<?php echo esc_attr($chef_name); ?>" class="regular-text" /></td>
        </tr>
    </table>
    
    <?php
}

// Save Restaurant Meta Data
function zhiyara_save_restaurant_meta($post_id) {
    if (!isset($_POST['zhiyara_restaurant_details_nonce']) || !wp_verify_nonce($_POST['zhiyara_restaurant_details_nonce'], 'zhiyara_restaurant_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array(
        'restaurant_star_rating',
        'restaurant_price_range',
        'restaurant_phone',
        'restaurant_address',
        'restaurant_website',
        'restaurant_opening_hours',
        'restaurant_featured_dish',
        'restaurant_chef_name'
    );
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'zhiyara_save_restaurant_meta');

// Enqueue Styles and Scripts
function zhiyara_enqueue_scripts() {
    wp_enqueue_style('zhiyara-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_style('zhiyara-persian-fonts', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700&display=swap', array(), '1.0.0');
    
    wp_enqueue_script('zhiyara-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('zhiyara-main', 'zhiyara_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('zhiyara_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'zhiyara_enqueue_scripts');

// Helper function to display star rating
function zhiyara_display_stars($rating) {
    $stars = '';
    $rating = intval($rating);
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '<span class="star filled">⭐</span>';
        } else {
            $stars .= '<span class="star empty">☆</span>';
        }
    }
    
    return $stars;
}

// Helper function to display price range
function zhiyara_display_price_range($range) {
    $range = intval($range);
    $symbols = '';
    
    for ($i = 1; $i <= 4; $i++) {
        if ($i <= $range) {
            $symbols .= '<span class="price-symbol filled">$</span>';
        } else {
            $symbols .= '<span class="price-symbol empty">$</span>';
        }
    }
    
    return $symbols;
}

// AJAX handler for restaurant filtering
function zhiyara_filter_restaurants() {
    check_ajax_referer('zhiyara_nonce', 'nonce');
    
    $category = sanitize_text_field($_POST['category']);
    $cuisine = sanitize_text_field($_POST['cuisine']);
    $location = sanitize_text_field($_POST['location']);
    $stars = sanitize_text_field($_POST['stars']);
    
    $args = array(
        'post_type' => 'restaurant',
        'posts_per_page' => 12,
        'post_status' => 'publish'
    );
    
    $tax_query = array();
    
    if (!empty($category)) {
        $tax_query[] = array(
            'taxonomy' => 'restaurant_category',
            'field' => 'slug',
            'terms' => $category
        );
    }
    
    if (!empty($cuisine)) {
        $tax_query[] = array(
            'taxonomy' => 'cuisine_type',
            'field' => 'slug',
            'terms' => $cuisine
        );
    }
    
    if (!empty($location)) {
        $tax_query[] = array(
            'taxonomy' => 'restaurant_location',
            'field' => 'slug',
            'terms' => $location
        );
    }
    
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }
    
    if (!empty($stars)) {
        $args['meta_query'] = array(
            array(
                'key' => '_restaurant_star_rating',
                'value' => $stars,
                'compare' => '>='
            )
        );
    }
    
    $restaurants = new WP_Query($args);
    
    if ($restaurants->have_posts()) {
        while ($restaurants->have_posts()) {
            $restaurants->the_post();
            get_template_part('template-parts/restaurant-card');
        }
    } else {
        echo '<p class="no-restaurants">' . __('رستورانی با این مشخصات یافت نشد.', 'zhiyara') . '</p>';
    }
    
    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_filter_restaurants', 'zhiyara_filter_restaurants');
add_action('wp_ajax_nopriv_filter_restaurants', 'zhiyara_filter_restaurants');

// Add default restaurant categories on theme activation
function zhiyara_add_default_categories() {
    $categories = array(
        'traditional' => 'سنتی',
        'fast-food' => 'فست فود',
        'persian' => 'ایرانی',
        'chinese' => 'چینی',
        'italian' => 'ایتالیایی',
        'french' => 'فرانسوی',
        'seafood' => 'غذاهای دریایی',
        'vegetarian' => 'گیاهی',
        'cafe' => 'کافه',
        'bakery' => 'نانوایی'
    );
    
    foreach ($categories as $slug => $name) {
        if (!term_exists($name, 'restaurant_category')) {
            wp_insert_term($name, 'restaurant_category', array('slug' => $slug));
        }
    }
}
add_action('after_switch_theme', 'zhiyara_add_default_categories');

// Customizer Settings
function zhiyara_customize_register($wp_customize) {
    // Site Identity Section
    $wp_customize->add_setting('site_description_persian', array(
        'default' => 'راهنمای بهترین رستوران‌ها و کافه‌های شهر',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('site_description_persian', array(
        'label' => __('توضیحات سایت (فارسی)', 'zhiyara'),
        'section' => 'title_tagline',
        'type' => 'text',
    ));
    
    // Hero Section
    $wp_customize->add_section('hero_section', array(
        'title' => __('بخش اصلی صفحه', 'zhiyara'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('hero_title', array(
        'default' => 'راهنمای رستوران‌های ژیارا',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label' => __('عنوان اصلی', 'zhiyara'),
        'section' => 'hero_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('hero_subtitle', array(
        'default' => 'بهترین رستوران‌ها و کافه‌های شهر را کشف کنید',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('hero_subtitle', array(
        'label' => __('زیرعنوان', 'zhiyara'),
        'section' => 'hero_section',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'zhiyara_customize_register');
?>
