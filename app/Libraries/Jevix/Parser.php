<?php

class Parser
{
    // Работа с контентом (Parsedown and Jevix)
    public static function content($content, $type)
    {
        $Parsedown = new Parsedown();
        $jevix = new Jevix();

        if ($type  == 'line') {
            $content = $Parsedown->line($content);
            $jevix->cfgAllowTags(['p', 'del', 'em', 'strong', 'sup']);
            $jevix->cfgSetTagCutWithContent(['script', 'style', 'details', 'style', 'iframe', 'code', 'a', 'table']);
            $item = [];
            return $jevix->parse($content, $item);
        }

        $content = $Parsedown->text($content);

        // Разрешённые теги. (все не разрешенные теги считаются запрещенными.)
        $jevix->cfgAllowTags([
            'p', 'del', 'em', 'strong', 'sup', 'img', 'hr', 'font', 'a',
            'ul', 'ol', 'li', 'del', 'details',
            'table', 'tbody', 'thead', 'tfoot', 'tr', 'td', 'th',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'pre', 'code', 'blockquote', 'iframe'
        ]);

        // После тега не нужно добавлять дополнительный <br/>
        $jevix->cfgSetTagBlockType(['p', 'li']);

        // Коротие теги. (не имеющие закрывающего тега)
        $jevix->cfgSetTagShort(['img', 'hr']);

        // Преформатированные теги. (в них все будет заменятся на HTML сущности)
        $jevix->cfgSetTagPreformatted(['pre', 'code']);

        // Теги, которые необходимо вырезать из текста вместе с контентом.
        $jevix->cfgSetTagCutWithContent(['script', 'style', 'br']);

        $jevix->cfgSetTagIsEmpty(['a', 'iframe']);

        // Разрешённые параметры тегов. 
        $jevix->cfgAllowTagParams('a', ['href' => '#link']);

        $jevix->cfgAllowTagParams('iframe', ['width' => '#int', 'height' => '#int', 'style' => '#text', 'frameborder' => '#int', 'allowfullscreen' => '#text', 'src' => ['#domain' => ['youtube.com', 'yandex.ru', 'rutube.ru', 'vk.com']]]);

        $jevix->cfgAllowTagParams('img', ['src', 'style' => '#text', 'alt' => '#text', 'title' => '#text', 'width' => '#int', 'height' => '#int', 'class' => '#text']);

        $jevix->cfgAllowTagParams('code', ['type' => '#text']);
        $jevix->cfgAllowTagParams('pre', ['class' => '#text']);
        $jevix->cfgAllowTagParams('details', ['title' => '#text', 'tl']);

        // Параметры тегов являющиеся обязательными. Без них вырезает тег оставляя содержимое.
        $jevix->cfgSetTagParamsRequired('img', 'src');
        $jevix->cfgSetTagParamsRequired('a', 'href');

        // Автозамена
        $jevix->cfgSetAutoReplace(['+/-', '(c)', '(с)', '(r)', '(C)', '(С)', '(R)'], ['±', '©', '©', '®', '©', '©', '®']);

        // Режим замены переноса строк на тег 
        $jevix->cfgSetAutoBrMode('');

        // Callback
        $jevix->cfgSetTagCallbackFull('a', ['Parser', 'linkNofollow']);
        $jevix->cfgSetTagCallbackFull('details', ['Parser', 'spoiler']);
        $jevix->cfgSetTagCallbackFull('pre', ['Parser', 'pre']);

        // Отключаем типографирование в определеннх тегах
        $jevix->cfgSetTagNoTypography(['pre', 'code', 'iframe']);

        $item = [];
        return $jevix->parse($content, $item);
    }

    public static function linkNofollow($tag, $params, $content)
    {
        $rel = '';
        $url = parse_url($params['href']);
        
        $host = $url['host'] ?? false;
        if (!in_array($host, config('meta.white_list_hosts'))) {
            $rel = 'rel="nofollow noreferrer noopener" target="_blank"';
        }

        return  '<a href="' . $params['href'] . '" ' . $rel . '>' . $content . '</a>';
    }


    public static function spoiler($tag, $params, $content)
    {
        if (empty($content)) {
            return '';
        }

        $title = $params['title'] ?? __('app.see_more');

        $tl = $params['tl'] ?? false;

        $spoiler = '<details><summary>' . $title . '</summary>' . $content . '</details>';

        if ($tl) {
            if (UserData::checkActiveUser()) {
                $spoiler = '<details><summary><svg class="icons gray-600 mr5"><use xlink:href="/assets/svg/icons.svg#lock"></use></svg> ' . $title . '</summary>' . $content . '</details>';
            } else {
                $spoiler = '<details class="gray"><summary><svg class="icons gray-600 mr5"><use xlink:href="/assets/svg/icons.svg#lock"></use></svg> ' . __('app.text_closed') . '.</summary>...</details>';
            }
        }

        return $spoiler;
    }

    public static function pre($tag, $params, $content)
    {
        $content = htmlspecialchars_decode($content);
        $content = preg_replace('#^<code>(.*)<\/code>$#uis', '$1', $content);

        $geshi = new GeSHi($content, 'php');
        // нумер.
        // $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
        $geshi->enable_classes();
        // $geshi->set_overall_class("code php");
        return '<pre class="language-css">' . $geshi->parse_code() . '</pre>';
    }
}
