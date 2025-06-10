@extends('layouts.app')

@section('title', 'لیست رزروهای من')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-list-check"></i> لیست رزروهای من
                    </h4>
                </div>

                <div class="card-body">
                    @if($reservations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>تاریخ سانس</th>
                                        <th>زمان</th>
                                        <th>وضعیت</th>
                                        <th>تعداد اعضا</th>
                                        <th>تیم</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $reservation)
                                        <tr>
                                            <td>{{ jdate($reservation->gymSession->date)->format('Y/m/d') }}</td>
                                            <td>{{ $reservation->gymSession->start_time }} - {{ $reservation->gymSession->end_time }}</td>
                                            <td>
                                                @if($reservation->status == 'pending')
                                                    <span class="badge bg-warning">در انتظار تایید</span>
                                                @elseif($reservation->status == 'confirmed')
                                                    <span class="badge bg-success">تایید شده</span>
                                                @elseif($reservation->status == 'cancelled')
                                                    <span class="badge bg-secondary">لغو شده</span>
                                                @endif
                                            </td>
                                            <td>{{ $reservation->member_count }}</td>
                                            <td>
                                                @if($reservation->team)
                                                    {{ $reservation->team->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('reservations.show', $reservation->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="مشاهده">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    
                                                    @if(in_array($reservation->status, ['pending', 'confirmed']))
                                                        <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    title="لغو رزرو" onclick="return confirm('آیا از لغو این رزرو مطمئن هستید؟')">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($reservation->status == 'cancelled')
                                                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    title="حذف" onclick="return confirm('آیا از حذف این رزرو مطمئن هستید؟')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $reservations->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> هیچ رزروی ثبت نکرده‌اید.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection