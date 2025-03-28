<?php

namespace App\Traits;

use App\Models\FacetModel;

trait AddFacetsContent
{
	/**
	 * Add fastes (blogs, topics) to the content
	 *
	 * @param array $fields
	 * @param int $content_id
	 * @param string $redirect
	 * @return string
	 */
	public static function addFacets(array $fields, int $content_id, string $redirect): string
	{
		$facets = $fields['facet_select'] ?? false;

		if (!$facets) {
			Msg::redirect(__('msg.select_topic'), 'error', $redirect);
		}

		$topics = json_decode($facets, true);

		$section = $fields['section_select'] ?? false;
		$OneFacets = [];

		if ($section) {
			$OneFacets = json_decode($section, true);
		}

		$blog_post = $fields['blog_select'] ?? false;
		$TwoFacets = [];

		if ($blog_post) {
			$TwoFacets = json_decode($blog_post, true);
		}

		$GeneralFacets = array_merge($OneFacets, $TwoFacets);

		FacetModel::addPostFacets(array_merge($GeneralFacets, $topics), $content_id);

		return true;
	}
}
