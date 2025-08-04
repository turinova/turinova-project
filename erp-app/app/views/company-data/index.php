<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Cégadatok</h4>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <i class="ri ri-check-line me-2"></i>
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <i class="ri ri-error-warning-line me-2"></i>
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="company-data.php?action=update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($company['id'] ?? '') ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Név</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($company['name'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ország</label>
                        <input type="text" class="form-control" name="country" value="<?= htmlspecialchars($company['country'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Irányítószám</label>
                        <input type="text" class="form-control" name="postal_code" value="<?= htmlspecialchars($company['postal_code'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Város</label>
                        <input type="text" class="form-control" name="city" value="<?= htmlspecialchars($company['city'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cím</label>
                        <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($company['address'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefonszám</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="<?= htmlspecialchars($company['phone'] ?? '') ?>" placeholder="+36 XX XXX XXXX" maxlength="15">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">E-mail</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($company['email'] ?? '') ?>" placeholder="xxxxx@xxxx.xx">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Weboldal</label>
                        <input type="text" class="form-control" name="website" value="<?= htmlspecialchars($company['website'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cégjegyzékszám</label>
                        <input type="text" class="form-control" name="company_registration_number" id="company_registration_number" value="<?= htmlspecialchars($company['company_registration_number'] ?? '') ?>" placeholder="XX XX XXXXXX" maxlength="12">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Adószám</label>
                        <input type="text" class="form-control" name="tax_number" id="tax_number" value="<?= htmlspecialchars($company['tax_number'] ?? '') ?>" placeholder="XXXXXXXX-X-XX" maxlength="13">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Közösségi adószám</label>
                        <input type="text" class="form-control" name="vat_number" value="<?= htmlspecialchars($company['vat_number'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jövedéki engedély szám</label>
                        <input type="text" class="form-control" name="excise_license_number" value="<?= htmlspecialchars($company['excise_license_number'] ?? '') ?>">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri ri-save-line me-2"></i>Mentés
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Phone number formatting: +36 XX XXX XXXX (max 15 characters including spaces and +)
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            
            if (value.length > 0) {
                if (value.startsWith('36')) {
                    value = value.substring(2);
                }
                
                // Limit to 9 digits (excluding +36 prefix)
                if (value.length > 9) {
                    value = value.substring(0, 9);
                }
                
                let formatted = '+36 ';
                if (value.length > 0) formatted += value.substring(0, 2);
                if (value.length > 2) formatted += ' ' + value.substring(2, 5);
                if (value.length > 5) formatted += ' ' + value.substring(5, 9);
                
                e.target.value = formatted;
            }
        });
        
        // Prevent typing more than maxlength
        phoneInput.addEventListener('keypress', function(e) {
            if (e.target.value.length >= 15 && e.key !== 'Backspace' && e.key !== 'Delete') {
                e.preventDefault();
            }
        });
        
        // Format existing value on load
        if (phoneInput.value) {
            phoneInput.dispatchEvent(new Event('input'));
        }
    }
    
    // Company registration number formatting: XX XX XXXXXX (max 12 characters including spaces)
    const companyRegInput = document.getElementById('company_registration_number');
    if (companyRegInput) {
        companyRegInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            
            // Limit to 10 digits
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            
            let formatted = '';
            if (value.length > 0) formatted += value.substring(0, 2);
            if (value.length > 2) formatted += ' ' + value.substring(2, 4);
            if (value.length > 4) formatted += ' ' + value.substring(4, 10);
            
            e.target.value = formatted;
        });
        
        // Prevent typing more than maxlength
        companyRegInput.addEventListener('keypress', function(e) {
            if (e.target.value.length >= 12 && e.key !== 'Backspace' && e.key !== 'Delete') {
                e.preventDefault();
            }
        });
        
        // Format existing value on load
        if (companyRegInput.value) {
            companyRegInput.dispatchEvent(new Event('input'));
        }
    }
    
    // Tax number formatting: XXXXXXXX-X-XX (max 13 characters including dashes)
    const taxNumberInput = document.getElementById('tax_number');
    if (taxNumberInput) {
        taxNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            
            // Limit to 11 digits
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            let formatted = '';
            if (value.length > 0) formatted += value.substring(0, 8);
            if (value.length > 8) formatted += '-' + value.substring(8, 9);
            if (value.length > 9) formatted += '-' + value.substring(9, 11);
            
            e.target.value = formatted;
        });
        
        // Prevent typing more than maxlength
        taxNumberInput.addEventListener('keypress', function(e) {
            if (e.target.value.length >= 13 && e.key !== 'Backspace' && e.key !== 'Delete') {
                e.preventDefault();
            }
        });
        
        // Format existing value on load
        if (taxNumberInput.value) {
            taxNumberInput.dispatchEvent(new Event('input'));
        }
    }
    
    // Email validation and formatting
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function(e) {
            let value = e.target.value.trim();
            if (value && !isValidEmail(value)) {
                emailInput.classList.add('is-invalid');
            } else {
                emailInput.classList.remove('is-invalid');
            }
        });
        
        emailInput.addEventListener('input', function(e) {
            emailInput.classList.remove('is-invalid');
        });
    }
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}
</script> 