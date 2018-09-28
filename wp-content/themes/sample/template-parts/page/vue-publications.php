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

<template v-if="items">

    <!-- use div with vue else or there is a problem - dunno why! -->
    <div>

        <!-- publications -->
        <div class="container-publications">

        <!-- vue - loop -->
        <!-- https://laracasts.com/discuss/channels/vue/use-v-for-without-wrapping-element -->
        <template v-for="item in items">

            <div class="published-item">
                <h5 class="heading-published"><a :href="item.url" v-html="item.title"></a></h5>
                <div class="published-publishers">
                    <template v-for="tax in item.taxonomy">
                        <span class="published-publisher" v-html="tax.name"></span>
                    </template>
                </div>
                <span class="published-date">{{ item.date }}</span>
            </div>

        </template>
        <!-- vue - loop -->

        </div>
        <!-- publications -->

    </div>
    <!-- use div with vue else or there is a problem - dunno why! -->

    <div class="container-spinner">
        <div v-if="loading === true" class="sk-circle">
          <div class="sk-circle1 sk-child"></div>
          <div class="sk-circle2 sk-child"></div>
          <div class="sk-circle3 sk-child"></div>
          <div class="sk-circle4 sk-child"></div>
          <div class="sk-circle5 sk-child"></div>
          <div class="sk-circle6 sk-child"></div>
          <div class="sk-circle7 sk-child"></div>
          <div class="sk-circle8 sk-child"></div>
          <div class="sk-circle9 sk-child"></div>
          <div class="sk-circle10 sk-child"></div>
          <div class="sk-circle11 sk-child"></div>
          <div class="sk-circle12 sk-child"></div>
       </div>
    </div>

</template>
