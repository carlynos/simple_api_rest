<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

function verificacao($request){
        if($request->header('application-key') == null){
            return ['error' => ['id' => 1001,'description' => 'Api key não recebida!']];
        }else{
            if($request->header('application-key') !== "minhakey123"){
                return ['error' => ['id' => 1002,'description' => 'Key informada invalida']];
            }else{
                return true;
            }
        }
    }

class DogController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Dog::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dadosverificacao = verificacao($request);

        if($dadosverificacao == "true"){
            if($request->raca !== null && $request->nome !== null){
                try{
                    $dog = Dog::create($request->all());
                    $result = $dog;
                }catch(Exception $e){
                    if($e->getCode() == "23000"){
                        $result = ['error' => ['id' => $e->getCode(),'description' => 'Nome informado já existe']];
                    }else{
                        $errrortrat = explode(":", $e->getPrevious()->getMessage());
                        $result = ['error' => ['id' => $e->getCode(),'description' => $errrortrat[1]]];
                    }
                }
                
            }else{
                $result = ['error' => ['id' => 1000,'description' => 'Necessario Informar nome e raca!']];
            }
        }else{
            $result = $dadosverificacao;
        }
          
        

        //return $request->header();
        return json_encode($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Dog::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dadosverificacao = verificacao($request);

        if($dadosverificacao == "true"){
            if($request->raca !== null && $request->nome !== null){
                try{
                    $dog = Dog::findOrFail($id);
                    $dog->update($request->all());
                    $result = $dog;
                }catch(Exception $e){
                    if($e->getCode() == "23000"){
                        $result = ['error' => ['id' => $e->getCode(),'description' => 'Nome informado já existe']];
                    }else{
                        $errrortrat = explode(":", $e->getPrevious()->getMessage());
                        $result = ['error' => ['id' => $e->getCode(),'description' => $errrortrat[1]]];
                    }
                }
                
            }else{
                $result = ['error' => ['id' => 1000,'description' => 'Necessario Informar nome e raca!']];
            }
        }else{
            $result = $dadosverificacao;
        }
          
        
        return $result;
        //return $request->header();
        //return json_encode($result);
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $dadosverificacao = verificacao($request);

        if($dadosverificacao == "true"){
            try{
                $dog = Dog::findOrFail($id);
                $dog->delete();
                $result = $dog;
            }catch(Exception $e){
                if($e->getCode() == "23000"){
                    $result = ['error' => ['id' => $e->getCode(),'description' => 'Nome informado já existe']];
                }else{
                    $errrortrat = explode(":", $e->getPrevious()->getMessage());
                    $result = ['error' => ['id' => $e->getCode(),'description' => $errrortrat[1]]];
                }
            }
        }else{
            $result = $dadosverificacao;
        }
          
        

        //return $request->header();
        return json_encode($result);

        
    }
}
