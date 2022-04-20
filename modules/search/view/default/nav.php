<?php $uri = $data['type']; ?>
<main class="main-search col-two">
    <nav>
        <div class="box-flex justify-between">
            <a href="<?= getUrlByName('search.indexer'); ?>" <?= ($uri == 'index' ? 'class="active"' : '') ?>>Statistics</a>
            <a href="<?= getUrlByName('search.admin.query'); ?>" <?= ($uri == 'query' ? 'class="active"' : '') ?>>Query</a>
            <a href="<?= getUrlByName('search.admin.schemas'); ?>" <?= ($uri == 'schemas' ? 'class="active"' : '') ?>>Schemas</a>
        </div>
    </nav>