<?php $uri = Request::getUri(); ?>
<aside id="sidebar"> 
  <a class="info-n<?php if($uri == '/info') { ?> active<?php } ?>" href="/info">&#8226; <?= lang('Info'); ?></a>
  <a class="info-n<?php if($uri == '/info/privacy') { ?> active<?php } ?>" href="/info/privacy">&#8226; <?= lang('Privacy'); ?></a>
  <a class="info-n<?php if($uri == '/info/markdown') { ?> active<?php } ?>" href="/info/markdown">&#8226; <?= lang('Мarkdown'); ?></a> 
  <a class="info-n<?php if($uri == '/info/about') { ?> active<?php } ?>" href="/info/about">&#8226; <?= lang('About'); ?></a>
  <div class="v-ots"></div>
  <a class="info-n<?php if($uri == '/info/stats') { ?> active<?php } ?>" href="/info/stats">&#8226; <?= lang('Statistics'); ?></a>
</aside>
