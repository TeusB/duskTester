<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriverBy;
use Exception;


class LoginSearchTest extends DuskTestCase
{
    //screenshots -> tests/screenshots

    public function testBasicExample(): void
    {
        //login
        $this->browse(function (Browser $browser) {
            $browser->visit('') // vul base URL in
                ->type('input[name="email"]', '') // vul email in
                ->type('input[name="password"]', '') //vul wachtwoord in
                ->press('Login')
                ->pause(3000)
                ->assertPathIs('/dashboard');
            $browser->pause(3000)->screenshot('dashboard');
        });

        //navigatie naar zoeken
        $this->browse(function (Browser $browser) {
            $element = $browser->driver->findElement(WebDriverBy::xpath('//span[text()="Zoeken"]'));
            $element->click();
            $browser->pause(3000)->screenshot('zoekenPage');
        });


        //vul zoeken in
        $this->browse(function (Browser $browser) {
            $browser->within('.searchbar_searchbar__3kRC4', function ($browser) {
                $browser->type('input[type="text"]', 'Breuken in groningen'); //vul in om error te testen -> LOL EI KIP
                $browser->click('#search_submit');
            });
        });

        //resultaat
        $this->browse(function (Browser $browser) {
            $browser->pause(5000)->screenshot('resultaat');
            for ($i = 1; $i <= 2; $i++) { // aanpassen voor hoeveel children
                $text = $browser->text("tbody tr:nth-child($i) td .MuiSlider-thumb");
                $int = intval($text);
                if ($int < 50) { // aanpassen voor score
                    throw new Exception("Child: $i, Score: $int"); //zal in de console het child en de score loggen als het onder 50 is.
                }
            }
        });
    }
}
