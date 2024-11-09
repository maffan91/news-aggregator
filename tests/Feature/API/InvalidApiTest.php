<?php

describe('Invalid API', function () {

    describe('/api/invalid', function () {
        it('retuns 404', function () {
            $response = $this->get('/api/invalid');
    
            $response->assertNotFound()
            ->assertJson([
                'message' => 'Not found.',
            ]);
        });
    });
});
