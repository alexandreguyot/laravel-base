<x-filament-panels::page>
    <div class="flex h-[calc(100vh-12rem)] gap-4 overflow-hidden">

        {{-- Sidebar conversations --}}
        <div class="w-80 flex flex-col gap-3 shrink-0">

            {{-- Nouvelle conversation --}}
            <x-filament::section>
                <x-slot name="heading">Nouvelle conversation</x-slot>
                <form wire:submit="createConversation" class="space-y-3">
                    {{ $this->newConvForm }}
                    <x-filament::button type="submit" size="sm" class="w-full">
                        Envoyer
                    </x-filament::button>
                </form>
            </x-filament::section>

            {{-- Liste des conversations --}}
            <x-filament::section class="flex-1 overflow-y-auto">
                <x-slot name="heading">Conversations</x-slot>
                <div class="space-y-1">
                    @forelse($this->getConversations() as $conv)
                        <button
                            wire:click="openConversation({{ $conv->id }})"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors
                                {{ $activeConversationId === $conv->id
                                    ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700'
                                    : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                        >
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-medium text-sm truncate">{{ $conv->subject }}</span>
                                @if($conv->is_unread)
                                    <span class="w-2 h-2 rounded-full bg-primary-500 shrink-0"></span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $conv->owner->name }}
                                · {{ $conv->updated_at->diffForHumans() }}
                            </div>
                        </button>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">Aucune conversation</p>
                    @endforelse
                </div>
            </x-filament::section>
        </div>

        {{-- Zone de conversation --}}
        <x-filament::section class="flex-1 flex flex-col overflow-hidden">
            @if($conversation = $this->getActiveConversation())
                <x-slot name="heading">
                    {{ $conversation->subject }}
                    <span class="text-xs font-normal text-gray-400 ml-2">
                        avec {{ $conversation->recipients->pluck('name')->implode(', ') }}
                    </span>
                </x-slot>

                {{-- Messages --}}
                <div class="flex-1 overflow-y-auto space-y-3 py-2 pr-1" id="pb-messages">
                    @foreach($conversation->messages->reverse() as $message)
                        <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%]">
                                <div class="text-xs text-gray-400 mb-1 {{ $message->user_id === auth()->id() ? 'text-right' : '' }}">
                                    {{ $message->user->name }} · {{ $message->created_at->diffForHumans() }}
                                </div>
                                <div class="px-4 py-2 rounded-2xl text-sm
                                    {{ $message->user_id === auth()->id()
                                        ? 'bg-primary-500 text-white rounded-tr-none'
                                        : 'bg-gray-100 dark:bg-gray-800 rounded-tl-none' }}">
                                    {!! $message->body !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Zone de saisie --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3 flex gap-2">
                    <textarea
                        wire:model="newMessage"
                        wire:keydown.ctrl.enter="sendMessage"
                        rows="2"
                        placeholder="Votre message… (Ctrl+Entrée pour envoyer)"
                        class="flex-1 resize-none rounded-lg border border-gray-300 dark:border-gray-700
                               bg-white dark:bg-gray-900 px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-primary-500"
                    ></textarea>
                    <x-filament::button wire:click="sendMessage" size="sm" class="self-end">
                        Envoyer
                    </x-filament::button>
                </div>
            @else
                <x-slot name="heading">Messagerie</x-slot>
                <div class="flex-1 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="w-12 h-12 mx-auto mb-3 opacity-30" />
                        <p>Sélectionnez une conversation ou créez-en une nouvelle</p>
                    </div>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
