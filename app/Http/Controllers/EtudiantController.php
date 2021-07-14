<?php

namespace App\Http\Controllers;

use App\Etudiant;
use App\Mail\EtudiantMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EtudiantController extends Controller
{
    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'prenom' => 'required',
            'mobile' => ['required', 'regex:/^((06)|(05)|(04))[0-9]{7}/'],
            'email' => 'required|email|unique:users',
            'sexe' => 'required|not_in:0',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            //'image' => 'required|image|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
        ]);

        $etudiant = Etudiant::all()->where('mobile', $request['mobile'])->first();
        if($etudiant != null){
            return redirect()->back()->with("error", "Désolé le numéro de téléphone de l'étudiant ".$request['name']." ".$request['prenom']." existe déjà...");
        }else{
            $etudiantEmail = Etudiant::all()->where('email', $request['email'])->first();
            if($etudiantEmail != null){
                return redirect()->back()->with("error", "Désolé l'adresse email de l'étudiant ".$request['name']." ".$request['prenom']." existe déjà...");
            }else{

                $etudiants = new Etudiant();

                $etudiants->name = $request->input('name');
                $etudiants->prenom = $request->input('prenom');
                $etudiants->email = $request->input('email');
                $etudiants->mobile = $request->input('telephone');
                $etudiants->password = bcrypt($request->input('telephone'));
                $etudiants->sexe = $request->input('sexe');
                $etudiants->isPassword = $request->input('isPassword');
                $etudiants->image = $request->input('image');

                $etudiants->save();
                Mail::to($request['email'])->send(new EtudiantMail($etudiants));
                return redirect()->route('etudiant.index')->with("success", "L'étudiant ".$request['name']." ".$request['prenom']." a été enregistré avec succès...");
            }
        }
    }

    public function edit($id){
        $etudiant = Etudiant::all()->where("id", $id)->first();
        if($etudiant != null){
            return view("layouts.etudiant-edit", compact("etudiant"));
        }
    }

    public function update(Request $request, $id){
        $image = $request->file('image');
        if($image != ''){
            $this->validate($request,[
                'name' => 'required',
                'prenom' => 'required',
                'mobile' => ['required', 'regex:/^((06)|(05)|(04))[0-9]{7}/'],
                'email' => 'required|email|unique:users',
                'sexe' => 'required|not_in:0',
                'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ]);

            $form_data = array(
                'name'    =>  $request->name,
                'prenom'    =>  $request->prenom,
                'email'    =>  $request->email,
                'mobile'    =>  $request->mobile,
                'sexe'    =>  $request->sexe,
                'imageEngin' =>  $request->image,
            );

            Etudiant::whereId($id)->update($form_data);
        }else{
            $this->validate($request,[
                'name' => 'required',
                'prenom' => 'required',
                'mobile' => ['required', 'regex:/^((06)|(05)|(04))[0-9]{7}/'],
                'email' => 'required|email|unique:users',
                'sexe' => 'required|not_in:0'
            ]);
            $form_data = array(
                'name'    =>  $request->name,
                'prenom'    =>  $request->prenom,
                'email'    =>  $request->email,
                'mobile'    =>  $request->mobile,
                'sexe'    =>  $request->sexe,
            );

            Etudiant::whereId($id)->update($form_data);
        }
        
        return redirect()->route('layouts.etudiant-update')->with("success", "L'étudiant ".$request['name']." ".$request['prenom']." a été modifié avec success...");
    }

    public function destroy($id){
        $etudiant = Etudiant::all()->where('id', $id)->first();
        if($etudiant != null){
            $etudiant->delete();
            return redirect()->back()->with("success", "L'étudiant ".$etudiant->name." ".$etudiant->prenom." a été supprimé de la plate-forme...");
        }
    }
}
