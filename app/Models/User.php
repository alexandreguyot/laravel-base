<?php

namespace App\Models;

use App\Models\UserAlert;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements HasLocalePreference, MustVerifyEmail, FilamentUser
{
    use HasFactory, HasAdvancedFilter, Notifiable, SoftDeletes;

    public $table = 'users';

    protected $casts = [
        'is_approved'      => 'boolean',
        'two_factor_enabled' => 'boolean',
    ];

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'is_approved',
        'two_factor_secret',
        'two_factor_enabled',
        'two_factor_confirmed_at',
        'ui_theme',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $orderable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'locale',
        'is_approved',
    ];

    public $filterable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'roles.title',
        'locale',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_approved && $this->hasVerifiedEmail();
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('title', 'Admin')->exists();
    }

    public function scopeAdmins()
    {
        return $this->whereHas('roles', fn ($q) => $q->where('title', 'Admin'));
    }

    public function alerts()
    {
        return $this->belongsToMany(UserAlert::class)->withPivot('seen_at');
    }

    public function preferredLocale()
    {
        return $this->locale;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        if (! $value) {
            $this->attributes['email_verified_at'] = null;
            return;
        }

        if ($value instanceof \DateTimeInterface) {
            $this->attributes['email_verified_at'] = Carbon::instance($value)->format('Y-m-d H:i:s');
            return;
        }

        $this->attributes['email_verified_at'] = Carbon::createFromFormat(config('project.datetime_format'), $value)->format('Y-m-d H:i:s');
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = Hash::needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function getDeletedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }
}
