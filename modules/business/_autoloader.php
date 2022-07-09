<?php
require_once __DIR__ . '/invoice.class.php';
require_once __DIR__ . '/invoice.proforma.class.php';
require_once __DIR__ . '/notes.class.php';
require_once __DIR__ . '/raport.class.php';
require_once __DIR__ . '/contrahent.class.php';
require_once __DIR__ . '/pdf.class.php';

global $b_contrahent, $b_invoice, $b_proforma, $b_notes, $b_raport;
$b_contrahent = new BusinessContrahents();
$b_invoice = new BusinessInvoice();
$b_proforma = new BusinessInvoiceProforma();
$b_notes = new BusinessNotes();
$b_raport = new BusinessInvoiceRaport();
