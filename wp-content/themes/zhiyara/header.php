<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container">
        <div class="header-content">
            <div class="site-branding">
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        🏍️ راهنمای ژیارا
                    </a>
                </h1>
                <p class="site-description">نگاه موتورسوار ناشناس به رستوران‌های شهر</p>
            </div>

            <nav class="main-nav">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'fallback_cb'    => 'zhiyara_default_menu',
                ));
                ?>
            </nav>

            <div class="header-search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" class="search-field" placeholder="جستجوی رستوران..." value="<?php echo get_search_query(); ?>" name="s" />
                    <input type="hidden" name="post_type" value="restaurant" />
                    <button type="submit" class="search-submit">🔍</button>
                </form>
            </div>
        </div>
    </div>
</header>

<?php
// Default menu fallback
function zhiyara_default_menu() {
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">خانه</a></li>';
    echo '<li><a href="' . esc_url(home_url('/restaurant/')) . '">رستوران‌ها</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about/')) . '">درباره ما</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact/')) . '">تماس با ما</a></li>';
    echo '</ul>';
}
?>
