<?php

$list = explode("\n", file_get_contents('https://publicsuffix.org/list/public_suffix_list.dat'));

function arrayToCode(array $data, $level = 0):string {
    $output = '['."\n";
    
    $level++;
    
    $tabs = str_repeat ("\t", $level);
    
    foreach($data as $key => $node) {
        $key = is_integer($key) ? '' : '\''.$key.'\' => ';
        $value = (is_array($node)) ? arrayToCode($node, $level) : str_replace("'", "\'", $node);
        $output .= $tabs.$key.((is_string($node)) ? '\''.$value.'\'' : $value).(($key !== array_key_last($data)) ? ', ' : '')."\n";
    }

    $level--;

    $tabs = str_repeat ("\t", $level);

    $output .= $tabs.']';

    return $output;
}

$type = null;
$comments = [];
$domains = [];

foreach ($list as $key => $line) {
    if(mb_strpos($line, '===BEGIN ICANN DOMAINS===')) {
        $type = 'ICANN';
        $comments = [];
        continue;
    }
    
    if(mb_strpos($line, '===END ICANN DOMAINS===')) {
        $type = null;
        continue;
    }
    
    if(mb_strpos($line, '===BEGIN PRIVATE DOMAINS===')) {
        $type = 'PRIVATE';
        $comments = [];
        continue;
    }
    
    if(mb_strpos($line, '===END PRIVATE DOMAINS===')) {
        $type = null;
        continue;
    }

    if(empty($line)) {
        continue;
    }

    if(mb_substr($line, 0, mb_strlen('// ')) === '// ') {
        $comments[] = mb_substr($line, mb_strlen('// '));
        continue;
    }

    $domains[$line] = [
        'suffix' => $line,
        'type' => $type,
        'comments' => $comments,
    ];

    $comments = [];
}

file_put_contents(__DIR__.'/data.php', "<?php\n\nreturn ".arrayToCode($domains).';');