<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;


    public function testIndexRouteIsAvailableOnlyForAuthUser()
    {
        $this->seed(PermissionSeeder::class);

        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertStatus(200);

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertStatus(200);
    }

    public function testSingleUserRouteIsAvailableOnlyForAuthUser()
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.show', $user));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.show', $user));
        $response->assertStatus(200);

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.show', $user));
        $response->assertStatus(200);
    }

    public function testCannotFetchOutOfBoundUserFromId()
    {
        $this->seed(PermissionSeeder::class);
        User::factory()->count(5)->create();

        $response = $this->get(route('users.show', ['user' => 69]));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.show', ['user' => 100]));
        $response->assertStatus(404);

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.show', ['user' => 100]));
        $response->assertStatus(404);
    }

    public function testUserCannotTrashSelf()
    {
        $this->seed(PermissionSeeder::class);

    }


}
