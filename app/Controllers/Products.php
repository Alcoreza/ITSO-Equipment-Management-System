<?php
namespace App\Controllers;

class Products extends BaseController
{
    public function index()
    {
        $productModel = model('Products_model');

        $data = array(
            'title' => 'Aling Basyang\'s Sisigan - Products List',
            'products' => $productModel->findAll()
        );

        return view('include\head_view', $data)
            . view('include\nav_view')
            . view('productslist_view', $data)
            . view('include\foot_view');
    }

    public function add()
    {
        $data = array(
            'title' => 'Aling Basyang\'s Sisigan - Add New Product',
        );

        return view('include\head_view', $data)
            . view('include\nav_view')
            . view('productsadd_view')
            . view('include\foot_view');
    }

    public function insert()
    {
        $productModel = model('Products_model');

        // Handle image upload
        $imageFile = $this->request->getFile('image');
        $imageName = 'default.png';

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'public/img', $imageName);
        }

        // Insert product data
        $data = [
            'product_name' => $this->request->getPost('product_name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'category' => $this->request->getPost('category'),
            'image' => $imageName,
        ];

        $productModel->insert($data);

        return redirect()->to('products');
    }

    public function view($id)
    {
        $productModel = model('Products_model');

        $data = array(
            'title' => 'Aling Basyang\'s Sisigan - View Product',
            'product' => $productModel->find($id)
        );

        return view('include\head_view', $data)
            . view('include\nav_view')
            . view('productsview_view', $data)
            . view('include\foot_view');
    }

    public function edit($id)
    {
        $productModel = model('Products_model');

        $data = array(
            'title' => 'Aling Basyang\'s Sisigan - Edit Product',
            'product' => $productModel->find($id)
        );

        return view('include\head_view', $data)
            . view('include\nav_view')
            . view('productsedit_view', $data)
            . view('include\foot_view');
    }

    public function update($id)
    {
        $productModel = model('Products_model');

        // Get the uploaded file and old image name
        $imageFile = $this->request->getFile('image');
        $oldImage = $this->request->getPost('old_image');

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'public/img', $newName);
            $imageName = $newName;

            if (!empty($oldImage) && $oldImage !== 'default.png') {
                $oldPath = FCPATH . 'public/img/' . $oldImage;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        } else {
            $imageName = $oldImage;
        }

        // Update data
        $data = [
            'product_name' => $this->request->getPost('product_name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'category' => $this->request->getPost('category'),
            'image' => $imageName,
        ];

        $productModel->update($id, $data);

        return redirect()->to('products')->with('success', 'Product updated successfully!');
    }

    public function delete($id)
    {
        $productModel = model('Products_model');
        $productModel->delete($id);
        return redirect()->to('products');
    }
}
