<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ManageSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:super {action} {email} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quản lý Super Admin: add, remove, list';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $email = $this->argument('email');

        switch ($action) {
            case 'add':
                $this->addSuperAdmin($email);
                break;
            case 'remove':
                $this->removeSuperAdmin($email);
                break;
            case 'list':
                $this->listSuperAdmins();
                break;
            default:
                $this->error('Hành động không hợp lệ. Sử dụng: add, remove, list');
                return 1;
        }

        return 0;
    }

    private function addSuperAdmin($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Không tìm thấy user với email: {$email}");
            return;
        }

        if ($user->role !== 'admin') {
            $this->error("User {$email} không phải là admin. Vui lòng cập nhật role thành admin trước.");
            return;
        }

        // Thêm email vào config
        $superAdminEmails = config('admin.super_admin_emails', []);
        
        if (in_array($email, $superAdminEmails)) {
            $this->info("User {$email} đã là Super Admin.");
            return;
        }

        $superAdminEmails[] = $email;
        
        // Cập nhật config file
        $configPath = config_path('admin.php');
        $configContent = file_get_contents($configPath);
        
        // Thay thế mảng super_admin_emails
        $pattern = "/'super_admin_emails' => \[([^\]]*)\]/";
        $replacement = "'super_admin_emails' => [" . implode(', ', array_map(function($email) {
            return "'{$email}'";
        }, $superAdminEmails)) . "]";
        
        $newConfigContent = preg_replace($pattern, $replacement, $configContent);
        file_put_contents($configPath, $newConfigContent);

        $this->info("Đã thêm {$email} làm Super Admin thành công!");
    }

    private function removeSuperAdmin($email)
    {
        $superAdminEmails = config('admin.super_admin_emails', []);
        
        if (!in_array($email, $superAdminEmails)) {
            $this->error("User {$email} không phải là Super Admin.");
            return;
        }

        // Kiểm tra xem có phải Super Admin cuối cùng không
        if (count($superAdminEmails) <= 1) {
            $this->error("Không thể xóa Super Admin cuối cùng!");
            return;
        }

        $superAdminEmails = array_diff($superAdminEmails, [$email]);
        
        // Cập nhật config file
        $configPath = config_path('admin.php');
        $configContent = file_get_contents($configPath);
        
        // Thay thế mảng super_admin_emails
        $pattern = "/'super_admin_emails' => \[([^\]]*)\]/";
        $replacement = "'super_admin_emails' => [" . implode(', ', array_map(function($email) {
            return "'{$email}'";
        }, $superAdminEmails)) . "]";
        
        $newConfigContent = preg_replace($pattern, $replacement, $configContent);
        file_put_contents($configPath, $newConfigContent);

        $this->info("Đã xóa {$email} khỏi Super Admin thành công!");
    }

    private function listSuperAdmins()
    {
        $superAdminEmails = config('admin.super_admin_emails', []);
        
        if (empty($superAdminEmails)) {
            $this->info("Chưa có Super Admin nào được cấu hình.");
            return;
        }

        $this->info("Danh sách Super Admin:");
        $this->table(['Email', 'Tên', 'Trạng thái'], function() use ($superAdminEmails) {
            $rows = [];
            foreach ($superAdminEmails as $email) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $rows[] = [
                        $email,
                        $user->name,
                        $user->status
                    ];
                } else {
                    $rows[] = [
                        $email,
                        'Không tồn tại',
                        'N/A'
                    ];
                }
            }
            return $rows;
        });
    }
}
