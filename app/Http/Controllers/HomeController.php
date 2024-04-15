<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\ProjectManager;
use App\Models\User;
class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function profile()
    {
        $user = Auth::user();
        $users = User::with('groups')->get(); // Получаем всех пользователей с их группами
        $groups = $user->groups()->get(); // Получаем все группы пользователя
        return view('profile', compact('groups', 'users'));
    }

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


    public function changeUser(Request $request)
    {
//        dd($request->all());
        $user = Auth::user();

        // Обновление имени пользователя
        $user->name = $request->input('user_name');
        $user->save();

        // Обработка групп пользователя
        if ($request->has('user_group')) {
            $groupIds = $request->input('user_group');
            $user->groups()->sync($groupIds);
        }

        // Удаление групп пользователя, если были выбраны опции удаления
        if ($request->has('deleted_group')) {
            $groupIdsToDelete = explode(',', $request->input('deleted_group'));
            if (!empty($groupIdsToDelete)) { // Проверяем, что массив не пустой
                $user->groups()->detach($groupIdsToDelete);
            }
        }

        // После обновления перенаправьте пользователя на нужную страницу или верните ответ JSON
        return redirect()->back()->with('success', 'Профиль пользователя успешно обновлен!');
    }

}
