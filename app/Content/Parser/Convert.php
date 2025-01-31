<?php

declare(strict_types=1);

namespace App\Content\Parser;

// https://github.com/erusev/parsedown/wiki/Tutorial:-Create-Extensions
class Convert extends \ParsedownExtraPlugin
{
    /**
     * @throws \Exception
     */
    function __construct()
    {
        parent::__construct(); // Судя по всему нужно вызвать родительский конструктор сначала.

        $this->InlineTypes['{'][] = 'Video';
        $this->InlineTypes['{'][] = 'Red';

        $this->inlineMarkerList .= '{';
    }

    protected function inlineVideo($excerpt)
    {
        if (preg_match('/^{video}(.*?){\/video}/', $excerpt['text'], $matches)) {
            return [
                'extent' => 120,
                'element' => [
                    'name' => 'video',
                    'text' => 'Your browser does not support the video tag.',
                    'attributes' => [
                        'src' => $matches[1],
                        'controls' => true,
                    ],
                ],

            ];
        }
    }

    protected function inlineRed($excerpt)
    {
        if (preg_match('/^{red}(.*?){\/red}/', $excerpt['text'], $matches)) {
            return [
                'extent' => 250,
                'element' => [
                    'name' => 'span',
                    'text' => $matches[1],
                    'attributes' => [
                        'class' => 'red',
                    ],
                ],

            ];
        }
    }

    protected function convertVideo($Element)
    {
        if (!$Element) return $Element;

        // https://www.youtube.com/shorts/bcXZ88y3Po0
        // https://www.youtube.com/watch?v=Fydyy-ypavU
        // https://youtu.be/Fydyy-ypavU
        // https://rutube.ru/video/17eecf937aa7d9eb19edbb7aec6679b4/
		// https://vkvideo.ru/video67745190_163988084
		// https://vkvideo.ru/video-192217945_456263309
        if (preg_match('/^(?:https?\:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.?be)\/(?:shorts|watch\?v=)?(.+)$/i', $Element['element']['attributes']['href'], $id)) {
            $src = 'https://www.youtube.com/embed/' . $id[1];

		} elseif (preg_match('/video([\d]+)_(\d+)/i', $Element['element']['attributes']['href'], $id)) {
            $src = "https://vk.com/video_ext.php?oid={$id[1]}&id={$id[2]}&hd=1&autoplay=1";
	
		} elseif (preg_match('/video-([\d]+)_(\d+)/i', $Element['element']['attributes']['href'], $id)) {
            $src = "https://vk.com/video_ext.php?oid=-{$id[1]}&id={$id[2]}&hd=2&autoplay=1";

        } elseif (preg_match('/[http|https]+:\/\/(?:www\.|)rutube\.ru\/video\/([a-zA-Z0-9_\-]+)\//i', $Element['element']['attributes']['href'], $id)) {
            $src = 'https://rutube.ru/video/embed/' . $id[1];
        }

        if (count($id)) {
            $Element['element']['name'] = 'iframe';
            $Element['element']['text'] = '';
            $Element['element']['attributes'] = [
                'width' => '576',
                'height' => '324',
                'src' => $src,
                'class' => 'video-pl',
                'frameborder' => '0',
                'allow' => 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture',
                'allowfullscreen' => '1'
            ];
        }

        return $Element;
    }

    protected function inlineLink($Excerpt)
    {
        $url = parent::inlineLink($Excerpt);
        return $this->convertVideo($url);
    }

    protected function inlineUrl($Excerpt)
    {
        $url = parent::inlineUrl($Excerpt);
        return $this->convertVideo($url);
    }
}
