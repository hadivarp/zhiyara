<?php
/**
 * Single Restaurant Template
 */

get_header();

while (have_posts()) :
    the_post();
    
    $restaurant_id = get_the_ID();
    $star_rating = get_post_meta($restaurant_id, '_restaurant_star_rating', true);
    $price_range = get_post_meta($restaurant_id, '_restaurant_price_range', true);
    $phone = get_post_meta($restaurant_id, '_restaurant_phone', true);
    $address = get_post_meta($restaurant_id, '_restaurant_address', true);
    $website = get_post_meta($restaurant_id, '_restaurant_website', true);
    $opening_hours = get_post_meta($restaurant_id, '_restaurant_opening_hours', true);
    $featured_dish = get_post_meta($restaurant_id, '_restaurant_featured_dish', true);
    $chef_name = get_post_meta($restaurant_id, '_restaurant_chef_name', true);
    
    // Get taxonomies
    $categories = get_the_terms($restaurant_id, 'restaurant_category');
    $cuisines = get_the_terms($restaurant_id, 'cuisine_type');
    $locations = get_the_terms($restaurant_id, 'restaurant_location');
?>

<article class="restaurant-single">
    <!-- Restaurant Header -->
    <section class="restaurant-header">
        <div class="container">
            <div class="restaurant-hero">
                <div class="restaurant-info">
                    <h1><?php the_title(); ?></h1>
                    
                    <?php if ($star_rating) : ?>
                        <div class="restaurant-rating-large">
                            <?php echo zhiyara_display_stars($star_rating); ?>
                            <span class="rating-number"><?php echo $star_rating; ?> از ۵</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="restaurant-categories">
                        <?php if ($categories && !is_wp_error($categories)) : ?>
                            <?php foreach ($categories as $category) : ?>
                                <span class="category-tag"><?php echo $category->name; ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if ($cuisines && !is_wp_error($cuisines)) : ?>
                            <?php foreach ($cuisines as $cuisine) : ?>
                                <span class="cuisine-tag"><?php echo $cuisine->name; ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="restaurant-details">
                    <?php if ($address) : ?>
                        <div class="detail-item">
                            <span class="detail-label">📍 آدرس:</span>
                            <span class="detail-value"><?php echo esc_html($address); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($phone) : ?>
                        <div class="detail-item">
                            <span class="detail-label">📞 تلفن:</span>
                            <span class="detail-value"><?php echo esc_html($phone); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($opening_hours) : ?>
                        <div class="detail-item">
                            <span class="detail-label">🕐 ساعات کاری:</span>
                            <span class="detail-value"><?php echo nl2br(esc_html($opening_hours)); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($price_range) : ?>
                        <div class="detail-item">
                            <span class="detail-label">💰 محدوده قیمت:</span>
                            <span class="detail-value"><?php echo zhiyara_display_price_range($price_range); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($website) : ?>
                        <div class="detail-item">
                            <span class="detail-label">🌐 وب‌سایت:</span>
                            <span class="detail-value"><a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_html($website); ?></a></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Restaurant Content -->
    <section class="restaurant-content-section">
        <div class="container">
            <div class="restaurant-main-content">
                <div class="restaurant-description">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="restaurant-featured-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="restaurant-text">
                        <?php the_content(); ?>
                    </div>
                    
                    <?php if ($featured_dish) : ?>
                        <div class="featured-dish-highlight">
                            <h3>🍽️ غذای ویژه</h3>
                            <p><?php echo esc_html($featured_dish); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($chef_name) : ?>
                        <div class="chef-info">
                            <h3>👨‍🍳 سرآشپز</h3>
                            <p><?php echo esc_html($chef_name); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="restaurant-sidebar">
                    <!-- Quick Info Card -->
                    <div class="quick-info-card">
                        <h3>اطلاعات سریع</h3>
                        
                        <?php if ($star_rating) : ?>
                            <div class="info-item">
                                <strong>امتیاز:</strong>
                                <?php echo zhiyara_display_stars($star_rating); ?>
                                (<?php echo $star_rating; ?>/۵)
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($locations && !is_wp_error($locations)) : ?>
                            <div class="info-item">
                                <strong>منطقه:</strong>
                                <?php echo $locations[0]->name; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <strong>تاریخ انتشار:</strong>
                            <?php echo get_the_date(); ?>
                        </div>
                    </div>
                    
                    <!-- Related Restaurants -->
                    <div class="related-restaurants">
                        <h3>رستوران‌های مشابه</h3>
                        <?php
                        $related_args = array(
                            'post_type' => 'restaurant',
                            'posts_per_page' => 3,
                            'post__not_in' => array($restaurant_id),
                            'tax_query' => array()
                        );
                        
                        if ($categories && !is_wp_error($categories)) {
                            $related_args['tax_query'][] = array(
                                'taxonomy' => 'restaurant_category',
                                'field' => 'term_id',
                                'terms' => wp_list_pluck($categories, 'term_id')
                            );
                        }
                        
                        $related_restaurants = new WP_Query($related_args);
                        
                        if ($related_restaurants->have_posts()) :
                            while ($related_restaurants->have_posts()) :
                                $related_restaurants->the_post();
                                $related_rating = get_post_meta(get_the_ID(), '_restaurant_star_rating', true);
                        ?>
                        <div class="related-restaurant-item">
                            <a href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                <?php endif; ?>
                                <div class="related-info">
                                    <h4><?php the_title(); ?></h4>
                                    <?php if ($related_rating) : ?>
                                        <div class="related-rating">
                                            <?php echo zhiyara_display_stars($related_rating); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                        <?php 
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

<?php endwhile; ?>

<?php get_footer(); ?>
