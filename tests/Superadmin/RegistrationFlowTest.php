<?php

namespace Tests\Superadmin;

use Tests\TestCase;

class RegistrationFlowTest extends TestCase
{
    public function test_registration_route_exists(): void
    {
        $response = $this->get('/tutorial/inscription');

        $response->assertOk();
        $response->assertSee("Continuer vers l'inscription");
    }
}
