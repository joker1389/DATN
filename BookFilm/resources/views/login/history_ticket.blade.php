@extends('layouts._layout')

@section('title', 'Lịch sử đặt vé')

@section('content')
    <div style="display: none;">{{ $dem = 1 }}</div>
        <section class="container">
            <div class="col-sm-8 col-md-9" style="margin-top: 50px;">
                <div class="movie">
                    <h2 class="page-heading">Lịch sử đặt vé và điểm thưởng</h2>
                    
                    <div class="movie__info">
                        <div class="col-md-12">
                           <div class="panel panel-default">
                            <div class="panel-heading" style="padding-bottom: 35px">
                                <div class="col-sm-4"><strong>Điểm thưởng tích lũy</strong></div>
                                <div class="col-md-2">Điểm: <strong style="color: red">{{ $member !== null ? $member->Point : '0'  }}</strong></div>
                                <div class="col-md-4">Thành viên: <strong>{{ $member !== null ? $member->Name : 'chưa xác định'  }}</strong></div>
                            </div>
                            <div class="panel-heading" style="padding-bottom: 35px">
                                <div class="col-sm-4"><strong>Danh sách vé</strong></div>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="width: 30%">Vé xem phim</th>
                                                <th>Số vé</th>
                                                <th>Thời gian xem</th>
                                                <th>Ngày đặt</th>
                                                <th>Trạng thái</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($query) > 0)
                                                @foreach ($query as $item)
                                                <tr>
                                                    <td>{{ $dem }}</td>
                                                    <td>{{ $item->Name }}</td>
                                                    <td>{{ $item->CountTicket }}</td>
                                                    <td>{{ Carbon\Carbon::parse($item->Date)->format('d-m-Y') }} , {{ Carbon\Carbon::parse($item->Time)->format('H:i .A') }}</td>
                                                    <td>{{ Carbon\Carbon::parse($item->CreatedDate)->format('d-m-Y') }}</td>

                                                    @if ($item->Status == true)
                                                    <td>
                                                        <span class="label label-info">Đã thanh toán</span>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <span class="label label-default">Đã hủy/hết hạn</span>
                                                    </td>
                                                    @endif

                                                    <td>
                                                        <a href="/chi-tiet-ve/{{ $item->ID }}" class="btn btn-default" title="Chi tiết vé"><i class="fa fa-edit"></i></a>
                                                        @if($item->ReleaseDate <= Carbon\Carbon::now('Asia/Ho_Chi_Minh') && $item->Status == true)
                                                        <button class="btn btn-danger btnCancer" data-id="{{ $item->ID }}" title="Hủy vé"><i class="fa fa-times"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <div style="display: none;">{{ $dem++ }}</div>
                                                @endforeach  
                                            @else
                                                <tr>
                                                    <td class="text-center" colspan="7">Bạn hãy đặt vé tại trang để tích lũy điểm thưởng với nhiều ưu đãi nha!!!</td>
                                                </tr>
                                            @endif
                                              

                                        </tbody>
                                    </table>
                                    Trang {{ $query->currentPage() }} / {{ $query->lastPage() }}
                                    {{ $query->links() }}
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                </div>
                    
                    

                </div>
               
            </div>

            <aside class="col-sm-4 col-md-3">
                <div class="sitebar">
                    <div class="banner-wrap">
                        <img alt='banner' style="width: 249px; height: 249px" src="{{asset('assets/images/cinema/action.jpg')}}">
                    </div>

                     <div class="banner-wrap">
                        <img alt='banner' style="width: 249px; height: 249px" src="{{asset('assets/images/cinema/141858.jpg')}}">
                    </div>

                     <div class="banner-wrap banner-wrap--last">
                        <img alt='banner' style="width: 249px; height: 249px" src="{{asset('assets/images/cinema/bg2.jpg')}}">
                    </div>

                    <div class="promo marginb-sm">
                      <div class="promo__head">A.Movie app</div>
                      <div class="promo__describe">for all smartphones<br> and tablets</div>
                      <div class="promo__content">
                          <ul>
                              <li class="store-variant"><a href="#"><img alt='' src="{{asset('assets/images/apple-store.svg')}}"></a></li>
                              <li class="store-variant"><a href="#"><img alt='' src="{{asset('assets/images/google-play.svg')}}"></a></li>
                              <li class="store-variant"><a href="#"><img alt='' src="{{asset('assets/images/windows-store.svg')}}"></a></li>
                          </ul>
                      </div>
                  </div>

                    <div class="category category--discuss category--count marginb-sm mobile-category ls-cat">
                        <h3 class="category__title">Đang công chiếu <br><span class="title-edition">tại rạp</span></h3>
                        <ol>
                            @foreach($MoviePlay as $item)
                                <div style="display: none;">{{ $url = '/phim/' . $item->Metatitle . "/" . $item->ID }}</div>
                                 <li>
                                    <a href="{{ $url }}" class="category__item" title="{{ $item->Name }}">
                                        {{-- {{ $item->Name }} --}}
                                        {{ Str::limit($item->Name, 25, '..'); }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="category category--cooming category--count marginb-sm mobile-category rs-cat">
                        <h3 class="category__title">Chuẩn bị chiếu<br><span class="title-edition">movies</span></h3>
                        <ol>
                            @foreach($ComingMovie as $item)
                                <div style="display: none;">{{ $url = '/phim/' . $item->Metatitle . "/" . $item->ID }}</div>
                                 <li>
                                    <a href="{{ $url }}" class="category__item" title="{{ $item->Name }}">
                                        {{-- {{ $item->Name }} --}}
                                        {{ Str::limit($item->Name, 25, '..'); }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                    
                   

                </div>
            </aside>

        </section>

@endsection

@section('jsSection')
    <script>
        $(document).ready(function () {
            $('.btnCancer').off('click').on('click', function () {

                $.ajax({
                    data: {},
                    url: '/Admin/Ticket/changeStatus/' + $(this).data('id'),
                    dataType: 'Json',
                    type: 'GET',
                    success: function () {
                            window.location.href = "/lich-su-dat-ve.html";
                            PNotify.success({
                                title: 'THÔNG BÁO!!',
                                text: 'Cập nhật trạng thái thành công.'
                            });
                    }
                });

            });
        });
    </script>
@endsection