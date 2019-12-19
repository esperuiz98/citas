<?php

namespace App\Http\Controllers;

use App\Enfermedad;
use App\Especialidad;
use Illuminate\Foundation\EnvironmentDetector;
use Illuminate\Http\Request;
use App\Paciente;

class PacienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //


        $pacientes=Paciente::all();
        $especialidades = Especialidad::all()->pluck('name','id');

        return view('pacientes/index',['pacientes'=>$pacientes,'especialidades'=>$especialidades]);

    }

    public function indexPacientesEspecialidad( Request $request)
    {
        $especialidades=Especialidad::all()->pluck('name','id');
        $especialidad_id=$request->get('especialidad_id');
        //$pacientes=DB::table('pacientes')

        $pacientes = Paciente::select('*')
            ->join('enfermedads', 'enfermedads.id','=','pacientes.enfermedad_id')
            ->join('especialidads','especialidads.id','=','enfermedads.especialidad_id')
            ->where('especialidads.id',$especialidad_id)->get();

        //return view('pacientes/index',['pacientes'=>$pacientes,'especialidades'=>$especialidades]);
        return view('pacientes/indexPacientesEspecialidad',['pacientes'=>$pacientes,'especialidades'=>$especialidades]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $enfermedades = Enfermedad::all()->pluck('name','id');

        return view('pacientes/create',['enfermedades'=>$enfermedades]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'nuhsa' => 'required|nuhsa|max:255',
            'address' => 'required|max: 255',
            'enfermedad_id' => 'required|exists:enfermedads,id'

        ]);

        //TODO: crear validación propia para nuhsa
        $paciente = new Paciente($request->all());
        $paciente->save();

        // return redirect('especialidades');

        flash('Paciente creado correctamente');

        return redirect()->route('pacientes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // TODO: Mostrar las citas de un paciente
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paciente = Paciente::find($id);
        $enfermedades = Enfermedad::all()->pluck('name','id');

        return view('pacientes/edit',['paciente'=> $paciente, 'enfermedades'=>$enfermedades]);

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
        $this->validate($request, [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'nuhsa' => 'required|nuhsa|max:255'
        ]);

        $paciente = Paciente::find($id);
        $paciente->fill($request->all());

        $paciente->save();

        flash('Paciente modificado correctamente');

        return redirect()->route('pacientes.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paciente = Paciente::find($id);
        $paciente->delete();
        flash('Paciente borrado correctamente');

        return redirect()->route('pacientes.index');
    }
}
