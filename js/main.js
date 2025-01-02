document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll('.validate-form');

    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let isValid = true;

            const inputs = form.querySelectorAll('input[required], select[required]');
            inputs.forEach(input => {
                if (!input.value) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    const errorText = document.createElement('div');
                    errorText.classList.add('invalid-feedback');
                    errorText.innerText = 'Bu alan zorunludur.';
                    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('invalid-feedback')) {
                        input.after(errorText);
                    }
                } else {
                    input.classList.remove('is-invalid');
                    if (input.nextElementSibling && input.nextElementSibling.classList.contains('invalid-feedback')) {
                        input.nextElementSibling.remove();
                    }
                }
            });

            if (!isValid) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
});