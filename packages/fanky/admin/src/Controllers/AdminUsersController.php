<?php namespace Fanky\Admin\Controllers;
use Fanky\Admin\Models\AdminUser;
use Request;
use Validator;
use DB;

class AdminUsersController extends AdminController {

	public function getIndex()
	{
		$users = AdminUser::all();

		return view('admin::users.main', ['users' => $users]);
	}

	public function postEdit($id = null)
	{
		if (!$id || !($user = AdminUser::findOrFail($id))) {
			$user = new AdminUser;
		}

		return view('admin::users.edit', ['user' => $user]);
	}

	public function postSave()
	{
		$id = Request::input('id');
		$data = Request::only(['name', 'email', 'role', 'username']);
		$password = Request::input('password');
		if ($password) $data['password'] = $password;

		// валидация данных
		$validator = Validator::make(
		    $data,
		    [
		    	'username' => 'required',
		    ]
		);
		if ($validator->fails()) {
			return ['errors' => $validator->messages()];
		}

		// сохраняем страницу
		$user = AdminUser::find($id);
		if (!$user) {
			$data['status'] = 1;
			$user = AdminUser::create($data);
		} else {
			$user->update($data);
		}

		return ['success' => true, 'id' => $user->id, 'row' => view('admin::users.user_row', ['item' => $user])->render()];
	}

	public function postDelete($id)
	{
		$user = AdminUser::findOrFail($id);
		$user->delete();

		return ['success' => true];
	}
}
