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
        // Récupérer les DRGs sans machine
        $drgsWithoutMachine = Drg::whereNull('machine_id')->get();
        
        // Récupérer les machines et leurs DRGs associés
        $machines = Machine::with('drgs')->get();
        
        // Passer les variables à la vue
        return view('kanban-machine', compact('drgsWithoutMachine', 'machines'));
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

    /**
     * Get all DRGs that are not assigned to any machine.
     *
     * This method retrieves all DRGs where the 'machine_id' is null,
     * indicating that they are not currently assigned to any machine.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDrgsWithoutMachine()
    {
        return Drg::whereNull('machine_id')->get();
    }

    /**
     * Assigns or removes a machine from the specified DRG.
     *
     * This method validates the incoming request to ensure that the 'machine_id' is either null or exists in the 'machines' table.
     * If the validation passes, it assigns the machine to the DRG or removes it if 'machine_id' is null.
     * The updated DRG is then saved to the database.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing the 'machine_id'.
     * @param \App\Models\Drg $drg The DRG instance to which the machine will be assigned or removed.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated DRG.
     */
    public function assignMachine(Request $request, Drg $drg)
    {
        $validated = $request->validate([
            'machine_id' => 'nullable|exists:machines,id',
        ]);

        // Assigner ou supprimer la machine du DRG
        $drg->machine_id = $validated['machine_id'];
        $drg->save();

        return response()->json($drg);
    }

    /**
     * Display a listing of the machines with their assigned DRGs.
     *
     * This method retrieves all machines along with their associated DRGs
     * using Eloquent's `with` method to eager load the relationships.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function indexKanban()
    {
        return Machine::with('drgs')->get(); // Inclut les DRGs assignés à chaque machine
    }
    
}
