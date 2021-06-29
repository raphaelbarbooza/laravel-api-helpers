<?php


namespace laravelApiHelpers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class QueryHelper
{
    private $query;
    private $created;
    private $updated;
    private $deleted;
    private $filtros;
    private $erro = false;

    public function __construct($query, $created = true, $updated = true, $deleted = false){
        $this->query = $query;
        $this->created = $created;
        $this->updated = $updated;
        $this->deleted = $deleted;

        $this->filtrarDatas();
    }

    public function getErro(){
        return $this->erro;
    }

    public function getQuery(){
        return $this->query;
    }

    private function filtrarDatas(){
        /** Recebe uma Query, e baseado na configuração dos filtros, retorna as buscas **/
        //Validar as possíveis data
        Validator::validate(request()->all(),[
            'criado_de' => 'sometimes|date',
            'criado_ate' => 'sometimes|date',
            'editado_de' => 'sometimes|date',
            'editado_ate' => 'sometimes|date',
            'deletado_de' => 'sometimes|date',
            'deletado_ate' => 'sometimes|date'
        ]);
        //Filtros de Criação
        if($this->created){
            //Verificar se existe um inicio e um fim
            if(request()->has('criado_de') && request()->has('criado_ate')){
                //Validar as datas
                $inicio = Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s',strtotime(request()->input('criado_de'))));
                $fim = Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s',strtotime(request()->input('criado_ate'))));
                if($inicio->gt($fim)){
                    $this->erro = ResponseHelper::erroValidacao(
                        ['criado_de' => "A data de ínicio deve ser menor que a data final."]
                    );
                    return false;
                }
                //Tudo ok, vamos fazer query
                $this->query->whereBetween('created_at', [request()->input('criado_de'), request()->input('criado_ate')]);
                $this->filtros['criado_de'] = request()->input('criado_de');
                $this->filtros['criado_ate'] = request()->input('criado_ate');
            }else{
                //Criado de
                if(request()->has('criado_de')){
                    //Tudo ok, vamos fazer query
                    $this->query->where('created_at',">=",request()->input('criado_de'));
                    $this->filtros['criado_de'] = request()->input('criado_de');
                }
                //Criado até
                if(request()->has('criado_ate')){
                    //Tudo ok, vamos fazer query
                    $this->query->where('created_at',"<=",request()->input('criado_ate'));
                    $this->filtros['criado_ate'] = request()->input('criado_ate');
                }
            }

        }
        //Filtros de Edição
        if($this->updated){
            //Verificar se existe um inicio e um fim
            if(request()->has('editado_de') && request()->has('editado_ate')){
                //Validar as datas
                $inicio = Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s',strtotime(request()->input('editado_de'))));
                $fim = Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s',strtotime(request()->input('editado_ate'))));
                if($inicio->gt($fim)){
                    $this->erro = ResponseHelper::erroValidacao(
                        ['editado_de' => "A data de ínicio deve ser menor que a data final."]
                    );
                    return false;
                }
                //Tudo ok, vamos fazer query
                $this->query->whereBetween('updated_at', [request()->input('editado_de'), request()->input('editado_ate')]);
                $this->filtros['editado_de'] = request()->input('editado_de');
                $this->filtros['editado_ate'] = request()->input('editado_ate');
            }else{
                //editado de
                if(request()->has('editado_de')){
                    //Tudo ok, vamos fazer query
                    $this->query->where('updated_at',">=",request()->input('editado_de'));
                    $this->filtros['editado_de'] = request()->input('editado_de');
                }
                //editado até
                if(request()->has('editado_ate')){
                    //Tudo ok, vamos fazer query
                    $this->query->where('updated_at',"<=",request()->input('editado_ate'));
                    $this->filtros['editado_ate'] = request()->input('editado_ate');
                }
            }

        }
        //Filtros de Deleção
        if($this->deleted){
            //Verificar se existe um inicio e um fim
            if(request()->has('deletado_de') && request()->has('deletado_ate')){
                //Validar as datas
                $inicio = Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s',strtotime(request()->input('deletado_de'))));
                $fim = Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s',strtotime(request()->input('deletado_ate'))));
                if($inicio->gt($fim)){
                    $this->erro = ResponseHelper::erroValidacao(
                        ['deletado_de' => "A data de ínicio deve ser menor que a data final."]
                    );
                    return false;
                }
                //Tudo ok, vamos fazer query
                $this->query->whereBetween('deleted_at', [request()->input('deletado_de'), request()->input('deletado_ate')]);
                $this->filtros['deletado_de'] = request()->input('deletado_de');
                $this->filtros['deletado_ate'] = request()->input('deletado_ate');
            }else{
                //deletado de
                if(request()->has('deletado_de')){
                    //Tudo ok, vamos fazer query
                    $this->query->where('deleted_at',">=",request()->input('deletado_de'));
                    $this->filtros['deletado_de'] = request()->input('deletado_de');
                }
                //deletado até
                if(request()->has('deletado_ate')){
                    //Tudo ok, vamos fazer query
                    $this->query->where('deleted_at',"<=",request()->input('deletado_ate'));
                    $this->filtros['deletado_ate'] = request()->input('deletado_ate');
                }
            }

        }

        return $this->query;

    }

    public function getFiltros($filtros){
        /** Retorna a estrutura de filtros baseado no valores dos requests **/
        //Verifica as solicitações
        foreach($filtros as $filtro){
            if(request()->has($filtro)){
                $this->filtros[$filtro] = request()->input($filtro);
            }
        }

        return $this->filtros;
    }
}