<?php

namespace App\Bootstrap\Services;

interface AccessInterface
{
	public function author(string $type, array|bool $parameters): bool;
	public function postAuthor(array $info_type, int $blog_user_id): bool;
	public function limitTl(int $type): bool;
	public function postingFrequency(string $type);
	public function limitTime(string $adding_time, int $limit_time);
	public function auditСontent(string $type_content, array $content);
	public function hiddenPost(array $content);
}
