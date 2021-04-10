<?php $uri = Request::getUri(); ?>
<aside id="sidebar"> 
    <div class="menu-info">
        <a class="info-n<?php if($uri == '/info') { ?> active<?php } ?>" href="/info">~ <?= lang('Info'); ?></a>
        <a class="info-n<?php if($uri == '/info/privacy') { ?> active<?php } ?>" href="/info/privacy">~ <?= lang('Privacy'); ?></a>
        <a class="info-n<?php if($uri == '/info/markdown') { ?> active<?php } ?>" href="/info/markdown">~ <?= lang('Мarkdown'); ?></a> 
        <a class="info-n<?php if($uri == '/info/about') { ?> active<?php } ?>" href="/info/about">~ <?= lang('About'); ?></a>
        <div class="v-ots"></div>
        <a class="info-n<?php if($uri == '/info/stats') { ?> active<?php } ?>" href="/info/stats">~ <?= lang('Statistics'); ?></a>
    </div>
</aside>
