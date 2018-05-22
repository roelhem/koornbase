@extends('layouts.app')

@section('content')

    <b-container>

        <b-row>
            <b-col>
                <b-card>
                    <b-form-group>
                        <b-form-input type="text" placeholder="Test placeholder"></b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <form-model-select-multi model="group-category"></form-model-select-multi>
                    </b-form-group>
                    <b-form-group>
                        <form-model-select-multi model="group"></form-model-select-multi>
                    </b-form-group>
                    <h3>Multiple selects</h3>
                    <b-form-group>
                        <form-model-select-multi multiple model="group-category"></form-model-select-multi>
                    </b-form-group>
                    <b-form-group>
                        <form-model-select-multi multiple model="group"></form-model-select-multi>
                    </b-form-group>
                </b-card>
            </b-col>
        </b-row>

    </b-container>

@endsection