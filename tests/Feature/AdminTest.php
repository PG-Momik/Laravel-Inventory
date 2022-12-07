<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test that admin can see all users.
     *
     * @return void
     */
    public function testAdminCanSeeAllUsers(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertStatus(200);
    }

    /**
     * Test that admin can fetch and see a user.
     *
     * @return void
     */
    public function testAdminCanSeeAnyUser(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.show', $user));
        $response->assertStatus(200);
    }

    /**
     * Test that admin cannot fetch out of bound user.
     *
     * @return void
     */
    public function testAdminCannotFetchOutOfBoundUserFromId(): void
    {
        $this->seed(PermissionSeeder::class);
        User::factory()->count(5)->create();

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.show', ['user' => 100]));
        $response->assertStatus(404);
    }

    /**
     * Test that admin can reach the edit user form.
     *
     * @return void
     */
    public function testAdminCanAccessEditForm(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.edit', $user));
        $response->assertStatus(200);
    }

    /**
     * Test that admin can update own details.
     *
     * @return void
     */
    public function testAdminCanUpdateSelf(): void
    {
        $this->seed(PermissionSeeder::class);

        $role       = Role::first();
        $user       = User::factory()->create()->assignRole('Admin');
        $user->name = "APPLE BOI";
        $user->role = $role->id;
        $response   = $this->actingAs($user)->from(route('users.edit', ['user' => $user]))
            ->put(route('users.update', ['user' => $user]), $user->toArray());
        $response->assertSessionHas('success')->assertRedirect(route('users.edit', ['user' => $user]));
    }

    /**
     * Test that admin cannot trash own-self.
     *
     * @return void
     */
    public function testAdminCannotTrashSelf(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->delete(route('users.destroy', ['user' => $user->id]));
        $response->assertStatus(403);
    }

//    public function testAdminCanTrashOtherUser()
//    {
//        $this->seed(PermissionSeeder::class);
//        $user      = User::factory()->create()->assignRole('Admin');
//        $otherUser = User::factory()->create()->assignRole('User');
//        $response  = $this->actingAs($user)->delete(route('users.destroy', ['user' => $otherUser]));
//        $response->assertStatus(200);
//    }


    /**
     * Test that admin cannot perma delete own-self.
     *
     * @return void
     */
    public function testAdminCannotPermaDeleteSelf(): void
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->from(route('users.trashed'))
            ->get(route('users.delete', ['id' => $user->id]));
        $response->assertRedirect(route('users.trashed'))->assertSessionHas('danger');
    }

//    public function testAdminCanPermaDeleteOtherUserOrAdmin()
//    {
//        $this->seed(PermissionSeeder::class);
//
//        $user      = User::factory()->create()->assignRole('Admin');
//        $otherUser = User::factory()->create()->assignRole('User');
//        $response  = $this->actingAs($user)->delete(route('users.delete', ['user' => $otherUser]));
//        $response->assertStatus(200);
//    }
    /**
     * Test that admin can create users.
     *
     * @return void
     */
    public function testAdminCanCreateUser(): void
    {
        $this->seed(PermissionSeeder::class);

        $role            = Role::findByName('User');
        $user            = User::factory()->create()->assignRole('Admin');
        $otherUser       = User::factory()->make()->assignRole('User');
        $otherUser->role = $role->id;

        $response = $this->actingAs($user)->from(route('users.create'))
            ->post(route('users.store', ['user' => $otherUser]), $otherUser->toArray());
        $response->assertRedirect(route('users.create'));
    }
    /**
     * Test that admin can create admin.
     *
     * @return void
     */
    public function testAdminCanCreateAdmin(): void
    {
        $this->seed(PermissionSeeder::class);

        $role            = Role::findByName('Admin');
        $user            = User::factory()->create()->assignRole('Admin');
        $otherUser       = User::factory()->make()->assignRole('Admin');
        $otherUser->role = $role->id;

        $response = $this->actingAs($user)->from(route('users.create'))
            ->post(route('users.store', ['user' => $otherUser]), $otherUser->toArray());
        $response->assertRedirect(route('users.create'));
    }
}
