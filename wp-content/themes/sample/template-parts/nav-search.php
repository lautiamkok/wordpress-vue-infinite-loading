<?php
/**
 * The template used for displaying global search form.
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<?php
// Get requested language.
$lang = get_query_var('lang');
?>

<form class="form-search" role="search" method="get" action="<?php echo switchUrl(site_url(), $lang); ?>">
  <div class="grid-container full-height">
    <div class="grid-x grid-padding-x align-middle small-padding-collapse">

        <div class="flex-item">
            <a href="#" class="button-search button-show-search float-right flip flex-search"><i class="fi-magnifying-glass"></i></a>
        </div>

        <div class="flex-item container-input position-relative">
            <input type="text" placeholder="<?php echo translateText(get_field('search', 'option'), $lang);?>" class="hide" name="s">
        </div>

    </div>
  </div>
</form>
