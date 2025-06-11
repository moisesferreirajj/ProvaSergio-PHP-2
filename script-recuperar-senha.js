        document.addEventListener('DOMContentLoaded', function() {
            // Focus automático no input de email
            document.getElementById('email').focus();
            
            // Animação dos elementos
            const elements = document.querySelectorAll('.fade-in, .fade-in-delay, .fade-in-delay-2');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Validação em tempo real do email
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function() {
                const email = this.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email && !emailRegex.test(email)) {
                    this.classList.add('is-invalid');
                    showValidationMessage('Por favor, digite um email válido.', 'danger');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            // Função para mostrar mensagens de validação
            function showValidationMessage(message, type) {
                const existingAlert = document.querySelector('.validation-alert');
                if (existingAlert) {
                    existingAlert.remove();
                }

                const alert = document.createElement('div');
                alert.className = `custom-alert alert-${type} validation-alert`;
                alert.innerHTML = `
                    <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-2"></i>
                    ${message}
                `;
                
                emailInput.parentNode.appendChild(alert);
                
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            }

            // Animação do botão durante o envio
            const form = document.querySelector('.login-form');
            const submitBtn = document.querySelector('.login-btn');
            
            form.addEventListener('submit', function() {
                submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Enviando...';
                submitBtn.disabled = true;
            });
        });

        // Efeito de partículas no background (opcional)
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
                animation: float 6s ease-in-out infinite;
                animation-delay: ${Math.random() * 6}s;
            `;
            
            document.querySelector('.login-background-animation').appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 6000);
        }

        // Criar partículas periodicamente
        setInterval(createParticle, 1000);
