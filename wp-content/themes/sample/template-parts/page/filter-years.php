<?php
/**
 * The template used for displaying the filter
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<?php
// Get years.
$years = get_posts_years($postType = $type);
?>

<?php if (count($years) > 0) { ?>
<?php foreach ($years as $key => $year) { ?>
    <li class="child"><a href="#" data-year="<?php echo $year; ?>" v-on:click.prevent="filterByYear"><?php echo $year; ?></a></li>
<?php } ?>
<?php } ?>
