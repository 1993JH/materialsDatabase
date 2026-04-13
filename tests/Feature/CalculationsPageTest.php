<?php

test('calculations page is accessible to guests', function () {
    $response = $this->get(route('calculations'));

    $response->assertOk();
    $response->assertSee('Calculations');
    $response->assertSee('Calculation Snapshot');
    $response->assertSee('Material Counts');
});
