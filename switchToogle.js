 document.querySelectorAll('.toggle-checkbox').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const label = this.nextElementSibling;
                if(this.checked) {
                    this.style.borderColor = '#7c3aed';
                    label.style.backgroundColor = '#7c3aed';
                    this.style.right = '0';
                    this.style.transform = 'translateX(100%)';
                } else {
                    this.style.borderColor = '#d1d5db';
                    label.style.backgroundColor = '#d1d5db';
                    this.style.transform = 'translateX(0)';
                }
            });
            // Init state
            toggle.dispatchEvent(new Event('change'));
        });