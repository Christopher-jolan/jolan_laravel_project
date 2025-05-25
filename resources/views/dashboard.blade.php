@extends('layouts.app')

@section('title', 'داشبورد کاربری')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-speedometer2"></i> داشبورد کاربری - {{ Auth::user()->name }}
                    </h4>
                </div>

                <div class="card-body">
                    <!-- بخش سانس‌های رزرو شده -->
                    <div class="mb-5">
                        <h5 class="border-bottom pb-2 text-primary">
                            <i class="bi bi-calendar-check"></i> سانس‌های رزرو شده توسط شما
                        </h5>
                        
                        @forelse($reservations as $reservation)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>
                                            {{ jdate($reservation->gymSession->date)->format('Y/m/d') }}
                                            - {{ $reservation->gymSession->start_time }} تا {{ $reservation->gymSession->end_time }}
                                        </h5>
                                        <span class="badge bg-{{ $reservation->status === 'approved' ? 'success' : 'warning' }}">
                                            {{ $reservation->status === 'approved' ? 'تأیید شده' : 'در انتظار تأیید' }}
                                        </span>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <p><i class="bi bi-people"></i> تعداد اعضا: {{ $reservation->member_count }}/{{ $reservation->gymSession->max_capacity }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            @if($reservation->team)
                                                <p><i class="bi bi-tag"></i> تیم: {{ $reservation->team->name }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <p><i class="bi bi-info-circle"></i> وضعیت سانس: {{ $reservation->gymSession->status === 'full' ? 'تکمیل ظرفیت' : 'ظرفیت موجود' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> هنوز هیچ سانسی رزرو نکرده‌اید.
                            </div>
                        @endforelse
                    </div>

                    <!-- بخش درخواست‌های دریافتی -->
                    <div class="mb-5">
                        <h5 class="border-bottom pb-2 text-primary">
                            <i class="bi bi-inbox"></i> درخواست‌های الحاق به سانس‌های شما
                        </h5>
                        
                        @forelse($receivedRequests as $request)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6>
                                                <i class="bi bi-person"></i> 
                                                درخواست از: {{ $request->user->name }}
                                            </h6>
                                            <p class="mb-0">
                                                <i class="bi bi-calendar"></i> 
                                                {{ jdate($request->reservation->gymSession->date)->format('Y/m/d') }}
                                                - {{ $request->reservation->gymSession->start_time }}
                                            </p>
                                        </div>
                                        <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ $request->status === 'approved' ? 'تأیید شده' : ($request->status === 'rejected' ? 'رد شده' : 'در انتظار بررسی') }}
                                        </span>
                                    </div>
                                    
                                    @if($request->message)
                                        <div class="alert alert-light mt-2">
                                            <strong><i class="bi bi-chat-left-text"></i> پیام:</strong> 
                                            {{ $request->message }}
                                        </div>
                                    @endif
                                    
                                    @if($request->status === 'pending')
                                        <form action="{{ route('join-requests.handle', $request->id) }}" method="POST" class="mt-3">
                                            @csrf
                                            <div class="d-flex gap-2">
                                                <button type="submit" name="action" value="approve" class="btn btn-success flex-grow-1">
                                                    <i class="bi bi-check-circle"></i> تأیید درخواست
                                                </button>
                                                <button type="submit" name="action" value="reject" class="btn btn-danger flex-grow-1">
                                                    <i class="bi bi-x-circle"></i> رد درخواست
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> هیچ درخواست الحاقی دریافت نکرده‌اید.
                            </div>
                        @endforelse
                    </div>

                    <!-- بخش درخواست‌های ارسالی -->
                    <div class="mb-3">
                        <h5 class="border-bottom pb-2 text-primary">
                            <i class="bi bi-send"></i> درخواست‌های ارسالی شما برای پیوستن به سانس‌ها
                        </h5>
                        
                        @forelse($sentRequests as $request)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6>
                                                <i class="bi bi-calendar-event"></i> 
                                                سانس: {{ jdate($request->reservation->gymSession->date)->format('Y/m/d') }}
                                                - {{ $request->reservation->gymSession->start_time }}
                                            </h6>
                                            <p class="mb-0">
                                                <i class="bi bi-person"></i> 
                                                رزروکننده: {{ $request->reservation->user->name }}
                                            </p>
                                        </div>
                                        <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ $request->status === 'approved' ? 'تأیید شده' : ($request->status === 'rejected' ? 'رد شده' : 'در انتظار بررسی') }}
                                        </span>
                                    </div>
                                    
                                    @if($request->message)
                                        <div class="alert alert-light mt-2">
                                            <strong><i class="bi bi-chat-left-text"></i> پیام شما:</strong> 
                                            {{ $request->message }}
                                        </div>
                                    @endif
                                    
                                    <div class="mt-2">
                                        <p class="mb-1">
                                            <i class="bi bi-people"></i> 
                                            ظرفیت: {{ $request->reservation->member_count }}/{{ $request->reservation->gymSession->max_capacity }}
                                        </p>
                                        <p class="mb-0">
                                            <i class="bi bi-info-circle"></i> 
                                            وضعیت سانس: {{ $request->reservation->gymSession->status === 'full' ? 'تکمیل ظرفیت' : 'ظرفیت موجود' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> هیچ درخواست الحاقی ارسال نکرده‌اید.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection