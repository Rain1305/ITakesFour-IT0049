<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Services;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // List all staff members
    public function index()
    {
        $data['users'] = $this->userModel->orderBy('id', 'DESC')->findAll();
        return view('users/index', $data);
    }

    // Show Add Staff form
    public function create()
    {
        return view('users/create');
    }

    // Store new staff member + avatar preparation + hashed password
    public function store()
    {
        $rules = [
            'username'  => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'full_name' => 'required|min_length[2]|max_length[100]',
            'password'  => 'required|min_length[6]',
            'avatar'    => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $avatarName = null;
        $file = $this->request->getFile('avatar');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $avatarName = $file->getRandomName();
            $uploadPath = FCPATH . 'uploads/avatars/';
            $file->move($uploadPath, $avatarName);

            // Prepare display-ready 200x200 square avatar
            $this->prepareAvatar($uploadPath . $avatarName);
        }

        $this->userModel->insert([
            'username'  => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'avatar'    => $avatarName,
        ]);

        return redirect()->to('/users')->with('success', 'Staff account created successfully.');
    }

    // Show Edit Staff form
    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/users')->with('error', 'Staff member not found.');
        }

        return view('users/edit', ['user' => $user]);
    }

    // Update staff member
    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/users')->with('error', 'Staff member not found.');
        }

        $rules = [
            'username'  => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'full_name' => 'required|min_length[2]|max_length[100]',
            'password'  => 'permit_empty|min_length[6]',
            'avatar'    => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $avatarName = $user['avatar'];
        $file = $this->request->getFile('avatar');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/avatars/';
            if ($avatarName && file_exists($uploadPath . $avatarName)) {
                unlink($uploadPath . $avatarName);
            }
            $avatarName = $file->getRandomName();
            $file->move($uploadPath, $avatarName);

            // Prepare display-ready 200x200 square avatar
            $this->prepareAvatar($uploadPath . $avatarName);
        }

        $updateData = [
            'username'  => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'avatar'    => $avatarName,
        ];

        // Only re-hash and update password if a new one was entered
        $newPassword = $this->request->getPost('password');
        if (! empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $updateData);

        return redirect()->to('/users')->with('success', 'Staff account updated successfully.');
    }

    // Delete staff member
    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/users')->with('error', 'Staff member not found.');
        }

        // Prevent deleting the currently logged-in user
        if (session()->get('user_id') == $id) {
            return redirect()->to('/users')->with('error', 'You cannot delete your own active account.');
        }

        try {
            $this->userModel->delete($id);
            if ($user['avatar'] && file_exists(FCPATH . 'uploads/avatars/' . $user['avatar'])) {
                unlink(FCPATH . 'uploads/avatars/' . $user['avatar']);
            }
            return redirect()->to('/users')->with('success', 'Staff account deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->to('/users')->with('error', 'Cannot delete staff member with recorded sales.');
        }
    }

    // Resize/crop uploaded avatar to 200x200 for clean display
    private function prepareAvatar(string $filePath): void
    {
        try {
            Services::image()
                ->withFile($filePath)
                ->fit(200, 200, 'center')
                ->save($filePath);
        } catch (\Throwable $e) {
            // Keep original uploaded file if GD extension is disabled
        }
    }
}