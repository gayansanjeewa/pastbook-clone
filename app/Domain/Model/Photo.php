<?php

namespace Domain\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class Photo extends Model
{
    protected $fillable = ['user_id', 'picture_id', 'image_source', 'updated_at', 'created_at'];
}
