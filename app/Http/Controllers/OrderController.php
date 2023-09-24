<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the robot name.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the robot name in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'robot_name' => 'required|string|max:255',
        ]);

        $order->robot_name = $request->input('robot_name');
        $order->save();

        return redirect()->route('orders.show', ['order' => $order])
            ->with('success', 'Robot name updated successfully.');
    }

    /**
     * Search for orders based on the provided query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Check if the query is a numeric value (potential ID match)
        if (is_numeric($query)) {
            // If it's a numeric value, search for an exact ID match
            $orders = Order::where('id', $query)->get();
        } else {
            // If it's not numeric, perform a partial search on customer_name and robot_name
            $orders = Order::where('customer_name', 'like', '%' . $query . '%')
                ->orWhere('robot_name', 'like', '%' . $query . '%')
                ->get();
        }

        return view('orders.index', compact('orders'));
    }
}
