<?php

declare(strict_types=1);

use LitEmoji\LitEmoji;

use Djot\DjotConverter;
use Djot\SafeMode;
use Djot\Node\Inline\Link;
use Djot\Node\Inline\Text;
use Djot\Event\RenderEvent;
use Djot\Node\Block\Div;

use Djot\Extension\AutolinkExtension;
use Djot\Extension\ExternalLinksExtension;
use Djot\Extension\SmartQuotesExtension;
use Djot\Extension\MentionsExtension;

use App\Models\User\UserModel;

class Parser
{
    public static function text(string $content, string $type)
    {
        $text = self::parse($content);
        $text = self::gif($text);
        $text = self::video($text);

        return LitEmoji::encodeUnicode($text);
    }

    public static function parse(string $content)
    {
        $content = str_replace('{cut}', '', $content);
        $content = str_replace('[^1]', '', $content);

        // https://github.com/php-collective/djot-php/tree/master
		$converter = new DjotConverter(
			safeMode: SafeMode::strict(),
			significantNewlines: true,
		);

        self::reminders($converter);

        self::topic($converter);

        $text = $converter
            ->addExtension(new AutolinkExtension())
            ->addExtension(new ExternalLinksExtension())
            ->addExtension(new SmartQuotesExtension(locale: config('general', 'lang')))
            ->addExtension(new MentionsExtension(urlTemplate: '/@{username}', cssClass: 'green',))
            ->convert($content);

        return $text;
    }

    public static function reminders($converter)
    {
        $admonitionIcons = [
            'note' => 'ℹ️',
            'tip' => '💡',
            'warning' => '⚠️',
            'danger' => '🚨',
            'success' => '✅',
        ];

        $converter->on('render.div', function (RenderEvent $event) use ($admonitionIcons): void {
            $div = $event->getNode();
            if (!$div instanceof Div) {
                return;
            }

            $class = $div->getAttribute('class') ?? '';
            foreach ($admonitionIcons as $type => $icon) {
                if (str_contains($class, $type)) {
                    $div->setAttribute('class', 'admonition ' . $class);
                    $div->setAttribute('data-icon', $icon);

                    return;
                }
            }
        });
    }

    public static function topic($converter)
    {
        $parser = $converter->getParser()->getInlineParser();
        $parser->addInlinePattern('/#([a-zA-Z][a-zA-Z0-9_]*)/', function ($match, $groups, $p) {
            $tag = $groups[1];
            $link = new Link('/topic/' . strtolower($tag));
            $link->appendChild(new Text('#' . $tag));
            $link->setAttribute('class', 'green');
            return $link;
        });
    }

    public static function gif($content)
    {
        preg_match_all('/\:(\w+)\:/mUs', strip_tags($content), $matchs);

        if (is_array($matchs[1])) {

            $match_name = [];
            foreach ($matchs[1] as $name) {
                if (in_array($name, $match_name)) {
                    continue;
                }

                $match_name[] = $name;
            }

            $match_name = array_unique($match_name);

            arsort($match_name);

            foreach ($match_name as $key => $name) {

                $img = '/assets/images/gif/' . $name . '.gif';
                if (file_exists('.' . $img)) {
                    $content = str_replace(':' . $name . ':', '<img class="gif" alt="' . $name . '" src="' . $img . '">', $content);
                }
            }
        }

        return  $content;
    }

    public static function video($content)
    {
        // TODO
        return $content;
    }

    // TODO: Let's check the simple version for now.
    public static function cut($text, $length = 800)
    {
        $charset = 'UTF-8';
        $beforeCut = $text;
        $afterCut = false;

        if (preg_match("#^(.*){cut([^}]*+)}(.*)$#Usi", $text, $match)) {
            $beforeCut  = $match[1];
            $afterCut   = $match[3];
        }

        if (!$afterCut) {
            $beforeCut = self::fragment($text, $length);
        }

        $button = false;
        if ($afterCut || mb_strlen($text, $charset) > $length) {
            $button = true;
        }

        return ['content' => $beforeCut, 'button' => $button];
    }

    // Content management
    public static function noHTML(string $content, int $lenght = 150)
    {
        $converter = new DjotConverter(safeMode: SafeMode::strict());
        $text = $converter->convert($content);

        $content = str_replace(["\r\n", "\r", "\n", "#"], ' ', $text);

        $str =  str_replace(['&gt;', '{cut}'], '', strip_tags($content));

        return self::fragment($str, $lenght);
    }

    public static function fragment(string $text, int $lenght = 150, string $charset = 'UTF-8'): string
    {
        $text = LitEmoji::encodeUnicode($text);

        if (mb_strlen($text, $charset) >= $lenght) {
            $wrap = wordwrap($text, $lenght, '~');
            $ret = mb_strpos($wrap, '~', 0, $charset);

            return  mb_substr($wrap, 0, (int)$ret, $charset) . '...';
        }

        if (empty($text)) $text = '...';

        return $text;
    }

    public static function parseUsers($content, $with_user = false, $to_uid = false)
    {
        preg_match_all('/(?<=^|\s|>)@([a-z0-9_]+)/i', strip_tags($content), $matchs);

        if (is_array($matchs[1])) {
            $match_name = [];
            foreach ($matchs[1] as $key => $login) {
                if (in_array($login, $match_name)) {
                    continue;
                }

                $match_name[] = $login;
            }

            $match_name = array_unique($match_name);

            arsort($match_name);

            $all_users = [];

            $content_uid = $content;

            foreach ($match_name as $key => $login) {

                if (preg_match('/^[0-9]+$/', $login)) {
                    $user_info = UserModel::get($login, 'id');
                } else {
                    $user_info = UserModel::get($login, 'slug');
                }

                if ($user_info) {
                    $content = str_replace('@' . $login, '[@' . $login . '](/@' .  $login . ')', $content);

                    if ($to_uid) {
                        $content_uid = str_replace('@' . $login, '@' . $user_info['id'], $content_uid);
                    }

                    if ($with_user) {
                        $all_users[] = $user_info['id'];
                    }
                }
            }
        }

        if ($with_user) {
            return $all_users;
        }

        if ($to_uid) {
            return $content_uid;
        }

        return $content;
    }
}
