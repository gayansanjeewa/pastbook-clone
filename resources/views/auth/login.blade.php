@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <?php
                                $url = sprintf('/auth/redirect/%s', App\Foundation\OAuth\ProviderType::FACEBOOK);
                                ?>
                                <a href="{{ url($url) }}" class="btn btn-primary">
                                    <i class="fa fa-facebook"></i>
                                    Log in with Facebook
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
