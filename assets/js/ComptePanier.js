document.addEventListener('DOMContentLoaded', function () {
    // Mettre à jour le compteur au chargement
    updateCartItemCount();

    // Activer les popovers Bootstrap
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    popoverTriggerList.forEach(popoverTriggerEl => {
        new bootstrap.Popover(popoverTriggerEl, {
            html: true
        });
    });

    // Mettre à jour la modale quand elle s'ouvre
    const cartModal = document.getElementById('cartModal');
    if (cartModal) {
        cartModal.addEventListener('show.bs.modal', function () {
            updateCartModal();
        });
    }
});

function updateCartModal() {
    fetch('/contenu_panier.php')
        .then(response => response.json())
        .then(data => {
            const modalBody = document.querySelector('#cartModal .modal-body');
            if (Object.keys(data).length === 0) {
                modalBody.innerHTML = '<p>Votre panier est vide.</p>';
            } else {
                let html = '<div class="list-group">';
                for (const [key, item] of Object.entries(data)) {
                    html += `
                        <div class="list-group-item d-flex align-items-center">
                            <img src="/assets/images/bde.webp" alt="Produit ${item.titre_produit || 'inconnu'}" class="img-fluid me-3" style="width: auto; height: 100px;">
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-1">${item.titre_produit || 'Produit inconnu'}</h5>
                                    <button class="btn btn-danger btn-sm" onclick="updateCart('${key}', 0)"><i class="bi bi-trash"></i></button>
                                </div>
                                <div class="d-flex gap-3 align-items-center mb-2">
                                    <span>Taille: ${item.taille || 'N/A'}</span>
                                    <span>Couleur: <div style="width: 20px; height: 20px; background-color: #${item.couleur || '000'}; border-radius: 50%;"></div></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-outline-secondary btn-sm me-2" onclick="updateCart('${key}', ${item.quantite - 1})">-</button>
                                        <span>${item.quantite || 0}</span>
                                        <button class="btn btn-outline-secondary btn-sm ms-2" onclick="updateCart('${key}', ${item.quantite + 1})">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                }
                html += '</div>';
                modalBody.innerHTML = html;
            }
            updateCartItemCount(data); // Met à jour le compteur avec les données reçues
        })
        .catch(error => {
            console.error('Erreur dans updateCartModal:', error);
            document.querySelector('#cartModal .modal-body').innerHTML = '<p>Erreur de chargement.</p>';
        });
}

function updateCartItemCount(cart = null) {
    const cartItemCountElements = document.querySelectorAll('.cart-item-count');
    if (!cart) {
        fetch('/contenu_panier.php')
            .then(response => response.json())
            .then(data => {
                const itemCount = Object.values(data).reduce((total, item) => total + (item.quantite || 0), 0);
                cartItemCountElements.forEach(element => {
                    element.textContent = itemCount;
                });
            })
            .catch(error => {
                console.error('Erreur dans updateCartItemCount:', error);
                cartItemCountElements.forEach(element => {
                    element.textContent = '0';
                });
            });
    } else {
        const itemCount = Object.values(cart).reduce((total, item) => total + (item.quantite || 0), 0);
        cartItemCountElements.forEach(element => {
            element.textContent = itemCount;
        });
    }
}

function updateCart(cartKey, quantite) {
    fetch('/maj_panier.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            cart_key: cartKey,
            quantite: quantite
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartModal(); // Recharge la modale et met à jour le compteur
        } else {
            alert(data.message || 'Une erreur est survenue.');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la mise à jour du panier :', error);
    });
}