<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class ProjectTaskSeeder extends Seeder
{
    public function run()
    {
        // Project 1: Website Redesign
        $websiteRedesign = Project::create([
            'owner_id' => 1,
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Corporate Website Redesign',
            'slug' => 'corporate-website-redesign',
            'summary' => 'Complete overhaul of the company website with modern design and improved UX',
            'description' => 'This project involves redesigning our corporate website to improve user experience, modernize the look and feel, and implement better content organization. The new design should be mobile-first and align with our updated brand guidelines.',
            'status' => 'active',
            'is_featured' => true,
            'starts_at' => Carbon::now()->subDays(15),
            'ends_at' => Carbon::now()->addDays(45),
            'budget' => 25000.00,
            'progress' => 35,
            'meta' => ['tags' => ['design', 'frontend', 'marketing']],
            'settings' => ['color' => '#3b82f6', 'notifications' => true]
        ]);

        // Tasks for Website Redesign
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
                'order_column' => 1
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
                'order_column' => 2
            ],
            [
                'title' => 'Design homepage mockup',
                'description' => 'Create high-fidelity design for homepage with 3 variants',
                'status' => 'in_progress',
                'priority' => 'high',
                'progress' => 80,
                'starts_at' => Carbon::now()->subDays(5),
                'due_at' => Carbon::now()->addDays(2),
                'order_column' => 3
            ],
            [
                'title' => 'Develop responsive navigation',
                'description' => 'Implement mobile-friendly navigation menu',
                'status' => 'pending',
                'priority' => 'medium',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(3),
                'due_at' => Carbon::now()->addDays(10),
                'order_column' => 4
            ],
            [
                'title' => 'Content migration plan',
                'description' => 'Create strategy for migrating existing content to new structure',
                'status' => 'pending',
                'priority' => 'medium',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(5),
                'due_at' => Carbon::now()->addDays(12),
                'order_column' => 5
            ]
        ];

        foreach ($websiteTasks as $task) {
            $websiteRedesign->tasks()->create($task);
        }

        // Project 2: Mobile App Development
        $mobileApp = Project::create([
            'owner_id' => 1,
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Customer Mobile App',
            'slug' => 'customer-mobile-app',
            'summary' => 'Development of iOS and Android app for our customers',
            'description' => 'This project will deliver a cross-platform mobile application that allows customers to access their accounts, make purchases, and receive personalized recommendations. The app should be developed using React Native for code sharing between platforms.',
            'status' => 'active',
            'is_featured' => false,
            'starts_at' => Carbon::now()->subDays(30),
            'ends_at' => Carbon::now()->addDays(90),
            'budget' => 120000.00,
            'progress' => 15,
            'meta' => ['tags' => ['mobile', 'development', 'react']],
            'settings' => ['color' => '#10b981', 'notifications' => true]
        ]);

        // Tasks for Mobile App
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
                'order_column' => 1
            ],
            [
                'title' => 'Design app architecture',
                'description' => 'Create technical architecture diagram and component structure',
                'status' => 'completed',
                'priority' => 'high',
                'progress' => 100,
                'starts_at' => Carbon::now()->subDays(25),
                'due_at' => Carbon::now()->subDays(15),
                'completed_at' => Carbon::now()->subDays(14),
                'order_column' => 2
            ],
            [
                'title' => 'Implement authentication flow',
                'description' => 'Develop login, registration, and password recovery screens',
                'status' => 'in_progress',
                'priority' => 'high',
                'progress' => 60,
                'starts_at' => Carbon::now()->subDays(10),
                'due_at' => Carbon::now()->addDays(5),
                'order_column' => 3
            ],
            [
                'title' => 'API integration planning',
                'description' => 'Coordinate with backend team on API specifications',
                'status' => 'pending',
                'priority' => 'medium',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(2),
                'due_at' => Carbon::now()->addDays(10),
                'order_column' => 4
            ],
            [
                'title' => 'Design product catalog screen',
                'description' => 'Create UI for browsing and searching products',
                'status' => 'pending',
                'priority' => 'medium',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(7),
                'due_at' => Carbon::now()->addDays(14),
                'order_column' => 5
            ],
            [
                'title' => 'Set up analytics',
                'description' => 'Integrate Firebase Analytics and configure events',
                'status' => 'pending',
                'priority' => 'low',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(20),
                'due_at' => Carbon::now()->addDays(25),
                'order_column' => 6
            ]
        ];

        foreach ($mobileTasks as $task) {
            $mobileApp->tasks()->create($task);
        }

        // Project 3: Office Relocation
        $officeMove = Project::create([
            'owner_id' => 1,
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'New Office Setup',
            'slug' => 'new-office-setup',
            'summary' => 'Relocate company headquarters to new downtown location',
            'description' => 'This project involves planning and executing the move of our company headquarters from the current location to the new downtown office space. Includes physical move, IT setup, and employee orientation.',
            'status' => 'planned',
            'is_featured' => false,
            'starts_at' => Carbon::now()->addDays(30),
            'ends_at' => Carbon::now()->addDays(90),
            'budget' => 75000.00,
            'progress' => 5,
            'meta' => ['tags' => ['facilities', 'logistics']],
            'settings' => ['color' => '#f59e0b', 'notifications' => false]
        ]);

        // Tasks for Office Relocation
        $officeTasks = [
            [
                'title' => 'Finalize lease agreement',
                'description' => 'Review and sign contract for new office space',
                'status' => 'in_progress',
                'priority' => 'critical',
                'progress' => 90,
                'starts_at' => Carbon::now()->subDays(10),
                'due_at' => Carbon::now()->addDays(5),
                'order_column' => 1
            ],
            [
                'title' => 'Hire moving company',
                'description' => 'Get quotes from 3 vendors and select best option',
                'status' => 'pending',
                'priority' => 'high',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(10),
                'due_at' => Carbon::now()->addDays(20),
                'order_column' => 2
            ],
            [
                'title' => 'Plan office layout',
                'description' => 'Design seating arrangements and common areas',
                'status' => 'pending',
                'priority' => 'medium',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(15),
                'due_at' => Carbon::now()->addDays(25),
                'order_column' => 3
            ],
            [
                'title' => 'Coordinate IT infrastructure',
                'description' => 'Plan network setup, servers, and workstations',
                'status' => 'pending',
                'priority' => 'high',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(20),
                'due_at' => Carbon::now()->addDays(35),
                'order_column' => 4
            ],
            [
                'title' => 'Employee orientation sessions',
                'description' => 'Schedule tours and info sessions for all staff',
                'status' => 'pending',
                'priority' => 'medium',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(50),
                'due_at' => Carbon::now()->addDays(60),
                'order_column' => 5
            ],
            [
                'title' => 'Office warming party',
                'description' => 'Plan celebration event for employees and clients',
                'status' => 'pending',
                'priority' => 'low',
                'progress' => 0,
                'starts_at' => Carbon::now()->addDays(80),
                'due_at' => Carbon::now()->addDays(85),
                'order_column' => 6
            ]
        ];

        foreach ($officeTasks as $task) {
            $officeMove->tasks()->create($task);
        }
    }
}
