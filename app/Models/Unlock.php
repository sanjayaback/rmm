<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unlock extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id','listing_id','amount_paid','payment_method','transaction_id',
        'khalti_token','khalti_idx','payment_status','paid_at','payment_response',
    ];
    protected $casts = ['amount_paid'=>'decimal:2','paid_at'=>'datetime','payment_response'=>'array'];
    public function user()    { return $this->belongsTo(User::class); }
    public function listing() { return $this->belongsTo(Listing::class); }
    public function isCompleted(): bool { return $this->payment_status === 'completed'; }
    public function markCompleted(array $data = []): void {
        $this->update(['payment_status'=>'completed','paid_at'=>now(),'payment_response'=>$data]);
    }
}
