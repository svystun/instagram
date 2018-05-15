<?php namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParseTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())
                ->visit('/')
                ->assertSee('Search bar')
                ->type('search', 'paris')
                ->press('Search')
                ->assertSee('Paris');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testImageSave()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Search bar')
                ->type('search', 'paris')
                ->press('Search')
                ->waitForText('Search results')
                ->mouseover('.label-danger')
                ->click('.label-danger');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testGalleryCheck()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/gallery')
                ->assertSee('My Gallery')
                ->mouseover('.label-danger')
                ->click('.label-danger');
        });
    }
}
