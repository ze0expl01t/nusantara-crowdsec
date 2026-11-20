<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CrowdSecLapiClient;
use App\Services\GeoIpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PortalModulesTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_authenticated_user_can_access_core_portal_routes(): void
    {
        $user = User::factory()->create();

        $mockLapi = Mockery::mock(CrowdSecLapiClient::class);
        $mockLapi->shouldReceive('isConnected')->andReturn(true);
        $mockLapi->shouldReceive('getDecisions')->andReturn([
            ['id' => '1', 'value' => '198.51.100.10', 'scenario' => 'crowdsecurity/ssh-bf', 'type' => 'ban', 'duration' => '3h59m', 'origin' => 'cscli'],
        ]);
        $mockLapi->shouldReceive('getAlerts')->andReturn([]);

        $mockGeo = Mockery::mock(GeoIpService::class);
        $mockGeo->shouldReceive('getAttackPaths')->andReturn([]);

        $this->app->instance(CrowdSecLapiClient::class, $mockLapi);
        $this->app->instance(GeoIpService::class, $mockGeo);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertStatus(200);

        $this->actingAs($user)
            ->get(route('decisions'))
            ->assertStatus(200);

        $this->actingAs($user)
            ->get(route('allowlists'))
            ->assertStatus(200);

        $this->actingAs($user)
            ->get(route('audit-logs'))
            ->assertStatus(200);
    }
}
