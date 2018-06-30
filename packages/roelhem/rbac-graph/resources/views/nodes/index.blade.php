@extends('rbac-graph::layouts.main')

@section('content')

    <div class="container-fluid my-2">
        <h1>Nodes</h1>


        <table class="table">
            <tbody>
                @foreach($nodes as $node)
                <tr>
                    <td>{{ $node->getId() }}</td>
                    <td>
                        <a href="{{ route('rbac-graph.nodes.view', ['node' => $node]) }}">
                            <code>{{ $node->getName() }}</code>
                        </a>
                    </td>
                    <td>
                        <span class="badge bg-{{ $node->getType()->conf('style.bootstrap.bg-color','gray') }} text-{{ $node->getType()->conf('style.bootstrap.fg-color','white') }}">
                        {{ $node->getType()->getLabel() }}
                        </span>
                    </td>
                    <td width="50%">
                        <div>{{ $node->getTitle() }}</div>
                        <div class="text-muted">{{ $node->getDescription() }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection