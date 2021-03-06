<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
	protected $fillable = [ 'name', 'description', 'slug', 'archived' ];
	protected $casts    = [ 'archived' => 'boolean' ];

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope( 'active', function( $builder )
		{
			$builder->where('archived', false)->orderBy( 'name', 'asc' );
		} );
	}

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
