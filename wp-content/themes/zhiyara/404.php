<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header(); ?>

<main class="main-content">
    <section class="error-404-section">
        <div class="container">
            <div class="error-content">
                <h1 class="error-title">۴۰۴</h1>
                <h2>صفحه یافت نشد</h2>
                <p>متأسفانه صفحه‌ای که دنبال آن می‌گردید وجود ندارد.</p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="cta-button">بازگشت به خانه</a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
