<?= includeTemplate('/view/default/search/nav', ['data' => $data, 'meta' => $meta]); ?>

<?php

use Hleb\Constructor\Handlers\Request;

$results = $data['results'] ?? [];
$sw = $sw ?? '?';

function displayValue($value)
{
    if (is_a($value, DateTime::class)) {
        return $value->format(DATE_ATOM);
    } elseif (is_array($value)) {
        return getFields($value);
    } else {
        return $value;
    }
}

function getFields($fields, $id = 0, $isDocument = false)
{
    $title = $fields['title'] ?? $fields['name'] ?? '';
    if (empty($title)) $title = (isset($fields['id']) ? 'Document ID = ' . $fields['id'] : '');
    if ($isDocument && !empty($title)) $title = '<a title="' . __('admin.edit') . '" href="' . url('admin.search.edit.form', ['id' => $id]) . '">' . $title . '</a>';
    $html = '<table class="mb0">' . $title . '<tbody>';
    foreach ($fields as $field => $value) {
        $html .= '<tr><td>';
        if (!is_numeric($field)) {
            if ($field == 'content') {
                $value = Html::fragment(Content::text($value, 'line'), 258);
            }
            $html .= $field . '</td><td>';
        }
        $html .= displayValue($value);
        $html .= '</td></tr>';
    }
    $html .= '</tbody></table>';
    return $html;
}

?>

<form action="">
    <div class="box bg-white">
        <h2><?= __('admin.search'); ?></h2>
        <div class="box-flex justify-between">
            <div class="container-v">
                <label for="q">query</label>
                <input id="q" class="query" type="text" name="q" value="<?= Request::getGet('q') ?? ''; ?>">
            </div>
            <div class="container-v">
                <label for="limit">limit</label>
                <input id="limit" type="number" name="limit" value="<?= Request::getGet('limit') ?? 10; ?>">
            </div>
            <div class="container-v">
                <label for="offset">offset</label>
                <input id="offset" type="number" name="offset" value="<?= Request::getGetInt('offset') ?? 0; ?>">
            </div>
            <div class="container-v">
                <label for="facets">cat</label>
                <input id="facets" type="text" name="cat" value="<?= Request::getGet('cat') ?? ''; ?>">
            </div>
        </div>
        <div class="box-flex justify-between">
            <div>
                <input id="connex" type="checkbox" name="connex" value="1" <?= (Request::getGet('connex') ?? false) ? 'checked' : '' ?>>
                <label for="connex">Enable Connex <?= __('admin.search'); ?></label>
            </div>
            <div class="container-v">
                <input class="btn btn-primary" type="submit" value="<?= __('admin.search'); ?>" style="">
            </div>
        </div>
    </div>
    <div class="container">
        <?php if (!empty($results['facets'])) : ?>
            <div class="container-v">
                <?php if (!empty($results['facets'])) : ?>
                    <div>
                        <h3>Facets</h3>
                        <div class="container-v">
                            <?php
                            foreach ($results['facets'] as $name => $values) {
                                echo '<div><h4>' . $name . '</h4><div style="margin-top:-20px;" class="container-v">';
                                $i = 0;
                                foreach ($values as $value => $count) {
                                    echo '<div>';
                                    echo "<input type='checkbox' id='facet-$name-$i' name='facet-" . $name . "[]' value='$value' " . (in_array($value, $_GET['facet-' . $name] ?? []) ? 'checked' : '') . " onclick='this.form.submit();' />";
                                    echo "<label for='facet-$name-$i'>$value ($count)</label>";
                                    echo '</div>';
                                    $i++;
                                }
                                echo '</div></div>';
                            }
                            ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        <?php endif ?>
        <div>
            <p class="ml5"><?php echo $results['numFound'] ?> Documents found in <?php echo $data['sw']; ?> ms</p>
            <div class="container">
                <?php
                foreach ($results['documents'] as $id => $result) {
                    echo '<div class="box bg-white">' . getFields($result, $id, true) . '</div>';
                }
                ?>
            </div>
            <?php if (!empty($results['connex'])) : ?>
                <div class="flex-delimiter">
                    <h3>Connex <?= __('admin.search'); ?></h3>
                </div>
                <div>
                    <div class="container">
                        <?php
                        if (empty($results['connex']['documents'])) {
                            echo '<p>Nothing relevant found</p>';
                        } else {
                            foreach ($results['connex']['documents'] as $id => $result) {
                                echo '<div class="flex-item">' . getFields($result, $id, true) . '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</form>


</main>

<?= includeTemplate('/view/default/footer'); ?>