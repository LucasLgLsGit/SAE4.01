document.addEventListener('DOMContentLoaded', function() 
{
	updateCartItemCount();

	const cartModal = document.getElementById('cartModal');
	if (cartModal) 
	{
		cartModal.addEventListener('show.bs.modal', updateCartModal);
	}
});

function updateCartModal() 
{
	const modalBody = document.querySelector('#cartModal .modal-body');
	const cartTotalElement = document.getElementById('cart-total');
	const checkoutButton = document.getElementById('checkout-button');
	const isAdherent = document.getElementById('cartModal').dataset.adherent === 'true';

	modalBody.innerHTML = `
		<div class="text-center my-4">
			<div class="spinner-border text-primary" role="status">
				<span class="visually-hidden">Chargement...</span>
			</div>
			<p>Chargement du panier...</p>
		</div>
	`;

	fetch('/contenu_panier.php')
		.then(response => response.json())
		.then(data => 
		{
			if (!data || Object.keys(data).length === 0) 
			{
				modalBody.innerHTML = '<p class="text-center py-4">Votre panier est vide</p>';
				cartTotalElement.textContent = '0.00 €';
				checkoutButton.disabled = true;
				return;
			}

			let html = '<div class="list-group">';
			let total = 0;

			for (const [key, item] of Object.entries(data)) 
			{
				const itemTotal = (item.prix || 0) * (item.quantite || 1);
				total += itemTotal;

				html += `
					<div class="list-group-item">
						<div class="d-flex align-items-center">
							<img src="/assets/images/bde.webp" alt="${item.titre_produit || 'Produit'}" class="img-thumbnail me-3" style="width:80px;height:80px;object-fit:cover;">
							<div class="flex-grow-1">
								<div class="d-flex justify-content-between">
									<h6 class="mb-1">${item.titre_produit || 'Produit'}</h6>
									<button class="btn btn-sm btn-danger" onclick="updateCart('${key}', 0)">
										<i class="bi bi-trash"></i>
									</button>
								</div>
								<div class="d-flex flex-wrap gap-3 my-2">
									<small>Taille: ${item.taille || 'N/A'}</small>
									<small>Couleur: <span class="d-inline-block" style="width:15px;height:15px;background-color:#${item.couleur || '000'};border-radius:50%;"></span></small>
								</div>
								<div class="d-flex justify-content-between align-items-center">
									<div class="btn-group">
										<button class="btn btn-sm btn-outline-secondary" onclick="updateCart('${key}', ${(item.quantite || 1) - 1})">-</button>
										<span class="px-2">${item.quantite || 1}</span>
										<button class="btn btn-sm btn-outline-secondary" onclick="updateCart('${key}', ${(item.quantite || 1) + 1})">+</button>
									</div>
									<strong>${itemTotal.toFixed(2)} €</strong>
								</div>
							</div>
						</div>
					</div>`;
			}

			html += '</div>';
			modalBody.innerHTML = html;

			if (isAdherent) 
			{
				const totalReduit = total * 0.9;
				cartTotalElement.innerHTML = `
					<span class="text-decoration-line-through text-muted me-2">${total.toFixed(2)} €</span>
					<span class="text-success fw-bold">${totalReduit.toFixed(2)} €</span>
					<small class="text-success">(-10%)</small>
				`;
			} 
			else 
			{
				cartTotalElement.textContent = `${total.toFixed(2)} €`;
			}

			checkoutButton.disabled = false;
		})
}

function updateCartItemCount(cart = null) 
{
	const cartItemCountElements = document.querySelectorAll('.cart-item-count');
	if (!cart) 
	{
		fetch('/contenu_panier.php')
			.then(response => response.json())
			.then(data => 
			{
				const itemCount = Object.values(data).reduce((total, item) => total + (item.quantite || 0), 0);
				cartItemCountElements.forEach(element => 
				{
					element.textContent = itemCount;
				});
			})
			.catch(error => 
			{
				console.error('Erreur dans updateCartItemCount:', error);
				cartItemCountElements.forEach(element => 
				{
					element.textContent = '0';
				});
			});
	} 
	else 
	{
		const itemCount = Object.values(cart).reduce((total, item) => total + (item.quantite || 0), 0);
		cartItemCountElements.forEach(element => 
		{
			element.textContent = itemCount;
		});
	}
}

function updateCart(cartKey, quantite) 
{
	fetch('/maj_panier.php', 
	{
		method: 'POST',
		headers: 
		{
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: new URLSearchParams({
			cart_key: cartKey,
			quantite: quantite
		})
	})
	.then(response => response.json())
	.then(data => 
	{
		if (data.success) 
		{
			updateCartModal();
		} 
		else 
		{
			alert(data.message || 'Une erreur est survenue.');
		}
	})
	.catch(error => 
	{
		console.error('Erreur lors de la mise à jour du panier :', error);
	});
}
