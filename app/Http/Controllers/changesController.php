<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Projects;
use App\Models\Change;
use App\Models\BasicInfo;

class changesController extends Controller
{
    // переход на страницу создание реализации
    public function create()
    {
        return view('add-changes');
    }

    // Отображение данных из расчета
    public function showDataCalculation($id)
    {
        $project = Projects::with('basicInfo')->find($id);
        return view('add-changes', compact('project'));
    }

    // сохранение данных
    public function storeNew($id, Request $request)
    {
        // поиск связанной карты проекта
        $project = Projects::find($id);

        if ($request->has('changes')) {
            $data_changes = array();
            foreach ($request->input('changes') as $index => $ChangesData) {
                $item = array(
                    'project_num' => $project->projNum,
                    'contractor' => $ChangesData['contractor'],
                    'contract_num' => $ChangesData['contract_num'],
                    'change' => $ChangesData['change'],
                    'impact' => $ChangesData['impact'],
                    'stage' => $ChangesData['stage'],
                    'corrective' => $ChangesData['corrective'],
                    'responsible' => $ChangesData['responsible']
                );
                array_push($data_changes, $item);
            }
            Change::insert($data_changes);
        }
        return redirect()->route('project-data-one');
    }


    public function delete($id)
    {
        $change = Change::find($id);

        if (!$change) {
            return response()->json(['message' => 'Change record not found'], 404);
        }

        $projectId = $change->project_id; 

        $change->delete();

        return response()->json(['projectId' => $projectId]);
    }


    public function update(Request $request, $id)
    {
        $change = Change::find($id);


        $change->contractor = $request->input('contractor');
        $change->contract_num = $request->input('contract_num');
        $change->change = $request->input('change');
        $change->impact = $request->input('impact');
        $change->stage = $request->input('stage');
        $change->corrective = $request->input('corrective');
        $change->responsible = $request->input('responsible');

        // Save the changes
        $change->save();

        // Get the project ID from the change record
        $projectId = $change->project_num;

        return redirect()->route('project-data-one', ['id' => $projectId]);
    }
}
