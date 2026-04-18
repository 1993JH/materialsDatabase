<?php

use App\Livewire\AdminDashboard;
use Livewire\Livewire;

test('admin dashboard livewire component filters by search and category', function () {
    Livewire::test(AdminDashboard::class)
        ->assertSee('Admin dashboard')
        ->set('search', 'import')
        ->assertSee('Import queue monitor')
        ->assertDontSee('User access controls')
        ->set('search', '')
        ->set('category', 'security')
        ->assertSee('User access controls')
        ->assertDontSee('Import queue monitor');
});

test('admin dashboard runs a panel action and shows feedback', function () {
    Livewire::test(AdminDashboard::class)
        ->call('selectPanel', 'imports')
        ->assertSet('selectedPanel', 'imports')
        ->call('runPanelAction', 'imports')
        ->assertSet('showSuccessBanner', true)
        ->assertSee('Action queued for Import queue monitor.')
        ->call('dismissSuccessBanner')
        ->assertSet('showSuccessBanner', false);
});
