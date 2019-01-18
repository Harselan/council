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

    	factory( Channel::class, 10 )
		    ->create();
    }

    protected function threads()
    {
    	Thread::truncate();
    	Reply::truncate();
    	ThreadSubscription::truncate();
	    Activity::truncate();
    	Favorite::truncate();

    	factory( Thread::class, 50 )
	        ->create();
    }
}
