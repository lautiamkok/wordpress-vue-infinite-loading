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
// Get requested language.
$lang = get_query_var('lang');
?>

<form class="form-search" v-on:submit.prevent="searchByKeywords">
  <div class="grid-container full-height">
    <div class="grid-x grid-padding-x align-middle small-padding-collapse">

        <div class="flex-item container-input position-relative active">
            <input type="text" placeholder="Search" class="input-local-search">
        </div>

        <div class="flex-item">
            <a href="#" class="button-search float-right flip flex-search button-local-search" data-posts-endpoint="<?php echo $endpoint; ?>" v-on:click.prevent="searchByKeywords"><i class="fi-magnifying-glass"></i></a>
        </div>

    </div>
  </div>
</form>
