<?php

$permissions = [
    'Admin' => [
        
        'user_view', 'user_add', 'user_edit', 'user_delete',
        'employee_view', 'employee_add', 'employee_edit', 'employee_delete',
        
        'department_view', 'department_add', 'department_edit', 'department_delete',
        'position_view', 'position_add', 'position_edit', 'position_delete',
        'attendance_view', 'attendance_manage',
        'leave_view', 'leave_approve',
        'salary_view', 'salary_manage',
        'reward_view', 'reward_manage',
        'dashboard_view'
    ],
    
    'Manager' => [
        
        'employee_view', 
        
        'department_view',
        'attendance_view',
        'leave_view', 'leave_approve', 
        'dashboard_view' 
    ],
    
    'Employee' => [
      
        'employee_view_own', 
        'attendance_checkinout',
        'leave_request',
        'salary_view_own',
        'reward_view_own'
    ]
];

if (!function_exists('hasPermission')) {
    function hasPermission($role, $permission) {
        global $permissions;
        
        if (isset($permissions[$role])) {
           
            return in_array($permission, $permissions[$role]);
        }
        
        return false;
    }
}