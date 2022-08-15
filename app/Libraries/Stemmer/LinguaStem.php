<?php

/**
 * @category        Library
 * @version         1.0.1
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/lingua-stem
 * @copyright       Copyright (c) 2020 by W.D.M.Group, Ukraine (https://wdmg.com.ua/)
 * @copyright       Copyright (c) 2017 by Roman Romadin <romadinr@i.ua>
 * @copyright       Copyright (c) 2005 by Richard Heyes (https://www.phpguru.org/)
 * @copyright       Copyright (c) 2005 by Jon Abernathy, Hostmake LLC
 * @copyright       Copyright (C) 2003 by Aldo Calpini <dada@perl.it>
 * @copyright       Copyright (C) 2004 by Aleksandr Guidrevitch <pillgrim@mail.ru>
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 * См. https://github.com/wdmg/lingua-stem
 * Usage:
 *
 *     $stem = new LinguaStem('en');
 *     print $stem::word($word);
 *
 *     or
 *
 *     $stem = new LinguaStem('en');
 *     print $stem->text($text);
 *
 */

class LinguaStem {

    /**
     * @var int, Default cahce level
     */
    public $caching = 0;

    /**
     * @var string, Locale in ISO 639-1, like `en`, `uk` or `ru`
     */
    protected $_locale;

    /**
     * @var array, RegEx patterns collertion
     */
    protected $_patterns;

    /**
     * @var array, Words basis cache
     */
    private $cache = [];

    /**
     * LinguaStem constructor.
     *
     * @param null $locale
     */
    function __construct($locale = null) {

        mb_internal_encoding('UTF-8');

        if (!is_null($locale) && is_string($locale)) {

            // Sub-string `en-US`, `en_US` to `en`
            $locale = mb_substr($locale, 0, 2);
            $this->_locale = mb_strtolower($locale);

            if ($library = require __DIR__ . '/lib/' . $this->_locale . '.php') {
                if (isset($library[$this->_locale])) {
                    $this->_patterns = $library[$this->_locale];
                }
            } else {
                // Exeption
            }
        }
    }

    /**
     * Performs pattern replacement
     *
     * @param $subject
     * @param $pattern
     * @param $replacement
     * @param int $limit
     * @return bool
     */
    private function stringReplace(&$subject, $pattern, $replacement, $limit = -1)
    {
        $orig = $subject;
        $subject = preg_replace($pattern, $replacement, $subject, $limit);
        return $orig !== $subject;
    }

    /**
     * Performs pattern matching
     *
     * @param $subject
     * @param $pattern
     * @return bool (false)|int
     */
    private function pregMatch($subject, $pattern)
    {
        return preg_match($pattern, $subject);
    }

    /**
     * Processing one words and return word basis
     *
     * @param $word
     * @return string
     */
    public function word($word)
    {
        $word = mb_strtolower($word);


        if (mb_strlen($word) <= 2)
            return $word;

        if ($this->_locale == 'ru')
            $word = str_replace('ё', 'е', $word);

        if ($this->_locale == 'uk')
            $word = str_replace('ґ', 'г', $word);

        # Check against cache of stemmed words
        if ($this->caching && isset($this->_cache[$word])) {
            return $this->_cache[$word];
        }
        
        $stem = $word;
        do {
            $start = null;
            $end = null;
            if (isset($this->_patterns['rvre'])) {

                if (!preg_match($this->_patterns['rvre'], $word, $p))
                    break;

                $start = $p[1];
                $end = $p[2];
            }

            if (!$start || !$end)
                break;
            
            // Step 1
            if ($this->_locale == 'en') {

                // Part a
                if (mb_substr($end, -1) == 's') {
                    $this->stringReplace($end, '/sses/', 'ss')
                    OR $this->stringReplace($end, '/ies/', 'i')
                    OR $this->stringReplace($end, '/ss/', 'ss')
                    OR $this->stringReplace($end, '/s/', '');
                }

                // Part b
                if (
                    mb_substr($end, -2, 1) != 'e'
                    OR !$this->stringReplace($end, '/eed/', 'ee')
                ) { // First rule
                    if (isset($this->_patterns['vowel'])) {

                        // ing and ed
                        if (
                            $this->pregMatch(mb_substr($end, 0, -3), $this->_patterns['vowel']) &&
                                $this->stringReplace($end, '/ing/', '')
                            OR $this->pregMatch(mb_substr($end, 0, -2), $this->_patterns['vowel']) &&
                                $this->stringReplace($end, '/ed/', '')
                        ) { // Note use of && and OR, for precedence reasons

                            // If one of above two test successful
                            if (
                                !$this->stringReplace($end, '/at/', 'ate')
                                AND !$this->stringReplace($end, '/bl/', 'ble')
                                AND !$this->stringReplace($end, '/iz/', 'ize')
                            ) {

                                // Double consonant ending
                                if (isset($this->_patterns['consonant'])) {
                                    if (
                                        $this->doubleConsonant($end, $this->_patterns['consonant'])
                                        AND mb_substr($end, -2) != 'll'
                                        AND mb_substr($end, -2) != 'ss'
                                        AND mb_substr($end, -2) != 'zz'
                                    ) {
                                        $end = mb_substr($end, 0, -1);
                                    } else if (
                                        $this->mCount($end, $this->_patterns['vowel'], $this->_patterns['consonant']) == 1
                                        AND $this->cvcSequence($end, $this->_patterns['vowel'], $this->_patterns['consonant'])
                                    ) {
                                        $end .= 'e';
                                    }
                                }
                            }
                        }

                        // Step 1c
                        if (
                            mb_substr($end, -1) == 'y' &&
                            $this->pregMatch(mb_substr($end, 0, -1), $this->_patterns['vowel'])
                        ) {
                            $this->stringReplace($end, '/y/', 'i');
                        }
                    }
                }

            } else if ($this->_locale == 'ru' || $this->_locale == 'uk') {

                if (isset($this->_patterns['perfectiveground']) && isset($this->_patterns['reflexive'])) {

                    if (!$this->stringReplace($end, $this->_patterns['perfectiveground'], '')) {

                        $this->stringReplace($end, $this->_patterns['reflexive'], '');

                        if (isset($this->_patterns['adjective']) && isset($this->_patterns['participle'])) {

                            if ($this->stringReplace($end, $this->_patterns['adjective'], '')) {

                                $this->stringReplace($end, $this->_patterns['participle'], '');

                            } else {

                                if (isset($this->_patterns['verb']) && isset($this->_patterns['noun'])) {

                                    if (!$this->stringReplace($end, $this->_patterns['verb'], ''))
                                        $this->stringReplace($end, $this->_patterns['noun'], '');

                                }

                            }
                        }
                    }
                }
            }

            // Step 2
            if ($this->_locale == 'en') {
                switch (mb_substr($end, -2, 1)) {

                    case 'a':
                        $this->stringReplace($end, '/ational/', 'ate')
                        OR $this->stringReplace($end, '/tional/', 'tion');
                        break;

                    case 'c':
                        $this->stringReplace($end, '/enci/', 'ence')
                        OR $this->stringReplace($end, '/anci/', 'ance');
                        break;

                    case 'e':
                        $this->stringReplace($end, '/izer/', 'ize');
                        break;

                    case 'g':
                        $this->stringReplace($end, '/logi/', 'log');
                        break;

                    case 'l':
                        $this->stringReplace($end, '/entli/', 'ent')
                        OR $this->stringReplace($end, '/ousli/', 'ous')
                        OR $this->stringReplace($end, '/alli/', 'al')
                        OR $this->stringReplace($end, '/bli/', 'ble')
                        OR $this->stringReplace($end, '/eli/', 'e');
                        break;

                    case 'o':
                        $this->stringReplace($end, '/ization/', 'ize')
                        OR $this->stringReplace($end, '/ation/', 'ate')
                        OR $this->stringReplace($end, '/ator/', 'ate');
                        break;

                    case 's':
                        $this->stringReplace($end, '/iveness/', 'ive')
                        OR $this->stringReplace($end, '/fulness/', 'ful')
                        OR $this->stringReplace($end, '/ousness/', 'ous')
                        OR $this->stringReplace($end, '/alism/', 'al');
                        break;

                    case 't':
                        $this->stringReplace($end, '/biliti/', 'ble')
                        OR $this->stringReplace($end, '/aliti/', 'al')
                        OR $this->stringReplace($end, '/iviti/', 'ive');
                        break;
                }
            } else if ($this->_locale == 'ru') {
                $this->stringReplace($end, '/и$/', '');
            } else if ($this->_locale == 'uk') {
                $this->stringReplace($end, '/ії$/', '');
            }

            // Step 3
            if ($this->_locale == 'en') {
                switch (mb_substr($end, -2, 1)) {
                    case 'a':
                        $this->stringReplace($end, '/ical/', 'ic');
                        break;

                    case 's':
                        $this->stringReplace($end, '/ness/', '');
                        break;

                    case 't':
                        $this->stringReplace($end, '/icate/', 'ic')
                        OR $this->stringReplace($end, '/iciti/', 'ic');
                        break;

                    case 'u':
                        $this->stringReplace($end, '/ful/', '');
                        break;

                    case 'v':
                        $this->stringReplace($end, '/ative/', '');
                        break;

                    case 'z':
                        $this->stringReplace($end, '/alize/', 'al');
                        break;
                }
            } else if ($this->_locale == 'ru' || $this->_locale == 'uk') {
                if (isset($this->_patterns['derivational'])) {
                    if ($this->pregMatch($end, $this->_patterns['derivational'])) {
                        if ($this->_locale == 'ru')
                            $this->stringReplace($end, '/ость?$/', '');
                        if ($this->_locale == 'uk')
                            $this->stringReplace($end, '/ость?$/', '');
                    }
                }
            }

            // Step 4
            if ($this->_locale == 'en') {
                switch (mb_substr($end, -2, 1)) {
                    case 'a':
                        $this->stringReplace($end, '/al/', '', 1);
                        break;

                    case 'c':
                        $this->stringReplace($end, '/ance/', '', 1)
                        OR $this->stringReplace($end, '/ence/', '', 1);
                        break;

                    case 'e':
                        $this->stringReplace($end, '/er/', '', 1);
                        break;

                    case 'i':
                        $this->stringReplace($end, '/ic/', '', 1);
                        break;

                    case 'l':
                        $this->stringReplace($end, '/able/', '', 1)
                        OR $this->stringReplace($end, '/ible/', '', 1);
                        break;

                    case 'n':
                        $this->stringReplace($end, '/ant/', '', 1)
                        OR $this->stringReplace($end, '/ement/', '', 1)
                        OR $this->stringReplace($end, '/ment/', '', 1)
                        OR $this->stringReplace($end, '/ent/', '', 1);
                        break;

                    case 'o':
                        if (substr($end, -4) == 'tion' OR substr($end, -4) == 'sion') {
                            $this->stringReplace($end, '/ion/', '', 1);
                        } else {
                            $this->stringReplace($end, '/ou/', '', 1);
                        }
                        break;

                    case 's':
                        $this->stringReplace($end, '/ism/', '', 1);
                        break;

                    case 't':
                        $this->stringReplace($end, '/ate/', '', 1)
                        OR $this->stringReplace($end, '/iti/', '', 1);
                        break;

                    case 'u':
                        $this->stringReplace($end, '/ous/', '', 1);
                        break;

                    case 'v':
                        $this->stringReplace($end, '/ive/', '', 1);
                        break;

                    case 'z':
                        $this->stringReplace($end, '/ize/', '', 1);
                        break;
                }
            } else if ($this->_locale == 'ru' || $this->_locale == 'uk') {
                if (!$this->stringReplace($end, '/ь$/', '')) {
                    $this->stringReplace($end, '/ейше?/', '');
                    $this->stringReplace($end, '/нн$/', 'н');
                }
            }

            // Step 5
            if ($this->_locale == 'en') {
                if (isset($this->_patterns['vowel']) && isset($this->_patterns['consonant'])) {

                    // Part a
                    if (mb_substr($end, -1) == 'e') {
                        if ($this->mCount(mb_substr($end, 0, -1), $this->_patterns['vowel'], $this->_patterns['consonant']) > 1) {
                            $this->stringReplace($end, '/e/', '');
                        } else if ($this->mCount(mb_substr($end, 0, -1)) == 1) {
                            if (!$this->cvcSequence(mb_substr($end, 0, -1), $this->_patterns['vowel'], $this->_patterns['consonant'])) {
                                $this->stringReplace($end, '/e/', '');
                            }
                        }
                    }

                    // Part b
                    if (
                        $this->mCount($end, $this->_patterns['vowel'], $this->_patterns['consonant']) > 1
                        AND $this->doubleConsonant($end, $this->_patterns['consonant'])
                        AND mb_substr($end, -1) == 'l'
                    ) {
                        $end = mb_substr($end, 0, -1);
                    }

                }
            }

            $stem = $start . $end;

        } while (false);
        
        if ($this->caching)
            $this->_cache[$word] = $stem;
        
        return $stem;
    }
    
    /**
     * Processing all words in the text, leaving spaces and other punctuation marks in place.
     *
     * @param $text
     * @return string
     */
    public function text($text)
    {
        $word_sep = array('?', ' ', '.', ',', ';', '!', '"', '\'', '`', "\r", "\n", "\t");
        $pos = 0;

        while($pos < mb_strlen($text)) {

            $min_new_pos = mb_strlen($text);
            foreach ($word_sep as $sep) {
                $new_pos_candidate = mb_strpos($text, $sep, $pos);
                if ($new_pos_candidate !== false) {
                    $min_new_pos = ($new_pos_candidate < $min_new_pos) ? $new_pos_candidate : $min_new_pos;
                }
            }

            $new_pos = $min_new_pos;
            $word_part = mb_substr($text, $pos, $new_pos - $pos);

            if (isset($this->_patterns['alphabet']))
                $word = preg_replace($this->_patterns['alphabet'], "", $word_part);
            else
                $word = preg_replace('/[^A-Za-z-]/u', "", $word_part);

            if ($word == '') {
                $pos = $new_pos + 1;
            } else {
                $stemmed = $this->word($word);
                $stemmed_part = str_ireplace($word, $stemmed, $word_part);
                $text = mb_substr($text, 0, $pos) . $stemmed_part . mb_substr($text, $new_pos);
                $pos = $new_pos - ((mb_strlen($word) - mb_strlen($stemmed)));
            }
        }

        return $text;
    }

    /**
     * Add words to cache
     *
     * '0' means 'no caching'. This is the default level.
     * '1' means 'cache per run'. This caches stemming results during a single call.
     * '2' means 'cache indefinitely'. This caches stemming results until either the process exits or the 'clear_cache' method is called.
     * @param int $param legal value
     * @return int|mixed
     */
    public function addCache($param)
    {
        $level = @$param['-level'];
        if ($level) {
            if (!$this->pregMatch($level, '/^[012]$/')) {
                die(__CLASS__ . "::addCache() - Legal values are '0', '1' or '2'. '$level' is not a legal value");
            }
            $this->caching = $level;
        }

        return $this->caching;
    }

    /**
     * Clearing words cache
     */
    public function clearCache()
    {
        $this->_cache = [];
    }

    /**
     * Measures the number of consonant sequences in $string.
     *
     * <c><v>       gives 0
     * <c>vc<v>     gives 1
     * <c>vcvc<v>   gives 2
     * <c>vcvcvc<v> gives 3
     *
     * @param null $string The string to return the m count for
     * @param null $vowel regex of vowel
     * @param null $consonant regex of consonant
     * @return int|null the count of consonant sequences
     */
    private function mCount($string = null, $vowel = null, $consonant = null)
    {
        if (!is_null($string) && !is_null($vowel) && !is_null($consonant)) {
            $vowel = ltrim(rtrim($vowel, '+/'), '/');
            $consonant = ltrim(rtrim($consonant, '+/'), '/');
            $string = preg_replace("/^$consonant+/", '', $string);
            $string = preg_replace("/$vowel+$/", '', $string);
            preg_match_all("/($vowel+$consonant+)/", $string, $matches);
            return count($matches[1]);
        }
        return null;
    }


    /**
     * Returns true/false as to whether the given string contains two
     * of the same consonant next to each other at the end of the string.
     *
     * @param null $string string to check
     * @param null $consonant regex of consonant
     * @return bool|null result
     */
    private function doubleConsonant($string = null, $consonant = null)
    {
        if (!is_null($string) && !is_null($consonant)) {
            $consonant = ltrim(rtrim($consonant, '+/'), '/');
            return preg_match("/$consonant[2]$/", $string, $matches) AND $matches[0][0] == $matches[0][1];
        }
        return null;
    }


    /**
     * Checks for ending CVC sequence where second C is not W, X or Y
     *
     * @param null $string  string to check
     * @param null $vowel regex of vowel
     * @param null $consonant regex of consonant
     * @return bool|null result
     */
    private function cvcSequence($string = null, $vowel = null, $consonant = null)
    {
        if (!is_null($string) && !is_null($vowel) && !is_null($consonant)) {

            $vowel = ltrim(rtrim($vowel, '+/'), '/');
            $consonant = ltrim(rtrim($consonant, '+/'), '/');

            return preg_match("/($consonant$vowel$consonant)$/", $string, $matches)
                AND mb_strlen($matches[1]) == 3
                AND $matches[1][2] != 'w'
                AND $matches[1][2] != 'x'
                AND $matches[1][2] != 'y';
        }
        return null;
    }
}