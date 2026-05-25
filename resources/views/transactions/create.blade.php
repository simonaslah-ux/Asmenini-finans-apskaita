@extends('layouts.admin')

@section('title', 'Naujas įrašas')

@section('content')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Kategorija</label>
                <select name="category_id" class="form-control">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
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
                <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="form-control">

                @error('amount')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Data</label>
                <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control">

                @error('date')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Aprašymas</label>
                <input type="text" name="description" value="{{ old('description') }}" class="form-control">

                @error('description')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Išsaugoti</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Atgal</a>
        </form>
    </div>
</div>

@endsection