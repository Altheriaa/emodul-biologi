<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\Models\User;

class UserTest extends TestCase
{
    /**
     * Test if a user can be identified as an admin.
     */
    public function test_user_can_be_identified_as_admin(): void
    {
        $user = new User();
        $user->role = 'admin';

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isDosen());
        $this->assertFalse($user->isMahasiswa());
    }

    /**
     * Test if a user can be identified as a dosen.
     */
    public function test_user_can_be_identified_as_dosen(): void
    {
        $user = new User();
        $user->role = 'dosen';

        $this->assertTrue($user->isDosen());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isMahasiswa());
    }

    /**
     * Test if a user can be identified as a mahasiswa.
     */
    public function test_user_can_be_identified_as_mahasiswa(): void
    {
        $user = new User();
        $user->role = 'mahasiswa';

        $this->assertTrue($user->isMahasiswa());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isDosen());
    }
}
