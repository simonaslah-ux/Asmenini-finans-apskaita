@extends('layouts.admin')

@section('title', 'Kategorijos')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            Pridėti kategoriją
        </a>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Pavadinimas</th>
                    <th>Tipas</th>
                    <th>Veiksmai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>
                            @if ($category->type == 'income')
                                Pajamos
                            @else
                                Išlaidos
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                Redaguoti
                            </a>

                            <form action="{{ route('categories.destroy', $category) }}"
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
                        <td colspan="3" class="text-center">Kategorijų dar nėra.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection