<?php
require_once __DIR__ . '/../helpers/auth.php';

class CompanyDataController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Cégadatok';
        $error = '';
        $success = '';
        // Fetch the single company record
        $company = $db->fetch("SELECT * FROM company_data LIMIT 1");
        if (!$company) {
            // Insert a default record if none exists
            $db->query("INSERT INTO company_data (name, country, postal_code, city, address, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?)", [
                '', '', '', '', '', '', ''
            ]);
            $company = $db->fetch("SELECT * FROM company_data LIMIT 1");
        }
        // Handle flash messages
        if (isset($_SESSION['company_data_success'])) {
            $success = $_SESSION['company_data_success'];
            unset($_SESSION['company_data_success']);
        }
        if (isset($_SESSION['company_data_error'])) {
            $error = $_SESSION['company_data_error'];
            unset($_SESSION['company_data_error']);
        }
        ob_start();
        include '../app/views/company-data/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function update() {
        global $db;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: company-data.php');
            exit;
        }
        $id = $_POST['id'] ?? null;
        $fields = [
            'name', 'country', 'postal_code', 'city', 'address', 'phone', 'email',
            'website', 'company_registration_number', 'tax_number', 'vat_number', 'excise_license_number'
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = trim($_POST[$field] ?? '');
        }
        // No validation required - all fields are optional
        try {
            $db->query("UPDATE company_data SET name=?, country=?, postal_code=?, city=?, address=?, phone=?, email=?, website=?, company_registration_number=?, tax_number=?, vat_number=?, excise_license_number=? WHERE id=?",
                [
                    $data['name'], $data['country'], $data['postal_code'], $data['city'], $data['address'],
                    $data['phone'], $data['email'], $data['website'], $data['company_registration_number'],
                    $data['tax_number'], $data['vat_number'], $data['excise_license_number'], $id
                ]
            );
            $_SESSION['company_data_success'] = 'Cégadatok sikeresen frissítve!';
        } catch (Exception $e) {
            $_SESSION['company_data_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        header('Location: company-data.php');
        exit;
    }
} 