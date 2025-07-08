@extends('layouts.app')

@section('title', 'ارسال درخواست الحاق')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>ارسال درخواست الحاق به تیم</h4>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('join-requests.store', ['reservation' => $reservation->id]) }}" id="joinRequestForm">
                @csrf
                
                <div class="mb-3">
                    <label for="message" class="form-label">پیام شما:</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" 
                              id="message" 
                              name="message" 
                              rows="3" 
                              required
                              placeholder="لطفاً دلیل درخواست الحاق خود را توضیح دهید">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-send-check"></i> ارسال درخواست
                </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> بازگشت
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('joinRequestForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...';
        });
    }
});
</script>
@endsection