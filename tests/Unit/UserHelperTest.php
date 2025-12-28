<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class UserHelperTest extends TestCase
{
    public function test_get_user_display_name_returns_placeholder_for_null_user()
    {
        $result = get_user_display_name(null, 'User Deleted');
        
        $this->assertEquals('User Deleted', $result);
    }

    public function test_get_user_display_name_uses_custom_placeholder()
    {
        $result = get_user_display_name(null, 'Unknown User');
        
        $this->assertEquals('Unknown User', $result);
    }

    public function test_get_user_role_display_returns_dash_for_null_user()
    {
        $result = get_user_role_display(null);
        
        $this->assertEquals('-', $result);
    }

    public function test_format_approval_status_returns_unknown_for_null_kartu()
    {
        $result = format_approval_status(null);
        
        $this->assertEquals('Unknown', $result);
    }

    public function test_get_creator_info_returns_unknown_for_null_kartu()
    {
        $result = get_creator_info(null);
        
        $this->assertEquals('Unknown', $result);
    }
}
