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
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
                
                <?php 
                $description = get_theme_mod('site_description_persian', get_bloginfo('description', 'display'));
                if ($description || is_customize_preview()) :
                ?>
                    <p class="site-description"><?php echo $description; ?></p>
                <?php endif; ?>
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
