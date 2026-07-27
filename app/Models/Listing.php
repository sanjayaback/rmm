<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id','title','description','price','city','area','exact_address','phone',
        'lat','lng','approx_lat','approx_lng','image_path','status','unlock_fee',
        'bedrooms','bathrooms','room_type','amenities','is_available','views','rejection_reason',
    ];

    protected $casts = [
        'price'=>'decimal:2','unlock_fee'=>'decimal:2',
        'lat'=>'decimal:7','lng'=>'decimal:7',
        'approx_lat'=>'decimal:7','approx_lng'=>'decimal:7',
        'amenities'=>'array','is_available'=>'boolean',
    ];

    // Relationships
    public function owner()   { return $this->belongsTo(User::class, 'user_id'); }
    public function unlocks() { return $this->hasMany(Unlock::class); }
    public function bookings(){ return $this->hasMany(Booking::class); }

    // Scopes
    public function scopeApproved($q) { return $q->where('status','approved'); }
    public function scopeAvailable($q){ return $q->where('is_available', true); }
    public function scopePublic($q)   { return $q->approved()->available(); }

    public function scopeForMap($q) {
        return $q->public()->select([
            'id','title','price','city','area',
            'approx_lat','approx_lng','image_path',
            'room_type','unlock_fee','bedrooms',
        ]);
    }

    // Accessors
    public function getImageUrlAttribute(): string {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::url($this->image_path);
        }
        return asset('images/room-placeholder.jpg');
    }

    public function getRoomTypeLabelAttribute(): string {
        return match($this->room_type) {
            'single'    => 'Single Room',
            'double'    => 'Double Room',
            'apartment' => 'Apartment',
            'hostel'    => 'Hostel',
            default     => ucfirst($this->room_type),
        };
    }

    public function getStatusBadgeAttribute(): string {
        return match($this->status) {
            'approved' => '<span class="badge-green">Approved</span>',
            'pending'  => '<span class="badge-yellow">Pending</span>',
            'rejected' => '<span class="badge-red">Rejected</span>',
            default    => $this->status,
        };
    }

    // Helpers
    public function incrementViews(): void { $this->increment('views'); }

    public function isUnlockedBy(?User $user): bool {
        if (!$user) return false;
        return $user->hasUnlocked($this->id);
    }

    public function getPublicData(): array {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'price'       => $this->price,
            'city'        => $this->city,
            'area'        => $this->area,
            'approx_lat'  => $this->approx_lat,
            'approx_lng'  => $this->approx_lng,
            'image_url'   => $this->image_url,
            'room_type'   => $this->room_type,
            'unlock_fee'  => $this->unlock_fee,
            'bedrooms'    => $this->bedrooms,
            'bathrooms'   => $this->bathrooms,
        ];
    }
}
