<?php
/*
 * Search engine setup
 * Настройка поисковой системы
 */

use Modules\Search\App\Tokenizers\AlphaNumericTokenizer;
use Modules\Search\App\Tokenizers\DateFormatTokenizer;
use Modules\Search\App\Tokenizers\DateSplitTokenizer;
use Modules\Search\App\Tokenizers\LowerCaseTokenizer;
use Modules\Search\App\Tokenizers\RemoveAccentsTokenizer;
use Modules\Search\App\Tokenizers\singleQuoteTokenizer;
use Modules\Search\App\Tokenizers\WhiteSpaceTokenizer;

use Modules\Search\App\Tokenizers\RussianStemmingTokenizer;
use Modules\Search\App\Tokenizers\RussianStopWordsTokenizer;

return [
    'config' => [
        'var_dir' => HLEB_GLOBAL_DIRECTORY . DIRECTORY_SEPARATOR . 'storage/var',
        'index_dir' => DIRECTORY_SEPARATOR . 'engine' . DIRECTORY_SEPARATOR . 'index',
        'documents_dir' => DIRECTORY_SEPARATOR . 'engine' . DIRECTORY_SEPARATOR . 'documents',
        'cache_dir' => DIRECTORY_SEPARATOR . 'engine' . DIRECTORY_SEPARATOR . 'cache',
        'fuzzy_cost' => 1,
        'approximate_limit' => 5,
        'connex' => [
            'threshold' => 0.9,
            'min' => 3,
            'max' => 10,
            'limitToken' => 20,
            'limitDocs' => 10
        ],
        'serializableObjects' => [

            DateTime::class => function ($datetime) {
                /** @var DateTime $datetime */ return $datetime->getTimestamp();
            }
        ]
    ],
    'schemas' => [
        'example-url' => [
            'title' => [
                '_type' => 'string',
                '_indexed' => true,
                '_boost' => 10
            ],
            'content' => [
                '_type' => 'text',
                '_indexed' => true,
                '_boost' => 0.5
            ],
            'url' => [
                '_type' => 'string',
                '_indexed' => true,
                '_boost' => 6
            ],
            'domain' => [
                '_type' => 'string',
                '_indexed' => true,
                '_boost' => 0.5
            ],
            'cat' => [
                '_type' => 'list',
                '_type.' => 'string',
                '_indexed' => true,
                '_filterable' => true,
                '_boost' => 6
            ],

        ]
    ],
    'types' => [
    //    'datetime' => [
    //        DateFormatTokenizer::class,
    //        DateSplitTokenizer::class
    //    ],
        '_default' => [
            RemoveAccentsTokenizer::class,
            LowerCaseTokenizer::class,
            WhiteSpaceTokenizer::class,
            singleQuoteTokenizer::class,
            AlphaNumericTokenizer::class,

            RussianStemmingTokenizer::class,
            RussianStopWordsTokenizer::class,
        ]
    ]
];
