<?php
namespace Tests\Feature;

use App\Trending;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

	#vendor/bin/phpunit tests/Feature/TrendingThreadsTest.php

	protected function setUp()
	{
		parent::setUp();

		$this->trending = new Trending();

		$this->trending->reset();
	}

    /** @test */
    function it_increments_a_threads_score_each_time_it_is_read()
    {
    	app()->instance( Trending::class, new FakeTrending );

    	$trending = app( Trending::class );

	    $trending->assertEmpty();

    	$thread = create( 'App\Thread' );

        $this->call( 'GET', $thread->path() );

        $trending->assertCount( 1 );

        $this->assertEquals( $thread->title, $trending->threads[0]->title );
    }
}

class FakeTrending extends Trending
{
	public $threads = [];

	public function push( $thread )
	{
		$this->threads[] = $thread;
	}

	public function assertEmpty()
	{
		\PHPUnit\Framework\Assert::assertEmpty( $this->threads );
	}

	public function assertCount( $count )
	{
		\PHPUnit\Framework\Assert::assertCount( $count, $this->threads );
	}
}