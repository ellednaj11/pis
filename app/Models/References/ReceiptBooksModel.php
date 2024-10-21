<?php

namespace App\Models\References;

use CodeIgniter\Model;

class ReceiptBooksModel extends Model {
    protected $table = 'receipt_books';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fund_code', 'or_number_start', 'or_number_end','orig_qty', 'used_qty', 'book_value','status','insert_by','insert_date','remove_by','remove_date'];
}
