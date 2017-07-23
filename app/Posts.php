<?php namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Posts extends Model
{
    protected $collection = 'posts';
    protected $fillable = ['user_id', 'insta_id', 'insta_url', 'insta_caption', 'insta_user', 'insta_name', 'insta_time', 'insta_type'];
}
