<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Redaguoti įrašą
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('transactions.update', $transaction) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block mb-1">Tipas</label>
                            <select name="type" class="w-full border-gray-300 rounded">
                                <option value="income" @selected($transaction->type == 'income')>Pajamos</option>
                                <option value="expense" @selected($transaction->type == 'expense')>Išlaidos</option>
                            </select>
                            @error('type')
                                <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Kategorija</label>
                            <select name="category_id" class="w-full border-gray-300 rounded">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($transaction->category_id == $category->id)>
                                        {{ $category->name }}
                                        @if ($category->type == 'income')
                                            - Pajamos
                                        @else
                                            - Išlaidos
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Suma</label>
                            <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}"
                                   class="w-full border-gray-300 rounded">
                            @error('amount')
                                <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Data</label>
                            <input type="date" name="date" value="{{ old('date', $transaction->date) }}"
                                   class="w-full border-gray-300 rounded">
                            @error('date')
                                <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Aprašymas</label>
                            <input type="text" name="description" value="{{ old('description', $transaction->description) }}"
                                   class="w-full border-gray-300 rounded">
                            @error('description')
                                <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Atnaujinti
                        </button>

                        <a href="{{ route('transactions.index') }}" class="ml-3 text-gray-600">
                            Atgal
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>