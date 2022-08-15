<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ActiveProviderScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('tbl_company.status', '1');
    }
}