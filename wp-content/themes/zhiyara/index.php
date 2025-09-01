<?php
/**
 * The main template file for Persian Restaurant Guide
 */

get_header(); ?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo get_theme_mod('hero_title', 'راهنمای رستوران‌های ژیارا'); ?></h1>
                <p><?php echo get_theme_mod('hero_subtitle', 'بهترین رستوران‌ها و کافه‌های شهر را کشف کنید'); ?></p>
                <a href="#categories" class="cta-button">مشاهده رستوران‌ها</a>
            </div>
        </div>
    </section>

    <!-- Restaurant Categories Section -->
    <section class="categories-section" id="categories">
        <div class="container">
            <h2 class="section-title">دسته‌بندی رستوران‌ها</h2>
            <div class="categories-grid">
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'restaurant_category',
                    'hide_empty' => false,
                ));
                
                $category_icons = array(
                    'traditional' => '🏛️',
                    'fast-food' => '🍔',
                    'persian' => '🇮🇷',
                    'chinese' => '🥢',
                    'italian' => '🍝',
                    'french' => '🥖',
                    'seafood' => '🦞',
                    'vegetarian' => '🥗',
                    'cafe' => '☕',
                    'bakery' => '🥐'
                );
                
                if ($categories && !is_wp_error($categories)) :
                    foreach ($categories as $category) :
                        $count = $category->count;
                        $icon = isset($category_icons[$category->slug]) ? $category_icons[$category->slug] : '🍽️';
                ?>
                <a href="<?php echo get_term_link($category); ?>" class="category-card">
                    <span class="category-icon"><?php echo $icon; ?></span>
                    <h3><?php echo $category->name; ?></h3>
                    <p><?php echo $category->description ? $category->description : 'بهترین ' . $category->name . ' شهر'; ?></p>
                    <span class="category-count"><?php echo $count; ?> رستوران</span>
                </a>
                <?php 
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-controls">
                <div class="filter-control">
                    <label for="category-filter">دسته‌بندی:</label>
                    <select id="category-filter">
                        <option value="">همه دسته‌ها</option>
                        <?php
                        if ($categories && !is_wp_error($categories)) :
                            foreach ($categories as $category) :
                        ?>
                        <option value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></option>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                
                <div class="filter-control">
                    <label for="cuisine-filter">نوع غذا:</label>
                    <select id="cuisine-filter">
                        <option value="">همه انواع</option>
                        <?php
                        $cuisines = get_terms(array(
                            'taxonomy' => 'cuisine_type',
                            'hide_empty' => false,
                        ));
                        if ($cuisines && !is_wp_error($cuisines)) :
                            foreach ($cuisines as $cuisine) :
                        ?>
                        <option value="<?php echo $cuisine->slug; ?>"><?php echo $cuisine->name; ?></option>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                
                <div class="filter-control">
                    <label for="location-filter">مکان:</label>
                    <select id="location-filter">
                        <option value="">همه مکان‌ها</option>
                        <?php
                        $locations = get_terms(array(
                            'taxonomy' => 'restaurant_location',
                            'hide_empty' => false,
                        ));
                        if ($locations && !is_wp_error($locations)) :
                            foreach ($locations as $location) :
                        ?>
                        <option value="<?php echo $location->slug; ?>"><?php echo $location->name; ?></option>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                
                <div class="filter-control">
                    <label for="stars-filter">حداقل امتیاز:</label>
                    <select id="stars-filter">
                        <option value="">همه امتیازها</option>
                        <option value="5">⭐⭐⭐⭐⭐ (۵ ستاره)</option>
                        <option value="4">⭐⭐⭐⭐ (۴ ستاره و بالاتر)</option>
                        <option value="3">⭐⭐⭐ (۳ ستاره و بالاتر)</option>
                        <option value="2">⭐⭐ (۲ ستاره و بالاتر)</option>
                        <option value="1">⭐ (۱ ستاره و بالاتر)</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-buttons">
                <button id="apply-filters" class="filter-btn">اعمال فیلتر</button>
                <button id="clear-filters" class="filter-btn clear-btn">پاک کردن فیلتر</button>
            </div>
        </div>
    </section>

    <!-- Featured Restaurants Section -->
    <section class="restaurants-section">
        <div class="container">
            <h2 class="section-title">رستوران‌های برتر</h2>
            <div id="restaurants-grid" class="restaurants-grid">
                <?php
                $featured_restaurants = new WP_Query(array(
                    'post_type' => 'restaurant',
                    'posts_per_page' => 12,
                    'post_status' => 'publish',
                    'meta_query' => array(
                        array(
                            'key' => '_restaurant_star_rating',
                            'value' => '4',
                            'compare' => '>='
                        )
                    )
                ));
                
                if ($featured_restaurants->have_posts()) :
                    while ($featured_restaurants->have_posts()) :
                        $featured_restaurants->the_post();
                        get_template_part('template-parts/restaurant-card');
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <div class="no-restaurants">
                        <p>هنوز رستورانی اضافه نشده است. لطفاً بعداً مراجعه کنید.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div id="loading" class="loading" style="display: none;"></div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>درباره راهنمای ژیارا</h2>
                    <p>راهنمای رستوران‌های ژیارا، جامع‌ترین مرجع برای کشف بهترین رستوران‌ها و کافه‌های شهر است. ما با دقت و تخصص، رستوران‌ها را بررسی و امتیازدهی می‌کنیم تا شما بتوانید بهترین تجربه غذایی را داشته باشید.</p>
                    <p>تیم ما متشکل از کارشناسان مجرب در زمینه غذا و رستوران‌داری است که هر رستوران را بر اساس معیارهای دقیق مانند کیفیت غذا، سرویس، فضا و قیمت ارزیابی می‌کنند.</p>
                    <a href="/about" class="cta-button">بیشتر بدانید</a>
                </div>
                <div class="about-image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/about-zhiyara.jpg" alt="درباره ژیارا" />
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
