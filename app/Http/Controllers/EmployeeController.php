<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Notifications\NewEmployeeAdded;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index(Request $request)
    {
        $employees = Employee::with('company')
            ->when($request->search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Employees/Index', [
            'employees' => $employees,
            'filters' => $request->only(['search'])
        ]);
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        
        return Inertia::render('Employees/Create', [
            'companies' => $companies
        ]);
    }

    /**
     * Store a newly created employee
     */
    public function store(StoreEmployeeRequest $request)
    {
        // Create employee
        $employee = Employee::create($request->validated());
        
        // Load the company relationship
        $employee->load('company');
        
        // Send notification to company if email exists
        if ($employee->company && $employee->company->email) {
            $employee->company->notify(new NewEmployeeAdded($employee, $employee->company));
        }

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified employee
     */
    public function show(Employee $employee)
    {
        $employee->load('company');
        
        return Inertia::render('Employees/Show', [
            'employee' => $employee
        ]);
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit(Employee $employee)
    {
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        
        return Inertia::render('Employees/Edit', [
            'employee' => $employee,
            'companies' => $companies
        ]);
    }

    /**
     * Update the specified employee
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
