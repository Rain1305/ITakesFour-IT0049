<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomerController extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    // List all customers
    public function index()
    {
        $data['customers'] = $this->customerModel->orderBy('id', 'DESC')->findAll();
        return view('customers/index', $data);
    }

    // Show Add Customer form
    public function create()
    {
        return view('customers/create');
    }

    // Store new customer
    public function store()
    {
        $rules = [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'email'     => 'required|valid_email|max_length[100]',
            'phone'     => 'permit_empty|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customerModel->insert([
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ]);

        return redirect()->to('/customers')->with('success', 'Customer added successfully.');
    }

    // Show Edit Customer form
    public function edit($id)
    {
        $customer = $this->customerModel->find($id);
        if (! $customer) {
            return redirect()->to('/customers')->with('error', 'Customer not found.');
        }

        return view('customers/edit', ['customer' => $customer]);
    }

    // Update customer
    public function update($id)
    {
        $customer = $this->customerModel->find($id);
        if (! $customer) {
            return redirect()->to('/customers')->with('error', 'Customer not found.');
        }

        $rules = [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'email'     => 'required|valid_email|max_length[100]',
            'phone'     => 'permit_empty|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customerModel->update($id, [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ]);

        return redirect()->to('/customers')->with('success', 'Customer updated successfully.');
    }

    // Delete customer
    public function delete($id)
    {
        $customer = $this->customerModel->find($id);
        if ($customer) {
            try {
                $this->customerModel->delete($id);
                return redirect()->to('/customers')->with('success', 'Customer deleted successfully.');
            } catch (\Throwable $e) {
                return redirect()->to('/customers')->with('error', 'Cannot delete customer with existing sales records.');
            }
        }

        return redirect()->to('/customers')->with('error', 'Customer not found.');
    }
}