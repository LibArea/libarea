<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Services\Meta\Profile;
use App\Controllers\Controller;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{FacetModel, FeedModel, AnswerModel, CommentModel, PostModel, IgnoredModel};
use UserData;

use App\Traits\Views;

class ProfileController extends Controller
{
    use Views;

    protected $limit = 15;

    // Member page (profile) 
    // Страница участника (профиль)
    function index()
    {
        $profile    = $this->profile();

        if (!$profile['about']) {
            $profile['about'] = __('app.riddle') . '...';
        }

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, 'profile.posts', $profile['id']);
        $pagesCount = FeedModel::feedCount('profile.posts', $profile['id']);

        $this->indexing($profile['id']);

        return $this->render(
            '/user/profile/index',
            [
                'meta'  => Profile::metadata('profile', $profile),
                'data'  => array_merge(
                    $this->sidebar($pagesCount, $profile),
                    [
                        'posts' => $posts,
                        'participation' => FacetModel::participation($profile['id'])
                    ]
                ),
            ]
        );
    }

    // User posts
    public function posts()
    {
        $profile    = $this->profile();

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, 'profile.posts', $profile['id']);
        $pagesCount = FeedModel::feedCount('profile.posts', $profile['id']);

        $this->indexing($profile['id']);

        return $this->render(
            '/user/profile/post',
            [
                'meta'  => Profile::metadata('profile_posts', $profile),
                'data'  => array_merge($this->sidebar($pagesCount, $profile), ['posts' => $posts]),
            ]
        );
    }

    // User comments
    public function comments()
    {
        $profile   = $this->profile();

        $answers    = AnswerModel::userAnswers($this->pageNumber, $this->limit, $profile['id'], $this->user['id']);
        $answerCount = AnswerModel::userAnswersCount($profile['id']);

        $comments   = CommentModel::userComments($this->pageNumber, $this->limit, $profile['id'], $this->user['id']);
        $commentCount = CommentModel::userCommentsCount($profile['id']);

        $pagesCount = $answerCount + $commentCount;

        $mergedArr = array_merge($comments, $answers);
        usort($mergedArr, function ($a, $b) {
            return ($b['comment_date'] ?? $b['answer_date']) <=> ($a['comment_date'] ?? $a['answer_date']);
        });

        $this->indexing($profile['id']);

        return $this->render(
            '/user/profile/comment',
            [
                'meta'  => Profile::metadata('profile_comments', $profile),
                'data'  => array_merge($this->sidebar($pagesCount, $profile), ['comments' => $mergedArr]),
            ]
        );
    }

    public function sidebar($pagesCount, $profile)
    {
        return [
            'pagesCount'    => ceil($pagesCount / $this->limit),
            'pNum'          => $this->pageNumber,
            'profile'       => $profile,
            'type'          => 'profile',
            'delet_count'   => UserModel::contentCount($profile['id'], 'remote'),
            'counts'        => UserModel::contentCount($profile['id'], 'active'),
            'topics'        => FacetModel::getFacetsTopicProfile($profile['id']),
            'blogs'         => FacetModel::getOwnerFacet($profile['id'], 'blog'),
            'badges'        => BadgeModel::getBadgeUserAll($profile['id']),
            'my_post'       => PostModel::getPost($profile['my_post'], 'id', $this->user),
            'button_pm'     => $this->accessPm($profile['id']),
            'ignored'       => IgnoredModel::getUserIgnored($profile['id']),
        ];
    }

    public function profile()
    {
        $result = Request::get('login');
        notEmptyOrView404($profile = UserModel::getUser($result, 'slug'));

        if ($profile['ban_list'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        $this->setProfileView($profile['id']);

        if (UserData::checkAdmin()) {
            Request::getResources()->addBottomScript('/assets/js/admin.js');
        }

        return $profile;
    }

    // Sending personal messages
    public function accessPm($for_user_id)
    {
        // We forbid sending to ourselves
        if ($this->user['id'] == $for_user_id) {
            return false;
        }

        // If the trust level is less than the established one
        if ($this->user['trust_level'] < config('trust-levels.tl_add_pm')) {
            return false;
        }

        return true;
    }
    
    // Index profile or not
    public function indexing($profile_id)
    {
        $amount = UserModel::contentCount($profile_id, 'active');
        if (($amount['count_answers'] + $amount['count_comments']) < 3) {
            Request::getHead()->addMeta('robots', 'noindex');
        }
        
        if (UserModel::isDeleted($profile_id)) {
            Request::getHead()->addMeta('robots', 'noindex');
        }
    }
}
