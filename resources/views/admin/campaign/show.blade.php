@extends('admin.layouts.admin')
@section('content')
<div class="p-4">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between  align-items-center">
            <h2 class="theme_primary_text d-inline-block">{{$title}}</h2>
            <div class="text-right d-inline-block">
                <a class="theme_primary_btn btn btn-sm float-right  ml-2" href="{{route('admin.campaigns.index')}}"><i class="fas fa-arrow-left"></i> Back</a>
                <a class="theme_primary_btn btn btn-sm float-right  ml-2" href="{{route('admin.campaigns.edit',$campaign->id)}}"><i class="fas fa-pen"></i> Edit</a>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body py-4 px-2">
                            <div class="card-header px-4 py-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#campaigns_detail" data-toggle="tab">Campaigns Detail</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#campaigns_transaction" data-toggle="tab">Campaigns Transaction</a></li>
                                </ul>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="campaigns_detail">
                                            <div class="card">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h3 class="theme_primary_text ">Campaign Detail</h3>
                                                    </div>
                                                    <div class="col-md-5 col-12">
                                                        <img width="100%" src="{{$campaign->image}}" alt="{{$campaign->name}}">
                                                    </div>
                                                    <div class="col-md-5 col-12">
                                                        <div class="text-muted">
                                                            <p class="text-sm">Campaign Name
                                                                <b class="d-block">{{$campaign->name}}</b>
                                                            </p>
                                                            <p class="text-sm">Campaign Category
                                                                <b class="d-block">{{$campaign->category->name}}</b>
                                                            </p>
                                                            <p class="text-sm">Campaign Date
                                                                <b class="d-block">{{$campaign->start_datetime .' - '.$campaign->end_datetime}}</b>
                                                            </p>
                                                            <p class="text-sm">Donation Target
                                                                <b class="d-block">{{$campaign->donation_target}}</b>
                                                            </p>
                                                            <p class="text-sm">Unique Code
                                                                <b class="d-block">{{$campaign->unique_code}}</b>
                                                            </p>
                                                            <p class="text-sm">Status
                                                                <b class="d-block">{{$campaign->status_text??'-'}}</b>
                                                            </p>
                                                            <p class="text-sm">Campaign Status
                                                                <b class="d-block">{{$campaign->campaign_status_text??'-'}}</b>
                                                            </p>
                                                            <p class="text-sm">Created At
                                                                <b class="d-block">{{$campaign->created_at}}</b>
                                                            </p>
                                                            <p class="text-sm">Description
                                                                <b class="d-block">{{$campaign->description}}</b>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <img width="100%" src="{{$campaign->qr_image}}" alt="">
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="px-2">
                                                                    {{$campaign->unique_code}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h3 class="theme_primary_text ">Uploaded Media</h3>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="row">
                                                                @foreach ($campaign->uploads as $upload)
                                                                @if($upload->upload_type != 'Links')
                                                                <div class="col-md-4 col-12 col-sm-2">
                                                                    <div class="card" style="width:100%">
                                                                        <img class="card-img-top" src="{{$upload->image}}" alt="Card image cap">
                                                                        <div class="card-body">
                                                                            <h5 class="card-title">{{$upload->title}}</h5>
                                                                            <p class="card-text">{{$upload->description}}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                @endforeach

                                                            </div>
                                                            <div class="row">
                                                                @foreach ($campaign->uploads as $upload)
                                                                @if($upload->upload_type == 'Links')
                                                                <div class="col-md-4 col-12 col-sm-2">
                                                                    <div class="card" style="width:100%">
                                                                        <video poster="placeholder.png" width="400" height="200" controls src="{{$upload->link}}">
                                                                            Browser not supported
                                                                        </video>
                                                                        <div class="card-body">
                                                                            <h5 class="card-title">{{$upload->title}}</h5>
                                                                            <p class="card-text">{{$upload->description}}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="campaigns_transaction">
                                            <div class="card" style="height: 400px;">
                                                <div class="row h-100 w-100">

                                                    <div class="h-100 w-100 d-flex justify-content-center align-items-center">
                                                        <h1 class="text-secondary">Coming Soon</h1>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content -->

@endsection
@push('script')

@endpush