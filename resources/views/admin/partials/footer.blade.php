<footer class="admin-footer">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="text-muted">
            &copy; {{ date('Y') }} <a href="#" class="text-dark text-decoration-none">Admin Panel</a>
        </div>
        <div class="d-flex gap-3">
            <a href="#" class="text-muted text-decoration-none">
                <i class="fas fa-lock me-1"></i> Privacy
            </a>
            <a href="#" class="text-muted text-decoration-none">
                <i class="fas fa-file-contract me-1"></i> Terms
            </a>
            <a href="tel:0344122842" class="text-dark text-decoration-none">
                <i class="fas fa-phone me-1"></i> Support
            </a>
        </div>
    </div>
</footer>

<style>
.admin-footer {
    width: 100%;
    height: 60px;
    background-color: white;
    border-top: 1px solid #e0e0e0;
    padding: 0 2rem;
    z-index: 1010;
}

@media (max-width: 767.98px) {
    .admin-footer {
        flex-direction: column;
        height: auto;
        padding: 1rem;
        text-align: center;
        gap: 0.5rem;
    }
}
</style>