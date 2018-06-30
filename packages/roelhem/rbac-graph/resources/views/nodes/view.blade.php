@extends('rbac-graph::layouts.main')

@section('content')

    <div class="container">

        <h1 class="my-4">Node Information</h1>

        <div class="card border-{{ $node->getType()->conf('style.bootstrap.bg-color') }}">

            <div class="card-header bg-{{ $node->getType()->conf('style.bootstrap.bg-color') }} text-{{ $node->getType()->conf('style.bootstrap.fg-color') }}">
                {{ $node->getType()->getLabel() }}
            </div>


            <div class="card-body">


                <h3 class="card-title">{{ $node->getTitle() }}</h3>

                <p>
                    <span class="mx-3">{{ $node->getId() }}</span>
                    <code>{{ $node->getName() }}</code>
                </p>

                <p>{{ $node->getDescription() }}</p>

                @if(count($node->getOptions()) > 0)
                <h4>Options</h4>

                <table class="table table-sm">
                    <tbody>
                        @foreach($node->getOptions() as $key => $option)
                        <tr>
                            <th>{{ $key }}</th>
                            <td>{{ var_dump($option) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>

        <h1 class="my-4">Incoming Edges <small class="text-muted">({{ count($incomingEdges) }})</small></h1>

        @foreach($incomingEdges as $incomingEdge)
            <div class="row my-2">

                <div class="col">

                    <div class="card border-{{ $incomingEdge->getParent()->getType()->conf('style.bootstrap.bg-color') }}">

                        <div class="card-header px-2 py-1 bg-{{ $incomingEdge->getParent()->getType()->conf('style.bootstrap.bg-color') }} text-{{ $incomingEdge->getParent()->getType()->conf('style.bootstrap.fg-color') }}">
                            {{ $incomingEdge->getParent()->getType()->getLabel() }}
                        </div>

                        <div class="card-body p-1">
                            <span class="mx-1">{{ $incomingEdge->getParent()->getId() }}</span>
                            <a href="{{route('rbac-graph.nodes.view', ['node' => $incomingEdge->getParent()->getId()])}}"><code>{{ $incomingEdge->getParent()->getName() }}</code></a>
                        </div>
                    </div>

                </div>

                <div >----&gt;</div>

                <div class="col">

                    <div class="card border-{{ $incomingEdge->getChild()->getType()->conf('style.bootstrap.bg-color') }}">

                        <div class="card-header px-2 py-1 bg-{{ $incomingEdge->getChild()->getType()->conf('style.bootstrap.bg-color') }} text-{{ $incomingEdge->getChild()->getType()->conf('style.bootstrap.fg-color') }}">
                            {{ $incomingEdge->getChild()->getType()->getLabel() }}
                        </div>

                        <div class="card-body p-1">
                            <span class="mx-1">{{ $incomingEdge->getChild()->getId() }}</span>
                            <a href="{{route('rbac-graph.nodes.view', ['node' => $incomingEdge->getChild()->getId()])}}"><code>{{ $incomingEdge->getChild()->getName() }}</code></a>
                        </div>
                    </div>

                </div>

            </div>

        @endforeach

        <h1 class="my-4">Outgoing Edges <small class="text-muted">({{ count($outgoingEdges) }})</small></h1>

        @foreach($outgoingEdges as $outgoingEdge)
            <div class="row my-2">

                <div class="col">

                    <div class="card border-{{ $outgoingEdge->getParent()->getType()->conf('style.bootstrap.bg-color') }}">

                        <div class="card-header px-2 py-1 bg-{{ $outgoingEdge->getParent()->getType()->conf('style.bootstrap.bg-color') }} text-{{ $outgoingEdge->getParent()->getType()->conf('style.bootstrap.fg-color') }}">
                            {{ $outgoingEdge->getParent()->getType()->getLabel() }}
                        </div>

                        <div class="card-body p-1">
                            <span class="mx-1">{{ $outgoingEdge->getParent()->getId() }}</span>
                            <a href="{{route('rbac-graph.nodes.view', ['node' => $outgoingEdge->getParent()->getId()])}}"><code>{{ $outgoingEdge->getParent()->getName() }}</code></a>
                        </div>
                    </div>

                </div>

                <div >----&gt;</div>

                <div class="col">

                    <div class="card border-{{ $outgoingEdge->getChild()->getType()->conf('style.bootstrap.bg-color') }}">

                        <div class="card-header px-2 py-1 bg-{{ $outgoingEdge->getChild()->getType()->conf('style.bootstrap.bg-color') }} text-{{ $outgoingEdge->getChild()->getType()->conf('style.bootstrap.fg-color') }}">
                            {{ $outgoingEdge->getChild()->getType()->getLabel() }}
                        </div>

                        <div class="card-body p-1">
                            <span class="mx-1">{{ $outgoingEdge->getChild()->getId() }}</span>
                            <a href="{{route('rbac-graph.nodes.view', ['node' => $outgoingEdge->getChild()->getId()])}}"><code>{{ $outgoingEdge->getChild()->getName() }}</code></a>
                        </div>
                    </div>

                </div>

            </div>
        @endforeach

    </div>

@endsection