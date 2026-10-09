<?php

namespace Database\Seeders;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Models\Campus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            CampusSeeder::class,
        ]);

        $campus = Campus::first();
        $categories = Category::all();

        // Administrator account (change the password immediately in production).
        $admin = User::updateOrCreate(
            ['email' => 'admin@campusbazar.test'],
            [
                'name' => 'CampusBazar Admin',
                'phone' => '01700000000',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'is_admin' => true,
                'verified_at' => now(),
                'status' => 'active',
            ],
        );

        // Verified demo seller.
        $seller = User::updateOrCreate(
            ['email' => 'seller@campusbazar.test'],
            [
                'name' => 'Rahim Uddin',
                'phone' => '01711111111',
                'password' => Hash::make('password'),
                'role' => UserRole::STUDENT,
                'campus_id' => $campus?->id,
                'verified_at' => now(),
                'status' => 'active',
            ],
        );

        $seller->verificationRequests()->create([
            'full_name' => 'Rahim Uddin',
            'institution_name' => $campus?->name ?? 'University of Dhaka',
            'department' => 'Computer Science',
            'student_id_number' => 'ID-2024-001',
            'academic_year' => '2024-2025',
            'front_image_path' => 'verification/demo/front.jpg',
            'status' => 'approved',
            'reviewer_id' => $admin->id,
            'reviewed_at' => now(),
        ]);

        // Unverified demo buyer.
        User::updateOrCreate(
            ['email' => 'student@campusbazar.test'],
            [
                'name' => 'Fatema Akter',
                'phone' => '01722222222',
                'password' => Hash::make('password'),
                'role' => UserRole::STUDENT,
                'campus_id' => $campus?->id,
                'verified_at' => null,
                'status' => 'active',
            ],
        );

        // A small set of demo listings owned by the verified seller.
        if ($categories->isNotEmpty()) {
            $samples = [
                ['title' => 'Scientific Calculator (Casio fx-991EX)', 'category' => 'Calculators', 'price' => 1200, 'condition' => ProductCondition::LIKE_NEW],
                ['title' => 'Engineering Mathematics Textbook', 'category' => 'Books and Study Materials', 'price' => 350, 'condition' => ProductCondition::GOOD],
                ['title' => 'Laptop Backpack - Water Resistant', 'category' => 'Clothing and Accessories', 'price' => 950, 'condition' => ProductCondition::GOOD],
                ['title' => 'Table Fan - Hostel Use', 'category' => 'Hostel Essentials', 'price' => 1500, 'condition' => ProductCondition::GOOD],
            ];

            foreach ($samples as $sample) {
                $category = $categories->firstWhere('name', $sample['category']) ?? $categories->first();

                Product::updateOrCreate(
                    ['title' => $sample['title']],
                    [
                        'user_id' => $seller->id,
                        'category_id' => $category->id,
                        'campus_id' => $campus?->id,
                        'slug' => Product::generateSlug($sample['title']),
                        'description' => 'Gently used, in ' . ProductCondition::label($sample['condition']) . ' condition. Perfect for students. Contact via CampusBazar messaging.',
                        'price' => $sample['price'],
                        'condition' => $sample['condition'],
                        'status' => ProductStatus::ACTIVE,
                        'approved_at' => now(),
                    ],
                );
            }
        }
    }
}
