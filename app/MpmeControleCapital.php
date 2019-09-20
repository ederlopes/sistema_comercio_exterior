<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeControleCapital extends Model
{

    protected $table = 'MPME_CONTROLE_CAPITAL';

    protected $primaryKey = 'ID_MPME_CONTROLE_CAPITAL';

    public $timestamps = false;

    protected $guarded = array();

    public function movimentacao_controle_capital()
    {
        return $this->hasMany(MPME_MOVIMENTACAO_CONTROLE_CAPITAL::class, 'ID_MPME_CONTROLE_CAPITAL', 'ID_MPME_CONTROLE_CAPITAL');
    }
}
