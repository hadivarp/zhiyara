<?php
/**
 * The template for displaying search results
 */

get_header(); ?>

<main class="main-content">
    <section class="search-results-section">
        <div class="container">
            <header class="search-header">
                <h1 class="search-title">
                    <?php
                    printf(
                        'نتایج جستجو برای: %s',
                        '<span>' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
                
                <?php if (have_posts()) : ?>
                    <p class="search-count"><?php echo $wp_query->found_posts; ?> نتیجه یافت شد</p>
                <?php endif; ?>
            </header>

            <div class="search-results">
                <?php if (have_posts()) : ?>
                    <div class="restaurants-grid">
                        <?php while (have_posts()) : the_post(); ?>
                            <?php get_template_part('template-parts/restaurant-card'); ?>
                        <?php endwhile; ?>
                    </div>
                    
                    <?php if ($wp_query->max_num_pages > 1) : ?>
                        <div class="pagination-wrapper">
                            <?php
                            echo paginate_links(array(
                                'total' => $wp_query->max_num_pages,
                                'current' => max(1, get_query_var('paged')),
                                'prev_text' => '← قبلی',
                                'next_text' => 'بعدی →',
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="no-results">
                        <h2>نتیجه‌ای یافت نشد</h2>
                        <p>متأسفانه هیچ رستورانی با این عبارت جستجو یافت نشد.</p>
                        <a href="<?php echo esc_url(home_url('/restaurant/')); ?>" class="cta-button">مشاهده همه رستوران‌ها</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
