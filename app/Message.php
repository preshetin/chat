<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * Fillable fields.
     * 
     * @var array
     */
    protected $fillable = ['body', 'user_id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['username'];


    /**
     * Belongs to user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get username attribute.
     * 
     * @return mixed
     */
    public function getUsernameAttribute()
    {
        return $this->user->name;
    }

}
