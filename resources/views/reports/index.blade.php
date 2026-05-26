@extends('layouts.admin')

@section('title', 'Ataskaitos')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <strong>Ataskaitos filtrai</strong>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Data nuo</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Data iki</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Kategorija</label>
                <select name="category_id" class="form-control">
                    <option value="">Visos kategorijos</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
                            {{ $category->name }}
                            @if ($category->type == 'income')
                                - Pajamos
                            @else
                                - Išlaidos
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filtruoti</button>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">Valyti</a>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <strong>PDF ataskaita</strong>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <a href="{{ route('reports.pdf', request()->query()) }}" class="btn btn-danger">
                Atsisiųsti PDF
            </a>
        </div>

        <form method="POST" action="{{ route('reports.sendEmail') }}" class="row g-3">
            @csrf

            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <input type="hidden" name="category_id" value="{{ $categoryId }}">

            <div class="col-md-6">
                <label class="form-label">El. paštas PDF siuntimui</label>
                <input type="email" name="email" class="form-control" placeholder="pvz. test@test.lt">

                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    Siųsti PDF el. paštu
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ number_format($totalIncome, 2) }} €</h3>
                <p>Pajamos pagal filtrą</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ number_format($totalExpense, 2) }} €</h3>
                <p>Išlaidos pagal filtrą</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>{{ number_format($balance, 2) }} €</h3>
                <p>Likutis pagal filtrą</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <strong>Statistinė analizė</strong>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Įrašų skaičius:</strong><br>
                {{ $transactions->count() }}
            </div>

            <div class="col-md-3">
                <strong>Mažiausia suma:</strong><br>
                {{ number_format($minAmount, 2) }} €
            </div>

            <div class="col-md-3">
                <strong>Didžiausia suma:</strong><br>
                {{ number_format($maxAmount, 2) }} €
            </div>

            <div class="col-md-3">
                <strong>Vidutinė suma:</strong><br>
                {{ number_format($avgAmount, 2) }} €
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <strong>Pajamos ir išlaidos</strong>
            </div>

            <div class="card-body">
                <canvas id="incomeExpenseChart" height="160"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <strong>Išlaidų pasiskirstymas pagal kategorijas</strong>
            </div>

            <div class="card-body">
                <canvas id="expenseCategoryChart" height="160"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <strong>Išlaidų suvestinė pagal kategorijas</strong>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Kategorija</th>
                    <th>Suma</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorySummary as $summary)
                    <tr>
                        <td>{{ $summary['category'] }}</td>
                        <td>{{ number_format($summary['total'], 2) }} €</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Duomenų nėra.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <strong>Ataskaitos įrašai</strong>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Įrašų pagal pasirinktus filtrus nėra.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const incomeExpenseLabels = @json($incomeExpenseLabels);
    const incomeExpenseData = @json($incomeExpenseData);

    const expenseCategoryLabels = @json($expenseCategoryLabels);
    const expenseCategoryData = @json($expenseCategoryData);

    const incomeExpenseChart = document.getElementById('incomeExpenseChart');

    if (incomeExpenseChart) {
        new Chart(incomeExpenseChart, {
            type: 'bar',
            data: {
                labels: incomeExpenseLabels,
                datasets: [{
                    label: 'Suma (€)',
                    data: incomeExpenseData,
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.6)',
                        'rgba(220, 53, 69, 0.6)'
                    ],
                    borderColor: [
                        'rgba(25, 135, 84, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    const expenseCategoryChart = document.getElementById('expenseCategoryChart');

    if (expenseCategoryChart) {
        new Chart(expenseCategoryChart, {
            type: 'doughnut',
            data: {
                labels: expenseCategoryLabels,
                datasets: [{
                    label: 'Išlaidos pagal kategorijas',
                    data: expenseCategoryData,
                    backgroundColor: [
                        '#36A2EB',
                        '#FF6384',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#8BC34A',
                        '#795548'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
</script>
@endsection