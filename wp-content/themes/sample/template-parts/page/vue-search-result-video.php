<?php
/**
 * Displays publications with vue template.
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<!-- publications -->
<div class="container-publications">

    <h3 class="heading-group">Videos</h3>

    <div class="published-item">

        <h5 class="heading-published">
            <a :href="item.url"><i class="fi-play-circle icon-play"></i><span v-html="item.title"></span></a>
        </h5>
        <span class="published-date">{{ item.date }}</span>
        <span class="published-publisher" v-if="item.contentSource" v-html="item.contentSource"></span>
    </div>

</div>
<!-- publications -->
