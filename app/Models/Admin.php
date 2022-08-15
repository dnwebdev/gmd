<?php

namespace App\Models;

use App\Notifications\Admin\Password\ResetNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Scopes\IsKlhkScope;

/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string $admin_name
 * @property string|null $admin_avatar
 * @property string $email
 * @property int $role_id
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereAdminAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use Notifiable;
    protected $guarded = ['id'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        $klhk_backoffice = env('KLHK_BACKOFFICE_URL', 'bupsha.' . env('APP_URL'));
        static::addGlobalScope(new IsKlhkScope(request()->getHttpHost() == $klhk_backoffice));
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_klhk' => 'boolean',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetNotification($token));
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getAdminAvatarAttribute($value)
    {
        if (empty($value)) {
            return 'https://placehold.it/200x200?text=Foto+Profil';
        }

        return asset($value);
    }
}
