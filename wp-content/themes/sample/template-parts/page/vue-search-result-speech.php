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

    <h3 class="heading-group">Speeches</h3>

    <div class="published-item">
        <h5 class="heading-published"><a :href="item.url" v-html="item.title"></a></h5>
        <span class="published-date">{{ item.date }}</span>
        <div class="published-publishers">
            <template v-for="tax in item.taxonomy">
                <span class="published-publisher" v-html="tax.name"></span>
            </template>
        </div>
    </div>

</div>
<!-- publications -->
