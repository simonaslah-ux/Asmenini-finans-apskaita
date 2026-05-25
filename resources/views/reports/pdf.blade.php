<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Finansų ataskaita</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1, h2 {
            margin-bottom: 10px;
        }

        .summary {
            margin-bottom: 20px;
        }

        .summary p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eee;
        }
    </style>
</head>
<body>

<h1>Asmeninių finansų ataskaita</h1>

<div class="summary">
    <p><strong>Laikotarpis:</strong>
        {{ $startDate ?: 'Nenurodyta' }} – {{ $endDate ?: 'Nenurodyta' }}
    </p>
    <p><strong>Bendros pajamos:</strong> {{ number_format($totalIncome, 2) }} EUR</p>
    <p><strong>Bendros išlaidos:</strong> {{ number_format($totalExpense, 2) }} EUR</p>
    <p><strong>Likutis:</strong> {{ number_format($balance, 2) }} EUR</p>
    <p><strong>Įrašų skaičius:</strong> {{ $transactions->count() }}</p>
    <p><strong>Mažiausia suma:</strong> {{ number_format($minAmount, 2) }} EUR</p>
    <p><strong>Didžiausia suma:</strong> {{ number_format($maxAmount, 2) }} EUR</p>
    <p><strong>Vidutinė suma:</strong> {{ number_format($avgAmount, 2) }} EUR</p>
</div>

<h2>Suvestinė pagal kategorijas</h2>

<table>
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
                <td>{{ number_format($summary['total'], 2) }} EUR</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">Duomenų nėra.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<h2>Ataskaitos įrašai</h2>

<table>
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
                <td>{{ $transaction->type == 'income' ? 'Pajamos' : 'Išlaidos' }}</td>
                <td>{{ $transaction->category->name ?? '-' }}</td>
                <td>{{ number_format($transaction->amount, 2) }} EUR</td>
                <td>{{ $transaction->description }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Įrašų nėra.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>