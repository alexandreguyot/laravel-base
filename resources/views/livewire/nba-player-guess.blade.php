<div class="max-w-lg mx-auto px-4 py-6 sm:py-8"
     x-data="{
        flipped: @js($revealedHints),
        newlyFlipped: @js($newlyRevealedHints),
        init() {
            if (this.newlyFlipped.length > 0) {
                this.newlyFlipped.forEach((key, i) => {
                    setTimeout(() => {
                        if (!this.flipped.includes(key)) {
                            this.flipped.push(key);
                        }
                    }, i * 250);
                });
            }
        }
     }"
     x-init="init()"
     wire:key="game-{{ $attempt }}"
>
    <style>
        .flip-card { perspective: 1000px; }
        .flip-card-inner {
            position: relative; width: 100%; height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .flip-card-inner.flipped { transform: rotateY(180deg); }
        .flip-card-face {
            position: absolute; width: 100%; height: 100%;
            backface-visibility: hidden; border-radius: 0.75rem;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .flip-card-back { transform: rotateY(180deg); }
        .card-surface {
            border: 2px solid rgba(167, 180, 204, 0.4);
            background: linear-gradient(145deg, #15293e, #0f2233);
            box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.25);
        }
        .card-revealed {
            border: 2px solid rgba(56, 152, 236, 0.6);
            background: linear-gradient(145deg, rgba(43, 104, 179, 0.9), rgba(25, 61, 98, 0.8));
            box-shadow: 0 4px 12px rgba(43, 104, 179, 0.3);
        }
        .guess-row {
            background: linear-gradient(160deg, #111e33, #0d1a2d);
            border: 1px solid rgba(148, 163, 184, 0.3);
            box-shadow: 0 4px 15px rgba(10, 20, 40, 0.2);
        }
        .cell-correct { background: rgba(16, 185, 129, 0.85); border-color: rgba(16, 185, 129, 0.5); }
        .cell-close { background: rgba(245, 158, 11, 0.85); border-color: rgba(245, 158, 11, 0.5); }
        .cell-wrong { background: rgba(100, 116, 139, 0.5); border-color: rgba(100, 116, 139, 0.3); }
        .cell-info { background: rgba(51, 65, 85, 0.7); border-color: rgba(100, 116, 139, 0.3); }
    </style>

    {{-- Header --}}
    <div class="text-center mb-6">
        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-1">Daily Dunk</h1>
        <p class="text-orange-400 text-sm sm:text-lg">Trouve le joueur mystère de la nuit !</p>
        @if ($totalChallenges > 1)
            <div class="flex justify-center gap-2 mt-3">
                @for ($i = 0; $i < $totalChallenges; $i++)
                    @php
                        $challengeState = session('nba_guess_' . ($allChallenges[$i]['id'] ?? ''));
                        $isDone = ($challengeState['game_won'] ?? false) || ($challengeState['game_lost'] ?? false);
                        $isWon = $challengeState['game_won'] ?? false;
                        $isCurrent = $i === $currentChallengeIndex;
                    @endphp
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all
                        {{ $isCurrent ? 'border-orange-400 bg-orange-400/20 text-orange-400 scale-110' :
                           ($isWon ? 'border-emerald-500 bg-emerald-500/20 text-emerald-400' :
                           ($isDone ? 'border-red-500 bg-red-500/20 text-red-400' :
                           'border-slate-600 bg-slate-800 text-slate-500')) }}">
                        {{ $i + 1 }}
                    </span>
                @endfor
            </div>
        @endif
        <div class="flex justify-center gap-3 mt-3">
            <button wire:click="toggleRules" class="text-xs sm:text-sm text-gray-400 hover:text-white transition-colors underline">
                Regles du jeu
            </button>
            <span class="text-gray-600">|</span>
            <span class="text-xs sm:text-sm text-gray-400">Joueur {{ $currentChallengeIndex + 1 }}/{{ $totalChallenges }} — Essai {{ $attempt }}/{{ $maxAttempts }}</span>
        </div>
    </div>

    {{-- Rules --}}
    @if ($showRules)
        <div class="bg-gray-700/80 border border-gray-600 rounded-xl p-4 sm:p-6 mb-6">
            <h2 class="text-lg font-bold text-white mb-3">Comment jouer ?</h2>
            <ul class="text-gray-300 space-y-2 text-xs sm:text-sm">
                <li>Devine le <strong class="text-orange-400">Joueur de la Nuit</strong> en {{ $maxAttempts }} essais.</li>
                <li>Tape au moins 3 lettres pour rechercher un joueur.</li>
                <li>A chaque essai, un nouvel indice se devoile :
                    <ol class="list-decimal ml-6 mt-1 text-gray-400">
                        <li>Points</li>
                        <li>Rebonds</li>
                        <li>Passes</li>
                        <li>Conference</li>
                        <li>Nationalite</li>
                        <li>Equipe</li>
                        <li>Poste</li>
                    </ol>
                </li>
                <li>Dans tes essais, les couleurs t'aident :
                    <div class="flex flex-wrap gap-2 mt-2 ml-2">
                        <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-emerald-500 inline-block"></span> Correct</span>
                        <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-amber-500 inline-block"></span> Proche</span>
                        <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-slate-500 inline-block"></span> Incorrect</span>
                    </div>
                </li>
            </ul>
            <button wire:click="toggleRules" class="mt-3 text-xs text-gray-400 hover:text-white transition-colors underline">Fermer</button>
        </div>
    @endif

    {{-- Error --}}
    @if ($error)
        <div class="bg-gray-700 border border-gray-600 rounded-xl p-6 text-center">
            <p class="text-gray-400">{{ $error }}</p>
        </div>
    @else
        {{-- Search Bar --}}
        @if (!$gameWon && !$gameLost)
            <div class="relative mb-6" x-data="{ open: false }" @click.away="open = false">
                <div class="flex items-center bg-slate-900 border border-slate-600 rounded-2xl overflow-hidden focus-within:border-sky-400 transition-colors">
                    <svg class="w-5 h-5 text-slate-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        wire:focus="focusSearch"
                        @focus="open = true"
                        @input="open = true"
                        placeholder="Rechercher un joueur..."
                        class="w-full bg-transparent text-white text-sm px-3 py-3 outline-none placeholder-slate-500"
                        autocomplete="off"
                    >
                    <div wire:loading wire:target="search" class="mr-3">
                        <svg class="animate-spin h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>

                @if (count($suggestions) > 0)
                    <div x-show="open" class="absolute z-50 w-full mt-1 bg-slate-800 border border-slate-600 rounded-xl shadow-xl overflow-hidden max-h-72 overflow-y-auto"
                         x-init="$watch('$wire.suggestions', v => { if (v.length > 0) open = true })">
                        @foreach ($suggestions as $index => $player)
                            <button
                                wire:click="selectPlayer({{ $index }})"
                                @click="open = false"
                                class="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-slate-700 transition-colors text-left border-b border-slate-700 last:border-b-0"
                            >
                                @if (!empty($player['headshot']))
                                    <img
                                        src="{{ $player['headshot'] }}"
                                        alt="{{ $player['name'] }}"
                                        class="w-10 h-10 rounded-full bg-slate-900 object-cover shrink-0"
                                        onerror="this.src=''; this.style.display='none'; this.nextElementSibling.style.display='flex'"
                                    >
                                    <div class="w-10 h-10 rounded-full bg-slate-900 items-center justify-center text-orange-400 font-bold text-xs shrink-0 hidden">
                                        {{ substr($player['name'], 0, 2) }}
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center text-orange-400 font-bold text-xs shrink-0">
                                        {{ substr($player['name'], 0, 2) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm font-medium truncate">{{ $player['name'] }}</p>
                                    <p class="text-slate-400 text-xs truncate">{{ $player['team'] }} — {{ $player['position'] ?: 'N/A' }}</p>
                                </div>
                                <img
                                    src="https://a.espncdn.com/i/teamlogos/nba/500/{{ strtolower($player['team_abbreviation']) }}.png"
                                    alt="{{ $player['team_abbreviation'] }}"
                                    class="w-6 h-6 shrink-0"
                                    onerror="this.style.display='none'"
                                >
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- Progressive Hints --}}
        @php
            $hints = [
                ['key' => 'pts',         'label' => 'PTS',   'value' => $challenge['pts'] ?? '?'],
                ['key' => 'reb',         'label' => 'REB',   'value' => $challenge['reb'] ?? '?'],
                ['key' => 'ast',         'label' => 'AST',   'value' => $challenge['ast'] ?? '?'],
                ['key' => 'conference',  'label' => 'CONF',  'value' => $challenge['conference'] ?? '?'],
                ['key' => 'nationality', 'label' => 'NAT.',  'value' => $challenge['nationality'] ?? ($challenge['country'] ?? '?')],
                ['key' => 'team',        'label' => 'EQUIPE','value' => $challenge['team_abbreviation'] ?? '?'],
                ['key' => 'position',    'label' => 'POSTE', 'value' => $challenge['position'] ?? '?'],
            ];
        @endphp

        <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 mb-8">
            @foreach ($hints as $i => $hint)
                <div class="aspect-square flip-card">
                    <div class="flip-card-inner w-full h-full"
                         :class="flipped.includes('{{ $hint['key'] }}') ? 'flipped' : ''">
                        <div class="flip-card-face card-surface p-3">
                            <p class="text-[9px] sm:text-[10px] text-slate-300 uppercase font-semibold tracking-wider mb-1">{{ $hint['label'] }}</p>
                            <p class="text-lg sm:text-xl font-bold text-slate-400">?</p>
                        </div>
                        <div class="flip-card-face flip-card-back card-revealed p-3">
                            <p class="text-[9px] sm:text-[10px] text-cyan-100 uppercase font-semibold tracking-wider mb-1">{{ $hint['label'] }}</p>
                            <p class="text-lg sm:text-xl font-bold text-white">{{ $hint['value'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Guesses History --}}
        @if (count($guesses) > 0)
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-slate-300 mb-4 uppercase tracking-wide">Tes essais</h2>

                <div class="space-y-3">
                    @foreach ($guesses as $guessIndex => $guess)
                        @php
                            $teamAbbrLower = strtolower($guess['team_abbreviation'] ?? '');
                            $compareStat = function($guessVal, $challengeVal) {
                                $g = (int) ($guessVal ?? 0);
                                $c = (int) ($challengeVal ?? 0);
                                if ($g === $c) return 'correct';
                                if (abs($g - $c) <= 3) return 'close';
                                return 'wrong';
                            };
                            $cells = [
                                ['label' => 'PTS', 'value' => $guess['pts'] ?? '—', 'status' => $compareStat($guess['pts'] ?? 0, $challenge['pts'] ?? 0)],
                                ['label' => 'REB', 'value' => $guess['reb'] ?? '—', 'status' => $compareStat($guess['reb'] ?? 0, $challenge['reb'] ?? 0)],
                                ['label' => 'AST', 'value' => $guess['ast'] ?? '—', 'status' => $compareStat($guess['ast'] ?? 0, $challenge['ast'] ?? 0)],
                                ['label' => 'CONF',  'value' => $guess['conference'] ?: '—',                                    'status' => $guess['feedback']['conference']],
                                ['label' => 'NAT.',  'value' => $guess['nationality'] ?? ($guess['country'] ?? '—'),          'status' => $guess['feedback']['nationality'] ?? 'wrong'],
                                ['label' => 'EQUIPE','value' => $guess['team_abbreviation'] ?: '—',                           'status' => $guess['feedback']['team']],
                                ['label' => 'POSTE', 'value' => $guess['position'] ?: '—',                                   'status' => $guess['feedback']['position']],
                            ];
                        @endphp

                        <div class="guess-row rounded-xl p-3">
                            {{-- Player header with photo --}}
                            @php
                                $guessHeadshot = $guess['headshot']
                                    ?? ($guess['espn_id'] ? "https://a.espncdn.com/i/headshots/nba/players/full/{$guess['espn_id']}.png" : null);
                                $guessTeamLogo = $guess['team_logo']
                                    ?? "https://a.espncdn.com/i/teamlogos/nba/500/{$teamAbbrLower}.png";
                            @endphp
                            <div class="flex items-center gap-3 mb-2 pb-2 border-b border-slate-700/50">
                                @if ($guessHeadshot)
                                    <img
                                        src="{{ $guessHeadshot }}"
                                        alt="{{ $guess['name'] }}"
                                        class="w-10 h-10 rounded-full bg-slate-800 object-cover shrink-0"
                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                                    >
                                    <div class="w-10 h-10 rounded-full bg-slate-700 items-center justify-center text-orange-400 font-bold text-xs shrink-0 hidden">
                                        {{ substr($guess['name'], 0, 2) }}
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-orange-400 font-bold text-xs shrink-0">
                                        {{ substr($guess['name'], 0, 2) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-bold text-sm truncate">{{ $guess['name'] }}</p>
                                    <p class="text-slate-400 text-xs">{{ $guess['team'] ?? $guess['team_abbreviation'] }}</p>
                                </div>
                                <img
                                    src="{{ $guessTeamLogo }}"
                                    alt="{{ $guess['team_abbreviation'] }}"
                                    class="w-8 h-8 shrink-0"
                                    onerror="this.style.display='none'"
                                >
                            </div>

                            {{-- Stats cells --}}
                            <div class="grid grid-cols-7 gap-1.5">
                                @foreach ($cells as $cell)
                                    @php
                                        $cellClass = match($cell['status']) {
                                            'correct' => 'cell-correct',
                                            'close' => 'cell-close',
                                            'info' => 'cell-info',
                                            default => 'cell-wrong',
                                        };
                                    @endphp
                                    <div class="rounded-lg border py-3 px-1 flex flex-col items-center justify-center text-center {{ $cellClass }}">
                                        <p class="text-[8px] sm:text-[9px] text-white/60 uppercase tracking-wider font-semibold mb-1">{{ $cell['label'] }}</p>
                                        <p class="text-white font-bold text-[10px] sm:text-xs leading-tight truncate w-full px-1">{{ $cell['value'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Mystery Player Stats (only after game ends) --}}
        @if ($gameWon || $gameLost)
            <div class="mb-6 rounded-xl border border-sky-500/40 bg-slate-900 p-4 shadow-lg">
                <div class="flex items-center gap-4 mb-4">
                    <img
                        src="{{ $challenge['headshot'] ?? '' }}"
                        alt="{{ $challenge['player_name'] }}"
                        class="w-16 h-16 rounded-full bg-slate-800 object-cover shrink-0"
                        onerror="this.style.display='none'"
                    >
                    <div>
                        <h3 class="text-white font-bold text-lg">{{ $challenge['player_name'] }}</h3>
                        <div class="flex items-center gap-2">
                            <img
                                src="{{ $challenge['team_logo'] ?? '' }}"
                                alt="{{ $challenge['team_abbreviation'] }}"
                                class="w-5 h-5"
                                onerror="this.style.display='none'"
                            >
                            <span class="text-slate-400 text-sm">{{ $challenge['team'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    @php
                        $mysteryStats = [
                            ['label' => 'PTS', 'value' => $challenge['pts']],
                            ['label' => 'REB', 'value' => $challenge['reb']],
                            ['label' => 'AST', 'value' => $challenge['ast']],
                            ['label' => 'MIN', 'value' => $challenge['min']],
                            ['label' => 'FG', 'value' => $challenge['fgm'] . '/' . $challenge['fga']],
                            ['label' => '3PT', 'value' => $challenge['fg3m'] . '/' . $challenge['fg3a']],
                            ['label' => 'FT', 'value' => $challenge['ftm'] . '/' . $challenge['fta']],
                            ['label' => 'EVAL', 'value' => $challenge['eval']],
                            ['label' => 'STL', 'value' => $challenge['stl']],
                            ['label' => 'BLK', 'value' => $challenge['blk']],
                            ['label' => 'TO', 'value' => $challenge['turnover']],
                            ['label' => 'NAT.', 'value' => $challenge['country'] ?? '—'],
                        ];
                    @endphp
                    @foreach ($mysteryStats as $stat)
                        <div class="bg-slate-800 rounded-lg py-2 px-3 text-center">
                            <p class="text-[9px] text-slate-400 uppercase tracking-wider font-semibold">{{ $stat['label'] }}</p>
                            <p class="text-white font-bold text-sm mt-0.5">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Win --}}
        @if ($gameWon)
            <div class="bg-emerald-900/30 border border-emerald-500/50 rounded-xl p-6 text-center mb-6">
                <p class="text-3xl mb-3">🏀</p>
                <h2 class="text-xl font-bold text-emerald-400 mb-2">Bravo !</h2>
                <p class="text-white mb-1">
                    C'etait <strong class="text-orange-400">{{ $challenge['player_name'] }}</strong>
                </p>
                <p class="text-slate-400 text-sm mb-4">Trouve en {{ $attempt }} essai{{ $attempt > 1 ? 's' : '' }} sur {{ $maxAttempts }}</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <button
                        onclick="navigator.clipboard.writeText(@js($this->getShareText())).then(() => this.innerText = 'Copie !')"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors text-sm"
                    >
                        Partager le resultat
                    </button>
                    @if ($currentChallengeIndex + 1 < $totalChallenges)
                        <button
                            wire:click="nextChallenge"
                            class="bg-sky-500 hover:bg-sky-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors text-sm"
                        >
                            Joueur suivant ({{ $currentChallengeIndex + 2 }}/{{ $totalChallenges }})
                        </button>
                    @endif
                </div>
            </div>
        @endif

        {{-- Lose --}}
        @if ($gameLost)
            <div class="bg-red-900/30 border border-red-500/50 rounded-xl p-6 text-center mb-6">
                <p class="text-3xl mb-3">😢</p>
                <h2 class="text-xl font-bold text-red-400 mb-2">Perdu !</h2>
                <p class="text-white mb-1">
                    C'etait <strong class="text-orange-400">{{ $challenge['player_name'] }}</strong>
                </p>
                <p class="text-slate-400 text-sm mb-4">
                    @if ($currentChallengeIndex + 1 < $totalChallenges)
                        Essaie le joueur suivant !
                    @else
                        Reviens demain pour un nouveau defi !
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <button
                        onclick="navigator.clipboard.writeText(@js($this->getShareText())).then(() => this.innerText = 'Copie !')"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors text-sm"
                    >
                        Partager le resultat
                    </button>
                    @if ($currentChallengeIndex + 1 < $totalChallenges)
                        <button
                            wire:click="nextChallenge"
                            class="bg-sky-500 hover:bg-sky-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors text-sm"
                        >
                            Joueur suivant ({{ $currentChallengeIndex + 2 }}/{{ $totalChallenges }})
                        </button>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
