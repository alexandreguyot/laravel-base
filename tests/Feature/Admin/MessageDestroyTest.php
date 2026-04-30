<?php

namespace Tests\Feature\Admin;

use App\Models\ImConversation;
use App\Models\User;
use Tests\AdminTestCase;

class MessageDestroyTest extends AdminTestCase
{
    private function user(): User
    {
        return User::factory()->create();
    }

    private function conversation(User $owner, array $participants = []): ImConversation
    {
        $conv = ImConversation::create([
            'subject'  => 'Test',
            'owner_id' => $owner->id,
        ]);

        foreach (array_merge([$owner->id], array_map(fn ($u) => $u->id, $participants)) as $id) {
            $conv->recipients()->attach($id, ['seen_at' => now()]);
        }

        return $conv;
    }

    public function test_owner_can_delete_conversation(): void
    {
        $owner = $this->user();
        $conv  = $this->conversation($owner);

        $this->actingAs($owner)
            ->post("/messages/{$conv->id}/destroy")
            ->assertRedirect();

        $this->assertSoftDeleted('im_conversations', ['id' => $conv->id]);
    }

    public function test_non_owner_only_detaches_themselves(): void
    {
        $owner     = $this->user();
        $recipient = $this->user();
        $conv      = $this->conversation($owner, [$recipient]);

        $this->actingAs($recipient)
            ->post("/messages/{$conv->id}/destroy")
            ->assertRedirect();

        // The conversation itself should still exist...
        $this->assertDatabaseHas('im_conversations', ['id' => $conv->id, 'deleted_at' => null]);
        // ...but the recipient is no longer attached.
        $this->assertFalse($conv->fresh()->recipients->contains('id', $recipient->id));
    }
}
