<?php

namespace App\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return ['name' => 'required|string|max:255',
				'email' => 'required|string|email|unique:users|max:255',
				'password' => 'required|string|confirmed|min:8|max:255'
				//'password_confirmation' => 'required',
			   ];
	}

	public function messages()
	{	
		return ['name.required' => 'Necesitamos tu nombre',
				'name.max' => 'Máximo 255 carateres',

				'email.required' => 'Necesitamos tu correo',
				'email.unique' => 'Ya hay alguien con este correo registrado',
				'email.email' => 'Este no parece un correo válido',
				'email.max' => 'Máximo 255 carateres',

				'password.required' => 'Tienes que crear una contraseña',
				'password.min' => 'Mínimo 8 caracteres',
				'password.max' => 'Máximo 255 carateres',
				'password.confirmed' => 'Las contraseñas no son iguales'];
	}
}
