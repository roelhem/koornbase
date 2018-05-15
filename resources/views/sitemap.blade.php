@extends('layouts.app')

@section('title', 'Sitemap')

@section('content')

    @inject('sitemap', 'App\Services\Navigation\SitemapService')

    <div class="container">
        @foreach($sitemap as $rootNode)
        <div class="row">

            @foreach($rootNode as $node)
            <div class="col-md-4">
                <h3><a href="{{ $node->href }}">{!! $node->icon('fe','fa') !!} {{ $node->name }}</a></h3>

                <ul class="list-unstyled">
                    @foreach($node as $subNode)
                        <li>
                            <a href="{{ $subNode->href }}">{!! $subNode->icon('fa','fe') !!} {{ $subNode->name }}</a>
                            <ul>
                                @foreach($subNode as $subSubNode)
                                    <li><a href="{{ $subSubNode->href }}">{{ $subSubNode->label }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endforeach

        </div>
        @endforeach
    </div>

@endsection()