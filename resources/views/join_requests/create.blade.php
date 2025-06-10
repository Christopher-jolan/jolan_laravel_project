@extends('layouts.app')

@section('title', 'ارسال درخواست الحاق')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>ارسال درخواست الحاق به تیم</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('join-requests.store', $reservation->id) }}" id="joinRequestForm">
                @csrf
                <div class="mb-3">
                    <label for="message" class="form-label">پیام شما:</label>
                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    ارسال درخواست
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('joinRequestForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...';
    
    return true;
});
</script>

@endsection