/*
========================================
ADMIN PANEL - SHARED TABLE & UI STYLES
========================================
Dùng chung cho tất cả các trang admin.
========================================
*/

:root {
    /* Table Colors */
    --table-bg: #ffffff;
    --table-border: #cbd5e1; /* Slightly darker for visible row lines */
    --table-hover: #f8fafc;
    --table-shadow: rgba(112, 144, 176, 0.08);

    /* Backgrounds */
    --body-bg: #f4f7fe;

/* Apply Body background globally */
}
body, .content-wrapper, .wrapper {
    background-color: var(--body-bg) !important;
}

    /* Header Colors */
    --header-bg: #f1f5f9; /* Better contrast against white table */
    --header-text: #64748b;

    /* Card Colors */
    --card-bg: #ffffff;
    --card-shadow: 0px 4px 20px rgba(112, 144, 176, 0.12);
    --card-header-bg: #ffffff;
    --card-header-text: #2b3674;

    /* Button Colors (Vibrant Pastel & Gradients) */
    --btn-warning: linear-gradient(135deg, #FFB75E 0%, #ED8F03 100%);
    --btn-info: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
    --btn-danger: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%); /* soft red/pink */
    --btn-danger: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);
    --btn-success: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    --btn-secondary: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
    --btn-primary: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --btn-accent: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

    /* Badge Colors (Soft Solid/Gradients) */
    --badge-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
    --badge-danger: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
    --badge-primary: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    --badge-secondary: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
    --badge-info: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    --badge-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);

    /* Border Radius - Apple-like Deep Radius */
    --border-radius: 16px;
    --btn-radius: 12px;
    --badge-radius: 8px;

    /* Spacing */
    --table-padding: 16px 20px;
    --btn-padding: 10px 20px;
    --badge-padding: 6px 12px;
}

/* Modern Table Styling */
.table {
    border-collapse: collapse !important;
    width: 100% !important;
    background: transparent !important;
    margin-bottom: 0 !important;
}

/* Table Header */
.table thead {
    background: var(--header-bg) !important;
}

.table thead th {
    color: var(--header-text) !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.72rem;
    letter-spacing: 0.8px;
    padding: var(--table-padding);
    border: none !important;
    position: relative;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: transparent !important;
}

/* Table Body */
.table tbody tr {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.table tbody tr:hover {
    background-color: var(--table-hover) !important;
}

.table tbody td {
    padding: var(--table-padding);
    vertical-align: middle;
    border-bottom: 1px solid var(--table-border) !important;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    color: #334155;
    font-size: 0.875rem;
    font-weight: 500;
}

.table tbody tr:last-child td {
    border-bottom: none !important;
}

/* Custom Badge Colors */
.badge.bg-success {
    background: var(--badge-success) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius) !important;
    font-size: 0.72rem;
    letter-spacing: 0.3px;
}

.badge.bg-danger {
    background: var(--badge-danger) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius) !important;
    font-size: 0.72rem;
    letter-spacing: 0.3px;
}

.badge.bg-primary {
    background: var(--badge-primary) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius) !important;
    font-size: 0.72rem;
    letter-spacing: 0.3px;
}

.badge.bg-secondary {
    background: var(--badge-secondary) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius) !important;
    font-size: 0.72rem;
    letter-spacing: 0.3px;
}

.badge.bg-info {
    background: var(--badge-info) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius) !important;
    font-size: 0.72rem;
    letter-spacing: 0.3px;
}

.badge.bg-warning {
    background: var(--badge-warning) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius) !important;
    font-size: 0.72rem;
    letter-spacing: 0.3px;
}

/* Custom Button Colors */
.btn-warning {
    background: var(--btn-warning) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: var(--btn-radius) !important;
    padding: var(--btn-padding) !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(237, 137, 54, 0.25);
}
.btn-warning:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(237, 137, 54, 0.35);
}

.btn-info {
    background: var(--btn-info) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: var(--btn-radius) !important;
    padding: var(--btn-padding) !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(66, 153, 225, 0.25);
}
.btn-info:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(66, 153, 225, 0.35);
}

.btn-danger {
    background: var(--btn-danger) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: var(--btn-radius) !important;
    padding: var(--btn-padding) !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(229, 62, 62, 0.25);
}
.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(229, 62, 62, 0.35);
}

.btn-success {
    background: var(--btn-success) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: var(--btn-radius) !important;
    padding: var(--btn-padding) !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(56, 161, 105, 0.25);
}
.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(56, 161, 105, 0.35);
}

.btn-secondary {
    background: var(--btn-secondary) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: var(--btn-radius) !important;
    padding: var(--btn-padding) !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(113, 128, 150, 0.25);
}
.btn-secondary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(113, 128, 150, 0.35);
}

.btn-primary {
    background: var(--btn-primary) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: var(--btn-radius) !important;
    padding: var(--btn-padding) !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.25);
}
.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.35);
}

/* Card Styling */
.card {
    border: none !important;
    border-radius: var(--border-radius) !important;
    overflow: hidden;
    box-shadow: 0 2px 12px var(--card-shadow) !important;
    background: var(--card-bg) !important;
}

.card-header {
    background: var(--card-header-bg) !important;
    color: var(--card-header-text) !important;
    border-bottom: 1px solid #edf2f7 !important;
    padding: 16px 20px;
}

.card-title {
    margin: 0;
    font-weight: 700;
    font-size: 1.1rem;
    letter-spacing: 0.3px;
}

/* Stats Cards */
.stats-card {
    transition: all 0.2s ease;
    border: none !important;
    background: var(--card-bg) !important;
    box-shadow: 0 2px 12px var(--card-shadow) !important;
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1) !important;
}

/* Pagination Styling */
.pagination {
    margin-bottom: 0;
    gap: 4px;
}

.page-item.active .page-link {
    background: var(--btn-primary) !important;
    border: none !important;
    border-radius: var(--btn-radius) !important;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3) !important;
}

.page-link {
    border: 1px solid #edf2f7 !important;
    border-radius: var(--btn-radius) !important;
    margin: 0 2px;
    color: #4a5568;
    font-weight: 500;
    transition: all 0.2s ease;
}

.page-link:hover {
    background: #edf2f7 !important;
    color: #5a67d8;
}

/* Action Buttons Container */
.table tbody td:last-child {
    white-space: nowrap;
}

.table tbody td:last-child .btn {
    margin: 0 2px;
}

/* Form Styling */
.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.2);
}

.form-label {
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .table {
        font-size: 0.8rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 10px 8px;
    }
    
    .btn-sm {
        padding: 6px 10px !important;
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
}

/* Action Buttons */
.action-btn {
    width: 32px;
    height: 32px;
    padding: 0 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px !important;
}

/* Card Enhancements */
.card-header {
    background: transparent !important;
    border-bottom: 1px solid var(--table-border) !important;
    padding: 1.25rem 1.5rem !important;
}

.card-header h3.card-title {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--card-header-text);
}

/* Custom Pagination Styling */
.pagination {
    margin-bottom: 0;
    gap: 0.25rem;
}

.pagination .page-item .page-link {
    border: none;
    border-radius: 8px;
    color: #475569;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.5rem 0.85rem;
    background-color: transparent;
    transition: all 0.2s ease;
}

.pagination .page-item:not(.active):not(.disabled) .page-link:hover {
    background-color: #f1f5f9;
    color: #1e293b;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.pagination .page-item.active .page-link {
    background: var(--btn-primary);
    color: #fff;
    box-shadow: 0 4px 10px rgba(0, 242, 254, 0.25);
}

.pagination .page-item.disabled .page-link {
    color: #94a3b8;
    background-color: transparent;
}
