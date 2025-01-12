<?php

declare(strict_types=1);

class MetaImage
{
	public static function get($title, $author, $avatar = '', $bgImage = '')
	{
		// Задаем размеры изображения
		$width = 1200;
		$height = 630;

		// Создаем пустое изображение
		$image = imagecreatetruecolor($width, $height);

		// Устанавливаем фон и определяем цвет текста
		if ($bgImage) {
			$imageData = self::loadImageFromUrl($bgImage);
			$bgImage = Img::createImageFromString($imageData);

			if ($bgImage) {
				// Получаем размеры фонового изображения
				$bgWidth = imagesx($bgImage);
				$bgHeight = imagesy($bgImage);

				// Масштабируем и центрируем изображение
				$scale = max($width / $bgWidth, $height / $bgHeight);
				$newWidth = $bgWidth * $scale;
				$newHeight = $bgHeight * $scale;
				$x = ($width - $newWidth) / 2;
				$y = ($height - $newHeight) / 2;

				imagecopyresampled($image, $bgImage, (int)$x, (int)$y, 0, 0, (int)$newWidth, (int)$newHeight, (int)$bgWidth, (int)$bgHeight);
				imagedestroy($bgImage);

				// Добавляем полупрозрачный оверлей для фото
				$overlay = imagecreatetruecolor($width, $height);
				$transparent = imagecolorallocatealpha($overlay, 0, 0, 0, 80);
				imagefill($overlay, 0, 0, $transparent);
				imagecopymerge($image, $overlay, 0, 0, 0, 0, $width, $height, 50);
				imagedestroy($overlay);

				// Белый текст для фото
				$textColor = imagecolorallocate($image, 255, 255, 255);
			} else {
				// Если изображение не загрузилось, используем светлый фон
				list($r, $g, $b) = self::generateLightColor();
				$bgColor = imagecolorallocate($image, $r, $g, $b);
				imagefill($image, 0, 0, $bgColor);
				// Черный текст для светлого фона
				$textColor = imagecolorallocate($image, 0, 0, 0);
			}
		} else {
			// Если URL изображения не указан, используем светлый фон
			list($r, $g, $b) = self::generateLightColor();
			$bgColor = imagecolorallocate($image, $r, $g, $b);
			imagefill($image, 0, 0, $bgColor);
			// Черный текст для светлого фона
			$textColor = imagecolorallocate($image, 0, 0, 0);
		}

		// Настройка шрифта
		$fontPath = HLEB_PUBLIC_DIR . '/assets/fonts/Ubuntu-Regular.ttf';
		if (!file_exists($fontPath)) {
			error_log("Шрифт не найден: " . $fontPath);
			die("Ошибка: шрифт не найден");
		}

		// Настройки текста
		$titleFontSize = 48;
		$margin = 70; // отступ по бокам
		$maxWidth = $width - (2 * $margin); // максимальная ширина текста
		$lineHeight = 60; // высота строки

		// Разбиваем заголовок на строки
		$titleLines = self::wrapText($title, $fontPath, $titleFontSize, $maxWidth);

		// Вычисляем общую высоту текста
		$totalHeight = count($titleLines) * $lineHeight;
		$startY = 120; // начальная позиция Y

		// Отрисовываем каждую строку заголовка
		foreach ($titleLines as $index => $line) {
			imagettftext(
				$image,
				$titleFontSize,
				0,
				$margin,
				$startY + ($index * $lineHeight),
				$textColor,
				$fontPath,
				$line
			);
		}

		// Отрисовываем подзаголовок (Автор)
		imagettftext($image, 24, 0, $margin, $height - 80, $textColor, $fontPath, $author);

		// Добавляем логотип
		if (isset($avatar)) {
			$logoData = self::loadImageFromUrl($avatar);
			$logoImage = Img::createImageFromString($logoData);

			if ($logoImage) {
				$logoWidth = imagesx($logoImage);
				$logoHeight = imagesy($logoImage);
				$maxLogoHeight = 80;
				$scale = $maxLogoHeight / $logoHeight;
				$newLogoWidth = $logoWidth * $scale;
				$newLogoHeight = $logoHeight * $scale;

				imagecopyresampled(
					$image,
					$logoImage,
					(int)$width - (int)$newLogoWidth - 60,
					(int)$height - (int)$newLogoHeight - 60,
					0,
					0,
					(int)$newLogoWidth,
					(int)$newLogoHeight,
					$logoWidth,
					$logoHeight
				);
				imagedestroy($logoImage);
			}
		}

		// Отправляем заголовки и выводим изображение
		header('Content-Type: image/png');
		imagepng($image);
		imagedestroy($image);
	}

	// Перенос текста
	public static function wrapText($text, $font, $fontSize, $maxWidth)
	{
		$words = explode(' ', $text);
		$lines = [];
		$currentLine = '';

		foreach ($words as $word) {
			$testLine = $currentLine . ' ' . $word;
			$bbox = imagettfbbox($fontSize, 0, $font, trim($testLine));
			$currentWidth = $bbox[2] - $bbox[0];

			if ($currentWidth > $maxWidth && $currentLine !== '') {
				$lines[] = trim($currentLine);
				$currentLine = $word;
			} else {
				$currentLine = trim($testLine);
			}
		}
		if ($currentLine !== '') {
			$lines[] = trim($currentLine);
		}
		return $lines;
	}

	// Генерации случайного светлого цвета
	public static function generateLightColor()
	{
		return array(
			rand(200, 255), // R
			rand(200, 255), // G
			rand(200, 255)  // B
		);
	}

	// Функция для загрузки изображения через cURL
	public static function loadImageFromUrl($url)
	{
		return file_get_contents(HLEB_PUBLIC_DIR . $url);
	}
}
