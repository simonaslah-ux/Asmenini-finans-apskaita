@extends('layouts.admin')

@section('title', 'Ataskaitos')

@section('content')

<style>
    .card {
        margin-bottom: 12px;
    }

    .card-header {
        padding: 7px 16px;
    }

    .card-body {
        padding: 10px 16px;
    }

    .form-label {
        margin-bottom: 4px;
        font-size: 14px;
    }

    .btn {
        padding: 6px 12px;
    }

    .small-box {
        min-height: 85px;
        margin-bottom: 0;
    }

    .small-box .inner {
        padding: 12px 14px;
    }

    .small-box h3 {
        font-size: 28px;
        margin-bottom: 6px;
    }

    .small-box p {
        font-size: 14px;
        margin-bottom: 0;
    }

    .chart-box {
        height: 200px;
        position: relative;
        padding: 8px;
    }

    .chart-box canvas {
        max-height: 200px;
    }

    .summary-box {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .summary-box p {
        margin-bottom: 8px;
        font-size: 13px;
    }

    .table th,
    .table td {
        padding: 7px 9px;
        font-size: 14px;
    }

    .pdf-download-btn {
        background-color: #abbf24 !important;
        border-color: #abbf24 !important;
        color: #fff !important;
    }

    .pdf-download-btn:hover {
        background-color: #8a9b20 !important;
        border-color: #8a9b20 !important;
        color: #fff !important;
    }
</style>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row mb-3">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <strong>Ataskaitos filtrai</strong>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('reports.index') }}" class="row g-2 align-items-end">
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

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            Filtruoti
                        </button>

                        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                            Valyti
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <strong>PDF ataskaita</strong>
            </div>

            <div class="card-body">
                <div class="mb-2">
                    <a href="{{ route('reports.pdf', request()->query()) }}" class="btn btn-danger pdf-download-btn">
                        Atsisiųsti PDF
                    </a>
                </div>

                <form method="POST" action="{{ route('reports.sendEmail') }}" class="row g-2">
                    @csrf

                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="category_id" value="{{ $categoryId }}">

                    <div class="col-md-8">
                        <input type="email" name="email" class="form-control" placeholder="El. paštas">
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            Siųsti
                        </button>
                    </div>

                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
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

<div class="row mb-3">
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header">
                <strong>Pajamos ir išlaidos</strong>
            </div>

            <div class="card-body chart-box">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <strong>Išlaidos pagal kategorijas</strong>
            </div>

            <div class="card-body chart-box">
                <canvas id="expenseCategoryChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">
                <strong>Statistinė analizė</strong>
            </div>

            <div class="card-body summary-box">
                <p>
                    <strong>Įrašų skaičius:</strong><br>
                    {{ $transactions->count() }}
                </p>

                <p>
                    <strong>Mažiausia suma:</strong><br>
                    {{ number_format($minAmount, 2) }} €
                </p>

                <p>
                    <strong>Didžiausia suma:</strong><br>
                    {{ number_format($maxAmount, 2) }} €
                </p>

                <p>
                    <strong>Vidutinė suma:</strong><br>
                    {{ number_format($avgAmount, 2) }} €
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <strong>Išlaidų suvestinė pagal kategorijas</strong>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped mb-0">
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
    </div>

    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <strong>Ataskaitos įrašai</strong>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped mb-0">
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
                                <td colspan="5" class="text-center">
                                    Įrašų pagal pasirinktus filtrus nėra.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endsection