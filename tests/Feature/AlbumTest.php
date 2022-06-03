<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AlbumTest extends TestCase
{
    /** @test */
    public function an_album_can_be_added_successfully()
    {
        $this->withoutExceptionHandling();



    }

    use RefreshDatabase;


}
