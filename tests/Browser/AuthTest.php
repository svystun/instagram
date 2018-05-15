<?php namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testRegister()
    {
        $faker = \Faker\Factory::create();

        $this->browse(function (Browser $browser) use ($faker) {
            $password = $faker->password;
            $browser->visit('/register')
                ->type('first_name', $faker->firstNameMale)
                ->type('last_name', $faker->lastName)
                ->type('email', $faker->email)
                ->type('password', $password)
                ->type('password_confirmation', $password)
                ->press('Register')
                ->assertPathIs('/home')
                ->assertSee('Search bar')
                ->clickLink('Logout');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testLogin()
    {
        $faker = \Faker\Factory::create();

        $user = factory(\App\User::class)->create([
            'first_name' => $faker->firstNameMale,
            'last_name' => $faker->lastName,
            'email' => $faker->email
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/')
                ->assertSee('Search bar')
                ->clickLink('Logout');
        });
    }
}
