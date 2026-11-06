<?php

namespace Modules\Pendaftaran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:180'],
            'publisher' => ['nullable', 'string', 'max:180'],
            'published_year' => ['nullable', 'integer', 'between:1000,'.(now()->year + 1)],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul buku wajib diisi.',
            'title.max' => 'Judul buku maksimal 255 karakter.',
            'author.required' => 'Nama penulis wajib diisi.',
            'author.max' => 'Nama penulis maksimal 180 karakter.',
            'publisher.max' => 'Nama penerbit maksimal 180 karakter.',
            'published_year.integer' => 'Tahun terbit harus berupa angka.',
            'published_year.between' => 'Tahun terbit tidak valid.',
            'stock.required' => 'Stok buku wajib diisi.',
            'stock.integer' => 'Stok buku harus berupa angka bulat.',
            'stock.min' => 'Stok buku tidak boleh kurang dari nol.',
            'stock.max' => 'Stok buku terlalu besar.',
        ];
    }
}
