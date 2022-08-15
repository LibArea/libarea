<?php
    return [
        'en' => [
            'vowel' => '/(?:[aeiou]|(?<![aeiou])y)+/',
            'consonant' => '/(?:[bcdfghjklmnpqrstvwxz]|(?<=[aeiou])y|^y)/',
            'rvre' => '/^(.*?[aeiou])(.*)$/',
            'alphabet' => '/[^A-Za-z\x{2010}-]/u',
        ]
    ];
?>