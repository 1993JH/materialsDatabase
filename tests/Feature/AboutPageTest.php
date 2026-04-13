<?php

test('about page is accessible to guests', function () {
    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertSee('About Us');
    $response->assertSee('about-fade-up');
    $response->assertSee('about-gradient-shift');
    $response->assertSee('images/about-wall-assembly.svg');
    $response->assertSee('images/about-layer-callouts.svg');
});
