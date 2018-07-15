<?php

namespace App;

use App\Classes\ImageService;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable
{
    use Notifiable;

    const AVATAR_PATH = 'images/users/avatars/';

    const RULES = [
        'name'     => 'required|string|min:1|max:60',
        'email'    => 'required|email|max:255|unique:users',
        'phone'    => 'required|regex:/^7[0-9]{10}$/',
        'password' => 'string|min:6|confirmed',
        'avatar'   => 'mimes:jpeg,gif,png|dimensions:min_width=50,min_height=50'
    ];

    public $maxQueryCount = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar'
    ];

    /**
     * The attributes for control toArray converting
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'email',
        'phone',
        'avatar',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get rules according to needed fields
     *
     * @param array $data
     * @return array
     */
    public function getRules(array $data)
    {
        $rules = [];
        foreach ($data as $fieldName => $value) {
            $key = (is_string($fieldName)) ? $fieldName: $value;
            if (isset(self::RULES[$key])) {
                $rules[$key] = self::RULES[$key];
                if ($key == 'email') {
                    $rules[$key] .= ',email,' . $this->id;
                }
            }
        }
        return $rules;
    }

    public function getAvatarPath()
    {
        return self::AVATAR_PATH . $this->id . '/';
    }

    /**
     * Get relative path of the avatar
     *
     * @return string
     */
    public function getUserAvatar()
    {
        if ($this->avatar) {
            return $this->getAvatarPath() . ImageService::THUMB_PREFIX . $this->avatar;
        } else {
            return '/img/avatar-not-found.png';
        }
    }

    /**
     * Get collection of users
     *
     * @return mixed
     */
    public function getUsers()
    {
        $users = self::orderBy('created_at', 'DESC')
            ->paginate($this->maxQueryCount);
        return $users;
    }

    /**
     * Transform response result
     *
     * @param array|object $data
     * @return mixed
     */
    public static function transformEntity($data)
    {
        if ($data instanceof User) {
            $data->avatar = URL::to($data->getUserAvatar());
        } else {
            foreach ($data as $item) {
                if ($item instanceof User) {
                    $item->avatar = URL::to($item->getUserAvatar());
                }
            }
        }
        return $data;
    }
}
