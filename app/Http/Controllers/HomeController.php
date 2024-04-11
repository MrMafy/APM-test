<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\ProjectManager;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show user's profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = Auth::user();
        $groups = $user->groups()->get(); // Получаем все группы пользователя
        return view('profile', compact('groups'));
    }


    /**
     * Update user's profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        // Update user's profile data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Update user's password
        $user = Auth::user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }



    // Метод для получения списка руководителей проектов
    public function getProjectManagers()
    {
        $projectManagers = ProjectManager::all();
        return response()->json($projectManagers);
    }

    // Метод для удаления руководителя проекта
    public function deleteProjectManager($id)
    {
        $projectManager = ProjectManager::findOrFail($id);
        $projectManager->delete();
        return response()->json(['message' => 'Project manager deleted successfully']);
    }

// Метод для сохранения изменений в руководителе проекта
    public function saveProjectManager(Request $request)
    {
        // Получаем данные из запроса
        $data = $request->all();

        // Находим руководителя проекта по ID
        $projectManager = ProjectManager::findOrFail($data['id']);

        // Обновляем данные руководителя проекта
        $projectManager->fio = $data['fio'];
        $projectManager->groupNum = $data['groupNum'];

        // Сохраняем изменения
        $projectManager->save();

        // Возвращаем успешный ответ
        return response()->json(['message' => 'Project manager updated successfully']);
    }
// Метод для добавления нового руководителя проекта
    public function addProjectManager(Request $request)
    {
        // Получаем данные из запроса
        $data = $request->all();

        // Создаем нового руководителя проекта
        $projectManager = new ProjectManager();
        $projectManager->fio = $data['fio'];
        $projectManager->groupNum = $data['groupNum'];
        $projectManager->save();

        // Возвращаем успешный ответ
        return response()->json(['message' => 'Project manager added successfully']);
    }

// Метод для обновления руководителя проекта
    public function editPM(Request $request)
    {
        // Получаем данные из запроса
        $data = $request->all();

        // Находим руководителя проекта по ID
        $projectManager = ProjectManager::findOrFail($data['editPmId']);

        // Обновляем данные руководителя проекта
        $projectManager->fio = $data['fio'];
        $projectManager->groupNum = $data['groupNum'];
        $projectManager->save();

        // Возвращаем успешный ответ
        return response()->json(['message' => 'Project manager updated successfully']);
    }



}
