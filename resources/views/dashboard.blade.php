@extends('layouts.admin')

@section('title', 'Finansų suvestinė')

@section('content')

<div class="row mb-4">
    <div class="col-lg-4 col-12">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ number_format($totalIncome, 2) }} €</h3>
                <p>Bendros pajamos</p>
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
                <p>Bendros išlaidos</p>
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
                <p>Likutis</p>
            </div>
            <div class="icon">
                <i class="bi bi-wallet2"></i>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">Pridėti įrašą</a>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kategorijos</a>
    <a href="{{ route('transactions.index') }}" class="btn btn-success">Visi įrašai</a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Paskutiniai įrašai</h3>
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
                        <td colspan="5" class="text-center">Įrašų dar nėra.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
