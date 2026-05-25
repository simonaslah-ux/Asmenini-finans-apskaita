@extends('layouts.admin')

@section('title', 'Nauja kategorija')

@section('content')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Pavadinimas</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control">

                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tipas</label>
                <select name="type" class="form-control">
                    <option value="income">Pajamos</option>
                    <option value="expense">Išlaidos</option>
                </select>

                @error('type')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Išsaugoti</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Atgal</a>
        </form>
    </div>
</div>

@endsection