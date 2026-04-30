<?php

namespace App\Filament\Pages;

use App\Models\ImConversation;
use App\Models\ImMessage;
use App\Models\User;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class Messaging extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Messagerie';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?int    $navigationSort  = 1;
    protected static string  $view            = 'filament.pages.messaging';

    // Conversation active
    public ?int $activeConversationId = null;

    // Nouveau message dans la conversation active
    public string $newMessage = '';

    // Formulaire nouvelle conversation
    public ?array $newConvData = [];

    public function mount(): void
    {
        $this->newConvForm->fill();
    }

    // ── Formulaire : nouvelle conversation ──────────────────────────
    protected function getForms(): array
    {
        return ['newConvForm'];
    }

    public function newConvForm(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('subject')
                    ->label('Sujet')
                    ->required()
                    ->maxLength(255),
                Select::make('recipients')
                    ->label('Destinataires')
                    ->multiple()
                    ->options(User::where('id', '!=', auth()->id())->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                RichEditor::make('body')
                    ->label('Message')
                    ->required()
                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'link']),
            ])
            ->statePath('newConvData');
    }

    // ── Données ──────────────────────────────────────────────────────
    public function getConversations(): Collection
    {
        return ImConversation::inboxOrOutbox()->with(['owner', 'recipients'])->get();
    }

    public function getActiveConversation(): ?ImConversation
    {
        if (! $this->activeConversationId) return null;
        return ImConversation::with(['messages.user', 'recipients'])->find($this->activeConversationId);
    }

    // ── Actions ──────────────────────────────────────────────────────
    public function openConversation(int $id): void
    {
        $this->activeConversationId = $id;
        $conversation = ImConversation::find($id);
        $conversation?->updateSeenAt();
    }

    public function sendMessage(): void
    {
        $this->validate(['newMessage' => 'required|string']);

        $conversation = ImConversation::find($this->activeConversationId);
        if (! $conversation) return;

        ImMessage::create([
            'conversation_id' => $conversation->id,
            'user_id'         => auth()->id(),
            'body'            => $this->newMessage,
        ]);

        $conversation->updateSeenAt();
        $this->newMessage = '';
    }

    public function createConversation(): void
    {
        $data = $this->newConvForm->getState();

        $conversation = ImConversation::create([
            'subject'  => $data['subject'],
            'owner_id' => auth()->id(),
        ]);

        // Ajouter owner + destinataires comme recipients
        $recipients = collect($data['recipients'])->push(auth()->id())->unique();
        foreach ($recipients as $userId) {
            $conversation->recipients()->attach($userId, ['seen_at' => now()]);
        }

        ImMessage::create([
            'conversation_id' => $conversation->id,
            'user_id'         => auth()->id(),
            'body'            => $data['body'],
        ]);

        $this->newConvForm->fill();
        $this->activeConversationId = $conversation->id;

        Notification::make()->title('Conversation créée')->success()->send();
    }
}
