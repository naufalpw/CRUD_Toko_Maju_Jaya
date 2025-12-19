<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\ActivityLog; 

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman Dashboard utama.
     */
    public function index()
    {
        // --- 1. DATA GRAFIK (7 HARI) ---
        $dates = [];
        $incomes = [];
        $expenses = [];
    
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('Y-m-d'); 
            $dates[] = $date->format('d M');
        
            $incomes[] = Transaction::whereDate('created_at', $dateString)->sum('total_price');
            $expenses[] = Expense::whereDate('date', $dateString)->sum('amount');
        }
    
        // --- 2. HITUNG LABA BERSIH (Pemasukan - Pengeluaran) ---
        
        // A. HARI INI
        $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('total_price');
        $todayExp   = Expense::whereDate('date', Carbon::today())->sum('amount');
        $todayProfit = $todaySales - $todayExp; // Bisa minus
    
        // B. MINGGU INI
        $startWeek = Carbon::now()->startOfWeek();
        $endWeek   = Carbon::now()->endOfWeek();
        $weekSales = Transaction::whereBetween('created_at', [$startWeek, $endWeek])->sum('total_price');
        $weekExp   = Expense::whereBetween('date', [$startWeek, $endWeek])->sum('amount');
        $weekProfit = $weekSales - $weekExp;
    
        // C. BULAN INI
        $monthSales = Transaction::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('total_price');
        $monthExp   = Expense::whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount');
        $monthProfit = $monthSales - $monthExp;
    
        // D. TAHUN INI
        $yearSales = Transaction::whereYear('created_at', Carbon::now()->year)->sum('total_price');
        $yearExp   = Expense::whereYear('date', Carbon::now()->year)->sum('amount');
        $yearProfit = $yearSales - $yearExp;
    
        // --- 3. DATA LIST ---
        $latestExpenses = Expense::latest()->take(5)->get();
        $logs = ActivityLog::with(['transaction.details.product'])->latest()->take(20)->get();
    
        return view('dashboard.index', compact(
            'dates', 'incomes', 'expenses', 'latestExpenses', 'logs',
            'todayProfit', 'weekProfit', 'monthProfit', 'yearProfit' // Kirim variabel profit/laba
        ));
    }

    /**
     * Menyimpan data pengeluaran baru.
     */
    public function storeExpense(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        Expense::create([
            'name' => $request->name,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }
}