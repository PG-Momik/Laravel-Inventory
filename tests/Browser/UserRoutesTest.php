<?php

namespace Tests\Browser;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that index route cannot be reached by unauthorized users.
     *
     * @return void
     */
    public function testIndexRouteCannotBeReachedByUnauthUsers(): void
    {
        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that show route cannot be reached by unauthorized users.
     *
     * @return void
     */
    public function testShowRouteCannotBeReachedByUnauthUsers(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.show', $user));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that add form route cannot be reached by unauthorized users.
     *
     * @return void
     */
    public function testAddFormRouteCannotBeReachedByUnauthUsers(): void
    {
        $response = $this->get(route('users.create'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that store route cannot be reached by unauthorized users.
     *
     * @return void
     */
    public function testStoreRouteCannotBeReachedByUnauthUsers(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->post(route('users.store', $user));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that edit form route cannot be reached by unauthorized users.
     *
     * @return void
     */
    public function testEditRouteCannotBeReachedByUnauthUsers(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.edit', $user));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that update route cannot be reached by unauthorized users.
     *
     * @return void
     */
    public function testUpdateRouteCannotBeReachedByUnauthUsers(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.update', $user));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that trash route cannot be reached by unauthorized users.
     *
     * @return void
     */
    public function testTrashRouteCannotBeReachedByUnauthUsers(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->delete(route('users.destroy', $user));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that perma delete route cannot be reached by unauthorized users.
     *
     * @return void
     */
    public function testDeleteRouteCannotBeReachedByUnauthUsers(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.delete', $user));
        $response->assertRedirect(route('login'));
    }
}
