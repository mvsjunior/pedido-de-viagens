<?php

namespace Tests\Feature;

use App\Domains\Travel\Models\User;
use App\Domains\Travel\Models\TravelRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $commonUser;
    private User $manager;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar usuários com diferentes papéis
        $this->commonUser = User::factory()->create(['role' => 'common_user']);
        $this->manager = User::factory()->create(['role' => 'manager']);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    
    public function test_user_can_create_a_travel_request()
    {
        $payload = [
            'destination' => 'Lisboa',
            'departureDate' => now()->addDays(5)->toDateString(),
            'returnDate' => now()->addDays(10)->toDateString(),
        ];

        $token = auth('api')->login($this->commonUser);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                            ->postJson(route('travel.open'), $payload);

        $response->assertCreated()
                 ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('travel_requests', [
            'destination' => 'Lisboa',
            'status' => 'pending',
            'user_id' => $this->commonUser->id,
        ]);
    }

    
    public function test_user_can_list_their_own_travel_requests()
    {
        TravelRequest::factory()->create([
            'user_id' => $this->commonUser->id,
            'destination' => 'Paris',
        ]);

        $token = auth('api')->login($this->commonUser);
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson(route('travel.list'));

        $response->assertOk()
                 ->assertJsonFragment(['destination' => 'Paris']);
    }

    
    public function test_user_can_view_a_specific_travel_request()
    {
        $travel = TravelRequest::factory()->create(['user_id' => $this->commonUser->id]);

        $token = auth('api')->login($this->commonUser);
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson(route('travel.show', $travel->id));

        $response->assertOk()
                 ->assertJsonFragment(['id' => $travel->id]);
    }

    
    public function test_user_can_edit_their_pending_travel_request()
    {
        $travel = TravelRequest::factory()->create([
            'user_id' => $this->commonUser->id,
            'destination' => 'Roma',
            'status' => 'pending',
        ]);

        $token = auth('api')->login($this->commonUser);
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->patchJson(route('travel.edit', $travel->id), [
                'destination' => 'Londres',
            ]);

        $response->assertOk();
                //  ->assertJsonFragment(['destination' => 'Londres']);

        $this->assertDatabaseHas('travel_requests', [
            'id' => $travel->id,
            'destination' => 'Londres',
        ]);
    }

    
    public function test_manager_can_approve_a_pending_request()
    {
        $travel = TravelRequest::factory()->create([
            'user_id' => $this->commonUser->id,
            'status' => 'pending',
        ]);

        $token = auth('api')->login($this->manager);
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->patchJson(route('travel.approve', $travel->id));

        $response->assertOk();

        $this->assertDatabaseHas('travel_requests', [
            'id' => $travel->id,
            'status' => 'approved',
            'approved_by' => $this->manager->id,
        ]);
    }

    
    public function test_manager_can_cancel_a_pending_request_with_reason()
    {
        $travel = TravelRequest::factory()->create([
            'user_id' => $this->commonUser->id,
            'status' => 'pending',
        ]);

        $token = auth('api')->login($this->manager);
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->patchJson(route('travel.cancel', $travel->id), [
                'cancelReason' => 'Mudança de agenda',
            ]);

        $response->assertOk();
                //  ->assertJsonFragment(['status' => 'canceled']);

        $this->assertDatabaseHas('travel_requests', [
            'id' => $travel->id,
            'status' => 'canceled',
            'cancel_reason' => 'Mudança de agenda',
            'canceled_by' => $this->manager->id,
        ]);
    }

    
    public function test_common_user_cannot_approve_or_cancel_requests()
    {
        $travel = TravelRequest::factory()->create([
            'user_id' => $this->commonUser->id,
            'status' => 'pending',
        ]);

        $token = auth('api')->login($this->commonUser);
        $this->withHeader('Authorization', "Bearer {$token}")
            ->patchJson(route('travel.approve', $travel->id))
            ->assertUnauthorized();

        $this->withHeader('Authorization', "Bearer {$token}")
            ->patchJson(route('travel.cancel', $travel->id), ['cancelReason' => 'teste'])
            ->assertUnauthorized();
    }

    
    public function test_admin_can_approve_or_cancel_any_request()
    {
        $travel = TravelRequest::factory()->create([
            'user_id' => $this->commonUser->id,
            'status' => 'pending',
        ]);

        $adminUserToken = auth('api')->login($this->admin);
        $this->withHeader('Authorization', "Bearer {$adminUserToken}")
            ->patchJson(route('travel.approve', $travel->id))
            ->assertOk();
            // ->assertJsonFragment(['status' => 'approved']);

        $this->withHeader('Authorization', "Bearer {$adminUserToken}")
            ->patchJson(route('travel.cancel', $travel->id), [
                'cancelReason' => 'Revisão administrativa',
            ])
            ->assertOk();
            // ->assertJsonFragment(['status' => 'canceled']);
    }
}
