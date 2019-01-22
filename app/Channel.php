<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
	protected $fillable = [ 'name', 'description', 'slug', 'archived' ];
	protected $casts    = [ 'archived' => 'boolean' ];

    public function getRouteKeyName()
    {
	    return 'slug';
    }

    public function threads()
    {
    	return $this->hasMany( Thread::class );
    }

    public function archive()
    {
    	$this->update( [ 'archived' => true ] );
    }
}
