<?php

use SimpleSoftwareIO\QrCode\Facades\QrCode;

if (!function_exists('generate_qr_data_uri')) {
    /**
     * Generate QR Code as base64 data URI
     * 
     * @param string $data The data to encode
     * @param int $size Size of QR code
     * @return string Base64 data URI
     */
    function generate_qr_data_uri(string $data, int $size = 300): string
    {
        try {
            $qrCode = QrCode::format('png')
                ->size($size)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($data);
            
            $base64 = base64_encode($qrCode);
            return 'data:image/png;base64,' . $base64;
        } catch (\Exception $e) {
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            
            // Return placeholder SVG
            $placeholder = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 300 300">
                <rect width="300" height="300" fill="#f3f4f6"/>
                <text x="150" y="140" text-anchor="middle" font-size="16" fill="#6b7280" font-family="Arial">QR Code</text>
                <text x="150" y="165" text-anchor="middle" font-size="12" fill="#9ca3af" font-family="Arial">Error generating</text>
            </svg>';
            
            return 'data:image/svg+xml;base64,' . base64_encode($placeholder);
        }
    }
}

if (!function_exists('get_user_display_name')) {
    /**
     * Get user display name with graceful handling for deleted users
     * 
     * @param \App\Models\User|null $user The user object
     * @param string $deletedPlaceholder Placeholder text for deleted users
     * @return string User name or placeholder
     */
    function get_user_display_name($user, string $deletedPlaceholder = 'User Deleted'): string
    {
        if (!$user) {
            return $deletedPlaceholder;
        }
        
        return $user->name ?? $deletedPlaceholder;
    }
}

if (!function_exists('get_user_role_display')) {
    /**
     * Get user role display name
     * 
     * @param \App\Models\User|null $user The user object
     * @return string Role display name
     */
    function get_user_role_display($user): string
    {
        if (!$user) {
            return '-';
        }
        
        // Check if user has Spatie roles
        if (method_exists($user, 'getRoleNames')) {
            $roleName = $user->getRoleNames()->first();
            
            if ($roleName) {
                // Map role names to display names
                $roleMap = [
                    'superadmin' => 'Superadmin',
                    'leader' => 'Leader',
                    'inspector' => 'Inspector',
                    'petugas' => 'Petugas',
                    'user' => 'User',
                ];
                
                return $roleMap[$roleName] ?? ucfirst($roleName);
            }
        }
        
        // Fallback to position if available
        if (isset($user->position)) {
            return ucfirst($user->position);
        }
        
        return 'User';
    }
}

if (!function_exists('format_approval_status')) {
    /**
     * Format approval status with user information
     * 
     * @param mixed $kartu The kartu object with approval information
     * @param bool $includeRole Whether to include user role
     * @return string Formatted approval status
     */
    function format_approval_status($kartu, bool $includeRole = false): string
    {
        if (!$kartu) {
            return 'Unknown';
        }
        
        // Check if approved
        if ($kartu->approved_at) {
            $approverName = get_user_display_name($kartu->approver, 'Unknown Approver');
            
            if ($includeRole && $kartu->approver) {
                $role = get_user_role_display($kartu->approver);
                return "Approved by {$approverName} ({$role})";
            }
            
            return "Approved by {$approverName}";
        }
        
        return 'Pending Approval';
    }
}

if (!function_exists('get_creator_info')) {
    /**
     * Get formatted creator information
     * 
     * @param mixed $kartu The kartu object with creator information
     * @param bool $includeRole Whether to include user role
     * @return string Formatted creator information
     */
    function get_creator_info($kartu, bool $includeRole = false): string
    {
        if (!$kartu) {
            return 'Unknown';
        }
        
        $creatorName = get_user_display_name($kartu->user, 'Unknown User');
        
        if ($includeRole && $kartu->user) {
            $role = get_user_role_display($kartu->user);
            return "{$creatorName} ({$role})";
        }
        
        return $creatorName;
    }
}
