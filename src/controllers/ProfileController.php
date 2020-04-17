<?php

namespace Abs\AuthPkg;
use Abs\EmployeePkg\Employee;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use File;
use Illuminate\Http\Request;
use Storage;
use Validator;

class ProfileController extends Controller {

	public function __construct() {
		$this->data['theme'] = config('custom.admin_theme');
	}

	public function profile(Request $request) {
		// dd(Auth::user()->id);
		$profile = User::with([
			'employee',
			'profileImage',
			'employee.designation',
		])
			->where('users.id', Auth::user()->id)
			->first();
		$profile->password_change = 'No';

		$this->data['profile_detail'] = $profile;
		// dd($this->data['profile_detail']);

		return response()->json($this->data);
	}

	public function updateProfile(Request $request) {
		// dd($request->all());
		try {
			$error_messages = [
				'personal_email.unique' => 'Personal Email is already taken',
				'alternate_mobile_number.max' => 'Alternate Mobile Number is Maximum 10 Charachers',
				'github_username.max' => 'Github Username is Maximum 64 Charachers',
			];
			$validator = Validator::make($request->employee, [
				'personal_email' => [
					'nullable',
					'min:3',
					'max:64',
					'unique:employees,personal_email,' . $request->entity_id . ',id,company_id,' . Auth::user()->company_id,
				],
				'alternate_mobile_number' => 'nullable|max:10',
				'github_username' => 'nullable|max:64',
			], $error_messages);
			if ($validator->fails()) {
				return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
			}
			$user_error_messages = [
				'mobile_number.required' => 'Mobile Number is Required',
				'mobile_number.min' => 'Mobile Number is Minimum 10 Charachers',
				'mobile_number.max' => 'Mobile Number is Maximum 10 Charachers',
				'mobile_number.unique' => 'Mobile Number is already taken',
			];
			$user_validator = Validator::make($request->all(), [
				'mobile_number' => [
					'nullable',
					'min:10',
					'max:10',
					'unique:users,mobile_number,' . $request->id . ',id',
				],
			], $user_error_messages);
			if ($user_validator->fails()) {
				return response()->json(['success' => false, 'errors' => $user_validator->errors()->all()]);
			}

			DB::beginTransaction();

			$employee = Employee::where('id', Auth::user()->entity_id)->update([
				'personal_email' => $request->employee['personal_email'],
				'alternate_mobile_number' => $request->employee['alternate_mobile_number'],
				'github_username' => $request->employee['github_username'],
			]);

			$user = User::find($request->id);

			$user->update([
				'mobile_number' => $request->mobile_number,
				'updated_by_id' => Auth::user()->id,
			]);

			//Profile Attachment
			$user_images_des = storage_path('app/public/user-profile-images/');
			//dump($user_images_des);
			Storage::makeDirectory($user_images_des, 0777);
			if (!empty($request['attachment'])) {
				if (!File::exists($user_images_des)) {
					File::makeDirectory($user_images_des, 0777, true);
				}
				$remove_previous_attachment = Attachment::where([
					'entity_id' => $user->id,
					'attachment_of_id' => 120,
					'attachment_type_id' => 140,
				])->first();
				if (!empty($remove_previous_attachment)) {
					$img_path = $user_images_des . $remove_previous_attachment->name;
					if (File::exists($img_path)) {
						File::delete($img_path);
					}
					$remove = $remove_previous_attachment->forceDelete();
				}
				$extension = $request['attachment']->getClientOriginalExtension();
				$request['attachment']->move(storage_path('app/public/user-profile-images/'), $user->id . '.' . $extension);
				$user_attachement = new Attachment;
				$user_attachement->company_id = Auth::user()->company_id;
				$user_attachement->attachment_of_id = 120; //ATTACHMENT OF EMPLOYEE
				$user_attachement->attachment_type_id = 140; //ATTACHMENT TYPE  EMPLOYEE
				$user_attachement->entity_id = $user->id;
				$user_attachement->name = $user->id . '.' . $extension;
				$user_attachement->save();
				$user->profile_image_id = $user_attachement->id;
				$user->save();

			}

			DB::commit();

			return response()->json(['success' => true, 'message' => 'Profile Updated Successfully']);

		} catch (Exceprion $e) {
			DB::rollBack();
			return response()->json([
				'success' => false,
				'error' => $e->getMessage(),
			]);
		}
	}
}
