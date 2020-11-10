<?php
/**
 * Template Name: TKT Content
 * Description: TKT Content page template
 *
 * @package tkt
 * @author the key technology
 */

get_header();
?>

    <!-- #Content -->
    <div id="Content">
        <div class="content_wrapper clearfix">



            <?php ob_start(); ?>


            <!-- .sections_group -->
            <div class="sections_group">

                <div class="entry-content tk-content-padding" itemprop="mainContentOfPage">

                    <?php
                    while (have_posts()) {
                        the_post();                            // Post Loop
                        tkMfnBuilderPrint(get_the_ID());    // Content Builder & WordPress Editor Content
                    }
                    /*if (function_exists("get_yuzo_related_posts")) {
                        get_yuzo_related_posts();
                    }*/
                    ?>

                </div>

                <?php if (mfn_opts_get('page-comments')): ?>
                    <div class="section section-page-comments">
                        <div class="section_wrapper clearfix">

                            <div class="column one comments">
                                <?php comments_template('', true); ?>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <?php $output = ob_get_clean( );

            $output = add_ids_to_header_tags($output); ?>


            <!-- .four-columns - sidebar -->
            <?php get_sidebar("Inhalt"); ?>


            <?php echo $output; ?>


        </div>
    </div>

<?php get_footer();

// Omit Closing PHP Tags
