<?= includeTemplate('/view/default/search/nav', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<?php

$stats = $data['stats'] ?? [];

function displayConfigArray($array, $prefix = '')
{
    $dictionary = [
        'var_dir' => 'Base directory for the engine',
        'index_dir' => 'Subdirectory of var_dir where the index will be stored',
        'documents_dir' => 'Subdirectory of var_dir where documents will be stored',
        'cache_dir' => 'Subdirectory of var_dir where the cache will be stored',
        'fuzzy_cost' => 'Define how many iterations of approximation will be ran on any non-found tokens. Greater is more CPU-intensive and too much won\'t help find accurately',
        'approximate_limit' => '(-1 = infinite) Define how many times the approximate function will be ran, per token. This function is CPU intensive and we don\'t want it to run in loop when the user typed nonsense',
        'connex.threshold' => '(percentage 0-1) Every document with a score that matches this threshold will be included to the connex search',
        'connex.min' => 'Minimum number of documents that will be internally included into the connex search',
        'connex.max' => 'Maximum number of documents that will be internally included into the connex search',
        'connex.limitToken' => 'Maximum number of tokens that will be retained in the connex search',
        'connex.limitDocs' => 'Maximum number of documents that\'ll be returned from the connex search'
    ];
    foreach ($array as $name => $value) {
        if (is_array($value)) {
            displayConfigArray($value, $name . '.');
        } elseif (is_string($value) || is_numeric($value)) {
            $title = '';
            if (isset($dictionary[$prefix . $name])) {
                $title = $dictionary[$prefix . $name];
            }
            echo '<tr title="' . $title . '"><td>' . $prefix . $name . '</td><td>' . $value . '</td></tr>';
        }
    }
}

?>

<div class="box bg-white">
    <h2><?= __('statistics'); ?></h2>
    <table style="width:100%">
        <tbody>
            <tr>
                <td>Total Indexed documents</td>
                <td><?php echo $stats['documentCount'] ?></td>
            </tr>
            <tr>
                <td>Total Indexed tokens</td>
                <td><?php echo $stats['totalTokens'] ?></td>
            </tr>
            <tr>
                <td>Cache Entries</td>
                <td><?php echo $stats['cacheEntries'] ?></td>
            </tr>
            <tr>
                <td>Schemas</td>
                <td><?php echo implode(', ', array_keys($stats['schemas'])) ?></td>
            </tr>
            <tr>
                <td>Types</td>
                <td><?php echo implode(', ', array_keys($stats['types'])) ?></td>
            </tr>
        </tbody>
    </table>
    <h2>Configuration</h2>
    <table class="hover" style="width:100%">
        <tbody>
            <?php
            displayConfigArray($stats['config']);
            ?>
        </tbody>
    </table>
</div>

</main>

<?= includeTemplate('/view/default/footer'); ?>