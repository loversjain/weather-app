<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;

/**
 * Class Event
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon $date
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static findOrFail(int|string $id)
 */
class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'date', 'location'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Create a new event.
     *
     * @param array $data The event data.
     * @return \App\Models\Event The created event instance.
     * @throws \Exception If an error occurs while creating the event.
     */
    public static function createEvent(array $data): Event
    {
        try {
            $event = self::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'date' => $data['date'],
                'location' => $data['location'],
            ]);

            return $event;
        } catch (Exception $e) {
            \Log::error([$e->getMessage(), $e->getTraceAsString()]);
            throw new Exception("Error in Store Event", 1);
        }
    }
}
