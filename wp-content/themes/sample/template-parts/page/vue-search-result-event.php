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

    <h3 class="heading-group">Events</h3>

    <div class="published-item">

        <h5 class="heading-published">
            <a :href="item.url" v-html="item.title"></a>
        </h5>
        <span class="published-publisher" v-if="item.contentSource" v-html="item.contentSource"></span>
        <span class="published-date">{{ item.date }}</span>
        <p class="book-description" style="margin-top: 1em; margin-bottom: 1em;" v-html="item.excerpt"></p>

        <nav class="nav-book">
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

</div>
<!-- publications -->
