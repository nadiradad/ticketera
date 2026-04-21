<?php

it('redirects guests from the home dashboard to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login', absolute: false));
});
