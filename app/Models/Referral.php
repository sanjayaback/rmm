<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model {
    use HasFactory;
    protected $fillable = ['referrer_id','referred_id','status','reward_amount','reward_type','rewarded_at'];
    protected $casts    = ['reward_amount'=>'decimal:2','rewarded_at'=>'datetime'];
    public function referrer() { return $this->belongsTo(User::class, 'referrer_id'); }
    public function referred() { return $this->belongsTo(User::class, 'referred_id'); }
    public function markRewarded(): void { $this->update(['status'=>'rewarded','rewarded_at'=>now()]); }
}
