<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>راهنمای ژیارا</h3>
                <p>جامع‌ترین مرجع رستوران‌های شهر</p>
                <p>با ما بهترین تجربه غذایی را داشته باشید</p>
            </div>
            
            <div class="footer-section">
                <h3>لینک‌های مفید</h3>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">خانه</a></li>
                    <li><a href="<?php echo esc_url(home_url('/restaurant/')); ?>">رستوران‌ها</a></li>
                    <li><a href="<?php echo esc_url(home_url('/about/')); ?>">درباره ما</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">تماس با ما</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>دسته‌بندی‌ها</h3>
                <ul>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'restaurant_category',
                        'hide_empty' => false,
                        'number' => 5
                    ));
                    if ($categories && !is_wp_error($categories)) :
                        foreach ($categories as $category) :
                    ?>
                    <li><a href="<?php echo get_term_link($category); ?>"><?php echo $category->name; ?></a></li>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>تماس با ما</h3>
                <p>📧 info@zhiyara.com</p>
                <p>📞 ۰۲۱-۱۲۳۴۵۶۷۸</p>
                <p>📍 تهران، ایران</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> راهنمای رستوران‌های ژیارا. تمامی حقوق محفوظ است.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
