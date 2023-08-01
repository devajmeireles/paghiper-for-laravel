<?php

it('should be able to install paghiper successfully', function () {
    $this->artisan('paghiper:install')
        ->assertSuccessful();
});
