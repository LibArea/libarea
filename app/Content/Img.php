<?php

declare(strict_types=1);

class Img
{
    // File paths for storing avatars, banners, etc.
    public const PATH = [
        'avatars'               => '/uploads/users/avatars/',
        'avatars_small'         => '/uploads/users/avatars/small/',
        'users_cover'           => '/uploads/users/cover/',
        'users_cover_small'     => '/uploads/users/cover/small/',
        'facets_logo'           => '/uploads/facets/logos/',
        'facets_logo_small'     => '/uploads/facets/logos/small/',
        'facets_cover'          => '/uploads/facets/cover/',
        'facets_cover_small'    => '/uploads/facets/cover/small/',
        'posts_content'         => '/uploads/posts/content/',
        'posts_cover'           => '/uploads/posts/cover/',
        'posts_thumb'           => '/uploads/posts/thumbnails/',
        'favicons'              => '/uploads/favicons/',
        'thumbs'                => '/uploads/thumbs/'
    ];

    /**
     * Generate image tag
     *
     * @param string $file
     * @param string $alt
     * @param string $style
     * @param string $type
     * @param string $size
     * @return string
     */
    public static function image(string $file, string $alt, string $style, string $type, string $size): string
    {
        $imgPath = self::generateImagePath($file, $type, $size);

        return '<img class="' . $style . '" src="' . $imgPath . '" alt="' . $alt . '">';
    }

    /**
     * Generate avatar image tag
     *
     * @param string $file
     * @param string $alt
     * @param string $style
     * @param string $size
     * @return string
     */
    public static function avatar(string $file, string $alt, string $style, string $size): string
    {
        $imgPath = self::generateImagePath($file, 'avatar', $size);

        return '<img class="' . $style . '" src="' . $imgPath . '" alt="' . $alt . '">';
    }

    /**
     * Generate website image tag
     *
     * @param string $type
     * @param string $host
     * @param string|null $css
     * @return string
     */
    public static function website(string $type, string $host, string|null $css = ''): string
    {
        $path = ($type === 'thumb') ? self::PATH['thumbs'] : self::PATH['favicons'];
        $itemprop = ($type === 'thumb') ? 'itemprop="image"' : '';
		$img = $path . $host . '.png';
        $imgPath = HLEB_PUBLIC_DIR . $img;

        if (file_exists($imgPath)) {
            return '<img ' . $itemprop . ' class="' . $css . '" src="' . $img . '" title="' . $host . '" alt="' . $host . '">';
        }

        return '<img class="mr5 ' . $css . '" src="' . config('meta', 'url') . $path . 'no-link.png" title="' . $host . '" alt="' . $host . '">';
    }

    /**
     * Generate cover image path
     *
     * @param string $file
     * @param string $type
     * @return string
     */
    public static function cover(string $file, string $type): string
    {
        return $type === 'blog' ? self::PATH['facets_cover'] . $file : self::PATH['users_cover'] . $file;
    }

    /**
     * Helper function to generate image path based on type and size
     *
     * @param string $file
     * @param string $type
     * @param string $size
     * @return string
     */
    private static function generateImagePath(string $file, string $type, string $size): string
    {
        $path = '';

        switch ($type) {
            case 'post':
                $path = ($size === 'thumbnails') ? self::PATH['posts_thumb'] : self::PATH['posts_cover'];
                break;
            case 'avatar':
                $path = ($size === 'small') ? self::PATH['avatars_small'] : self::PATH['avatars'];
                break;
            case 'logo':
                $path = ($size === 'small') ? self::PATH['facets_logo_small'] : self::PATH['facets_logo'];
                break;
        }

        return $path . $file;
    }
	
	// Cоздания изображения из строки данных
	public static function createImageFromString($imageData)
	{
		if (!$imageData) {
			return null;
		}

		$temp_dir = HLEB_GLOBAL_DIR . '/storage/tmp';

		$tempFile = tempnam($temp_dir, 'og_image');
		if ($tempFile === false) {
			error_log("Не удалось создать временный файл");
			return null;
		}

		file_put_contents($tempFile, $imageData);

		$imageInfo = getimagesize($tempFile);
		if (!$imageInfo) {
			unlink($tempFile);
			error_log("Не удалось получить информацию об изображении");
			return null;
		}

		$image = null;
		switch ($imageInfo[2]) {
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($tempFile);
				break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($tempFile);
				break;
			case IMAGETYPE_WEBP:
				$image = imagecreatefromwebp($tempFile);
				break;
		}

		unlink($tempFile);

		return $image;
	}
	
	public static function createTempImage($imageData)
	{
		if (!$imageData) {
			return null;
		}

    	 $data = explode( ',', $imageData);

		// Obtain the original content (usually binary data)
		$imageData = base64_decode($data[1]);

		$temp_dir = HLEB_GLOBAL_DIR . '/storage/tmp';

		$tempFile = tempnam($temp_dir, 'og_image');
		if ($tempFile === false) {
			error_log("Не удалось создать временный файл");
			return null;
		}

		file_put_contents($tempFile, $imageData);

		$imageInfo = getimagesize($tempFile);
		if (!$imageInfo) {
			unlink($tempFile);
			error_log("Не удалось получить информацию об изображении");
			return null;
		}

		return [$imageInfo, $tempFile];
	}
}
