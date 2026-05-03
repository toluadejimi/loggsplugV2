<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CouponCode;
use App\Models\Order;
use App\Models\Product;
use App\Constants\Status;
use App\Models\ProductDetail;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{



    public function all()
    {
        $pageTitle = 'All Products';
        $products = Product::searchable(['name', 'category:name'])->filter(['category_id'])->orderBy('id', 'DESC')->with('category', 'productDetails')->paginate(getPaginate());
        return view('admin.product.all', compact('pageTitle', 'products'));
    }

    public function form($id = null)
    {

        $pageTitle = 'Add Product';
        $formAction = route('admin.product.add');

        if ($id) {
            $pageTitle = 'Update Product';
            $formAction = route('admin.product.update');
        }

        $categories = Category::active()->get();
        $product = Product::find($id);

        return view('admin.product.form', compact('pageTitle', 'categories', 'product', 'formAction'));
    }

    public function add()
    {

        $this->formSubmit();

        $notify[] = ['success', 'Product added successfully'];
        return back()->withNotify($notify);
    }

    public function update()
    {

        $this->formSubmit(update: true);

        $notify[] = ['success', 'Product updated successfully'];
        return back()->withNotify($notify);
    }

    private function formSubmit($update = false)
    {

        $request = request();
        $rule = [
            'category_id' => 'required|integer',
            'name' => 'required',
            'price' => 'required|numeric|gt:0',
            'description' => 'nullable'
        ];

        if ($update) {
            $rule['id'] = 'required|integer';
            $rule['file'] = ['nullable', new FileTypeValidate(['txt'])];
            $rule['image'] = ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])];
        } else {
            $rule['file'] = ['required', new FileTypeValidate(['txt'])];
            $rule['image'] = ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])];
        }

        $request->validate($rule);
        $category = Category::active()->findOrFail($request->category_id);

        if ($update) {
            $product = Product::findOrFail($request->id);
        } else {
            $product = new Product;
        }

        $purifier = new \HTMLPurifier();
        $description = htmlspecialchars_decode($purifier->purify($request->description));

        $product->category_id = $category->id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $description;

        if ($request->hasFile('image')) {
            try {
                $old = $product->image;
                $product->image = fileUploader($request->image, getFilePath('product'), getFileSize('product'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $product->save();

        if ($request->hasFile('file')) {
            $this->importProductDetailsFromTxt($request->file('file'), $product->id);
        }

        return $product;
    }

    /**
     * Stream the uploaded .txt and insert rows in batches (one INSERT per batch, not one per line).
     */
    private function importProductDetailsFromTxt(\Illuminate\Http\UploadedFile $file, int $productId): void
    {
        $path = $file->getRealPath();
        if ($path === false) {
            $path = $file->path();
        }

        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new \RuntimeException('Could not read uploaded stock file.');
        }

        $now = now();
        $batchSize = 500;
        $batch = [];
        $hasIsSold = Schema::hasColumn('product_details', 'is_sold');
        $hasUserId = Schema::hasColumn('product_details', 'user_id');

        try {
            while (($line = fgets($handle)) !== false) {
                $line = rtrim($line, "\r\n");
                if ($line === '') {
                    continue;
                }

                $row = [
                    'product_id' => $productId,
                    'details' => $line,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                if ($hasUserId) {
                    $row['user_id'] = 0;
                }
                if ($hasIsSold) {
                    $row['is_sold'] = Status::NO;
                }

                $batch[] = $row;

                if (count($batch) >= $batchSize) {
                    DB::table('product_details')->insert($batch);
                    $batch = [];
                }
            }

            if (count($batch) > 0) {
                DB::table('product_details')->insert($batch);
            }
        } finally {
            fclose($handle);
        }
    }

    public function status($id)
    {
        return Product::changeStatus($id);
    }

    public function downloadDemoTxt()
    {

        $text = 'Username:username | Password:username';
        $content = "$text\n$text\n";
        $filename = 'demo_format.txt';

        $headers = [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Type' => 'text/plain',
        ];

        return new Response($content, 200, $headers);
    }

    public function accounts($id)
    {
        $product = Product::findOrFail($id);
        $pageTitle = strLimit($product->name, 50) . " - In Stock($product->in_stock)";
        $accounts = ProductDetail::where('product_id', $product->id)->searchable(['details'])->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.product.account', compact('pageTitle', 'accounts'));
    }

    public function deleteAccount($id)
    {

        $accountDetails = ProductDetail::findOrFail($id);
        $accountDetails->delete();

        $notify[] = ['success', 'Account deleted successfully'];
        return back()->withNotify($notify);
    }


    public function deleteOrder($id)
    {
        $accountDetails = Order::findOrFail($id);
        $accountDetails->delete();

        $notify[] = ['success', 'Order deleted successfully'];
        return back()->withNotify($notify);
    }


    public function delete($id)
    {

        $product = Product::findOrFail($id);
        $accountDetails = ProductDetail::where('product_id', $id);
        $accountDetails->delete();
        $product->delete();

        $notify[] = ['success', 'Product deleted successfully'];
        return back()->withNotify($notify);
    }

    public function updateAccount(Request $request)
    {

        $request->validate([
            'id' => 'required'
        ]);

        $accountDetails = ProductDetail::findOrFail($request->id);
        $accountDetails->details = $request->details;
        $accountDetails->save();

        $notify[] = ['success', 'Account details updated successfully'];
        return back()->withNotify($notify);
    }
}
