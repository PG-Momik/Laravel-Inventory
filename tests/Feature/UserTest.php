<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that user can see all users.
     *
     * @return void
     */
    public function testUserCanSeeAllUsers(): void
    {
        $this->seed(PermissionSeeder::class);
        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertStatus(200);
    }

    /**
     * Test that user can fetch and see a user.
     *
     * @return void
     */
    public function testUserCanSeeAnyUser(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.show', $user));
        $response->assertStatus(200);
    }

    /**
     * Test that user cannot fetch out of bound user.
     *
     * @return void
     */
    public function testUserCannotFetchOutOfBoundUserFromId(): void
    {
        $this->seed(PermissionSeeder::class);
        User::factory()->count(5)->create();

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.show', ['user' => 100]));
        $response->assertStatus(404);
    }

    /**
     * Test that user can reach the edit user form.
     *
     * @return void
     */
    public function testUserCanAccessEditForm(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.edit', $user));
        $response->assertStatus(200);
    }

    /**
     * Test that user can update own details.
     *
     * @return void
     */
    public function testUserCanUpdateSelf(): void
    {
        $this->seed(PermissionSeeder::class);

        $user       = User::factory()->create()->assignRole('User');
        $role       = Role::first();
        $user->name = "APPLE BOI";
        $user->role = $role->id;
        $response   = $this->actingAs($user)->from(route('users.edit', ['user' => $user]))
            ->put(route('users.update', ['user' => $user]), $user->toArray());
        $response->assertSessionHas('success')->assertRedirect(route('users.edit', ['user' => $user]));
    }

    /**
     * Test that user cannot trash own-self.
     *
     * @return void
     */
    public function testUserCannotTrashSelf(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->delete(route('users.destroy', ['user' => $user->id]));
        $response->assertStatus(403);
    }

    /**
     * Test that user cannot perma delete own-self.
     *
     * @return void
     */
    public function testUserCannotPermaDeleteSelf(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->from(route('users.trashed'))
            ->get(route('users.delete', ['id' => $user->id]));
        $response->assertRedirect(route('users.trashed'))->assertSessionHas('danger');
    }

    /**
     * @return void
     */
    public function testUserCanCreateUser(): void
    {
        $this->seed(PermissionSeeder::class);

        $role            = Role::findByName('User');
        $user            = User::factory()->create()->assignRole('User');
        $otherUser       = User::factory()->make()->assignRole('User');
        $otherUser->role = $role->id;

        $response = $this->actingAs($user)->from(route('users.create'))
            ->post(route('users.store', ['user' => $otherUser]), $otherUser->toArray());
        $response->assertRedirect(route('users.create'))->assertSessionHas('success');
    }
    /**
     * @return void
     */
    public function testUserCannotCreateAdmin(): void
    {
        $this->seed(PermissionSeeder::class);

        $role            = Role::findByName('Admin');
        $user            = User::factory()->create()->assignRole('User');
        $otherUser       = User::factory()->make()->assignRole('Admin');
        $otherUser->role = $role->id;

        $response = $this->actingAs($user)->from(route('users.create'))
            ->post(route('users.store', ['user' => $otherUser]), $otherUser->toArray());
        $response->assertStatus(403);
    }
}
