<?php

namespace App\Http\Controllers;
use App\Models\User;  // Import the User model
use App\Models\Models\Employee\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::get();
        // dd($employees);
        return view('adminPanel.employee.add_employee', compact('employees'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'contact' => 'required|numeric|min:0',
            'has_login' => 'nullable|in:on,off', // Make sure the value is either 'on' or 'off'
            'password' => 'nullable|string|min:8', // Make sure the password is nullable and has a minimum length
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        DB::beginTransaction();
    
        try {
            // Handle 'has_login' checkbox: if checked, set value to 'on', else 'off'
            $hasLogin = $request->has('has_login') ? 'on' : 'off';
            
            $userId = null;
            // Create the employee
            $employee = Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'position' => $request->position,
                'salary' => $request->salary,
                'contact' => $request->contact,
                'has_login' => $hasLogin, // Store the value as 'on' or 'off'
                'user_id' => $userId,
            ]);
    
            // If 'has_login' is 'on' and password is provided, create a user
            if ($hasLogin === 'on' && $request->password) {
                // Validate the password length (optional, as it's already validated earlier)
                if (strlen($request->password) >= 8) {
                    // Create the user with a hashed password
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => bcrypt($request->password), // Encrypt the password
                        'employee_id' => $employee->id,  // Associate the user with the employee
                    ]);

                    $role = Role::where(['name' => 'user'])->first();
                    $user->assignRole($role);

                     $employee->user_id = $user->id;
                     $employee->save();
                } else {
                    // Return an error if the password is too short
                    return redirect()->back()->withErrors(['password' => 'Password must be at least 8 characters long.'])->withInput();
                }
            }
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Employee added successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback if there's an error
            return redirect()->back()->withErrors(['error' => 'Something went wrong!'])->withInput();
        }
    }

public function update(Request $request, $id)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:employees,email,' . $id, // Ignore the current employee's email for uniqueness
        'position' => 'required|string|max:255',
        'salary' => 'required|numeric|min:0',
        'contact' => 'required|numeric|min:0',
        'has_login' => 'nullable|in:on,off', // Ensure value is 'on' or 'off'
        'password' => 'nullable|string|min:8', // Password is optional but must meet length requirements
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        Log::info('Validation errors: ', $validator->errors()->toArray()); // Log validation errors
        return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {
        // Find the employee
        $employee = Employee::findOrFail($id);

        // Log the incoming request data for debugging
        Log::info('Updating employee with ID: ' . $id, $request->all());

        // Handle 'has_login' checkbox and check if the employee is linked to a user
        $hasLogin = $request->has('has_login') ? 'on' : 'off';

        // Update the employee details
        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'salary' => $request->salary,
            'contact' => $request->contact,
            'has_login' => $hasLogin,
        ]);

        // If the employee has a user and the 'has_login' checkbox is checked, update user details
        if ($hasLogin === 'on') {
            if ($employee->user_id) {
                // If the employee already has a user, update it
                $user = User::findOrFail($employee->user_id);
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

                // Update password if provided
                if ($request->filled('password')) {
                    $user->update(['password' => bcrypt($request->password)]);
                }
            } else {
                // If the employee doesn't have a user, create one if password is provided
                if ($request->filled('password')) {
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                        'employee_id' => $employee->id, // Link user to employee
                    ]);

                    // Link the user to the employee
                    $employee->user_id = $user->id;
                    $employee->save();
                }
            }
        } else {
            // If 'has_login' is 'off', remove associated user if exists
            if ($employee->user_id) {
                $user = User::find($employee->user_id);
                if ($user) {
                    $user->delete();
                }
                // Clear user_id on employee
                $employee->user_id = null;
                $employee->save();
            }
        }

        DB::commit();

        return redirect()->back()->with('success', 'Employee updated successfully!');
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback if there's an error
        \Log::error('Update failed for employee ID: ' . $id . ' - Error: ' . $e->getMessage()); // Log the error message
        return redirect()->back()->withErrors(['error' => 'Something went wrong!'])->withInput();
    }
}

        public function softdestroy($id)
        {
            DB::beginTransaction();

            try {
                $employee = Employee::findOrFail($id);
                $employee->delete(); // Soft delete

                DB::commit();

                // Return a JSON response for AJAX
                return response()->json(['message' => 'Employee deleted successfully!']);
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback if there's an error
                // Return error message for AJAX
                return response()->json(['error' => 'Something went wrong!'], 500);
            }
        }


        public function restore($id)
        {
            $employee = Employee::withTrashed()->find($id);

            if (!$employee) {
                return response()->json(['message' => 'Employee not found'], 404);
            }

            $employee->restore();

            return response()->json(['message' => 'Employee restored successfully.'], 200);
        }

        

        public function trashedEmployees()
        {
            // Fetch only soft-deleted employees
            $employees = Employee::onlyTrashed()->get();

            return view('adminPanel.employee.trashed_employees', compact('employees'));
        }


}
