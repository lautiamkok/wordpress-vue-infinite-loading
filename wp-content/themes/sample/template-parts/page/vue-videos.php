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
    <!-- publications -->
    <div class="container-videos">

        <!-- grid-x -->
        <div class="grid-x grid-padding-x align-stretch">

        <!-- vue - loop -->
        <!-- https://laracasts.com/discuss/channels/vue/use-v-for-without-wrapping-element -->
        <template v-for="item in items">

            <!-- cell -->
            <div class="large-12 cell cell-card">

                <div class="container-card bar-bottom">

                    <!-- card -->
                    <div class="card card-video">

                        <div class="card-section full-height">

                            <!-- grid-x -->
                            <div class="grid-x grid-padding-x align-stretch">

                                <!-- cell -->
                                <div class="large-6 cell align-self-middle">
                                    <div class="responsive-embed widescreen collapse-margin-bottom">
                                        <iframe width="560" height="315" :src="item.youTubeUrl" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <!-- cell -->

                                <!-- cell -->
                                <div class="large-6 cell">

                                    <div class="container-text full-height">

                                        <!-- grid-x -->
                                        <div class="grid-x grid-padding-x align-stretch full-height">

                                            <!-- cell -->
                                            <div class="small-12 cell align-self-top">

                                                <h3 class="card-heading"><a :href="item.url" v-html="item.title"></a></h3>
                                                <div class="card-meta">
                                                    <span class="card-source" v-if="item.contentSource" v-html="item.contentSource"></span>

                                                    <span class="card-date">{{ item.date }}</span>
                                                </div>
                                                <p class="card-excerpt" v-html="item.excerpt"></p>

                                            </div>
                                            <!-- cell -->

                                            <!-- cell -->
                                            <div class="small-12 cell align-self-bottom">
                                                <div class="container-nav">
                                                    <nav class="nav-card">
                                                        <ul class="menu simple">
                                                            <template v-for="tax in item.taxonomy">
                                                                <li><a :href="'<?php echo switchUrl(site_url(), $lang); ?>videos/' + tax.slug" class="flex-centre uppercase" v-html="tax.name"></a></li>
                                                            </template>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                            <!-- cell -->

                                        </div>
                                        <!-- grid-x -->

                                    </div>

                                </div>
                                <!-- cell -->

                            </div>
                            <!-- grid-x -->

                        </div>

                    </div>
                    <!-- card -->

                </div>

            </div>
            <!-- cell -->

        </template>
        <!-- vue - loop -->

        </div>
        <!-- grid-x -->

    </div>
    <!-- publications -->
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
