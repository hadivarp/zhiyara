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
                <h1>راهنمای ژیارا</h1>
                <p class="hero-subtitle">🏍️ نگاه یک موتورسوار ناشناس به رستوران‌های شهر</p>
                <div class="zhiyara-story">
                    <p class="story-text">
                        ژیارا، موتورسوار مرموزی است که مثل بازرسان راهنمای میشلن، ناشناس و بی‌طرف رستوران‌ها را از نگاه یک موتورسوار بررسی می‌کند.
                        هر نقد، داستان یک سفر موتورسواری و تجربه‌ای واقعی است.
                    </p>
                </div>
                <div class="hero-search">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
                        <input type="search" name="s" placeholder="جستجوی رستوران..." value="<?php echo get_search_query(); ?>" class="search-field">
                        <button type="submit" class="search-button">🔍 جستجو</button>
                    </form>
                </div>
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

    <!-- About Zhiyara Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>🏍️ کیست ژیارا؟</h2>
                    <p>ژیارا موتورسوار مرموزی است که شبیه بازرسان ناشناس راهنمای میشلن، بدون اعلام هویت به رستوران‌ها می‌رود و آن‌ها را از دیدگاه یک موتورسوار واقعی بررسی می‌کند.</p>
                    <p>او با موتورش در خیابان‌های شهر می‌چرخد، رستوران‌ها را کشف می‌کند و تجربه‌های صادقانه‌ای از کیفیت غذا، سرویس، و مهم‌تر از همه، میزان دوستی رستوران با موتورسواران ارائه می‌دهد.</p>
                    <div class="zhiyara-features">
                        <div class="feature">🎭 هویت ناشناس</div>
                        <div class="feature">🏍️ نگاه موتورسوار</div>
                        <div class="feature">⭐ ارزیابی صادقانه</div>
                        <div class="feature">🛣️ تجربه واقعی</div>
                    </div>
                    <a href="/about" class="cta-button">داستان ژیارا</a>
                </div>
                <div class="about-image">
                    <div class="zhiyara-silhouette">
                        <div class="motorcycle-icon">🏍️</div>
                        <p class="mystery-text">موتورسوار ناشناس شهر</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
