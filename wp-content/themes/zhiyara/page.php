<?php
/**
 * The template for displaying all pages
 */

get_header(); ?>

<main class="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <article class="page-content">
            <div class="container">
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="page-body">
                    <?php the_content(); ?>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
