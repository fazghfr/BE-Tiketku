<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Seat;
use App\Models\Transaction;

class TicketController extends Controller
{
    public SeatController $seatController;
    public UsersController $userController;

    public function __construct(SeatController $seatController, UsersController $userController)
    {
        $this->seatController = $seatController;
        $this->userController = $userController;
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ticket
        ]);
    }

    public function index_by_email(Request $request)
    {
        $user = $this->userController->get_user_by_email($request->email);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        // get transactions by user id
        $transaction = Transaction::where('users_id', $user->id)->get();

        // karena bisa multiple transactions, for each transaction id get all tickets
        $tickets = [];
        foreach ($transaction as $trans) {
            $ticket = Ticket::where('transactions_id', $trans->id)->get();
            array_push($tickets, $ticket);
        }

        return response()->json([
            'status' => 'success',
            'data' => $tickets
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NIK' => 'required',
            'cust_name' => 'required',
            'price_each' => 'required',
            'transactions_id' => 'required',
            'cars_id' => 'required',
            'trips_id' => 'required',
            'is_chose_seat' => 'required|boolean',
            'seats_id' => 'sometimes|nullable|exists:seats,id'
        ]);

        $seatId = null;

        if ($request->is_chose_seat == false) {
            // calling function to get random seat from seatcontroller
            $seat = $this->seatController->choose_random($request, $request->trips_id);
            return response()->json([
                'status' => 'success',
                'data' => $seat
            ]);
            $seatId = $seat->id;
        } else {
            // logic to assign seat based on user input
            $seat = $this->seatController->choose($request, $request->trips_id, $request->seats_id);

            $seatId = $seat->id;
        }

        $ticketData = array_merge($validatedData, ['seats_id' => $seatId]);
        $ticket = Ticket::create($ticketData);

        return response()->json([
            'status' => 'success',
            'data' => $ticket
        ]);
    }

    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found'
            ], 404);
        }

        // find the seat associated with the ticket
        $seat = Seat::where('id', $ticket->seats_id)->first();
        if ($seat) {
            $seat->update([
                'is_taken' => false
            ]);
        }

        $ticket->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket deleted'
        ]);
    }
}
