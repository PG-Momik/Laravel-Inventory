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

    private User $user;

    /**
     * Setup Permission seeder and create User instance.
     *
     * */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->user = $this->createUser();
    }

    /**
     * Test that user can see all users.
     *
     * @return void
     */
    public function testUserCanSeeAllUsers(): void
    {
        $response = $this->actingAs($this->user)->get(route('users.index'));
        $response->assertStatus(200);
    }

    /**
     * Test that user can fetch and see a user.
     *
     * @return void
     */
    public function testUserCanSeeAnyUser(): void
    {
        $response = $this->actingAs($this->user)->get(route('users.show', $this->user));
        $response->assertStatus(200);
    }

    /**
     * Test that user cannot fetch out of bound user.
     *
     * @return void
     */
    public function testUserCannotFetchOutOfBoundUserFromId(): void
    {
        User::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->get(route('users.show', ['user' => 100]));
        $response->assertStatus(404);
    }

    /**
     * Test that user can reach the edit user form.
     *
     * @return void
     */
    public function testUserCanAccessEditForm(): void
    {
        $response = $this->actingAs($this->user)->get(route('users.edit', $this->user));
        $response->assertStatus(200);
    }

    /**
     * Test that user can update own details.
     *
     * @return void
     */
    public function testUserCanUpdateSelf(): void
    {
        $this->user       = User::factory()->create()->assignRole('User');
        $role             = Role::first();
        $this->user->name = "APPLE BOI";
        $this->user->role = $role->id;
        $response         = $this->actingAs($this->user)->from(route('users.edit', ['user' => $this->user]))
            ->put(route('users.update', ['user' => $this->user]), $this->user->toArray());
        $response->assertSessionHas('success')->assertRedirect(route('users.edit', ['user' => $this->user]));
    }

    /**
     * Test that user cannot trash own-self.
     *
     * @return void
     */
    public function testUserCannotTrashSelf(): void
    {
        $response = $this->actingAs($this->user)->delete(route('users.destroy', ['user' => $this->user->id]));
        $response->assertStatus(403);
    }

    /**
     * Test that user cannot perma delete own-self.
     *
     * @return void
     */
    public function testUserCannotPermaDeleteSelf(): void
    {
        $response = $this->actingAs($this->user)->from(route('users.trashed'))
            ->get(route('users.delete', ['id' => $this->user->id]));
        $response->assertRedirect(route('users.trashed'))->assertSessionHas('danger');
    }

    /**
     * @return void
     */
    public function testUserCannotCreateUser(): void
    {
        $role            = Role::findByName('User');
        $otherUser       = User::factory()->make()->assignRole('User');
        $otherUser->role = $role->id;

        $response = $this->actingAs($this->user)->from(route('users.create'))
            ->post(route('users.store', ['user' => $otherUser]), $otherUser->toArray());
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function testUserCannotCreateAdmin(): void
    {
        $role            = Role::findByName('Admin');
        $otherUser       = User::factory()->make()->assignRole('Admin');
        $otherUser->role = $role->id;

        $response = $this->actingAs($this->user)->from(route('users.create'))
            ->post(route('users.store', ['user' => $otherUser]), $otherUser->toArray());
        $response->assertStatus(403);
    }

    /**
     * Create User instance with user role.
     *
     * @return User
     */
    private function createUser(): User
    {
        return User::factory()->create()->assignRole('User');
    }
}
