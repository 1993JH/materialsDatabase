const dataElement = document.getElementById('wallLayersData');
const dialogElement = document.getElementById('wallLayersDialog');
const dialogTitleElement = document.getElementById('wallLayersDialogTitle');
const dialogBodyElement = document.getElementById('wallLayersDialogBody');
const closeDialogButton = document.getElementById('closeWallLayersDialog');

if (dataElement && dialogElement && dialogTitleElement && dialogBodyElement && closeDialogButton) {
	const wallLayers = JSON.parse(dataElement.textContent || '{}');

	const renderRows = (layers) => {
		if (!layers || layers.length === 0) {
			dialogBodyElement.innerHTML = `
				<tr>
					<td colspan="5" class="px-3 py-4 text-zinc-500 dark:text-zinc-400">No layer records found for this wall.</td>
				</tr>
			`;

			return;
		}

		dialogBodyElement.innerHTML = layers.map((layer) => `
			<tr>
				<td class="px-3 py-2">${layer.layer_number}</td>
				<td class="px-3 py-2">${layer.material_name}</td>
				<td class="px-3 py-2">${layer.layer_thickness}</td>
				<td class="px-3 py-2">${layer.embodied_carbon}</td>
				<td class="px-3 py-2">${layer.r_value ?? '-'}</td>
			</tr>
		`).join('');
	};

	document.querySelectorAll('.wall-card').forEach((wallCard) => {
		wallCard.addEventListener('click', () => {
			const wallId = wallCard.getAttribute('data-wall-id');
			const wallName = wallCard.getAttribute('data-wall-name') || 'Wall';

			dialogTitleElement.textContent = wallName;
			renderRows(wallLayers[String(wallId)] || []);
			dialogElement.showModal();
		});
	});

	closeDialogButton.addEventListener('click', () => dialogElement.close());

	dialogElement.addEventListener('click', (event) => {
		const dialogBounds = dialogElement.getBoundingClientRect();
		const clickedOutside =
			event.clientX < dialogBounds.left ||
			event.clientX > dialogBounds.right ||
			event.clientY < dialogBounds.top ||
			event.clientY > dialogBounds.bottom;

		if (clickedOutside) {
			dialogElement.close();
		}
	});
}

// Search functionality
const searchInput = document.getElementById('wallSearch');
if (searchInput) {
	searchInput.addEventListener('input', (event) => {
		const searchTerm = event.target.value.toLowerCase();
		const wallCards = document.querySelectorAll('.wall-card');

		wallCards.forEach((card) => {
			const searchText = card.getAttribute('data-search-text') || '';
			const isVisible = searchText.includes(searchTerm);
			card.style.display = isVisible ? '' : 'none';
		});
	});
}

