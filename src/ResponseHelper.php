<?php


namespace laravelApiHelpers;

class ResponseHelper
{
  /**
   * Helper para simplificar os retornos JSON da API dentro do padrão do sistema
   */
  public static function dbError($msg = "Erro na transação com banco de dados.",$exception = null){
      return response()->json([
          'status' => 'erro',
          'codigo' => 'db01',
          'msg' => $msg,
          'trace' => $exception
      ],500);
  }

  public static function exception($exception,$msg = "Exception lançada"){
        return response()->json([
            'status' => 'erro',
            'codigo' => 'ex01',
            'msg' => $msg,
            'trace' => $exception->getTrace(),
        ],500);
    }

  public static function erroValidacao($errors, $originalBag = false, $codigo = "vl01"){
      /** Tratamento da BAG de Erros **/

      //Bag original
      $bag = $errors;
      //Vamos correr os erros
      if(!$originalBag){
          //Nova bag
          $bag = array();
          foreach($errors as $chaveA => $erroA){
              if(is_array($erroA)){
                  foreach ($erroA as $erroB){
                      $bag[] = $chaveA."|".$erroB;
                  }
              }else{
                  $bag[] = $chaveA."|".$erroA;
              }
          }
      }
      /** Retorno de dados **/
        return response()->json([
            'status' => 'erro',
            'codigo' => $codigo,
            'msg' => 'Erro na validação dos dados',
            'bag' => $bag
        ],406);
  }

  public static function erroGeral($msg = "Erro aconteceu", $codigo = 'gr01'){
        return response()->json([
            'status' => 'erro',
            'code' => $codigo,
            'msg' => $msg
        ],500);
  }

  public static function proibido($msg = "Você não ter permissão para efetuar essa ação.",$code = 'pr01'){
        return response()->json([
            'status' => 'erro',
            'code' => $code,
            'msg' => $msg
        ],403);
  }

  public static function nadaFeito($msg = "Nenhuma ação foi executada."){
      return response()->json([
          'status' => 'aviso',
          'code' => 'av02',
          'msg' => $msg
      ],202);
  }

  public static function aviso($msg, $code = 'av01'){
        return response()->json([
            'status' => 'aviso',
            'code' => 'av01',
            'msg' => $msg
        ],202);
    }

  public static function sucessoAcao($msg = "Ação executada com sucesso.", $dados = null, $code = 'acao_executada'){
      return response()->json([
          'status' => 'sucesso',
          'code' => $code,
          'msg' => $msg,
          'dados' => $dados
      ],200);
  }

  public static function sucessoCriar($dados, $msg = "Objeto criado com sucesso."){
        return response()->json([
            'status' => 'sucesso',
            'code' => 'sc02',
            'msg' => $msg,
            'dados' => $dados
        ],201);
    }

  public static function sucessoSalvar($dados, $msg = "Objeto salvo com sucesso."){
        return response()->json([
            'status' => 'sucesso',
            'code' => 'sc03',
            'msg' => $msg,
            'dados' => $dados
        ],200);
    }

  public static function sucessoObter($dados, $filtros = [], $codigo = 'sc09', $msg = "Dados obtidos com sucesso"){
        return response()->json([
            'status' => 'sucesso',
            'code' => $codigo,
            'msg' => $msg,
            'dados' => $dados,
            'filtros' => $filtros
        ],200);
    }

  public static function sucessoVazio($msg = "A requisição retornou um objeto vazio.", $code = 'sc00'){
      return response()->json([
          'status' => 'sucesso',
          'code' => $code,
          'msg' => $msg,
          'dados' => []
      ],202);
  }

  public static function laravelPaginate($paginate, $total_db, $filtros = []){
        /** Retorna sucesso, porém deixa claro que é uma páginação **/
      //Construindo os dados a serem retornados
      $dados = [
          'total_registros' => $total_db,
          'total_filtrado' => $paginate->total(),
          'pagina_atual' => $paginate->currentPage(),
          'total_paginas' => $paginate->lastPage(),
          'itens_por_pagina' => $paginate->perPage(),
          'total_na_pagina' => $paginate->count(),
          'primeiro_item' => $paginate->firstItem(),
          'ultimo_item' => $paginate->lastItem(),
          'resultado' => $paginate->items()
      ];
      //Retornando
      return self::sucessoObter($dados,$filtros,"sc10","Paginação obtida com sucesso");

  }
}
