@extends('layouts.admin')

@section('title', 'Finansų suvestinė')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Finansų suvestinė</h3>
</div>

<div class="card mb-4">
    <div class="card-header">
        <strong>Laikotarpio pasirinkimas</strong>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('dashboard') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Laikotarpis</label>

                <select name="month" class="form-control">
                    <option value="all" @selected($month === 'all')>
                        Visi mėnesiai
                    </option>

                    <option value="{{ now()->format('Y-m') }}" @selected($month === now()->format('Y-m'))>
                        Šis mėnuo
                    </option>

                    @for ($i = 1; $i <= 12; $i++)
                        @php
                            $monthValue = now()->subMonths($i)->format('Y-m');
                        @endphp

                        <option value="{{ $monthValue }}" @selected($month === $monthValue)>
                            {{ $monthValue }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    Filtruoti
                </button>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    Šis mėnuo
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-4 col-12">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ number_format($totalIncome, 2) }} €</h3>
                <p>{{ $month === 'all' ? 'Bendros pajamos' : 'Pajamos per pasirinktą mėnesį' }}</p>
            </div>
            <div class="icon">
                <i class="bi bi-arrow-down-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ number_format($totalExpense, 2) }} €</h3>
                <p>{{ $month === 'all' ? 'Bendros išlaidos' : 'Išlaidos per pasirinktą mėnesį' }}</p>
            </div>
            <div class="icon">
                <i class="bi bi-arrow-up-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>{{ number_format($balance, 2) }} €</h3>
                <p>{{ $month === 'all' ? 'Bendras likutis' : 'Likutis per pasirinktą mėnesį' }}</p>
            </div>
            <div class="icon">
                <i class="bi bi-wallet2"></i>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
        Pridėti įrašą
    </a>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        Kategorijos
    </a>

    <a href="{{ route('transactions.index') }}" class="btn btn-success">
        Visi įrašai
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            {{ $month === 'all' ? 'Paskutiniai įrašai' : 'Paskutiniai pasirinkto mėnesio įrašai' }}
        </h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipas</th>
                    <th>Kategorija</th>
                    <th>Suma</th>
                    <th>Aprašymas</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($latestTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->date }}</td>

                        <td>
                            @if ($transaction->type == 'income')
                                Pajamos
                            @else
                                Išlaidos
                            @endif
                        </td>

                        <td>{{ $transaction->category->name ?? '-' }}</td>
                        <td>{{ number_format($transaction->amount, 2) }} €</td>
                        <td>{{ $transaction->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            {{ $month === 'all' ? 'Įrašų nėra.' : 'Pasirinktą mėnesį įrašų nėra.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection