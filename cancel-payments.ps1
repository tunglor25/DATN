# Ðu?ng d?n d?n project
$projectPath = "C:\laragon\www\tlo_fashion"

# Chuy?n d?n thu m?c project
Set-Location $projectPath

# Ch?y command
php artisan payments:cancel-expired

# Log th?i gian
$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
Write-Output "[$timestamp] Payment cancellation check completed" | Out-File -Append "storage/logs/payment-cancellation.log"
