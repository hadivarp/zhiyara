<?php
/**
 * Archive template for restaurants
 */

get_header(); ?>

<main class="main-content">
    <!-- Archive Header -->
    <section class="archive-header">
        <div class="container">
            <h1 class="archive-title">
                <?php
                if (is_tax()) {
                    single_term_title();
                } else {
                    echo 'همه رستوران‌ها';
                }
                ?>
            </h1>
            
            <?php if (is_tax() && term_description()) : ?>
                <div class="archive-description">
                    <?php echo term_description(); ?>
                </div>
            <?php endif; ?>
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
                        $categories = get_terms(array(
                            'taxonomy' => 'restaurant_category',
                            'hide_empty' => false,
                        ));
                        if ($categories && !is_wp_error($categories)) :
                            foreach ($categories as $category) :
                                $selected = (is_tax('restaurant_category', $category->slug)) ? 'selected' : '';
                        ?>
                        <option value="<?php echo $category->slug; ?>" <?php echo $selected; ?>><?php echo $category->name; ?></option>
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
                                $selected = (is_tax('cuisine_type', $cuisine->slug)) ? 'selected' : '';
                        ?>
                        <option value="<?php echo $cuisine->slug; ?>" <?php echo $selected; ?>><?php echo $cuisine->name; ?></option>
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
                                $selected = (is_tax('restaurant_location', $location->slug)) ? 'selected' : '';
                        ?>
                        <option value="<?php echo $location->slug; ?>" <?php echo $selected; ?>><?php echo $location->name; ?></option>
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
                
                <div class="filter-control">
                    <label for="sort-filter">مرتب‌سازی:</label>
                    <select id="sort-filter">
                        <option value="date">جدیدترین</option>
                        <option value="rating">بالاترین امتیاز</option>
                        <option value="title">نام (الفبایی)</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-buttons">
                <button id="apply-filters" class="filter-btn">اعمال فیلتر</button>
                <button id="clear-filters" class="filter-btn clear-btn">پاک کردن فیلتر</button>
            </div>
        </div>
    </section>

    <!-- Restaurants Grid -->
    <section class="restaurants-section">
        <div class="container">
            <div class="results-info">
                <p>نمایش <?php echo $wp_query->found_posts; ?> رستوران</p>
            </div>
            
            <div id="restaurants-grid" class="restaurants-grid">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/restaurant-card'); ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="no-restaurants">
                        <p>رستورانی در این دسته‌بندی یافت نشد.</p>
                        <a href="<?php echo esc_url(home_url('/restaurant/')); ?>" class="cta-button">مشاهده همه رستوران‌ها</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div id="loading" class="loading" style="display: none;"></div>
            
            <!-- Pagination -->
            <?php if ($wp_query->max_num_pages > 1) : ?>
                <div class="pagination-wrapper">
                    <?php
                    echo paginate_links(array(
                        'total' => $wp_query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'prev_text' => '← قبلی',
                        'next_text' => 'بعدی →',
                        'mid_size' => 2,
                    ));
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
