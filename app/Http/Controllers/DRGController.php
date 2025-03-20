<?php

namespace App\Http\Controllers;

use App\Models\Drg;
use App\Models\Machine;
use Illuminate\Http\Request;

class DRGController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function kanbanMachine()
    {
        $machines = Machine::with('drgs')->get(); // Récupère les machines avec leurs DRGs
        $unassignedDrgs = Drg::whereNull('machine_id')->get(); // Récupère les DRGs sans machine
    
        return view('kanban-machine', [
            'machines' => $machines,
            'unassignedDrgs' => $unassignedDrgs, // Ajouter les DRGs sans machine
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function kanbanStatut()
    {
        $drgs = Drg::all(); // Récupère les DRGs sans machine
    
        return view('kanban-statut', [
            'drgs' => $drgs, // Ajouter les DRGs sans machine
        ]);
    }
    
    public function getDrgsByStatus()
    {
        $drgs = Drg::all(); // Récupère tous les DRGs

        return response()->json($drgs);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function planned()
    {
        return view('planned');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tobeplanned()
    {
        return view('tobeplanned');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function cut()
    {
        return view('cut');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete()
    {
        return view('delete');
    }

    public function index()
    {
        $drgs = Drg::with('machine')->get();
        return view('drgs.index', compact('drgs'));
    }

    public function create()
    {
        $machines = Machine::all();
        return view('drgs.create', compact('machines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'drg_name' => 'required|string|max:255',
            'file_path' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'thickness' => 'required|numeric',
            'sheet_qty' => 'required|integer',
            'unit_time' => 'required|numeric',
            'machine_id' => 'nullable|exists:machines,id',
        ]);

        Drg::create($validated);
        return redirect()->route('drgs.index')->with('success', 'Drg ajouté avec succès.');
    }

    public function show(Drg $drg)
    {
        return view('drgs.show', compact('drg'));
    }

    public function edit(Drg $drg)
    {
        $machines = Machine::all();
        return view('drgs.edit', compact('drg', 'machines'));
    }

    public function update(Request $request, Drg $drg)
    {
        $validated = $request->validate([
            'drg_name' => 'required|string|max:255',
            'file_path' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'thickness' => 'required|numeric',
            'sheet_qty' => 'required|integer',
            'unit_time' => 'required|numeric',
            'machine_id' => 'nullable|exists:machines,id',
        ]);

        $drg->update($validated);
        return redirect()->route('drgs.index')->with('success', 'Drg mis à jour avec succès.');
    }

    /**
     * Remove the specified Drg resource from storage.
     *
     * @param  \App\Models\Drg  $drg
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Drg $drg)
    {
        $drg->delete();
        return redirect()->route('drgs.index')->with('success', 'Drg supprimé avec succès.');
    }

}
