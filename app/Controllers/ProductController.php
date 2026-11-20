<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    // List all products
    public function index()
    {
        $data['products'] = $this->productModel->orderBy('id', 'DESC')->findAll();
        return view('products/index', $data);
    }

    // Show Add Product form
    public function create()
    {
        return view('products/create');
    }

    // Store new product + handle image upload
    public function store()
    {
        $rules = [
            'name'           => 'required|min_length[2]|max_length[100]',
            'price'          => 'required|decimal|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image'          => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageName = null;
        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $imageName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $imageName);
        }

        $this->productModel->insert([
            'name'           => $this->request->getPost('name'),
            'price'          => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'image'          => $imageName,
        ]);

        return redirect()->to('/products')->with('success', 'Product added successfully.');
    }

    // Show Edit Product form
    public function edit($id)
    {
        $product = $this->productModel->find($id);
        if (! $product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        return view('products/edit', ['product' => $product]);
    }

    // Update product + replace image if a new one is uploaded
    public function update($id)
    {
        $product = $this->productModel->find($id);
        if (! $product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $rules = [
            'name'           => 'required|min_length[2]|max_length[100]',
            'price'          => 'required|decimal|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image'          => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageName = $product['image'];
        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            // Delete old image file if it exists
            if ($imageName && file_exists(FCPATH . 'uploads/products/' . $imageName)) {
                unlink(FCPATH . 'uploads/products/' . $imageName);
            }
            $imageName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $imageName);
        }

        $this->productModel->update($id, [
            'name'           => $this->request->getPost('name'),
            'price'          => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'image'          => $imageName,
        ]);

        return redirect()->to('/products')->with('success', 'Product updated successfully.');
    }

    // Delete product + remove image file
    public function delete($id)
    {
        $product = $this->productModel->find($id);
        if ($product) {
            if ($product['image'] && file_exists(FCPATH . 'uploads/products/' . $product['image'])) {
                unlink(FCPATH . 'uploads/products/' . $product['image']);
            }
            $this->productModel->delete($id);
            return redirect()->to('/products')->with('success', 'Product deleted successfully.');
        }

        return redirect()->to('/products')->with('error', 'Product not found.');
    }
}