<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    use HasFactory;
    protected $fillable = ['user_id','listing_id','move_in_date','move_out_date','monthly_rent','status','message','owner_notes','confirmed_at'];
    protected $casts    = ['move_in_date'=>'date','move_out_date'=>'date','monthly_rent'=>'decimal:2','confirmed_at'=>'datetime'];
    public function user()    { return $this->belongsTo(User::class); }
    public function listing() { return $this->belongsTo(Listing::class); }
}
