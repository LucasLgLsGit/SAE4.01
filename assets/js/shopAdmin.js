document.addEventListener('DOMContentLoaded', function () {
	let sortOrder = {
        id: 1,
        titre: 1,
        description: 1,
        date: 1,
        couleur: 1,
        taille: 1,
        stock: 1,
        prix: 1,
        auteur: 1
    };

    function sortTableByColumn(columnName, type) {
        const rows = Array.from(document.querySelectorAll('.products-table tbody tr'));
        const columnIndices = {
            id: 0,
            titre: 1,
            description: 2,
            date: 3,
            couleur: 4,
            taille: 5,
            stock: 6,
            prix: 7,
            auteur: 8
        };

        const columnIndex = columnIndices[columnName];

        const sortedRows = rows.sort((a, b) => {
            const cellA = a.cells[columnIndex];
            const cellB = b.cells[columnIndex];

            if (!cellA || !cellB) {
                return 0;
            }

            const valueA = cellA.textContent.trim();
            const valueB = cellB.textContent.trim();

            let comparison = 0;
            if (type === 'number') {
                comparison = parseFloat(valueA.replace(',', '.')) - parseFloat(valueB.replace(',', '.'));
            } else if (type === 'date') {
                comparison = new Date(valueA) - new Date(valueB);
            } else {
                comparison = valueA.localeCompare(valueB);
            }

            return comparison * sortOrder[columnName];
        });

        const tbody = document.querySelector('.products-table tbody');
        tbody.innerHTML = '';
        tbody.append(...sortedRows);
    }

    function setupSortableColumns() {
        const headers = document.querySelectorAll('.products-table .sortable');
        headers.forEach(header => {
            header.addEventListener('click', function () {
                const columnName = header.getAttribute('data-column');
                const type = columnName === 'id' || columnName === 'stock' || columnName === 'prix' || columnName === 'auteur' ? 'number' : 
                             columnName === 'date' ? 'date' : 'string';
                sortOrder[columnName] = sortOrder[columnName] * -1;
                sortTableByColumn(columnName, type);
            });
        });
    }

    setupSortableColumns();
	
	let imageCounter = 1;

	function handleImagePreview(input, previewContainerId) {
		const previewContainer = document.getElementById(previewContainerId);
		previewContainer.innerHTML = '';

		const file = input.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = function (e) {
				const img = document.createElement('img');
				img.src = e.target.result;
				img.style.width = '100px';
				img.style.marginTop = '10px';
				img.style.borderRadius = '5px';
				img.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.2)';
				previewContainer.appendChild(img);
			};
			reader.readAsDataURL(file);
		}
	}

	document.getElementById('imageInput0').addEventListener('change', function () {
		handleImagePreview(this, 'previewContainer0');
	});

	document.getElementById('addImageButton').addEventListener('click', function () {
		const additionalImagesContainer = document.getElementById('additionalImagesContainer');

		const newImageDiv = document.createElement('div');
		newImageDiv.classList.add('mb-3', 'd-flex', 'align-items-center', 'gap-3');

		const newImageInputId = `imageInput${imageCounter}`;
		const newPreviewContainerId = `previewContainer${imageCounter}`;

		newImageDiv.innerHTML = `
			<button type="button" class="btn btn-danger btn-sm remove-image-button">
				<i class="bi bi-trash"></i>
			</button>
			<div>
				<input class="form-control image-input" type="file" id="${newImageInputId}" name="images[]" accept="image/*">
			</div>
			<div id="${newPreviewContainerId}" class="mt-2"></div>
		`;

		if (imageCounter == 1)
			additionalImagesContainer.innerHTML = '<h5 class="complementary-images">Images supplémentaires (facultatif) :</h5>';

		additionalImagesContainer.appendChild(newImageDiv);

		document.getElementById(newImageInputId).addEventListener('change', function () {
			handleImagePreview(this, newPreviewContainerId);
		});

		newImageDiv.querySelector('.remove-image-button').addEventListener('click', function () {
			newImageDiv.remove();
			if (additionalImagesContainer.querySelectorAll('.image-input').length === 0) {
				const complementaryImagesTitle = document.querySelector('.complementary-images');
				if (complementaryImagesTitle) {
					complementaryImagesTitle.remove();
				}
			}
			imageCounter--;
		});
		imageCounter++;
	});

	function setupSearch() {
		const searchInput = document.getElementById('searchInput');
		const searchAttribute = document.getElementById('searchAttribute');
		const resetButton = document.getElementById('resetSearch');
		const rows = document.querySelectorAll('.table tbody tr');

		function filterRows() {
			const searchTerm = searchInput.value.trim().toLowerCase();
			const attribute = searchAttribute.value;

			const columnIndices = {
				'id': 0,  
				'nom': 1,  
				'stock': 6, 
				'prix': 7,  
				'taille': 5 
			};

			const columnIndex = columnIndices[attribute];

			rows.forEach(row => {
				const cell = row.querySelector(`td:nth-child(${columnIndex + 1})`);
				let cellText = cell.textContent.trim().toLowerCase();

				if (attribute === 'prix') {
					cellText = cellText.replace(/[\s,]/g, ''); // "12,34" devient "1234"
				}

				if (cellText.includes(searchTerm)) {
					row.style.display = '';
				} else {
					row.style.display = 'none';
				}
			});
		}

		function resetSearch() {
			searchInput.value = '';
			searchAttribute.value = 'nom'; 
			rows.forEach(row => {
				row.style.display = '';
			});
		}

		
		function updatePlaceholder() {
			const placeholders = {
				'id': 'Rechercher par ID...',
				'nom': 'Rechercher par nom...',
				'stock': 'Rechercher par stock...',
				'prix': 'Rechercher par prix...',
				'taille': 'Rechercher par taille...' 
			};
			searchInput.placeholder = placeholders[searchAttribute.value];
		}

		searchInput.addEventListener('input', filterRows);
		searchAttribute.addEventListener('change', function () {
			updatePlaceholder();
			filterRows();
		});
		resetButton.addEventListener('click', resetSearch);

		updatePlaceholder();
	}


	setupSearch();
	addEventRow();
	addEventRowReset();
});


function addEventRowReset() {
	const resetButton = document.getElementById('resetProductButton');
	const createProductForm = document.getElementById('createProductForm');
	const stockTableBody = document.querySelector('#stockTable tbody');

	resetButton.addEventListener('click', function () {
		createProductForm.reset();

		stockTableBody.innerHTML = `
			<tr>
				<td>
					<input type="color" class="form-control form-control-color" name="couleurs[]" value="#000000">
				</td>
				<td><input type="number" class="form-control" name="stock[0][]" min="0" placeholder="Stock"></td>
				<td><input type="number" class="form-control" name="stock[0][]" min="0" placeholder="Stock"></td>
				<td><input type="number" class="form-control" name="stock[0][]" min="0" placeholder="Stock"></td>
				<td><input type="number" class="form-control" name="stock[0][]" min="0" placeholder="Stock"></td>
				<td>
					<button type="button" class="btn btn-danger btn-sm remove-color-row"><i class="bi bi-trash-fill remove-color-row"></i></button>
				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex justify-content-center">
						<button type="button" class="btn btn-success btn-sm add-color-row"><i class="bi bi-plus-square"></i></button>
					</div>
				</td>
			</tr>
		`;
		addEventRow();
	});
}

function addEventRow() {
	document.querySelector('.add-color-row').addEventListener('click', function () {
		const tableBody = document.querySelector('#stockTable tbody');
		const newRow = document.createElement('tr');
		newRow.innerHTML = `
			<td>
				<input type="color" class="form-control form-control-color" name="couleurs[]" value="#000000">
			</td>
			<td><input type="number" class="form-control" name="stock[${tableBody.rows.length - 1}][]" min="0" placeholder="Stock"></td>
			<td><input type="number" class="form-control" name="stock[${tableBody.rows.length - 1}][]" min="0" placeholder="Stock"></td>
			<td><input type="number" class="form-control" name="stock[${tableBody.rows.length - 1}][]" min="0" placeholder="Stock"></td>
			<td><input type="number" class="form-control" name="stock[${tableBody.rows.length - 1}][]" min="0" placeholder="Stock"></td>
			<td>
				<button type="button" class="btn btn-danger btn-sm remove-color-row"><i class="bi bi-trash-fill remove-color-row"></i></button>
			</td>
		`;
		tableBody.insertBefore(newRow, tableBody.lastElementChild);
	});

	document.querySelector('#stockTable').addEventListener('click', function (e) {
		if (e.target.classList.contains('remove-color-row')) {
			const row = e.target.closest('tr');
			row.remove();
		}
	});

	const editButtons = document.querySelectorAll('.edit-product');
    console.log('Nombre de boutons trouvés :', editButtons.length);

    if (editButtons.length === 0) {
        console.error('Aucun bouton .edit-product trouvé. Vérifiez le HTML.');
    }

    editButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('Clic sur Modifier détecté pour ID :', this.getAttribute('data-product-id'));

            const productData = {
                id: this.getAttribute('data-product-id'),
                nom: this.getAttribute('data-product-name'),
                description: this.getAttribute('data-product-description'),
                prix: this.getAttribute('data-product-price'),
                couleur: this.getAttribute('data-product-color'),
                taille: this.getAttribute('data-product-size'),
                stock: this.getAttribute('data-product-stock')
            };

            console.log('Données du produit récupérées :', productData);

            document.getElementById('modifProductId').value = productData.id || '';
            document.getElementById('modifProductName').value = productData.nom || '';
            document.getElementById('modifProductDescription').value = productData.description || '';
            document.getElementById('modifProductPrice').value = productData.prix || '';
            document.getElementById('modifProductColor').value = productData.couleur || '#000000';
            document.getElementById('modifProductSize').value = productData.taille || 'S';
            document.getElementById('modifProductStock').value = productData.stock || '0';

            console.log('Valeur de modifProductName après remplissage :', document.getElementById('modifProductName').value);

            const modalElement = document.getElementById('modifProductModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log('Modal ouvert');
            } else {
                console.error('Modal #modifProductModal introuvable');
            }
        });
    });
}