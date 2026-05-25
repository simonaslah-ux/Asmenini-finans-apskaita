@extends('layouts.admin')

@section('title', 'Redaguoti įrašą')

@section('content')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.update', $transaction) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Kategorija</label>
                <select name="category_id" class="form-control">
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
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Suma</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}" class="form-control">

                @error('amount')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Data</label>
                <input type="date" name="date" value="{{ old('date', $transaction->date) }}" class="form-control">

                @error('date')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Aprašymas</label>
                <input type="text" name="description" value="{{ old('description', $transaction->description) }}" class="form-control">

                @error('description')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Atnaujinti</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Atgal</a>
        </form>
    </div>
</div>

@endsection