<?php

declare(strict_types=1);

namespace App\Content\Сheck;

use App\Models\{CommentModel, PublicationModel, FacetModel, PollModel};

class Availability
{
    public static function content(int|string $element, string $type_element = 'id'): array
    {
        $content = PublicationModel::getPost($element, $type_element);

        notEmptyOrView404($content);

        return $content;
    }


    public static function comment(int $comment_id): array
    {
        $comment = CommentModel::getCommentId($comment_id);

        notEmptyOrView404($comment);

        return $comment;
    }

    // mixed $element (> PHP 8.0)
    public static function facet(int|string $element, string $type_element = 'id', string $type = 'topic'): array
    {
        $facet = FacetModel::getFacet($element, $type_element, $type);

        notEmptyOrView404($facet);

        return $facet;
    }

    public static function allFacet(int $id): array
    {
        $facet = FacetModel::uniqueById($id);

        notEmptyOrView404($facet);

        return $facet;
    }

    public static function poll(int $id): array
    {
        $question = PollModel::getQuestion($id);

        notEmptyOrView404($question);

        return $question;
    }
}
