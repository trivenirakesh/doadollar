@extends('admin.layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">

    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- Main Content will goes here -->

        <div class="row">
            <div class="col-md-7">
                <div class="row h-100 bg-white rounded-16 mx-0 pt-4 px-3 shadow-sm">
                    <div class="col-12 mb-2">
                        <h3 class="text-bold color-primary">This Month's Statistics</h2>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="bg-titan-white mb-4 p-3 rounded-16">
                            <div class="d-flex justify-content-center align-items-center bg-windsor mb-3 p-2 rounded-circle text-center wrapper-56">
                                <i class="fas fa-calendar-alt fa-2x text-white"></i>
                            </div>
                            <h4 class="text-bold">0</h4>
                            <div class="font-weight-bold mb-2">Total Calls</div>
                            <div class="font-weight-bold text-success">+18% from last month</div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <div class="bg-corn-silk mb-4 p-3 rounded-16">
                            <div class="d-flex justify-content-center align-items-center bg-yellow-orange mb-3 p-2 rounded-circle text-center wrapper-56">
                                <i class="fa fa-suitcase fa-2x text-white"></i>
                            </div>
                            <h4 class="text-bold">@if(isset($dashboardDetails) && !empty($dashboardDetails['getTotalEngineer'])) {{$dashboardDetails['getTotalEngineer']}} @else 0 @endif</h4>
                            <div class="font-weight-bold mb-2">Total Engineers</div>
                            <div class="font-weight-bold text-success">-10% from last month</div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <div class="bg-blue-chalk mb-4 p-3 rounded-16">
                            <div class="d-flex justify-content-center align-items-center bg-heliotrope mb-3 p-2 rounded-circle text-center wrapper-56">
                                <i class="fa fa-users fa-2x text-white"></i>
                            </div>
                            <h4 class="text-bold">@if(isset($dashboardDetails) && !empty($dashboardDetails['getTotalClient'])) {{$dashboardDetails['getTotalClient']}} @else 0 @endif</h4>
                            <div class="font-weight-bold mb-2">Total Clients</div>
                            <div class="font-weight-bold text-success">+20% from last month</div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <div class="bg-misty-rose mb-4 p-3 rounded-16">
                            <div class="d-flex justify-content-center align-items-center bg-brink-pink mb-3 p-2 rounded-circle text-center wrapper-56">
                                <i class="fa fa-paint-brush fa-2x text-white"></i>
                            </div>
                            <h4 class="text-bold">@if(isset($dashboardDetails) && !empty($dashboardDetails['getTotalItems'])) {{$dashboardDetails['getTotalItems']}} @else 0 @endif</h4>
                            <div class="font-weight-bold mb-2">Total Products</div>
                            <div class="font-weight-bold text-success">25% from last month</div>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
            </div>
            <div class="col-md-5">
                <div class="row h-100 bg-white rounded-16 mx-0 pt-4 px-3 shadow-sm">
                    <div class="col-12 mb-2">
                        <h3 class="text-bold color-primary">Visitors Insights</h2>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection