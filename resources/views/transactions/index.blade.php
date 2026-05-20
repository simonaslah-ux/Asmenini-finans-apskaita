<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pajamų ir išlaidų įrašai
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('transactions.create') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Pridėti įrašą
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="min-w-full border">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2 text-left">Data</th>
                                <th class="border px-4 py-2 text-left">Tipas</th>
                                <th class="border px-4 py-2 text-left">Kategorija</th>
                                <th class="border px-4 py-2 text-left">Suma</th>
                                <th class="border px-4 py-2 text-left">Aprašymas</th>
                                <th class="border px-4 py-2 text-left">Veiksmai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="border px-4 py-2">{{ $transaction->date }}</td>

                                    <td class="border px-4 py-2">
                                        @if ($transaction->type == 'income')
                                            Pajamos
                                        @else
                                            Išlaidos
                                        @endif
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $transaction->category->name ?? '-' }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ number_format($transaction->amount, 2) }} €
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $transaction->description }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        <a href="{{ route('transactions.edit', $transaction) }}"
                                           class="text-blue-600 hover:underline">
                                            Redaguoti
                                        </a>

                                        <form action="{{ route('transactions.destroy', $transaction) }}"
                                              method="POST"
                                              class="inline-block ml-3"
                                              onsubmit="return confirm('Ar tikrai norite ištrinti?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">
                                                Trinti
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border px-4 py-2 text-center">
                                        Įrašų dar nėra.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>