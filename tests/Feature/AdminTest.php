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
     * Test that admin can see all users.
     *
     * @return void
     */
    public function testAdminCanSeeAllUsers(): void
    {
        $response = $this->actingAs($this->user)->get(route('users.index'));
        $response->assertStatus(200);
    }

    /**
     * Test that admin can fetch and see a user.
     *
     * @return void
     */
    public function testAdminCanSeeAnyUser(): void
    {
        $response = $this->actingAs($this->user)->get(route('users.show', $this->user));
        $response->assertStatus(200);
    }

    /**
     * Test that admin cannot fetch out of bound user.
     *
     * @return void
     */
    public function testAdminCannotFetchOutOfBoundUserFromId(): void
    {
        User::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->get(route('users.show', ['user' => 100]));
        $response->assertStatus(404);
    }

    /**
     * Test that admin can reach the edit user form.
     *
     * @return void
     */
    public function testAdminCanAccessEditForm(): void
    {
        $response = $this->actingAs($this->user)->get(route('users.edit', $this->user));
        $response->assertStatus(200);
    }

    /**
     * Test that admin can update own details.
     *
     * @return void
     */
    public function testAdminCanUpdateSelf(): void
    {
        $role             = Role::first();
        $this->user->name = "APPLE BOI";
        $this->user->role = $role->id;
        $response         = $this->actingAs($this->user)->from(route('users.edit', ['user' => $this->user]))
            ->put(route('users.update', ['user' => $this->user]), $this->user->toArray());
        $response->assertSessionHas('success')->assertRedirect(route('users.edit', ['user' => $this->user]));
    }

    /**
     * Test that admin cannot trash own-self.
     *
     * @return void
     */
    public function testAdminCannotTrashSelf(): void
    {
        $response = $this->actingAs($this->user)->delete(route('users.destroy', ['user' => $this->user->id]));
        $response->assertStatus(403);
    }

    /**
     * Test that admin cannot perma delete own-self.
     *
     * @return void
     */
    public function testAdminCannotPermaDeleteSelf(): void
    {
        $response = $this->actingAs($this->user)->from(route('users.trashed'))
            ->get(route('users.delete', ['id' => $this->user->id]));
        $response->assertRedirect(route('users.trashed'))->assertSessionHas('danger');
    }

    /**
     * Test that admin can create users.
     *
     * @return void
     */
    public function testAdminCanCreateUser(): void
    {
        $role            = Role::findByName('User');
        $otherUser       = User::factory()->make()->assignRole('User');
        $otherUser->role = $role->id;

        $response = $this->actingAs($this->user)->from(route('users.create'))
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
        $role            = Role::findByName('Admin');
        $otherUser       = User::factory()->make()->assignRole('Admin');
        $otherUser->role = $role->id;

        $response = $this->actingAs($this->user)->from(route('users.create'))
            ->post(route('users.store', ['user' => $otherUser]), $otherUser->toArray());
        $response->assertRedirect(route('users.create'));
    }

    /**
     * Create User instance with admin role.
     *
     * @return User
     */
    private function createUser(): User
    {
        return User::factory()->create()->assignRole('Admin');
    }
}
