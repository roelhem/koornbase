@extends('layouts.app')

@section('title', 'Ontwikkelaars Assistent - Navigatie')

@section('content')

    @inject('items', 'App\Services\Navigation\NavigationItemRepository')
    @inject('breadcrumb', 'App\Services\Navigation\BreadcrumbService')

    <div class="container">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Navigation Items</h3>
            </div>

            <div class="table-responsive">
                <table class="table card-table table-sm">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Route/Link</th>
                        <th>Path</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items->list() as $id => $item)
                        <tr>
                            <td><strong>{{ $id }}</strong></td>
                            <td>{!! $item->icon('fe','fa') !!} {{ $item->name }}</td>
                            <td>
                                @if($item->link)
                                <a href="{{ $item->href }}">{{ $item->route ?? $item->link }}</a>
                                @else
                                {{ $item->route }}
                                @endif
                            </td>
                            <td>
                                <ol class="breadcrumb my-0 mx-0 small">
                                    @each('shared.nav.breadcrumb-item', $breadcrumb->pathTo($id), 'item')
                                </ol>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection()