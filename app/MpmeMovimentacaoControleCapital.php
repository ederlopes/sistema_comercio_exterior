<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeMovimentacaoControleCapital extends Model
{

    protected $table = 'MPME_MOVIMENTACAO_CONTROLE_CAPITAL';

    protected $primaryKey = 'ID_MPME_MOVIMENTACAO_CONTROLE_CAPITAL';

    public $timestamps = false;

    protected $guarded = array();

    public function controle_capital()
    {
        return $this->belongsTo(MpmeControleCapital::class, 'ID_MPME_CONTROLE_CAPITAL', 'ID_MPME_CONTROLE_CAPITAL');
    }
}
