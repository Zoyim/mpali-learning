<?php

namespace App\Http\Controllers;

use App\Formateur;
use App\Mail\FormateurMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FormateurController extends Controller
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

        $formateur = Formateur::all()->where('mobile', $request['mobile'])->first();
        if($formateur != null){
            return redirect()->back()->with("error", "Désolé le numéro de téléphone de l'étudiant ".$request['name']." ".$request['prenom']." existe déjà...");
        }else{
            $formateurEmail = Formateur::all()->where('email', $request['email'])->first();
            if($formateurEmail != null){
                return redirect()->back()->with("error", "Désolé l'adresse email de l'étudiant ".$request['name']." ".$request['prenom']." existe déjà...");
            }else{

                $formateurs = new Formateur();

                $formateurs->name = $request->input('name');
                $formateurs->prenom = $request->input('prenom');
                $formateurs->email = $request->input('email');
                $formateurs->mobile = $request->input('telephone');
                $formateurs->password = bcrypt($request->input('telephone'));
                $formateurs->sexe = $request->input('sexe');
                $formateurs->description = $request->input('description');
                $formateurs->isPassword = $request->input('isPassword');
                $formateurs->image = $request->input('image');

                $formateurs->save();
                Mail::to($request['email'])->send(new FormateurMail($formateurs));
                return redirect()->route('formateur.index')->with("success", "Le formateur ".$request['name']." ".$request['prenom']." a été enregistré avec succès...");
            }
        }
    }

    public function edit($id){
        $formateur = Formateur::all()->where("id", $id)->first();
        if($formateur != null){
            return view("layouts.formateur-edit", compact("formateur"));
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
                'description' => 'required',
                'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ]);

            $form_data = array(
                'name'    =>  $request->name,
                'prenom'    =>  $request->prenom,
                'email'    =>  $request->email,
                'mobile'    =>  $request->mobile,
                'sexe'    =>  $request->sexe,
                'description'    =>  $request->description,
                'imageEngin' =>  $request->image,
            );

            Formateur::whereId($id)->update($form_data);
        }else{
            $this->validate($request,[
                'name' => 'required',
                'prenom' => 'required',
                'mobile' => ['required', 'regex:/^((06)|(05)|(04))[0-9]{7}/'],
                'email' => 'required|email|unique:users',
                'sexe' => 'required|not_in:0',
                'description' => 'required'
            ]);
            $form_data = array(
                'name'    =>  $request->name,
                'prenom'    =>  $request->prenom,
                'email'    =>  $request->email,
                'mobile'    =>  $request->mobile,
                'sexe'    =>  $request->sexe,
                'description'    =>  $request->description,
            );

            Formateur::whereId($id)->update($form_data);
        }
        
        return redirect()->route('layouts.formateur-update')->with("success", "Le formateur ".$request['name']." ".$request['prenom']." a été modifié avec success...");
    }

    public function destroy($id){
        $formateur = Formateur::all()->where('id', $id)->first();
        if($formateur != null){
            $formateur->delete();
            return redirect()->back()->with("success", "Le formateur ".$formateur->name." ".$formateur->prenom." a été supprimé de la plate-forme...");
        }
    }
}
