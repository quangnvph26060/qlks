<?php

namespace App\Services;

use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
    public function getPaginatedTransaction()
    {
        try {
            return $this->transaction->orderByDesc('created_at')->paginate(10);
        } catch (Exception $e) {
            Log::error('Failed to get paginated transaction: ' . $e->getMessage());
            throw new Exception("Failed to get paginated transactions");
        }
    }

    public function getAllTransaction()
    {
        try {
            return $this->transaction->orderByDesc('created_at')->get();
        } catch (Exception $e) {
            Log::error('Failed to get all Transaction methods: ' . $e->getMessage());
            throw new Exception('Failed to get all transaction methods');
        }
    }

    public function findTransactionById($id)
    {
        try {
            return $this->transaction->find($id);
        } catch (Exception $e) {
            Log::error("Failed to find this transaction method: " . $e->getMessage());
            throw new Exception("Failed to find this transaction method");
        }
    }

    public function addNewTransactionMethod(array $data)
    {
        DB::beginTransaction(); // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu

        try {
            Log::info('Creating new transaction with data: ' . json_encode($data));

            $transaction = $this->transaction->create([
                'name' => $data['name'],
                'status' => 1,
            ]);

            Log::info('Transaction created successfully with ID: ' . $transaction->id);

            DB::commit(); // Commit transaction
            return $transaction;
        } catch (Exception $e) {
            DB::rollBack(); // Rollback transaction nếu có lỗi
            Log::error('Failed to add new transaction method: ' . $e->getMessage());
            throw new Exception("Failed to add new transaction method");
        }
    }


    public function updateTransaction(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $transaction = $this->transaction->find($id);
            if (!$transaction) {
                throw new Exception('Transaction method not found');
            }

            $transaction->update([
                'name' => $data['name'],
            ]);
            DB::commit();
            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update transaction: " . $e->getMessage());
            throw new Exception("Failed to update transaction");
        }
    }

    public function deleteTransaction($id)
    {
        DB::beginTransaction();
        try {
            $transaction  = $this->transaction->find($id);
            if (!$transaction) {
                throw new Exception("Không tìm thấy phương thức thanh toán với ID này");
            }
            $transaction->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete this transaction: ' . $e->getMessage());
            throw new Exception("Failed to delete this transaction");
        }
    }
}
