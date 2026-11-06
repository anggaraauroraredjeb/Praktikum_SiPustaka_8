<?php

namespace Modules\Pendaftaran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['required', 'exists:books,id'],
            'borrower_name' => ['required', 'string', 'max:120'],
            'school' => ['required', 'string', 'max:180'],
            'study_program' => ['required', 'string', 'max:180'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'loan_date' => ['required', 'date'],
            'return_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'status' => ['required', Rule::in(['Dipinjam', 'Dikembalikan'])],
            'document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'remove_document' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'Pilih buku yang dipinjam.',
            'book_id.exists' => 'Buku yang dipilih tidak tersedia.',
            'borrower_name.required' => 'Nama peminjam wajib diisi.',
            'school.required' => 'Asal sekolah wajib diisi.',
            'study_program.required' => 'Prodi tujuan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'loan_date.required' => 'Tanggal pinjam wajib diisi.',
            'loan_date.date' => 'Tanggal pinjam tidak valid.',
            'return_date.required' => 'Tanggal kembali wajib diisi.',
            'return_date.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
            'status.required' => 'Status peminjaman wajib dipilih.',
            'status.in' => 'Status peminjaman tidak valid.',
            'document.mimes' => 'Berkas harus berupa JPG, PNG, atau PDF.',
            'document.max' => 'Ukuran berkas maksimal 2 MB.',
        ];
    }
}
