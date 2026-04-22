<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->paginate(10);
        return view();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['email', 'nullable', 'unique:employees'],
            'opening_balance' => ['nullable', 'numeric'],
            'opening_advance_balance' => ['nullable', 'numeric'],
            'basic_salary' => ['required', 'numeric'],
        ]);

        $result = Employee::create([
            'name' => $request->name,
            'opening_balance' => $request->opening_balance,
            'balance' => $request->opening_balance,
            'opening_advance_balance' => $request->opening_advance_balnce,
            'advance_balance' => $request->opening_advance_balnce,
            'basic_salary' => $request->basic_salary,
            'email' => $request->email,
            'phone' => $request->phone,
            'joining_date' => $request->joining_date,
            'address' => $request->address,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Employee Added Successfully']);
        }
        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }

    public function update(Employee $employee, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['email', 'nullable', Rule::unique('employees')->ignore($employee->id)],
            'basic_salary' => ['required', 'numeric'],
        ]);

        $result = $employee->update([
            'name' => $request->name,
            'basic_salary' => $request->basic_salary,
            'email' => $request->email,
            'phone' => $request->phone,
            'joining_date' => $request->joining_date,
            'address' => $request->address,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Employee Updated Successfully']);
        }
        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }
}
