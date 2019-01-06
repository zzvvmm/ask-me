<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'website',
        'country',
        'avatar',
        'description',
        'facebook_account',
        'twitter_account',
        'github_account',
        'googleplus_account',    
    ];

    const FOLDER_IMAGE = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function uploadImage($file, $dir)
    {
        $time = Carbon::now();
        $dataFile = $file;
        $nameFile = $time->timestamp . $dataFile->getClientOriginalName();
        $destinationPath = base_path() . '/public/images/' . $dir;
        $file->move($destinationPath, $nameFile);
        return $nameFile;
    }   

    public function updateUser(array $data , $id)
    {
        $user = User::withTrashed()->find($id);
        if (isset($data['password']) && $data['password'] != '') {
            $data['password'] = Hash::make($data['password']);
        } else {
            $data['password'] = $user->password;
        }

        $data['image'] = $this->uploadImage($data['avatar'], User::FOLDER_IMAGE);

        $input = [ 
            'name' => $data['name'],
            'address' => $data['address'],
            'password' => $data['password'],
            'email' => $data['email'],
            'website' => $data['website'],
            'country' => $data['country'],
            'avatar' => $data['image'],
            'description' => $data['description'],
            'facebook_account' => $data['facebook_account'],
            'twitter_account' => $data['twitter_account'],
            'github_account' => $data['github_account'],
            'googleplus_account' => $data['googleplus_account'],
        ];
        return $user->update($input);
    }
}
