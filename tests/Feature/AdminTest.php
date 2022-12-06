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


    public function testAdminCanSeeAllUsers()
    {
        $this->seed(PermissionSeeder::class);

        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertStatus(200);
    }

    public function testAdminCanSeeAnyUser()
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.show', $user));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.show', $user));
        $response->assertStatus(200);
    }

    public function testAdminCannotFetchOutOfBoundUserFromId()
    {
        $this->seed(PermissionSeeder::class);
        User::factory()->count(5)->create();

        $response = $this->get(route('users.show', ['user' => 100]));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.show', ['user' => 100]));
        $response->assertStatus(404);
    }

    public function testAdminCanAccessEditForm()
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.edit', $user));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('Admin');
        $response = $this->actingAs($user)->get(route('users.edit', $user));
        $response->assertStatus(200);
    }

    public function testAdminCanUpdateSelf()
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.update', $user));
        $response->assertRedirect(route('login'));

        $role       = Role::first();
        $user       = User::factory()->create()->assignRole('Admin');
        $user->name = "APPLE BOI";
        $user->role = $role->id;
        $response   = $this->actingAs($user)->from(route('users.edit', ['user' => $user]))
            ->put(route('users.update', ['user' => $user]), $user->toArray());
        $response->assertSessionHas('success')->assertRedirect(route('users.edit', ['user' => $user]));
    }


    public function testAdminCannotTrashSelf()
    {
        $this->seed(PermissionSeeder::class);

        $response = $this->delete(route('users.destroy', ['user' => 1]));
        $response->assertRedirect(route('login'));

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

    public function testAdminCannotPermaDeleteSelf()
    {
        $this->seed(PermissionSeeder::class);

        $response = $this->get(route('users.delete', ['id' => 1]));
        $response->assertRedirect(route('login'));

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
}
