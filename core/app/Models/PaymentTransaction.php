<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
class PaymentTransaction extends Model
{
    use HasFactory;

    protected $table = 'payment_transactions';

    protected $fillable = [
        'payment_code',
        'receipts_and_payments_id',
        'amount',
        'payment_method',
        'note',
        'paid_at',
        'unit_code',
        'subdomain',
        'created_by',
        'status',
    ];
    protected $appends = ['status_badge'];
    public $timestamps = true;

    public function receiptAndPayment()
    {
        return $this->belongsTo(ReceiptAndPayment::class, 'receipts_and_payments_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function statusBadge(): Attribute
    {
        $className = 'badge badge--';
        if ($this->status == Status::DISABLE) {
            $className .= 'warning';
            $text = 'Đang xử lý';
        } elseif ($this->status == Status::ENABLE) {
            $className .= 'success';
            $text = 'Thành công';
        }
        return new Attribute(
            get: fn() => "<span class='badge badge--$className'>" . trans($text) . "</span>",
        );
    }
}
