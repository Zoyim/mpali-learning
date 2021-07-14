<?php

namespace App\Http\Controllers;

use App\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function store(Request $request){

        $this->validate($request,[
            'libelle' => 'required'
        ]); 

        $departement = new Departement();
        $departement->libelle = $request['libelle'];
        $departement->save();

        return redirect()->back()->with("success", "Le département ".$request['libelle']." a été enregistré avec success !");
    }

    public function edit($id){
        $departement = Departement::all()->where("id", $id)->first();
        if($departement != null){
            return view("layouts.departement-edit", compact("departement"));
        }
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'libelle' => 'required'
        ]);

        $form_data = array(
            'libelle'    =>  $request->libelle,
        );

        Departement::whereId($id)->update($form_data);
        return redirect()->route('layouts.departement-update')->with("success", "Le département ".$request->libelle." a été modifié avec success !!!");
    }

    public function destroy($id){
        $departement = Departement::all()->where('id', $id)->first();
        if($departement != null){
            $departement->delete();
            return redirect()->back()->with("success", "Le département ".$departement->libelle." a été supprimé de la plate-forme !!!");
        }
    }
}
