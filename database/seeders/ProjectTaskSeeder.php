<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ProjectTaskSeeder extends Seeder
{
    public function run()
    {
        // Create users with different roles
        $users = [
            [
                'name' => 'Alex Johnson',
                'email' => 'alex.johnson@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'James Wilson',
                'email' => 'james.wilson@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Chen',
                'email' => 'sarah.chen@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'David Kim',
                'email' => 'david.kim@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        $createdUsers = [];
        foreach ($users as $user) {
            $createdUsers[] = User::create($user);
        }

        // Project 1: Website Redesign
        $websiteRedesign = Project::create([
            'owner_id' => $createdUsers[0]->id, // Alex owns this project
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Corporate Website Redesign',
            'slug' => 'corporate-website-redesign',
            'summary' => 'Complete overhaul of the company website with modern design and improved UX',
            'description' => 'This project involves redesigning our corporate website to improve user experience, modernize the look and feel, and implement better content organization.',
            'status' => 'active',
            'is_featured' => true,
            'starts_at' => Carbon::now()->subDays(15),
            'ends_at' => Carbon::now()->addDays(45),
            'budget' => 25000.00,
            'progress' => 35,
            'meta' => ['tags' => ['design', 'frontend', 'marketing']],
            'settings' => ['color' => '#3b82f6', 'notifications' => true]
        ]);

        // Tasks for Website Redesign with assignments
        $websiteTasks = [
            [
                'title' => 'Conduct user research',
                'description' => 'Interview 20 customers about their experience with current website',
                'status' => 'completed',
                'priority' => 'high',
                'progress' => 100,
                'starts_at' => Carbon::now()->subDays(14),
                'due_at' => Carbon::now()->subDays(7),
                'completed_at' => Carbon::now()->subDays(5),
                'order_column' => 1,
                'assignments' => [
                    ['user_id' => $createdUsers[1]->id, 'role' => 'researcher'], // Maria
                    ['user_id' => $createdUsers[3]->id, 'role' => 'assistant']    // Sarah
                ]
            ],
            [
                'title' => 'Create wireframes',
                'description' => 'Develop low-fidelity wireframes for all key pages',
                'status' => 'completed',
                'priority' => 'high',
                'progress' => 100,
                'starts_at' => Carbon::now()->subDays(10),
                'due_at' => Carbon::now()->subDays(3),
                'completed_at' => Carbon::now()->subDays(2),
                'order_column' => 2,
                'assignments' => [
                    ['user_id' => $createdUsers[2]->id, 'role' => 'designer'],   // James
                ]
            ],
            [
                'title' => 'Design homepage mockup',
                'description' => 'Create high-fidelity design for homepage with 3 variants',
                'status' => 'in_progress',
                'priority' => 'high',
                'progress' => 80,
                'starts_at' => Carbon::now()->subDays(5),
                'due_at' => Carbon::now()->addDays(2),
                'order_column' => 3,
                'assignments' => [
                    ['user_id' => $createdUsers[2]->id, 'role' => 'lead designer'],  // James
                    ['user_id' => $createdUsers[4]->id, 'role' => 'reviewer']        // David
                ]
            ],
            [
                'title' => 'Develop responsive navigation',
                'description' => 'Implement mobile-friendly navigation menu',
                'status' => 'pending',
                'priority' => 'medium',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(3),
                'due_at' => Carbon::now()->addDays(10),
                'order_column' => 4,
                'assignments' => [
                    ['user_id' => $createdUsers[3]->id, 'role' => 'developer'],   // Sarah
                ]
            ],
        ];

        foreach ($websiteTasks as $taskData) {
            $task = $websiteRedesign->tasks()->create(collect($taskData)->except('assignments')->toArray());

            // Assign users to the task
            foreach ($taskData['assignments'] as $assignment) {
                $task->users()->attach($assignment['user_id'], ['role' => $assignment['role']]);
            }
        }

        // Project 2: Mobile App Development
        $mobileApp = Project::create([
            'owner_id' => $createdUsers[1]->id, // Maria owns this project
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Customer Mobile App',
            'slug' => 'customer-mobile-app',
            'summary' => 'Development of iOS and Android app for our customers',
            'description' => 'This project will deliver a cross-platform mobile application that allows customers to access their accounts, make purchases, and receive personalized recommendations.',
            'status' => 'active',
            'is_featured' => false,
            'starts_at' => Carbon::now()->subDays(30),
            'ends_at' => Carbon::now()->addDays(90),
            'budget' => 120000.00,
            'progress' => 15,
            'meta' => ['tags' => ['mobile', 'development', 'react']],
            'settings' => ['color' => '#10b981', 'notifications' => true]
        ]);

        // Tasks for Mobile App with assignments
        $mobileTasks = [
            [
                'title' => 'Set up development environment',
                'description' => 'Configure React Native, CI/CD pipeline, and testing tools',
                'status' => 'completed',
                'priority' => 'high',
                'progress' => 100,
                'starts_at' => Carbon::now()->subDays(28),
                'due_at' => Carbon::now()->subDays(20),
                'completed_at' => Carbon::now()->subDays(18),
                'order_column' => 1,
                'assignments' => [
                    ['user_id' => $createdUsers[3]->id, 'role' => 'devops'],    // Sarah
                    ['user_id' => $createdUsers[4]->id, 'role' => 'developer']   // David
                ]
            ],
            [
                'title' => 'Implement authentication flow',
                'description' => 'Develop login, registration, and password recovery screens',
                'status' => 'in_progress',
                'priority' => 'high',
                'progress' => 60,
                'starts_at' => Carbon::now()->subDays(10),
                'due_at' => Carbon::now()->addDays(5),
                'order_column' => 2,
                'assignments' => [
                    ['user_id' => $createdUsers[0]->id, 'role' => 'frontend'],   // Alex
                    ['user_id' => $createdUsers[4]->id, 'role' => 'backend']    // David
                ]
            ],
            [
                'title' => 'Design product catalog screen',
                'description' => 'Create UI for browsing and searching products',
                'status' => 'pending',
                'priority' => 'medium',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(7),
                'due_at' => Carbon::now()->addDays(14),
                'order_column' => 3,
                'assignments' => [
                    ['user_id' => $createdUsers[2]->id, 'role' => 'designer'], // James
                ]
            ],
        ];

        foreach ($mobileTasks as $taskData) {
            $task = $mobileApp->tasks()->create(collect($taskData)->except('assignments')->toArray());

            foreach ($taskData['assignments'] as $assignment) {
                $task->users()->attach($assignment['user_id'], ['role' => $assignment['role']]);
            }
        }

        // Project 3: Office Relocation
        $officeMove = Project::create([
            'owner_id' => $createdUsers[4]->id, // David owns this project
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'New Office Setup',
            'slug' => 'new-office-setup',
            'summary' => 'Relocate company headquarters to new downtown location',
            'description' => 'This project involves planning and executing the move of our company headquarters from the current location to the new downtown office space.',
            'status' => 'planned',
            'is_featured' => false,
            'starts_at' => Carbon::now()->addDays(30),
            'ends_at' => Carbon::now()->addDays(90),
            'budget' => 75000.00,
            'progress' => 5,
            'meta' => ['tags' => ['facilities', 'logistics']],
            'settings' => ['color' => '#f59e0b', 'notifications' => false]
        ]);

        // Tasks for Office Relocation with assignments
        $officeTasks = [
            [
                'title' => 'Finalize lease agreement',
                'description' => 'Review and sign contract for new office space',
                'status' => 'in_progress',
                'priority' => 'critical',
                'progress' => 90,
                'starts_at' => Carbon::now()->subDays(10),
                'due_at' => Carbon::now()->addDays(5),
                'order_column' => 1,
                'assignments' => [
                    ['user_id' => $createdUsers[4]->id, 'role' => 'manager'],   // David
                    ['user_id' => $createdUsers[0]->id, 'role' => 'legal']      // Alex
                ]
            ],
            [
                'title' => 'Plan office layout',
                'description' => 'Design seating arrangements and common areas',
                'status' => 'pending',
                'priority' => 'medium',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(15),
                'due_at' => Carbon::now()->addDays(25),
                'order_column' => 2,
                'assignments' => [
                    ['user_id' => $createdUsers[1]->id, 'role' => 'planner'],   // Maria
                ]
            ],
        ];

        foreach ($officeTasks as $taskData) {
            $task = $officeMove->tasks()->create(collect($taskData)->except('assignments')->toArray());

            foreach ($taskData['assignments'] as $assignment) {
                $task->users()->attach($assignment['user_id'], ['role' => $assignment['role']]);
            }
        }
    }
}
