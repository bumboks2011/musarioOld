<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class BlogCategoryRepository
 *
 * @package App\Repositories
 */
class BlogPostRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Get posts list for output
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate()
    {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id'
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC')
            ->with([
                'category:id,title',
                'user:id,name'
            ])
            ->paginate(25);

        return $result;
    }

    /**
     * Get posts statistic by user id
     *
     * @var $userId
     *
     * @return array
     */
    public function getByIdPosts($userId)
    {
        $result = $this->startConditions()
            ->select(\DB::raw('COUNT(`id`) AS `score`, COUNT(`published_at`) as `published`, COUNT(`deleted_at`) as `deleted`'))
            ->where('user_id', '=', $userId)
            ->withTrashed()
            ->toBase()
            ->get();

        $countPosts = $result[0]->score;
        $countPostsPublished = $result[0]->published;
        $countPostsDeleted = $result[0]->deleted;
        $countPostsNotPublished = $countPosts - $countPostsPublished - $countPostsDeleted;

        return [
            'score' => $countPosts,
            'published' => $countPostsPublished,
            'hides' => $countPostsNotPublished,
            'deleted' => $countPostsDeleted,
        ];
    }

    /**
     * Get model for edit in admin.
     *
     * @param int $id
     *
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}
