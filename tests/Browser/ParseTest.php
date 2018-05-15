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
        $faker = \Faker\Factory::create();

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
        //
    }

    /**
     * @throws \Throwable
     */
    public function testGalleryCheck()
    {
        //
    }
}
