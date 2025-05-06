@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        <h2>Create Email Campaign</h2>
        <form method="POST" action="{{ route('campaigns.store') }}">
            @csrf
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Body</label>
                <textarea name="body" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Create Campaign</button>
        </form>

        <hr>

        <h2>Filter Audience</h2>
        <form method="GET" action="{{ route('campaigns.filter') }}">
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">-- Any --</option>
                    <option value="Paid">Paid</option>
                    <option value="Grace period">Grace period</option>
                    <option value="Expired">Expired</option>
                </select>
            </div>
            <div class="form-group">
                <label>Days to Expiry</label>
                <input type="number" name="days_to_expiry" class="form-control">
            </div>
            <button type="submit" class="btn btn-secondary mt-2">Filter Audience</button>
        </form>

        @if (isset($customers) && count($customers))
            <hr>
            <h2>Filtered Customers</h2>
            <form method="POST" action="{{ route('campaigns.sendEmails') }}">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $campaign_id ?? '' }}">
                @foreach ($customers as $customer)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="customer_ids[]" value="{{ $customer->id }}"
                            checked>
                        <label class="form-check-label">{{ $customer->name }} ({{ $customer->email }})</label>
                    </div>
                @endforeach
                @if (isset($campaigns) && count($campaigns))
                    <div class="form-group mt-3">
                        <h2>Select Campaign</h2>
                        <select name="campaign_id" class="form-control" required>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}">{{ $campaign->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <button type="submit" class="btn btn-success mt-3">Send Emails</button>
            </form>
        @endif
    </div>
@endsection
