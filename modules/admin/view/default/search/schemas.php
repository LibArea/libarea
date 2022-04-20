<?= includeTemplate('/view/default/search/nav', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<?php
$schemas = $data['schemas'] ?? [];

function getFieldType($schemaField){
    $result = [];
    $type = $schemaField['_type'] ?? '';
    $subType = $schemaField['_type.'] ?? '';
    $indexed = $schemaField['_indexed'] ?? false;
    $filterable = $schemaField['_filterable'] ?? false;
    if($filterable && !$indexed){
        $result[] = '<span class="schema-error">WARNING : field is \'_filterable\' but not \'_indexed\'. undefined behavior may occur</span>';
    }
    if(!empty($type)){
        if($type == 'array' || $subType == 'array'){
            if(isset($schemaField['_array'])){
                $result[] = displaySchema('',$schemaField['_array']);
            } else {
                return '<span class="schema-error">INVALID SCHEMA : field is of type \'array\' without an \'_array\' entry</span>';
            }
        } else {
            if($type == 'list'){
                if(!empty($subType)){
                    $result[] = ($indexed ? 'Indexed ':'').'<span class="schema-type">list of '. $subType.'</span>'.($filterable ? ' (Filterable)' :'');
                } else {
                    return '<span class="schema-error">INVALID SCHEMA : field is of type \'list\' without a \'_type.\' entry</span>';
                }
            } else {
                $result[] = ($indexed ? 'Indexed ':'').'<span class="schema-type">'.$type.'</span>'.($filterable ? ' (Filterable)' :'');
            }
            if($schemaField['_boost'] ?? false){
                $result[] = 'Boost = '.$schemaField['_boost'];
            }
        }
    } else {
        return '<span class="schema-error">INVALID SCHEMA : field don\'t have a \'_type\' entry</span>';
    }

    return implode('<br>',$result);
}

function prefillType($definitions, $name = 'value'){
    if(!empty($definitions['_type'] ?? '')){
        switch ($definitions['_type']){
            case 'datetime':
                return '@@@DateTime:'.time();
                break;
            case 'list':
                if(!empty($definitions['_type.'] ?? '')){
                    return [
                        prefillType(['_type'=>$definitions['_type.'],'_array'=>$definitions['_array'] ?? []], $name),
                        prefillType(['_type'=>$definitions['_type.'],'_array'=>$definitions['_array'] ?? []], $name)
                    ];
                }
                break;
            case 'array':
                if(!empty($definitions['_array'] ?? [])){
                    return prefillSchema($definitions['_array']);
                }
                break;
            default:
                return "my ".$name;
        }
    }
}

function prefillSchema($schema, $schemaName = null){
    $prefill = [];
    if(isset($schemaName)){
        $prefill = [
            "id" => 0,
            "type" => $schemaName
        ];
    }
    foreach($schema as $name=>$definitions){
        $prefill[$name] = prefillType($definitions, $name);
    }
    return $prefill;
}

function displaySchema($name, $schema){
    $html = '<form method="POST" action="/edit">';
    if(!empty($name)){
        $html .= '<table style="width:100%;"><input title="Create a document" type="submit" value="'.$name.'"/><tbody>';
    } else {
        $html .= '<table style="width:100%;"><tbody>';
    }

    foreach($schema as $fieldName=>$value){
        $html .= '<tr><td>'.$fieldName.'</td>';
        $html .= '<td>'.getFieldType($value).'</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';
    if(!empty($name)){
        $html .= '<input type="hidden" name="prefill" value="'.htmlspecialchars(json_encode(prefillSchema($schema, $name),JSON_PRETTY_PRINT)).'" />';
        $html .= '</form>';
    }
    return $html;
}
?>
<div class="box bg-white">
     <h2>Schema Debugging</h2>
    <?php
    echo '<div class="container">';
    foreach($schemas as $schemaName => $schema){
        echo '<div class="flex-item">';
        echo displaySchema($schemaName, $schema);
        echo '</div>';
    }
    echo '</div>';
    ?>
</div>    
</main>

<?= includeTemplate('/view/default/footer'); ?>