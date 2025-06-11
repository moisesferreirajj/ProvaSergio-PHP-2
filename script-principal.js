        document.addEventListener('DOMContentLoaded', function() {
            // Animação de entrada dos elementos
            const elements = document.querySelectorAll('.fade-in, .fade-in-delay');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Hover effects nos cards
            const menuCards = document.querySelectorAll('.menu-card');
            menuCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Confirmação de logout
            const logoutBtn = document.querySelector('.logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    if (!confirm('Tem certeza que deseja sair do sistema?')) {
                        e.preventDefault();
                    }
                });
            }
        });

        // Função para busca rápida (opcional)
        function quickSearch() {
            const searchTerm = prompt('Digite o que você está procurando:');
            if (searchTerm) {
                const cards = document.querySelectorAll('.menu-card');
                let found = false;
                
                cards.forEach(card => {
                    const title = card.querySelector('.menu-card-title').textContent.toLowerCase();
                    const description = card.querySelector('.menu-card-description').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm.toLowerCase()) || description.includes(searchTerm.toLowerCase())) {
                        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        card.style.boxShadow = '0 0 20px rgba(37, 99, 235, 0.5)';
                        setTimeout(() => {
                            card.style.boxShadow = '';
                        }, 2000);
                        found = true;
                        return;
                    }
                });
                
                if (!found) {
                    alert('Nenhum item encontrado com o termo: ' + searchTerm);
                }
            }
        }

        // Atalho de teclado para busca rápida (Ctrl + F)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                quickSearch();
            }
        });