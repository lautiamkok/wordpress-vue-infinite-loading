<?php
/**
 * The template used for displaying the article attributes
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<?php
// Get content source.
// Get the taxonomy that attached to the post.
$itemsSource = get_the_terms($post, $taxonomySource);

// Get requested language.
$lang = get_query_var('lang');

// Get content source name.
$contentSource = get_field('content_source', $post->ID);

// Get authors.
// http://www.developerdrive.com/2016/09/how-to-incorporate-co-authors-plus-into-your-wordpress-theme/
?>

<div class="large-7 medium-6 cell">
    <div class="article-author"><?php echo translateText(get_field('author_by', 'option'), $lang); ?>
        <?php coauthors(); ?>
    </div>
    <div class="article-source published-publishers">

        <?php if ($itemsSource && count($itemsSource) > 0) { ?>
            <?php foreach ($itemsSource as $key => $itemSource) { ?>
                <span class="published-publisher"><?php echo $itemSource->name; ?></span>
            <?php } ?>
        <?php } ?>

        <?php if ($contentSource) { ?>
            <span class="published-publisher"><?php echo $contentSource; ?></span>
        <?php } ?>

        <?php if ($itemsSource && count($itemsSource) > 0 || $contentSource) { ?>
        &#124;
        <?php } ?>
        <?php echo get_the_date('j F Y', $post); ?>
    </div>
</div>

<div class="large-5 medium-6 cell hide-for-small-only">

    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5b215e1d1f252fb7"></script>

    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <div class="addthis_inline_share_toolbox float-right-for-medium container-addthis"></div>

</div>
