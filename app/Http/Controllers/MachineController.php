<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::all();
        return view('machines.index', compact('machines'));
    }

    public function create()
    {
        return view('machines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:machines',
            'name' => 'required',
            'type' => 'required',
            'capacity' => 'required|integer',
            'hourly_rate' => 'required|numeric',
            'zone_x' => 'required|numeric',
            'zone_y' => 'required|numeric',
            'color' => 'required|unique:machines',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if($request->file('image')){
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $request->image->move(public_path('images/machines'), $filename);
        }
        else{
            $filename = null;
        }
        
        Machine::create(array_merge($validated, ['image' => $filename]));

        return redirect()->route('machines.index')->with('success', 'Machine ajoutée avec succès');
    }

    public function show(Machine $machine)
    {
        return view('machines.show', compact('machine'));
    }

    public function edit(Machine $machine)
    {
        return view('machines.edit', compact('machine'));
    }

    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'code' => 'required|unique:machines,code,' . $machine->id,
            'name' => 'required',
            'type' => 'required',
            'capacity' => 'required|integer',
            'hourly_rate' => 'required|numeric',
            'zone_x' => 'required|numeric',
            'zone_y' => 'required|numeric',
            'color' => 'required|unique:machines',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if($request->file('image')){
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $request->image->move(public_path('images/machines'), $filename);
            $machine->update(array_merge($validated, ['image' => $filename]));
        }
        else{
            $machine->update($validated);
        }

        return redirect()->route('machines.index')->with('success', 'Machine mise à jour avec succès');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();
        return redirect()->route('machines.index')->with('success', 'Machine supprimée avec succès');
    }
    public function toggleMachineStatus($id)
    {
        $machine = Machine::find($id);
    
        if ($machine->is_active) {
            $machine->deactivate();
        } else {
            $machine->activate();
        }
    
        return redirect()->back()->with('status', 'Machine status updated!');
    }

    public function getMachineLoad(Request $request)
    {
        // Date range for the planning (either daily or weekly)
        $startDate = $request->input('start_date', now()->startOfWeek()); // default start of week
        $endDate = $request->input('end_date', now()->endOfWeek()); // default end of week

        // Fetch machines with their DRGs for the given date range
        $machines = Machine::active()->with(['drgs' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])->get();

        // Prepare data for each machine
        $machineData = $machines->map(function ($machine) {
            return [
                'machine' => $machine->name,
                'drgs' => $machine->drgs->map(function ($drg) {
                    return [
                        'drg_name' => $drg->drg_name,
                        'total_time' => $drg->TotalTime(),
                        'remaining_time' => $drg->RemaningTotalTime(),
                        'advancement' => $drg->Advencemnt(),
                        'created_at' => $drg->created_at->format('Y-m-d'), // group by day
                    ];
                }),
            ];
        });

        return response()->json($machineData);
    }
}
