<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kategorijos
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
                <a href="{{ route('categories.create') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Pridėti kategoriją
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="min-w-full border">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2 text-left">Pavadinimas</th>
                                <th class="border px-4 py-2 text-left">Tipas</th>
                                <th class="border px-4 py-2 text-left">Veiksmai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="border px-4 py-2">{{ $category->name }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($category->type == 'income')
                                            Pajamos
                                        @else
                                            Išlaidos
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('categories.edit', $category) }}"
                                           class="text-blue-600 hover:underline">
                                            Redaguoti
                                        </a>

                                        <form action="{{ route('categories.destroy', $category) }}"
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
                                    <td colspan="3" class="border px-4 py-2 text-center">
                                        Kategorijų dar nėra.
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