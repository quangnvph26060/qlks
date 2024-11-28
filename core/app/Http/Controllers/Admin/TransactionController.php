<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        try {
            $transactions = $this->transactionService->getAllTransaction();
            if (request()->ajax()) {
                $view = view('admin.transaction.table', compact('transactions'))->render();
                return response()->json(['success' => true, 'table' => $view]);
            }
            return view('admin.transaction.index', compact('transactions'));
        } catch (Exception $e) {
            Log::error('Failed to get pagianted Transaction list: ' . $e->getMessage());
        }
    }

    public function add()
    {
        return view('admin.transaction.add');
    }

    public function store(Request $request)
    {
        try {
            $transaction = $this->transactionService->addNewTransactionMethod($request->all());
            session()->flash('success', 'Thêm phương thức thanh toán thành công');
            return redirect()->route('admin.transaction.index');
        } catch (Exception $e) {
            Log::error('Failed to create new Transaction: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Thêm phương thức thanh toán thất bại'
            ], 500);
        }
    }



    public function edit($id)
    {
        try {
            $transactions = $this->transactionService->findTransactionById($id);
            return view('admin.transaction.edit', compact('transactions'));
        } catch (Exception $e) {
            Log::error('Failed to find transaction information: ' . $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $transactions = $this->transactionService->updateTransaction($request->all(), $id);
            session()->flash('success', 'Cập nhật phương thức thanh toán thành công');
            return redirect()->route('admin.transaction.index');
        } catch (Exception $e) {
            Log::error("Failed to update Transaction method: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Cập nhật phương thức thanh toán thất bại'
            ], 500);
        }
    }
    public function delete($id)
    {
        Log::info("Đã nhận được yêu cầu xóa với ID: " . $id);
        try {
            $this->transactionService->deleteTransaction($id);
            return redirect()->route('admin.transaction.index');
        } catch (Exception $e) {
            Log::error('Failed to delete transaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Xóa phương thức thanh toán thất bại'
            ]);
        }
    }


    public function changeTransactionStatus($id)
    {
        DB::beginTransaction(); // Nên bắt đầu một transaction để đảm bảo tính toàn vẹn dữ liệu
        try {
            $transaction = $this->transactionService->findTransactionById($id);
            $transaction->status = $transaction->status == 1 ? 0 : 1;
            $transaction->save(); // Lưu lại thay đổi trạng thái

            DB::commit(); // Xác nhận transaction nếu không có lỗi
            return redirect()->route('admin.transaction.index')->with('success', 'Trạng thái đã thay đổi thành công');
        } catch (Exception $e) {
            DB::rollback(); // Hoàn tác transaction nếu có lỗi
            Log::error("Failed to change this transaction's status: " . $e->getMessage());
            return redirect()->route('admin.transaction.index')->with('error', 'Failed to change status.');
        }
    }
}
