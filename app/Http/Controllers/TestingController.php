<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestingController extends Controller
{
    /**
     * Create unit accounts for auditees
     */
    public function createUnitAccounts()
    {
        $auditees = [
            // Program Studi S1
            'Dr. Achmad Sulton, M.Pd.I.',
            'Dr. Ali Wardana, M.Pd.',
            'Achmad Misbah, M.Pd.I.',
            'Achmad Zuhdi, M.H.I.',
            'Ahmad Robiin, M.M.',
            'Muhamad Syafiq, M.Pd.',
            'Reiza Praselanova, M.I.Kom.',
            'Samsul Huda, M.Pd.',
            'Rofiudin, M.M.',
            // Program Studi S2
            'Dr. Syamfa Agny Anggara, M.Pd.',
            'Dr. Yusuf Arisandi, M.Pd.',
            'Dr. Jaudi, M.Pd.',
            'Dr. Achmad Djuaini, M.Pd.',
            'Dr. Sodiqin, M.Pd.',
            'Dr. Asep Rahmatullah, M.Pd.',
            // Program Studi S3
            'Dr. Kholili Hasib, M.Ud.',
            'Dr. Ahmad Farid, M.Sos.',
            'Dr. Muhamad Solehudin, M.Pd.',
            'Dr. Tohiri Habib, M.Pd.',
            // LP2M (already included Dr. Asep Rahmatullah, Dr. Achmad Djuaini)
            // Layanan
            'Wiranama Wirabangsa, M.Pd.',
            'Moh. Hud, M.Pd.',
            'Sukron, M.Pd.',
            'Abdul Majid, M.Pd.',
            'Hasan Basri, M.Pd.',
            'Husin Baharun, M.Pd.',
            'Muhamad Adil, M.Pd.',
            // Layanan Kerjasama (already included Dr. Kholili Hasib)
            // Unit
            'Abdul Muid, S.IP',
            'Khairul Umam, M.Pd.',
        ];

        // Remove duplicates
        $auditees = array_unique($auditees);

        $createdUsers = [];
        $skippedUsers = [];

        foreach ($auditees as $name) {
            // Check if user with this name already exists
            $existingUser = User::where('name', $name)->first();

            if ($existingUser) {
                $skippedUsers[] = $name;
                continue;
            }

            // Generate random username (8 digit numbers)
            $username = (string) rand(10000000, 99999999);

            // Make sure username is unique
            while (User::where('username', $username)->exists()) {
                $username = (string) rand(10000000, 99999999);
            }

            $user = User::create([
                'username' => $username,
                'name' => $name,
                'email' => null,
                'password' => Hash::make('dalwa123'),
                'role' => 'unit',
                'sex' => 'male',
            ]);

            $createdUsers[] = [
                'id' => $user->id,
                'username' => $username,
                'name' => $name,
            ];
        }

        return response()->json([
            'message' => 'Unit accounts created successfully',
            'total_created' => count($createdUsers),
            'total_skipped' => count($skippedUsers),
            'created_users' => $createdUsers,
            'skipped_users' => $skippedUsers,
        ]);
    }
}
