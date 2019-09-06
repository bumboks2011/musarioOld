<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
/**
 * Class CoreRepository
 *
 * @package App\Repositories
 *
 * Repository for working with an entity.
 * Can produce data-sets.
 * Cannot create/modify entities.
 */
abstract class CoreRepository
{
    /**
     * $var Model
     */
    protected $model;

    /**
     * CoreRepository constructor.
     */
    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
     */
    abstract protected function getModelClass();

    /**
     * @return Model|\Illuminate\Foundation\Application|mixed
     */
    protected function startConditions()
    {
        return clone $this->model;
    }
}
