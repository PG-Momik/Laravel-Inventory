<?php

namespace Tests\Feature;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;


    public function testUserCanSeeAllUsers()
    {
        $this->seed(PermissionSeeder::class);

        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertStatus(200);
    }

    public function testUserCanSeeAnyUser()
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.show', $user));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.show', $user));
        $response->assertStatus(200);
    }

    public function testUserCannotFetchOutOfBoundUserFromId()
    {
        $this->seed(PermissionSeeder::class);
        User::factory()->count(5)->create();

        $response = $this->get(route('users.show', ['user' => 100]));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.show', ['user' => 100]));
        $response->assertStatus(404);
    }

    public function testUserCanAccessEditForm()
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.edit', $user));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->get(route('users.edit', $user));
        $response->assertStatus(200);
    }

    public function testUserCanUpdateSelf()
    {
        $this->seed(PermissionSeeder::class);

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->get(route('users.update', $user));
        $response->assertRedirect(route('login'));

        $user = User::factory()->create()->assignRole('User');
        $role = Role::first();
//        dd($role->toArray());
        $user->name = "APPLE BOI";
        $user->role = $role->id;
//        $this->mock(UpdateUserRequest::class, function ($mock) {
//            $mock->shouldReceive('passes')->andReturn(true);
//        });
        $response = $this->actingAs($user)->from(route('users.edit', ['user' => $user]))
            ->put(route('users.update', ['user' => $user]), $user->toArray());
        $response->assertSessionHas('success')->assertRedirect(route('users.edit', ['user' => $user]));
    }


    public function testUserCannotTrashSelf()
    {
        $this->seed(PermissionSeeder::class);

        $response = $this->delete(route('users.destroy', ['user' => 1]));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->delete(route('users.destroy', ['user' => $user->id]));
        $response->assertStatus(403);
    }

//    public function testUserCannotTrashOtherUser()
//    {
//        $this->seed(PermissionSeeder::class);
//
//        $user      = User::factory()->create()->assignRole('User');
//        $otherUser = User::factory()->create()->assignRole('User');
//        $response  = $this->actingAs($user)->delete(route('users.destroy', ['user' => $otherUser]));
//        $response->assertStatus(403);
//    }

    public function testUserCannotPermaDeleteSelf()
    {
        $this->seed(PermissionSeeder::class);

        $response = $this->get(route('users.delete', ['id' => 1]));
        $response->assertRedirect(route('login'));

        $user     = User::factory()->create()->assignRole('User');
        $response = $this->actingAs($user)->from(route('users.trashed'))
            ->get(route('users.delete', ['id' => $user->id]));
        $response->assertRedirect(route('users.trashed'))->assertSessionHas('danger');
    }

//    public function testUserCannotPermaDeleteOtherUser()
//    {
//        $this->seed(PermissionSeeder::class);
//
//        $user      = User::factory()->create()->assignRole('User');
//        $otherUser = User::factory()->create()->assignRole('User');
////        dd($user->getPermissionsViaRoles()->toArray());
//        $response = $this->actingAs($user)->delete(route('users.delete', ['user' => $otherUser]));
//        $response->assertStatus(403);
//    }
}
