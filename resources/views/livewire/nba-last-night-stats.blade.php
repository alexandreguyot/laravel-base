<div class="max-w-7xl mx-auto px-4 py-8">
    @if ($error)
        <div class="bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded-lg mb-6">
            {{ $error }}
        </div>
    @elseif (empty($players))
        <div class="bg-gray-700 rounded-lg p-8 text-center">
            <p class="text-gray-400 text-lg">Aucune donnee disponible pour cette date.</p>
        </div>
    @else
        {{-- Date header with navigation --}}
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-white mb-1">Stats de la nuit</h1>
            <div class="flex items-center justify-center gap-4 mt-3">
                @if ($previousDate)
                    <a href="{{ route('get.stats', $previousDate) }}" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                @else
                    <div class="w-6"></div>
                @endif
                <div>
                    <p class="text-orange-400 text-lg font-semibold capitalize">{{ $displayDate }}</p>
                    <p class="text-gray-400 text-sm">{{ count($players) }} joueurs</p>
                </div>
                @if ($nextDate)
                    <a href="{{ route('get.stats', $nextDate) }}" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <div class="w-6"></div>
                @endif
            </div>
        </div>

        {{-- Date pills --}}
        @if (count($availableDates) > 1)
            <div class="flex justify-center gap-2 mb-8 flex-wrap">
                @foreach ($availableDates as $d)
                    <a href="{{ route('get.stats', $d) }}"
                       class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors
                           {{ $d === $date
                               ? 'bg-orange-500 text-white'
                               : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                        {{ \Carbon\Carbon::parse($d)->translatedFormat('D j M Y') }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg border border-gray-600">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-700 text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Joueur</th>
                        <th class="px-4 py-3">Equipe</th>
                        <th class="px-4 py-3 text-center">Poste</th>
                        <th class="px-4 py-3 text-center">MIN</th>
                        <th class="px-4 py-3 text-center">PTS</th>
                        <th class="px-4 py-3 text-center">REB</th>
                        <th class="px-4 py-3 text-center">AST</th>
                        <th class="px-4 py-3 text-center">STL</th>
                        <th class="px-4 py-3 text-center">BLK</th>
                        <th class="px-4 py-3 text-center">TO</th>
                        <th class="px-4 py-3 text-center">FG</th>
                        <th class="px-4 py-3 text-center">3PT</th>
                        <th class="px-4 py-3 text-center">FT</th>
                        <th class="px-4 py-3 text-center">EVAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach ($players as $index => $player)
                        <tr class="{{ $index % 2 === 0 ? 'bg-gray-800' : 'bg-gray-800/50' }} hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-3 font-medium text-white whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if (!empty($player['headshot']))
                                        <img src="{{ $player['headshot'] }}" alt="{{ $player['player_name'] }}" class="w-8 h-8 rounded-full bg-gray-700 object-cover shrink-0" onerror="this.style.display='none'">
                                    @endif
                                    {{ $player['player_name'] }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-400">
                                <div class="flex items-center gap-2">
                                    @if (!empty($player['team_logo']))
                                        <img src="{{ $player['team_logo'] }}" alt="{{ $player['team_abbreviation'] }}" class="w-5 h-5 shrink-0" onerror="this.style.display='none'">
                                    @endif
                                    {{ $player['team_abbreviation'] ?? $player['team'] }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $player['position'] ?? '—' }}</td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $player['min'] ?? '—' }}</td>
                            <td class="px-4 py-3 text-center font-bold {{ ($player['pts'] ?? 0) >= 30 ? 'text-orange-400' : 'text-white' }}">
                                {{ $player['pts'] ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $player['reb'] ?? 0 }}</td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $player['ast'] ?? 0 }}</td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $player['stl'] ?? 0 }}</td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $player['blk'] ?? 0 }}</td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $player['turnover'] ?? 0 }}</td>
                            <td class="px-4 py-3 text-center text-gray-300">
                                {{ $player['fgm'] ?? 0 }}/{{ $player['fga'] ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-300">
                                {{ $player['fg3m'] ?? 0 }}/{{ $player['fg3a'] ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-300">
                                {{ $player['ftm'] ?? 0 }}/{{ $player['fta'] ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-center font-bold {{ ($player['eval'] ?? 0) >= 30 ? 'text-orange-400' : 'text-white' }}">
                                {{ $player['eval'] ?? 0 }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination prev/next --}}
        <div class="flex items-center justify-between mt-8">
            @if ($previousDate)
                <a href="{{ route('get.stats', $previousDate) }}"
                   class="flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-white px-5 py-3 rounded-xl transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($previousDate)->format('d/m/Y') }}
                </a>
            @else
                <div></div>
            @endif

            @if ($nextDate)
                <a href="{{ route('get.stats', $nextDate) }}"
                   class="flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-white px-5 py-3 rounded-xl transition-colors text-sm font-medium">
                    {{ \Carbon\Carbon::parse($nextDate)->format('d/m/Y') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <div></div>
            @endif
        </div>
    @endif
</div>
