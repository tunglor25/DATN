<style>
    /* Form Sections */
    .addr-form-section {
        margin-bottom: 28px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--tlo-border, #e2e8f0);
    }
    .addr-form-section:last-of-type {
        border-bottom: none;
        padding-bottom: 0;
    }
    .addr-form-section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--tlo-text-primary, #1e293b);
        margin-bottom: 18px;
    }
    .addr-form-section-title i {
        color: var(--tlo-accent, #ff6b6b);
        margin-right: 8px;
        font-size: 0.95rem;
    }

    /* Grid */
    .addr-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    /* Form Group */
    .addr-form-group {
        margin-bottom: 4px;
    }
    .addr-form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--tlo-text-primary, #1e293b);
        margin-bottom: 6px;
    }
    .addr-form-group label .required {
        color: #ef4444;
    }

    /* Inputs */
    .addr-form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--tlo-border, #e2e8f0);
        border-radius: 10px;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        color: var(--tlo-text-primary, #1e293b);
        background: var(--tlo-surface, #fff);
        transition: all 0.3s ease;
        outline: none;
        box-sizing: border-box;
    }
    .addr-form-input:focus {
        border-color: var(--tlo-accent, #ff6b6b);
        box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
    }
    .addr-form-input.has-error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    textarea.addr-form-input {
        resize: vertical;
        min-height: 80px;
    }
    select.addr-form-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8.825L.35 3.175 1.425 2.1 6 6.675 10.575 2.1l1.075 1.075z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

    /* Error */
    .addr-form-error {
        color: #ef4444;
        font-size: 0.78rem;
        margin-top: 4px;
        margin-bottom: 0;
    }

    /* Alerts */
    .addr-form-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 0.85rem;
        line-height: 1.5;
    }
    .addr-form-alert i {
        flex-shrink: 0;
        margin-top: 2px;
    }
    .addr-form-alert.warning {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
    }
    .addr-form-alert.warning i { color: #f59e0b; }
    .addr-form-alert.info {
        background: rgba(255, 107, 107, 0.05);
        border: 1px solid rgba(255, 107, 107, 0.15);
        color: var(--tlo-text-secondary, #64748b);
    }
    .addr-form-alert.info i { color: var(--tlo-accent, #ff6b6b); }
    .addr-form-alert.info strong { color: var(--tlo-text-primary, #1e293b); display: block; margin-bottom: 2px; }
    .addr-form-alert.info p { margin: 0; }

    /* Checkbox */
    .addr-form-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 0.9rem;
        color: var(--tlo-text-primary, #1e293b);
    }
    .addr-form-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--tlo-accent, #ff6b6b);
        cursor: pointer;
    }
    .addr-form-checkbox em {
        color: var(--tlo-accent, #ff6b6b);
        font-weight: 500;
    }

    /* Actions */
    .addr-form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 24px;
        border-top: 1px solid var(--tlo-border, #e2e8f0);
        flex-wrap: wrap;
        gap: 12px;
    }
    .addr-form-actions-right {
        display: flex;
        gap: 10px;
        margin-left: auto;
    }
    .addr-form-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 22px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }
    .addr-form-btn.primary {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: #fff;
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
    }
    .addr-form-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.35);
    }
    .addr-form-btn.outline {
        background: transparent;
        border: 1px solid var(--tlo-border, #e2e8f0);
        color: var(--tlo-text-secondary, #64748b);
    }
    .addr-form-btn.outline:hover {
        border-color: var(--tlo-text-secondary, #94a3b8);
        color: var(--tlo-text-primary, #1e293b);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .addr-form-grid {
            grid-template-columns: 1fr;
        }
        .addr-form-actions {
            flex-direction: column;
        }
        .addr-form-actions-right {
            width: 100%;
            margin-left: 0;
        }
        .addr-form-btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>
