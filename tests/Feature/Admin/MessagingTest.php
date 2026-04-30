<?php

namespace Tests\Feature\Admin;

use App\Models\ImConversation;
use App\Models\ImMessage;
use App\Models\User;
use Tests\AdminTestCase;

class MessagingTest extends AdminTestCase
{

    private function user(): User
    {
        return User::factory()->create();
    }

    private function conversation(User $owner, array $recipients = []): ImConversation
    {
        $conversation = ImConversation::create([
            'subject'  => 'Test conversation',
            'owner_id' => $owner->id,
        ]);

        $allParticipants = array_merge([$owner->id], array_map(fn ($u) => $u->id, $recipients));
        foreach ($allParticipants as $userId) {
            $conversation->recipients()->attach($userId, ['seen_at' => now()]);
        }

        return $conversation;
    }

    // --- Access ---

    public function test_messages_index_requires_auth(): void
    {
        $this->get('/messages')->assertRedirect('/login');
    }

    public function test_messages_inbox_requires_auth(): void
    {
        $this->get('/messages/inbox')->assertRedirect('/login');
    }

    public function test_messages_index_is_accessible(): void
    {
        $this->actingAs($this->user())
            ->get('/messages')
            ->assertOk();
    }

    public function test_messages_inbox_is_accessible(): void
    {
        $this->actingAs($this->user())
            ->get('/messages/inbox')
            ->assertOk();
    }

    public function test_messages_outbox_is_accessible(): void
    {
        $this->actingAs($this->user())
            ->get('/messages/outbox')
            ->assertOk();
    }

    public function test_messages_create_page_is_accessible(): void
    {
        $this->actingAs($this->user())
            ->get('/messages/create')
            ->assertOk();
    }

    // --- Create conversation ---

    public function test_conversation_can_be_created(): void
    {
        $sender    = $this->user();
        $recipient = $this->user();

        $this->actingAs($sender)
            ->post('/messages', [
                'subject' => 'Bonjour',
                'to'      => [$recipient->id],
                'body'    => 'Ceci est un premier message.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('im_conversations', ['subject' => 'Bonjour']);
    }

    public function test_conversation_creation_requires_message(): void
    {
        $sender    = $this->user();
        $recipient = $this->user();

        $this->actingAs($sender)
            ->post('/messages', [
                'subject' => 'Bonjour',
                'to'      => [$recipient->id],
                'body'    => '',
            ])
            ->assertSessionHasErrors('body');
    }

    public function test_conversation_creation_requires_recipients(): void
    {
        $sender = $this->user();

        $this->actingAs($sender)
            ->post('/messages', [
                'subject' => 'Test',
                'to'      => [],
                'body'    => 'Un message.',
            ])
            ->assertSessionHasErrors('to');
    }

    // --- Show conversation ---

    public function test_conversation_show_is_accessible_for_participant(): void
    {
        $sender       = $this->user();
        $conversation = $this->conversation($sender);

        $this->actingAs($sender)
            ->get("/messages/{$conversation->id}")
            ->assertOk();
    }

    // --- Reply ---

    public function test_reply_can_be_sent(): void
    {
        $sender       = $this->user();
        $conversation = $this->conversation($sender);

        $this->actingAs($sender)
            ->post("/messages/{$conversation->id}", [
                'body' => 'Ma réponse ici.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('im_messages', ['body' => 'Ma réponse ici.']);
    }

    public function test_reply_requires_body(): void
    {
        $sender       = $this->user();
        $conversation = $this->conversation($sender);

        $this->actingAs($sender)
            ->post("/messages/{$conversation->id}", ['body' => ''])
            ->assertSessionHasErrors('body');
    }

    // --- Model ---

    public function test_message_belongs_to_conversation(): void
    {
        $user         = $this->user();
        $conversation = $this->conversation($user);

        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'body'    => 'Test body',
        ]);

        $this->assertEquals($conversation->id, $message->conversation->id);
    }

    public function test_conversation_has_recipients(): void
    {
        $owner     = $this->user();
        $recipient = $this->user();
        $conv      = $this->conversation($owner, [$recipient]);

        $this->assertTrue($conv->recipients->contains('id', $owner->id));
        $this->assertTrue($conv->recipients->contains('id', $recipient->id));
    }
}
