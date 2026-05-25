@extends('layouts.admin')

@section('title', 'Pajamų ir išlaidų įrašai')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            Pridėti įrašą
        </a>
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
                    <th>Veiksmai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
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
                        <td>
                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-warning">
                                Redaguoti
                            </a>

                            <form action="{{ route('transactions.destroy', $transaction) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Ar tikrai norite ištrinti?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Trinti
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Įrašų dar nėra.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection