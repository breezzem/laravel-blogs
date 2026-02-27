<?php

/**
 * WHAT YOU NEED TO DO:
 * 
 * This is just a basic sanity check - make sure your app is set up correctly.
 * The root route should return a 200 status. You probably don't need to do anything here!
 */

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
