<?php
/**
 * Template part for displaying restaurant cards
 */

$restaurant_id = get_the_ID();
$star_rating = get_post_meta($restaurant_id, '_restaurant_star_rating', true);
$price_range = get_post_meta($restaurant_id, '_restaurant_price_range', true);
$phone = get_post_meta($restaurant_id, '_restaurant_phone', true);
$address = get_post_meta($restaurant_id, '_restaurant_address', true);
$featured_dish = get_post_meta($restaurant_id, '_restaurant_featured_dish', true);

// Get restaurant categories
$categories = get_the_terms($restaurant_id, 'restaurant_category');
$category_name = '';
if ($categories && !is_wp_error($categories)) {
    $category_name = $categories[0]->name;
}

// Get cuisine types
$cuisines = get_the_terms($restaurant_id, 'cuisine_type');
$cuisine_name = '';
if ($cuisines && !is_wp_error($cuisines)) {
    $cuisine_name = $cuisines[0]->name;
}
?>

<article class="restaurant-card">
    <a href="<?php the_permalink(); ?>" class="restaurant-link">
        <div class="restaurant-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large'); ?>
            <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/restaurant-placeholder.jpg" alt="<?php the_title(); ?>" />
            <?php endif; ?>
            
            <?php if ($star_rating) : ?>
                <div class="restaurant-rating">
                    <?php echo zhiyara_display_stars($star_rating); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="restaurant-content">
            <h3 class="restaurant-title"><?php the_title(); ?></h3>
            
            <?php if ($category_name) : ?>
                <div class="restaurant-category"><?php echo $category_name; ?></div>
            <?php endif; ?>
            
            <?php if ($cuisine_name) : ?>
                <div class="restaurant-cuisine">نوع غذا: <?php echo $cuisine_name; ?></div>
            <?php endif; ?>
            
            <div class="restaurant-description">
                <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
            </div>
            
            <?php if ($featured_dish) : ?>
                <div class="featured-dish">
                    <strong>غذای ویژه:</strong> <?php echo esc_html($featured_dish); ?>
                </div>
            <?php endif; ?>
            
            <div class="restaurant-meta">
                <div class="restaurant-stars">
                    <?php if ($star_rating) : ?>
                        <?php echo zhiyara_display_stars($star_rating); ?>
                        <span class="rating-text">(<?php echo $star_rating; ?>/۵)</span>
                    <?php endif; ?>
                </div>
                
                <div class="restaurant-price">
                    <?php if ($price_range) : ?>
                        <?php echo zhiyara_display_price_range($price_range); ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($address) : ?>
                <div class="restaurant-address">
                    <small>📍 <?php echo esc_html(wp_trim_words($address, 8, '...')); ?></small>
                </div>
            <?php endif; ?>
        </div>
    </a>
</article>
