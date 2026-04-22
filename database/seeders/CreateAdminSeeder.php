<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'employee_id' => null,
            ]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // List all permissions
        $permissions = [
            'general.category',
            'general.location',
            'general.measuring_unit',
            'general.manage_tables',
            'general.manage_deals',
            'general.manage_recipes',
            'product.create_product',
            'product.product',
            'product.product_variant',
            'product.trashed',
            'product.print_variants_barcode',
            'product.low_stock_list',
            'stock_management.stock_adjustment',
            'stock_management.transfer_stock',
            'stock_management.transfer_stock_list',
            'stock_management.trashed_transfer_stock_list',
            'stock_management.purchase',
            'stock_management.purchase_return',
            'stock_management.purchase_list',
            'sale.sale',
            'sale.sale_list',
            'party',
            'accounts.accounts_list',
            'accounts.payments_receiving',
            'accounts.capital_management',
            'accounts.balance_sheet',
            'accounts.trial_balance_sheet',
            'accounts.date_wise_profit_margin',
            'expense.expense_list',
            'expense.categories',
            'expense.sub_categories',
            'user_management.user_list',
            'user_management.employees',
            'user_management.trashed_employees',
            'pos_closing.today_invoices',
            'pos_closing.pos_closing',
            'pos_closing.day_book',
            'pos_closing.datewise_day_book',
            'reports.stock_report',
            'reports.sale_report',
            'reports.purchase_report',
            'reports.expanse_report',
            'reports.payments_recv_report',
            'reports.ledgers_reports',
            'reports.summary_reports',
            'reports.party_statements',
        ];

        // Assign all permissions to Admin role
        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission]);
            $adminRole->givePermissionTo($perm);
        }

        // Assign a subset of permissions to User role (example subset)
        // $userPermissions = [
        //     'product.product',
        //     'sale.sale',
        //     'party',
        //     'reports.sale_report',
        // ];
        // foreach ($userPermissions as $permission) {
        //     $perm = Permission::firstOrCreate(['name' => $permission]);
        //     $userRole->givePermissionTo($perm);
        // }

        if (!$user->hasRole($adminRole->name)) {
            $user->assignRole($adminRole);
        }
    }
}
