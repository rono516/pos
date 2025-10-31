<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return response(
                Supplier::all()
            );
        }
        $suppliers = Supplier::latest()->paginate(10);
        return view('purchaseorders.index')->with('suppliers', $suppliers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Supplier $supplier)
    {
        $products = Product::all();
        return view('purchaseorders.create', compact('products', 'supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'items.*.product_id'   => 'nullable|exists:products,id',
            'items.*.product_name' => 'string',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_price'   => 'nullable|numeric|min:0',
        ]);

        $purchase_order = PurchaseOrder::create([
            'supplier_id'  => $request->supplier_id,
            'fulfilled'    => false,
            'total_amount' => 0,
        ]);

        $total = 0;

        foreach ($request->items as $item) {
            $isNew     = is_null($item['product_id']);
            $productId = $item['product_id'];

            $subTotal = $item['quantity'] * $item['unit_price'];
            $total += $subTotal;

            if ($isNew) {
                do {
                    $barcode = random_int(100000, 999999);
                } while (Product::where('barcode', $barcode)->exists());
                $product = Product::create([
                    'name'       => $item['product_name'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['unit_price'],
                    'totalprice' => $subTotal,
                    'status'     => false,
                    'barcode'    => $barcode,

                ]);
                $productId = $product->id;
            }

            $purchase_order->items()->create([
                'product_id'     => $productId,
                'product_name'   => $item['product_name'],
                'quantity'       => $item['quantity'],
                'unit_price'     => $item['unit_price'],
                'sub_total'      => $subTotal,
                'is_new_product' => $isNew,
            ]);
        }

        $purchase_order->update(['total_amount' => $total]);

        return redirect()->route('purchaseorders.index')->with('success', 'Purchase order created successfully!');
    }
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'supplier_id'          => 'required|exists:suppliers,id',
    //         'items'                => 'required|array|min:1',
    //         'items.*.product_id'   => 'nullable|exists:products,id',
    //         'items.*.product_name' => 'required_if:items.*.product_id,null|string',
    //         'items.*.quantity'     => 'required|integer|min:1',
    //         'items.*.unit_price'   => 'required_if:items.*.product_id,null|numeric|min:0',
    //     ]);

    //     DB::transaction(function () use ($data) {

    //         // Create purchase order
    //         $purchaseOrder = PurchaseOrder::create([
    //             'supplier_id'  => $data['supplier_id'],
    //             'fulfilled'    => false,
    //             'total_amount' => 0,
    //         ]);

    //         $total = 0;

    //         foreach ($data['items'] as $item) {
    //             $isNew     = is_null($item['product_id']);
    //             $productId = $item['product_id'];

    //             // Calculate subtotal safely
    //             $subTotal = $item['quantity'] * ($item['unit_price'] ?? 0);
    //             $total += $subTotal;

    //             // Create product if needed
    //             if ($isNew) {
    //                 $barcode = $this->generateUniqueBarcode();

    //                 $product = Product::create([
    //                     'name'       => $item['product_name'],
    //                     'quantity'   => $item['quantity'],
    //                     'price'      => $item['unit_price'],
    //                     'totalprice' => $subTotal,
    //                     'status'     => false,
    //                     'barcode'    => $barcode,
    //                 ]);

    //                 $productId = $product->id;
    //             }

    //             // Save purchase order item
    //             $purchaseOrder->items()->create([
    //                 'product_id'     => $productId,
    //                 'product_name'   => $item['product_name'] ?? null,
    //                 'quantity'       => $item['quantity'],
    //                 'unit_price'     => $item['unit_price'] ?? null,
    //                 'sub_total'      => $subTotal,
    //                 'is_new_product' => $isNew,
    //             ]);
    //         }

    //         // Update total
    //         $purchaseOrder->update(['total_amount' => $total]);
    //     });

    //     return redirect()
    //         ->route('purchaseorders.index')
    //         ->with('success', 'Purchase order created successfully!');
    // }

/**
 * Generate a unique random barcode (6 digits)
 */
    private function generateUniqueBarcode()
    {
        do {
            $barcode = random_int(100000, 999999);
        } while (Product::where('barcode', $barcode)->exists());

        return $barcode;
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
