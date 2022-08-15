<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class AudienceProviderGeneralScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereHas('audience', function ($audience) {
            $audience->where('audience_name','provider');
        });
    }
}