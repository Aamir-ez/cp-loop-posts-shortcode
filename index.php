<?php
/*
 * Plugin Name: CP Custom Posts Loop Layout 
 * Author: CodePoint
 * Author URI: n/a
 * Version: 1
 * Description: A simple plugin that provides a shortcode that outputs custom posts loop with custom layout
 */

add_shortcode('cp_posts_query', 'cp_posts_query');

function cp_posts_query() {

    $paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
    $posts_query = new WP_Query(array('post_type' => 'post',
        'posts_per_page' => 30,
        'paged' => $paged)
    );

    ob_start();
    ?>
    <style>
        .cp_loop_posts *{font-size:13px;}
        .cp_loop_posts h6 a{font-size:15px;font-weight: 500;}
        .cp_loop_post_single{border-bottom:1px dotted #CECECE;padding-top:20px;padding-bottom:20px;}
        .cp_post_title a{color:#555!important}
        .cp_post_author{color:#555!important;text-decoration:underline}
        .the_links{position:relative;float:right}.the_links a{color:#9e1818;display:inline-block}
        .pagination{display: table;    bottom: 15px;    position: relative;    margin-top: 80px;    margin-bottom: 35px;}
        .pagination span, .pagination a{padding:5px;margin:0 1px;border-radius:1px;}
        .pagination a{border:1px solid #3B3B37;color:#E3E3E3;background: #3B3B37;} 
        .pagination span, .pagination a:hover{border:1px solid #9e1818;color:#fff !important;background-color:#9e1818;}
    </style>
    <div class="cp_loop_posts">
        <?php
        $pn = 1;
        while ($posts_query->have_posts()) {
            $posts_query->the_post();
            ?>
            <div class="cp_loop_post_single">

                <h6 class="cp_post_title">
                    <a href="<?php echo get_the_permalink(); ?>">
                        <?php echo $pn ?>.&nbsp;<?php echo the_title(); ?>
                    </a>
                </h6>
                <div class="cp_post_meta">
                    <div class="cp_post_author">
                        <?php echo ucfirst(get_the_author()); ?>
                    </div>
                    <?php
                    $total_pages = get_field('pages');
                    $cats = get_the_category(get_the_id());
//                    print_r($cats);
                    ?>
                    <div class="cp_post_info">
                        <?php echo isset($cats[0]->name) ? ' <em>Under:</em> ' . $cats[0]->name : ''; ?>&nbsp;&nbsp;<?php if ($total_pages != ""): ?><em>Pages</em> <?php echo $total_pages; ?><?php endif; ?>
                    </div>
                </div>
                <div class="the_links">
                    <a class="cp_post_link" href="<?php echo get_the_permalink(); ?>">
                        Abstract
                    </a> <?php $pdf_link = get_field('pdf'); ?>
                    <?php if ($pdf_link != "") : ?>|
                        <a class="cp_post_link" href="<?php echo $pdf_link; ?>" target="_blank">
                            Full Text PDF
                        </a>
                    <?php endif; ?></div>
                <div class="clearfix">&nbsp;</div>
            </div>
            <?php
            $pn++;
        }
        ?>

        <div class="pagination">
            <?php pagination_bar($posts_query); ?>
        </div>

    </div>
    <?php
    $html = ob_get_clean();
    echo $html;
}

function pagination_bar($query_wp) {
    $pages = $query_wp->max_num_pages;
    $big = 999999999; // need an unlikely integer
    if ($pages > 1) {
        $page_current = max(1, get_query_var('page'));
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => $page_current,
            'total' => $pages,
        ));
    }
}
