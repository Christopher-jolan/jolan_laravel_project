@extends('layouts.app')

@section('title', 'صفحه اصلی - سیستم نوبت‌دهی سالن ورزشی')

@section('content')
<div class="container">
        <div class="main-content">
            @foreach ($gymSessions as $session)
                @php
                    $totalReserved = 0;
                    $reservationOwner = null;
                    
                    if($session->reservations->count() > 0) {
                        foreach($session->reservations as $reservation) {
                            $totalReserved += $reservation->member_count;
                            $reservationOwner = $reservation->user_id;
                        }
                    }
                    
                    $isFull = $totalReserved >= $session->max_capacity;
                    $remainingCapacity = $session->max_capacity - $totalReserved;
                @endphp

                <div class="card {{ $isFull ? 'bg-light' : '' }}">
                    <div class="card-body">
                        <div class="session-time">
                            <h5 class="card-title">
                                {{ jdate($session->date)->format('Y/m/d') }} - 
                                {{ $session->start_time }} تا {{ $session->end_time }}
                            </h5>
                            <span class="{{ $isFull ? 'status-full' : 'status-available' }}">
                                {{ $isFull ? 'تکمیل ظرفیت' : 'ظرفیت موجود' }}
                            </span>
                        </div>
                        
                        <div class="session-details mb-3">
                            <p class="mb-1">
                                <i class="bi bi-people-fill"></i> 
                                ظرفیت: {{ $totalReserved }}/{{ $session->max_capacity }}
                                @if(!$isFull)
                                    ({{ $remainingCapacity }} ظرفیت خالی)
                                @endif
                            </p>
                            
                            @if($session->reservations->count() > 0)
                                <p class="mb-1">
                                    <i class="bi bi-person-badge"></i>
                                    رزروکننده: {{ $session->reservations->first()->user->name }}
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-people"></i>
                                    تعداد اعضا: {{ $session->reservations->first()->member_count }}
                                </p>
                            @endif
                        </div>
                        @auth
                            @if($session->status !== 'full')
                                @if($session->reservations->count() > 0)
                                    @if($session->reservations->first()->user_id == auth()->id())
                                        {{-- کاربر مالک رزرو است --}}
                                        <a href="{{ route('reservations.show', $session->reservations->first()->id) }}" class="btn btn-info">
                                            <i class="bi bi-people"></i> مدیریت تیم
                                        </a>
                                   @else
                                        {{-- سانس توسط کاربر دیگری رزرو شده و ظرفیت دارد --}}
                                        <a href="{{ route('join-requests.create', $session->reservations->first()->id) }}" class="btn btn-success">
                                            <i class="bi bi-plus-circle"></i> الحاق به تیم
                                        </a>
                                    @endif
                                @else
                                    {{-- سانس رزرو نشده است --}}
                                    <a href="{{ route('reservations.create', $session->id) }}" class="btn btn-primary">
                                        رزرو این سانس
                                    </a>
                                @endif
                            @else
                                <button class="btn btn-secondary" disabled>تکمیل ظرفیت</button>
                            @endif
                        @else
                            <div class="auth-message">
                                <i class="bi bi-info-circle"></i> برای رزرو سانس، لطفاً 
                                <a href="{{ route('register') }}">ثبت‌نام</a> یا 
                                <a href="{{ route('login') }}">وارد</a> شوید.
                            </div>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="sidebar">
            <h3 style="color: var(--primary-color); border-bottom: 2px solid var(--accent-color); padding-bottom: 8px; font-size: 1.2rem;">
                <i class="bi bi-megaphone"></i> اطلاعیه‌ها
            </h3>
            
            <div class="announcement">
                <h5><i class="bi bi-exclamation-triangle"></i> تعطیلی سالن ورزشی</h5>
                <p>سالن ورزشی در تاریخ 24 اسفند به دلیل تعمیرات تعطیل می‌باشد.</p>
            </div>
            
            <div class="announcement">
                <h5><i class="bi bi-calendar-event"></i> برنامه هفتگی</h5>
                <p>برنامه هفتگی سانس‌های ورزشی در بخش داشبورد قابل مشاهده است.</p>
            </div>
            
            <div class="announcement">
                <h5><i class="bi bi-credit-card"></i> پرداخت اینترنتی</h5>
                <p>امکان پرداخت اینترنتی هزینه سانس‌ها از طریق درگاه بانکی فراهم شد.</p>
            </div>
            
            @auth
                <div style="margin-top: 15px;">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary w-100">
                        <i class="bi bi-speedometer2"></i> رفتن به داشبورد
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
</html>