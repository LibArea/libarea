<?php

namespace App\Traits;

trait LastDataModified
{
    // Отправка Last-Modified и обработка HTTP_IF_MODIFIED_SINCE
    public function getDataModified($data)
    {
        // время последнего изменения страницы в формате unix time
        $lastModified = strtotime($data);

        // дата последней загрузки, отправляемая клиентом
        $ifModified = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '', 5));

        if ($ifModified && $ifModified >= $lastModified) {
            // страница не изменилась, отдача http статуса 304
            header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
            exit;
        }

        header('Last-Modified: ' . gmdate("D, d M Y H:i:s \G\M\T", $lastModified));
    }
}
