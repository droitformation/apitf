<?php

namespace App\Droit\Transfert\Colloque\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colloque extends Model
{
    use SoftDeletes;

    protected $table = 'colloques';

    protected $dates = ['deleted_at','start_at','end_at','registration_at','active_at'];

    protected $fillable = [
        'titre', 'soustitre', 'sujet', 'remarques', 'capacite','notice','start_at', 'end_at', 'registration_at', 'active_at', 'organisateur',
        'location_id', 'compte_id', 'visible', 'bon', 'facture', 'email' ,'adresse_id','created_at', 'updated_at','url','slide_text'
    ];

    public function getIllustrationAttribute()
    {
        if(isset($this->documents))
        {
            $illustration = $this->documents->filter(function ($item){
                return $item->type == 'illustration';
            });

            if(!$illustration->isEmpty()) {
                return $illustration->first();
            }

            return null;
        }

        return null;
    }

    public function getIsOpenAttribute()
    {
        $inscriptions = $this->inscriptions->count();

        if(!$this->capacite){
            return true;
        }

        return $this->capacite > $inscriptions ? true : false;
    }

    public function getTitleAttribute()
    {
        return $this->titre;
    }

    public function getSlidesAttribute()
    {
        return $this->getMedia('slides')->filter(function ($slide, $key) {
            return ((date('Y-m-d') >= $slide->getCustomProperty('start_at', '')) && (date('Y-m-d') <= $slide->getCustomProperty('end_at', '')));
        });
    }

    public function getFrontendIllustrationAttribute()
    {
        $illustration = $this->documents->filter(function ($item){
            return $item->type == 'illustration';
        });
  
        if(!$illustration->isEmpty())
        {
            return asset('files/colloques/illustration/'.$illustration->first()->path);
        }

        return asset('files/colloques/illustration/illu.png');
    }

    public function getProgrammeAttribute()
    {
        $programme = $this->documents->filter(function ($item){
            return $item->type == 'programme';
        });

        if(!$programme->isEmpty())
        {
            return $programme->first();
        }

        return false;
    }

    public function getProgrammeAttachementAttribute()
    {
        $programme = $this->documents->filter(function ($item){
            return $item->type == 'programme';
        });

        if(!$programme->isEmpty())
        {
            $file = public_path('files/colloques/'.$programme->first()->type.'/'.$programme->first()->path);

            if (\File::exists($file)) {
                return ['name' => 'Programme', 'file' => $file, 'url' => asset('files/colloques/'.$programme->first()->type.'/'.$programme->first()->path)];
            }
        }

        return false;
    }

    public function getPricesActiveAttribute()
    {
        return $this->prices->reject(function ($price, $key) {
                return $price->type == 'admin';
            })->filter(function ($price, $key) {

            if($price->end_at){
                return $price->end_at > \Carbon\Carbon::now() ? $price : false;
            }

            return $price;
        });
    }

    public function getIsActiveAttribute()
    {
        return $this->registration_at >= \Carbon\Carbon::today()->toDateString() ? true : false;
    }

    public function getIsFullAttribute()
    {
        return $this->full ? '' : false;
    }

    public function getEventDateAttribute()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        if(isset($this->end_at) && ($this->start_at != $this->end_at))
        {
            $month  = ($this->start_at->month == $this->end_at->month ? '%d' : '%d %B');
            $year   = ($this->start_at->year ==  $this->end_at->year ? '' : '%Y');
            $format = $month.' '.$year;

            return 'Du '.$this->start_at->formatLocalized($format).' au '.$this->end_at->formatLocalized('%d %B %Y');
        }
        else
        {
            return $this->start_at->formatLocalized('%A %d %B %Y');
        }
    }

    public function getOccurrenceDisplayAttribute()
    {
        return $this->occurrences->mapWithKeys_v2(function ($occurrence) {
            return [$occurrence->id => [
                'id'             => $occurrence->id,
                'colloque_id'    => $occurrence->colloque_id,
                'title'          => $occurrence->title,
                'starting_at'    => $occurrence->starting_at->format('Y-m-d'),
                'lieux'          => isset($occurrence->location) ? $occurrence->location->name : '',
                'lieux_id'       => $occurrence->lieux_id,
                'prices'         => $occurrence->prices->pluck('id'),
                'prices_names'   => $occurrence->prices->implode('description', ', '),
                'capacite_salle' => $occurrence->capacite_salle,
                'state'          => false,
            ]];
        });
    }

    public function getPriceDisplayAttribute()
    {
        return $this->prices->groupBy('type')->flatten(1)->mapWithKeys_v2(function ($price) {
            return [$price->id => [
                'id'             => $price->id,
                'colloque_id'    => $price->colloque_id,
                'description'    => $price->description,
                'price'          => $price->price_cents,
                'type'           => $price->type,
                'remarque'       => $price->remarque,
                'rang'           => $price->rang,
                'occurrences'    => $price->occurrence_list,
                'end_at'         => $price->end_at ? $price->end_at->format('Y-m-d') : null,
                'state'          => false,
            ]];
        });
    }

    public function getOptionDisplayAttribute()
    {
        $choix = ['checkbox' => 'Case à cocher', 'choix' => 'Choix multiple', 'text' => 'Texte'];
        
        return $this->options->mapWithKeys_v2(function ($option) use ($choix) {
            return [$option->id => [
                'id'          => $option->id,
                'colloque_id' => $option->colloque_id,
                'title'       => $option->title,
                'type'        => $option->type,
                'type_name'   => isset($choix[$option->type]) ? $choix[$option->type] : '',
                'groupe'      => $option->groupe->mapWithKeys(function ($item) {return [$item['id'] => [ 'text' => $item['text'], 'id' => $item['id'] ]];}),
                'state'       => false,
                'isUsed'      => $option->inscriptions->count() > 0 ? true : false,
            ]];
        });
    }

    public function getAnnexeAttribute()
    {
        $annexes = [];

        if($this->bon) {
            $annexes[] = 'bon';
        }

        if($this->facture) {
            $annexes[] = 'facture';
            $annexes[] = 'bv';
        }

        return $annexes;
    }

    public function scopeVisible($query,$visible)
    {
        if($visible) $query->where('visible','=',1);
    }

    public function scopeIsVisible($query,$isVisible)
    {
        if($isVisible) $query->whereNotNull('visible');
    }

    public function scopeActive($query,$status)
    {
        if($status) $query->where(function ($query) {
            $query->whereNotNull('end_at')->where('end_at','>',date('Y-m-d'));
        })->orWhere('start_at','>=',date('Y-m-d'));
    }

    public function scopeAdmin($query,$status)
    {
        if($status) $query->where(function ($query) {
            $query->whereNotNull('end_at')->where('end_at','>',date('Y-m-d'));
        })->orWhere('start_at','>',date('Y-m-d'))->orWhere('active_at','>',date('Y-m-d'));
    }

    public function scopeArchives($query,$archives = null)
    {
        if($archives) $query->where('start_at','<=',date('Y-m-d'));
    }

    public function scopeArchived($query,$archived = false)
    {
        if($archived){
            $query->where('start_at','<=',date('Y-m-d'));
        }
        else{
            $query->where('start_at','>=',date('Y-m-d'));
        }
    }

    public function scopeCentres($query,$centres = null)
    {
        if($centres && !empty($centres)) $query->whereHas('centres', function ($query) use ($centres) {
            $query->whereIn('organisateurs.id', $centres);
        });
    }
    
    public function scopeRegistration($query,$status)
    {
        if($status) $query->where('registration_at', '>=', date('Y-m-d'));
    }

    public function scopeName($query,$name)
    {
        if($name) $query->where('titre', 'LIKE', '%'.$name.'%');
    }

    public function scopeFinished($query,$status)
    {
        if($status) $query->where('registration_at','<',date('Y-m-d'));
    }

    public function location()
    {
        return $this->belongsTo('App\Droit\Transfert\Location\Entities\Location');
    }

    public function documents()
    {
        return $this->hasMany('App\Droit\Transfert\Document\Entities\Document');
    }
}
