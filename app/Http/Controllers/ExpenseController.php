<?php

namespace App\Http\Controllers;
use App\Expense;
use App\Location;
use Illuminate\Http\Request;

use RealRashid\SweetAlert\Facades\Alert;

class ExpenseController extends Controller
{
    //
    public function index()
    {
        $locations = auth()->user()->locations;
        $locationIds = $locations->pluck('id');
        $locations_d = Location::whereIn('id',$locationIds)->get();
        $expenses = Expense::whereIn('location_id', $locationIds)->latest()->get();
        return view('expenses.index', array('expenses' => $expenses,
        'locations' => $locations_d
        
    ));
    }
     public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls|max:2048',
            'remarks' => 'nullable|string',
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/expenses'), $filename);
            $validated['attachment'] = $filename;
        }

        $expense = new Expense();
        $expense->name = $request->name;
        $expense->reference_number = $request->reference_number;
        $expense->date = $request->date;
        $expense->location_id = $request->location;
        $expense->amount = $request->amount;
        $expense->remarks = $request->remarks;
        $expense->payment_type = $request->type;
        $expense->attachment = $validated['attachment'] ?? null;
        $expense->user_id = auth()->user()->id ?? null; // optional if you want to track who added it
        $expense->save();
        Alert::success('Expense added successfully.')->persistent('Dismiss');
        return back();
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls|max:2048',
            'remarks' => 'nullable|string',
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/expenses'), $filename);
            $expense->attachment = $filename;
        }

        $expense->name = $request->name;
        $expense->reference_number = $request->reference_number;
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->remarks = $request->remarks;
        $expense->save();

        Alert::success('Expense updated successfully.')->persistent('Dismiss');
        return back();
    }
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        Alert::success('Expense deleted successfully.')->persistent('Dismiss');
        return back();
    }
}
