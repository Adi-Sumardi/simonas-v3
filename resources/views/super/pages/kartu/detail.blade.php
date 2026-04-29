@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Raport Warga Asrama</h3>
    </div>

    <div class="card">
        <div class="card-body pt-1">
            <form action="{{$user->id}}" method="post">
                @csrf
                @method('PATCH')
                <div class="card-body pt-1">
                    <div class="">
                        <h6 class="">Filter Data Warga</h6>
                        <div class="row input-daterange">
                            <div class="col-md-4">
                                <input type="date" name="from_date" id="from_date" class="form-control" placeholder="From Date"/>
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="to_date" id="to_date" class="form-control" placeholder="To Date" />
                            </div>
                            <div class="col-md-4">
                                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-secondary">Refresh</button>
                            </div>
                        </div>  
                    </div>
                    <div><p>
                    </p></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p><img src="{{ url ('/uploads/avatars/'.$user->avatar)}}" class="img-responsive" alt="avatar"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <ul class="list-group">
                                    <li class="list-group-item active" aria-current="true">Aktifitas <i class="fa fa-dashboard fa-1x"></i></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Akademik</strong><span class="badge badge-primary badge-pill">{{$akademiks}}</span> </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Leadership</strong><span class="badge badge-primary badge-pill">{{$leaderships}}</span> </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Karakter Islami</strong><span class="badge badge-primary badge-pill">{{$karakters}}</span> </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Kreatifitas</strong><span class="badge badge-primary badge-pill">{{$kreatifs}}</span> </li>
                                  </ul> 
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="page-content container">
                <div class="page-header text-blue-d2">
                    <h1 class="page-title text-secondary-d1">
                        Raport
                        <small class="page-info">
                            <i class="fa fa-angle-double-right text-80"></i>
                            ID: #{{$user->name}}
                        </small>
                    </h1>
            
                    <div class="page-tools">
                        <div class="action-buttons">
                            <a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="Print">
                                <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                                Print
                            </a>
                            <a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="PDF">
                                <i class="mr-1 fa fa-file-pdf-o text-danger-m1 text-120 w-2"></i>
                                Export
                            </a>
                        </div>
                    </div>
                </div>
            
                <div class="container px-0">
                    <div class="row mt-4">
                        <div class="col-12 col-lg-10 offset-lg-1">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-center text-150">
                                        <i class="fa-2x text-success-m2 mr-1"></i>
                                        <span class="text-default-d3 font-weight-bold">KARTU HASIL PENILAIAN WARGA</span><br>
                                        <span class="text-default-d3 font-weight-bold">YAYASAN ASRAMA PELAJAR ISLAM</span>
                                    </div>
                                </div>
                            </div>
                            <!-- .row -->
            
                            <hr class="row brc-default-l1 mx-n1 mb-4" />
            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        <span class="text-sm text-grey-m2 align-middle">Nama:</span>
                                        <span class="text-600 text-110 text-blue align-middle">Alex Doe</span>
                                    </div>
                                    <div class="text-grey-m2">
                                        <div class="my-1">
                                            <span class="text-sm text-grey-m2 align-middle">Asrama:</span>
                                            <span class="text-600 text-110 text-blue align-middle">Asrama Sunan Giri</span>    
                                        </div>
                                        <div class="my-1">
                                            <span class="text-sm text-grey-m2 align-middle">Universitas:</span>
                                            <span class="text-600 text-110 text-blue align-middle">Universitas YAPI Center</span>    
                                        </div>
                                        {{-- <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b class="text-600">111-111-111</b></div> --}}
                                    </div>
                                </div>
                                <!-- /.col -->
            
                                {{-- <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                                    <hr class="d-sm-none" />
                                    <div class="text-grey-m2">
                                        <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                            Invoice
                                        </div>
            
                                        <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">ID:</span> #111-222</div>
            
                                        <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Issue Date:</span> Oct 12, 2019</div>
            
                                        <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Status:</span> <span class="badge badge-warning badge-pill px-25">Unpaid</span></div>
                                    </div>
                                </div> --}}
                                <!-- /.col -->
                            </div>
            
                            <div class="mt-4">
                                <div class="row text-600 text-white bg-primary py-25">
                                    <div class="d-none d-sm-block col-1">#</div>
                                    <div class="col-9 col-sm-5">Aspek Asrama</div>
                                    <div class="d-none d-sm-block col-4 col-sm-2">Jumlah Upload</div>
                                    <div class="d-none d-sm-block col-sm-2"></div>
                                    <div class="col-2">Jumlah Nilai</div>
                                </div>
            
                                <div class="text-95 text-secondary-d3">
                                    <div class="row mb-2 mb-sm-0 py-25">
                                        <div class="d-none d-sm-block col-1">1</div>
                                        <div class="col-9 col-sm-5">Akademik</div>
                                        <div class="d-none d-sm-block col-2">25</div>
                                        <div class="d-none d-sm-block col-2 text-95"></div>
                                        <div class="col-2 text-secondary-d2">20</div>
                                    </div>
            
                                    <div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">
                                        <div class="d-none d-sm-block col-1">2</div>
                                        <div class="col-9 col-sm-5">Leadership</div>
                                        <div class="d-none d-sm-block col-2">15</div>
                                        <div class="d-none d-sm-block col-2 text-95"></div>
                                        <div class="col-2 text-secondary-d2">15</div>
                                    </div>
            
                                    <div class="row mb-2 mb-sm-0 py-25">
                                        <div class="d-none d-sm-block col-1">3</div>
                                        <div class="col-9 col-sm-5">Karakter Islami</div>
                                        <div class="d-none d-sm-block col-2">35</div>
                                        <div class="d-none d-sm-block col-2 text-95"></div>
                                        <div class="col-2 text-secondary-d2">10</div>
                                    </div>
            
                                    <div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">
                                        <div class="d-none d-sm-block col-1">4</div>
                                        <div class="col-9 col-sm-5">Kreativitas dan Kewirausahaan</div>
                                        <div class="d-none d-sm-block col-2">11</div>
                                        <div class="d-none d-sm-block col-2 text-95"></div>
                                        <div class="col-2 text-secondary-d2">50</div>
                                    </div>
                                </div>
            
                                <div class="row border-b-2 brc-default-l2"></div>
            
                                <!-- or use a table instead -->
                                <!--
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                                <thead class="bg-none bgc-default-tp1">
                                    <tr class="text-white">
                                        <th class="opacity-2">#</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th width="140">Amount</th>
                                    </tr>
                                </thead>
            
                                <tbody class="text-95 text-secondary-d3">
                                    <tr></tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Domain registration</td>
                                        <td>2</td>
                                        <td class="text-95">$10</td>
                                        <td class="text-secondary-d2">$20</td>
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        -->
            
                                <div class="row mt-3">
                                    <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                        Keterangan:
                                    </div>
            
                                    <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                        <div class="row my-2">
                                            <div class="col-7 text-right">
                                                Nilai Akhir
                                            </div>
                                            <div class="col-5">
                                                <span class="text-120 text-secondary-d1">3,4</span>
                                            </div>
                                        </div>
            
                                        <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                            <div class="col-7 text-right">
                                                
                                            </div>
                                            <div class="col-5">
                                                <span class="text-150 text-success-d3 opacity-2">Ttd Direktur Asrama</span>
                                            </div>
                                        </div>
                                        <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                            <div class="col-7 text-right">
                                                
                                            </div>
                                            <div class="col-5">
                                                <span class="text-150 text-success-d3 opacity-2"></span>
                                            </div>
                                        </div>
                                        <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                            <div class="col-7 text-right">
                                                
                                            </div>
                                            <div class="col-5">
                                                <span class="text-150 text-success-d3 opacity-2">Babang Cristiano</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                                <hr />
            
                                <div>
                                    <span class="text-secondary-d1 text-105">Direktorat Yayasan Asrama Pelajar Islam</span>
                                    {{-- <a href="#" class="btn btn-info btn-bold px-4 float-right mt-3 mt-lg-0">Pay Now</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    
</section>
@endsection