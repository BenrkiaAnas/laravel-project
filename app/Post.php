<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    /*
    // Rename Table
    protected $table = "my_post";

    // Change Primary Key
    protected $primaryKey = "post_id";

    // Disable auto increment

    public $incrementing = false;

    // Disable Timestamp

    public $timestamps = false;

    // New Database Connection
    protected $connection = "connection_name";

    // Default Value

    protected $attributes = ['title' => 'Titre'];
    */

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
