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

    <!-- row list -->
    <div class="row row-card">
        <div class="grid-container">

            <!-- grid-x -->
            <div class="grid-x grid-padding-x align-stretch">

            <!-- vue - loop -->
            <!-- https://laracasts.com/discuss/channels/vue/use-v-for-without-wrapping-element -->
            <template v-for="item in items">

                <!-- cell -->
                <div class="large-6 medium-6 cell cell-card">

                    <!-- container card -->
                    <div class="container-card bar-bottom" data-aos="fade-up">

                        <!-- card -->
                        <div class="card card-event position-relative position-relative">
                            <div class="card-image">
                                <div class="image-background landscape-image" v-bind:style="{ 'background-image' : 'url(' + item.image.url + ')' }">
                                    <img :src="item.image.url" :alt="item.image.alt">
                                </div>
                            </div>
                            <div class="card-section position-relative">
                                <h4 class="card-heading" v-html="item.title"></h4>

                                <div class="card-meta">
                                <span class="card-source" v-if="item.contentSource" v-html="item.contentSource"></span>

                                <span class="card-date">{{ item.date }}</span>
                                </div>

                                <p class="card-excerpt" v-html="item.excerpt"></p>

                                <!-- container -->
                                <div class="container-nav">
                                    <nav class="nav-card">
                                        <ul class="menu simple">

                                            <template v-for="externalLink in item.externalLinks">

                                                <li><a :href="externalLink.link" class="flex-centre" target="_blank">
                                                        <em class="font-normal" v-html="externalLink.name"></em>
                                                        <i class="material-icons">keyboard_arrow_right</i>
                                                    </a>
                                                </li>

                                            </template>

                                            <template v-for="internalLink in item.internalLinks">

                                                <li><a :href="internalLink.link" class="flex-centre">
                                                        <em class="font-normal" v-html="internalLink.name"></em>
                                                        <i class="material-icons">keyboard_arrow_right</i>
                                                    </a>
                                                </li>

                                            </template>

                                             <template v-for="internalFile in item.internalFiles">

                                                <li><a :href="internalFile.link" class="flex-centre" target="_blank">
                                                        <em class="font-normal" v-html="internalFile.name"></em>
                                                        <i class="material-icons">keyboard_arrow_right</i>
                                                    </a>
                                                </li>

                                            </template>

                                        </ul>
                                    </nav>
                                </div>
                                <!-- container -->

                            </div>
                        </div>
                        <!-- card -->

                    </div>
                    <!-- container card -->

                </div>
                <!-- cell -->

            </template>
            <!-- vue - loop -->

            </div>
            <!-- grid-x -->

        </div>
    </div>
    <!-- row list -->

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
