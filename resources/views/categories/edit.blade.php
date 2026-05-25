@extends('layouts.admin')

@section('title', 'Redaguoti kategoriją')

@section('content')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Pavadinimas</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control">

                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tipas</label>
                <select name="type" class="form-control">
                    <option value="income" @selected($category->type == 'income')>Pajamos</option>
                    <option value="expense" @selected($category->type == 'expense')>Išlaidos</option>
                </select>

                @error('type')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Atnaujinti</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Atgal</a>
        </form>
    </div>
</div>

@endsection