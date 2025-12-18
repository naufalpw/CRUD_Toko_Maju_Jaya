<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Ambil 7 hari terakhir untuk grafik
        $startDate = Carbon::now()->subDays(6);
        $endDate = Carbon::now();

        $dates = [];
        $incomes = [];
        $expenses = [];

        // Loop dari 6 hari lalu sampai hari ini
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $currentDate = $date->format('Y-m-d');
            $dates[] = $date->format('d M'); // Label Tanggal (misal: 12 Aug)

            // Hitung total pemasukan hari itu
            $incomes[] = Transaction::whereDate('created_at', $currentDate)->sum('total_price');

            // Hitung total pengeluaran hari itu
            $expenses[] = Expense::whereDate('date', $currentDate)->sum('amount');
        }

        // 2. Ambil list pengeluaran terbaru untuk tabel di bawah grafik
        $latestExpenses = Expense::latest()->take(5)->get();

        return view('reports.index', compact('dates', 'incomes', 'expenses', 'latestExpenses'));
    }

    // Fungsi untuk menyimpan pengeluaran baru
    public function storeExpense(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Expense::create($request->all());

        return redirect()->route('reports.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }
}