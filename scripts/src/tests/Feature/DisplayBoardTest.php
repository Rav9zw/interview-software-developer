<?php

namespace Tests\Feature;

use Tests\TestCase;

class DisplayBoardTest extends TestCase
{

    public function testCanDisplayParkingBoard(): void
    {
        $response = $this->get('/api/parking_lot/board');

        $response->assertStatus(200);
    }

    public function testDisplayParkingBoardStructure()
    {
        $response = $this->getJson('/api/parking_lot/board');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'motorcycle',
                    'car',
                    'bus'
                ]
            ]);
    }
}
