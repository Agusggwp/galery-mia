<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:100'],
            'student_number' => ['required', 'string', 'max:50'],
            'class_name' => ['required', 'string', 'max:50'],
            'major' => ['required', 'string', 'max:100'],
            'generation' => ['required', 'string', 'max:10'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // Max 5MB
            'bio' => ['nullable', 'string', 'max:1000'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'privacy_agreed' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nickname.required' => 'Nama panggilan wajib diisi.',
            'student_number.required' => 'NIM / Nomor Absen wajib diisi.',
            'class_name.required' => 'Kelas wajib diisi.',
            'major.required' => 'Jurusan wajib diisi.',
            'generation.required' => 'Angkatan wajib diisi.',
            'photo.required' => 'Foto profil wajib diunggah.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal adalah 5MB.',
            'privacy_agreed.accepted' => 'Anda harus menyetujui persetujuan privasi sebelum mengirim formulir.',
        ];
    }
}
