@extends('layouts.app')

@section('title', 'جزئیات رزرو')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-info-circle"></i> جزئیات رزرو
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>اطلاعات سانس:</h5>
                            <p><i class="bi bi-calendar"></i> تاریخ: {{ jdate($reservation->gymSession->date)->format('Y/m/d') }}</p>
                            <p><i class="bi bi-clock"></i> زمان: {{ $reservation->gymSession->start_time }} تا {{ $reservation->gymSession->end_time }}</p>
                            <p><i class="bi bi-people"></i> ظرفیت سانس: {{ $reservation->gymSession->max_capacity }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>اطلاعات رزرو:</h5>
                            <p>
                                <i class="bi bi-person"></i> رزروکننده: 
                                {{ $reservation->user->name }}
                            </p>
                            <p>
                                <i class="bi bi-info-circle"></i> وضعیت: 
                                @if($reservation->status == 'pending')
                                    <span class="badge bg-warning">در انتظار تایید</span>
                                @elseif($reservation->status == 'confirmed')
                                    <span class="badge bg-success">تایید شده</span>
                                @elseif($reservation->status == 'cancelled')
                                    <span class="badge bg-secondary">لغو شده</span>
                                @endif
                            </p>
                            <p><i class="bi bi-people"></i> تعداد اعضا: {{ $reservation->member_count }}</p>
                        </div>
                    </div>

                    @if($reservation->team)
                        <div class="alert alert-secondary">
                            <h5><i class="bi bi-people-fill"></i> اطلاعات تیم:</h5>
                            <p>نام تیم: {{ $reservation->team->name }}</p>
                            <p>تعداد اعضای تیم: {{ $reservation->team->member_count }}</p>
                        </div>
                    @endif

                    @if($reservation->notes)
                        <div class="alert alert-light">
                            <h5><i class="bi bi-card-text"></i> توضیحات:</h5>
                            <p>{{ $reservation->notes }}</p>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-right"></i> بازگشت به لیست
                        </a>

                        <div class="d-flex gap-2">
                            @if(in_array($reservation->status, ['pending', 'confirmed']))
                                <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> ویرایش
                                </a>
                                
                                <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('آیا از لغو این رزرو مطمئن هستید؟')">
                                        <i class="bi bi-x-circle"></i> لغو رزرو
                                    </button>
                                </form>
                            @endif
                            
                            @if($reservation->status == 'cancelled')
                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('آیا از حذف این رزرو مطمئن هستید؟')">
                                        <i class="bi bi-trash"></i> حذف رزرو
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection