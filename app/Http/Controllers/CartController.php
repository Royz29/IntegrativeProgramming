<?php

namespace App\Http\Controllers;

use App\Repositories\CartRepository;
use DOMImplementation;
use Illuminate\Http\Request;
use XSLTProcessor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index()
    {
        $carts = $this->cartRepository->getAll();
        return view('products.cart', compact('carts'));
    }

    public function updateQuantity(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $result = $this->cartRepository->updateQuantity($id, $request->input('quantity'));

        if (!$result) {
            return redirect()->back()->with(['success', 'The quantity exceeds the available stock.']);
        }

        return redirect()->route('cart.index')->with('success', 'Cart item updated successfully.');
    }

    public function destroy($id)
    {
        $this->cartRepository->delete($id);

        return redirect()->route('cart.index')->with('success', 'Cart item deleted successfully.');
    }

    public function checkout()
    {
        $userId = Auth::user()->id;

        $carts = $this->cartRepository->getAll();

        if ($carts->count() > 0) {
            $xmlFilePath = public_path('receipt.xml');

            // Load existing XML file if it exists
            if (file_exists($xmlFilePath)) {
                $xml = new \DOMDocument();
                $xml->load($xmlFilePath);
                $cart = $xml->getElementsByTagName('cart')->item(0);
            } else {
                // Create new XML file with root element
                $xml = new \DOMDocument();
                $xml->formatOutput = true;

                // Create the doctype node and link it with the DTD file
                $impl = new DOMImplementation();
                $dtd = $impl->createDocumentType('cart', '', 'receipt.dtd');
                $xml->appendChild($dtd);

                $cart = $xml->createElement('cart');
                $xml->appendChild($cart);
            }

            // Create XML structure for cart items
            $user = Auth::user();
            $totalPrice = 0;
            foreach ($carts as $cartItem) {
                $item = $xml->createElement('item');
                $item->appendChild($xml->createElement('userId', $user->id));
                $item->appendChild($xml->createElement('product_id', $cartItem->product_id));
                $item->appendChild($xml->createElement('name', $cartItem->name));
                $item->appendChild($xml->createElement('quantity', $cartItem->quantity));
                $item->appendChild($xml->createElement('image', $cartItem->image));
                $item->appendChild($xml->createElement('price', $cartItem->price));
                $item->appendChild($xml->createElement('created_at', $cartItem->created_at));

                //Calculate total price and price per unit
                $itemTotalPrice = $cartItem->quantity * $cartItem->price;
                $itemPricePerUnit = $cartItem->price;
                if ($cartItem->quantity > 1) {
                    $itemPricePerUnit = $itemTotalPrice / $cartItem->quantity;
                }

                $item->appendChild($xml->createElement('total_price', $itemTotalPrice));
                $item->appendChild($xml->createElement('price_per_unit', $itemPricePerUnit));

                $totalPrice += $itemTotalPrice;
                $cart->appendChild($item);
            }

            // Save XML to file
            $xml->save($xmlFilePath);

            session()->flash('error', 'Payment Done');

            // Clear the cart table by user id
            $this->cartRepository->deleteByUserId($userId);

            return redirect()->route('products.product');
            //     return redirect()->route('cart.receipt', ['xmlFilePath' => $xmlFilePath]);
            // } else {
            // return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        } else {
            session()->flash('error', 'Your cart is empty.');
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }
    }
}
