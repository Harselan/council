<?php
namespace Tests\Feature;

use App\Trending;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
    	parent::setUp();

    	$this->trending = new Trending();

    	$this->trending->reset();
    }
    
    /** @test */
    public function it_stores_trending_threads_in_redis()
    {
        $this->assertEmpty( $this->trending->get() );

        $this->trending->push( make( 'App\Thread' ) );

        $this->assertCount( 1, $this->trending->get() );
    }
}
