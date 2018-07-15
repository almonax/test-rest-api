<?php

namespace Tests\Feature;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\View\Compilers\Concerns\CompilesHelpers;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestUserSeeder extends TestCase
{
    use CompilesHelpers;
    use DispatchesJobs;
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->seed(\UsersTableSeeder::class);
    }
}
