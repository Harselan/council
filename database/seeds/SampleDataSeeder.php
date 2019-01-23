<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use App\ThreadSubscription;
use App\Favorite;
use App\Activity;
use App\Channel;
use App\Thread;
use App\Reply;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->channels();
        $this->threads();
	    Schema::enableForeignKeyConstraints();
    }

    protected function channels()
    {
    	Channel::truncate();

    	collect( [
		    [
			    'name' => 'PHP',
			    'description' => 'A channel for general PHP questions. Use this channel if you can\'t find a more specific channel for your question.',
			    'archived' => false,
			    'color' => '#008000'
		    ],
		    [
			    'name' => 'VueJS',
			    'description' => 'A channel for general VueJS questions. Use this channel if you can\'t find a more specific channel for your question.',
			    'archived' => false,
			    'color' => '#cccccc'
		    ],
		    [
			    'name' => 'Laravel Mix',
			    'description' => 'This channel is for all Laravel Mix related questions.',
			    'archived' => false,
			    'color' => '#43DDF5'
		    ],
		    [
			    'name' => 'Eloquent',
			    'description' => 'This channel is for all Laravel Eloquent related questions.',
			    'archived' => false,
			    'color' => '#a01212'
		    ],
		    [
			    'name' => 'VueEx',
			    'description' => 'This channel is for all VueEx specific questions.',
			    'archived' => false,
			    'color' => '#ff8822'
		    ],
	    ] )->each( function( $channel )
	    {
	    	factory( Channel::class )->create( [
	    		'name'          => $channel['name'],
			    'description'   => $channel['description'],
			    'archived'      => false,
			    'color'         => $channel['color']
		    ] );
	    } );
    }

    protected function threads()
    {
    	Thread::truncate();
    	Reply::truncate();
    	ThreadSubscription::truncate();
	    Activity::truncate();
    	Favorite::truncate();

    	factory( Thread::class, 50 )->states('from_existing_channels_and_users')
	        ->create()
	        ->each( function( $thread )
	        {
	        	$this->recordActivity( $thread, 'created', $thread->creator()->first()->id );
	        } );
    }

    public function recordActivity( $model, $event_type, $user_id )
    {
    	$type = strtolower( ( new \ReflectionClass($model) )->getShortName() );

    	$model->morphMany( 'App\Activity', 'subject' )->create([
    		'user_id' => $user_id,
		    'type' => "{$event_type}_{$type}"
	    ]);
    }
}
