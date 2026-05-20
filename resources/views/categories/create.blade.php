<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nauja kategorija
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block mb-1">Pavadinimas</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full border-gray-300 rounded">
                            @error('name')
                                <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Tipas</label>
                            <select name="type" class="w-full border-gray-300 rounded">
                                <option value="income">Pajamos</option>
                                <option value="expense">Išlaidos</option>
                            </select>
                            @error('type')
                                <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Išsaugoti
                        </button>

                        <a href="{{ route('categories.index') }}" class="ml-3 text-gray-600">
                            Atgal
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>