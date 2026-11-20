<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;

class SaleController extends BaseController
{
    protected $saleModel;
    protected $productModel;
    protected $customerModel;

    public function __construct()
    {
        $this->saleModel     = new SaleModel();
        $this->productModel  = new ProductModel();$this->customerModel = new CustomerModel();
    }

    // List all past sales transactions
    public function index()
    {
        $sales =$this->saleModel
            ->select('sales.*, products.name AS product_name, customers.full_name AS customer_name, users.full_name AS staff_name')
            ->join('products', 'products.id = sales.product_id')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('users', 'users.id = sales.sold_by')
            ->orderBy('sales.id', 'DESC')
            ->findAll();

        return view('sales/index', ['sales' => $sales]);
    }

    // Show Record Sale form
    public function create()
    {
        $data = [
            'products'  => $this->productModel->orderBy('name', 'ASC')->findAll(),
            'customers' => $this->customerModel->orderBy('full_name', 'ASC')->findAll(),
        ];

        return view('sales/create', $data);
    }

    // Process and record the sale
    public function store()
    {
        $rules = [
            'product_id'  => 'required|integer|is_not_unique[products.id]',
            'customer_id' => 'permit_empty|integer',
            'quantity'    => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productId  = (int)$this->request->getPost('product_id');
        $customerId =$this->request->getPost('customer_id') ?: null;
        $quantity   = (int)$this->request->getPost('quantity');

        if ($customerId !== null && ! $this->customerModel->find($customerId)) {
            return redirect()->back()->withInput()->with('error', 'Selected customer does not exist.');
        }

        $product = $this->productModel->find($productId);
        if (! $product) {
            return redirect()->back()->withInput()->with('error', 'Selected product does not exist.');
        }

        $availableStock = (int)$product['stock_quantity'];
        if ($quantity >$availableStock) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Insufficient stock for \"{$product['name']}\". Requested: {$quantity}, Available: {$availableStock}.");
        }

        $totalPrice = (float) $product['price'] *$quantity;
        $soldBy     = (int) session()->get('user_id');

        $db = \Config\Database::connect();$db->transBegin();

        try {
            $this->saleModel->insert([
                'product_id'  => $productId,
                'customer_id' => $customerId,
                'sold_by'     => $soldBy,
                'quantity'    => $quantity,
                'total_price' => $totalPrice,
            ]);

            $this->productModel->update($productId, [
                'stock_quantity' => $availableStock -$quantity,
            ]);

            if ($db->transStatus() === false) {$db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Failed to record sale. Please try again.');
            }

            $db->transCommit();
            return redirect()->to('/sales')->with('success', 'Sale recorded! Total: ₱' . number_format($totalPrice, 2) . '.');
        } catch (\Throwable $e) {$db->transRollback();
            return redirect()->back()->withInput()->with('error', 'An error occurred while recording the sale.');
        }
    }

    // Delete (void) a sale and restore the product's stock_quantity
    public function delete($id)
    {
        $sale = $this->saleModel->find($id);
        if (! $sale) {
            return redirect()->to('/sales')->with('error', 'Sale record not found.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $product = $this->productModel->find($sale['product_id']);
            if ($product) {
                $this->productModel->update($product['id'], [
                    'stock_quantity' => (int) $product['stock_quantity'] + (int) $sale['quantity'],
                ]);
            }

            $this->saleModel->delete($id);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to('/sales')->with('error', 'Failed to delete sale record.');
            }

            $db->transCommit();
            return redirect()->to('/sales')->with('success', 'Sale record deleted and product stock restored.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/sales')->with('error', 'An error occurred while deleting the sale.');
        }
    }
    
}