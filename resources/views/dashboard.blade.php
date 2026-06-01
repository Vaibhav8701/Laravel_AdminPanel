@extends('layout.app')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endsection

@section('content')
    <!-- Stats Grid -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-card-icon blue">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-card-label">Total Posts</div>
            <div class="stat-card-value">{{ $postsCount ?? 0 }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-arrow-up"></i> 12% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon green">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-card-label">Total Users</div>
            <div class="stat-card-value">{{ $usersCount ?? 0 }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-arrow-up"></i> 8% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon orange">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-card-label">Total Roles</div>
            <div class="stat-card-value">{{ $rolesCount ?? 0 }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-arrow-up"></i> 5% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon red">
                <i class="fas fa-cube"></i>
            </div>
            <div class="stat-card-label">Total Modules</div>
            <div class="stat-card-value">{{ $modulesCount ?? 0 }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-arrow-up"></i> 3% from last month
            </div>
        </div>
    </div>

    <!-- Annual Report Section -->
    <div class="chart-section">
        <h3 class="chart-section-title">Annual Report</h3>
        
        <div class="annual-report">
            <div class="report-item">
                <div class="report-item-amount" style="color: #0f766e;">$4,516</div>
                <div class="report-item-label">Total Revenue</div>
            </div>
            <div class="report-item">
                <div class="report-item-amount" style="color: #0891b2;">$6,481</div>
                <div class="report-item-label">Monthly Revenue</div>
            </div>
            <div class="report-item">
                <div class="report-item-amount" style="color: #a855f7;">$3,915</div>
                <div class="report-item-label">Total Profit</div>
            </div>
            <div class="report-item">
                <div class="report-item-amount" style="color: #7c3aed;">85%</div>
                <div class="report-item-label">Growth Rate</div>
            </div>
        </div>

        <!-- Chart (Optional - if you want to add charts later) -->
        <div class="chart-container" id="salesChart" style="display: none;">
            <!-- Chart will be rendered here -->
        </div>
    </div>

    <!-- Summary Tables -->
    <div class="tables-grid">
        <div class="summary-table">
            <h3 class="summary-table-title">Quick Stats</h3>
            
            <div class="stat-item">
                <div class="stat-item-name">
                    <div class="stat-item-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span>Posted Articles</span>
                </div>
                <div class="stat-item-value">{{ $postsCount ?? 0 }}</div>
            </div>

            <div class="stat-item">
                <div class="stat-item-name">
                    <div class="stat-item-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span>Registered Users</span>
                </div>
                <div class="stat-item-value">{{ $usersCount ?? 0 }}</div>
            </div>

            <div class="stat-item">
                <div class="stat-item-name">
                    <div class="stat-item-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <span>User Roles</span>
                </div>
                <div class="stat-item-value">{{ $rolesCount ?? 0 }}</div>
            </div>

            <div class="stat-item">
                <div class="stat-item-name">
                    <div class="stat-item-icon">
                        <i class="fas fa-cube"></i>
                    </div>
                    <span>System Modules</span>
                </div>
                <div class="stat-item-value">{{ $modulesCount ?? 0 }}</div>
            </div>
        </div>

        <div class="summary-table">
            <h3 class="summary-table-title">System Information</h3>
            
            <div class="stat-item">
                <div class="stat-item-name">
                    <span>Current User</span>
                </div>
                <div class="stat-item-value">{{ auth()->user()->name }}</div>
            </div>

            <div class="stat-item">
                <div class="stat-item-name">
                    <span>Email</span>
                </div>
                <div class="stat-item-value" style="font-size: 12px;">{{ auth()->user()->email }}</div>
            </div>

            <div class="stat-item">
                <div class="stat-item-name">
                    <span>Status</span>
                </div>
                <div class="stat-item-value" style="color: var(--primary);">
                    <i class="fas fa-circle"></i> Active
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-item-name">
                    <span>Last Login</span>
                </div>
                <div class="stat-item-value" style="font-size: 12px;">Today at 10:30 AM</div>
            </div>
        </div>
    </div>
@endsection


