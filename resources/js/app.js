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

// Search and climate zone filtering
const searchInput = document.getElementById('wallSearch');
const selectedFiltersSummary = document.getElementById('selectedFiltersSummary');
const clearAllFiltersButton = document.getElementById('clearAllFilters');
const climateZoneFilter = document.getElementById('climateZoneFilter');
const climateZoneCheckboxes = document.querySelectorAll('.climate-zone-checkbox');
const insulationFilterCheckboxes = document.querySelectorAll('.insulation-filter-checkbox');
const airBarrierFilterCheckboxes = document.querySelectorAll('.air-barrier-filter-checkbox');

const extractClimateZoneCodes = (value) => {
	const rawValue = String(value || '').toUpperCase();
	const matchedCodes = [];
	const hasLessThanFour = /<\s*4/.test(rawValue);
	const hasLowClimateZone = /\b[1-3](?:[A-C])?\b/.test(rawValue);

	if (hasLessThanFour || hasLowClimateZone) {
		matchedCodes.push('LT4');
	}

	if (/\b7A\b/.test(rawValue)) {
		matchedCodes.push('7A');
	}

	if (/\b7B\b/.test(rawValue)) {
		matchedCodes.push('7B');
	}

	if (/\b6\b/.test(rawValue)) {
		matchedCodes.push('6');
	}

	if (/\b5\b/.test(rawValue)) {
		matchedCodes.push('5');
	}

	if (/\b4\b/.test(rawValue) && !hasLessThanFour) {
		matchedCodes.push('4');
	}

	if (/\b8\b/.test(rawValue)) {
		matchedCodes.push('8');
	}

	return [...new Set(matchedCodes)];
};

const applyWallFilters = () => {
	const wallCards = document.querySelectorAll('.wall-card');
	const searchTerm = searchInput?.value.toLowerCase() || '';
	const selectedClimateZones = Array.from(climateZoneCheckboxes)
		.filter((checkbox) => checkbox.checked)
		.map((checkbox) => checkbox.value.toUpperCase());
	const selectedInsulationOptions = Array.from(insulationFilterCheckboxes)
		.filter((checkbox) => checkbox.checked)
		.map((checkbox) => checkbox.value.toLowerCase());
	const selectedAirBarrierOptions = Array.from(airBarrierFilterCheckboxes)
		.filter((checkbox) => checkbox.checked)
		.map((checkbox) => checkbox.value);

	wallCards.forEach((card) => {
		const searchText = card.getAttribute('data-search-text') || '';
		const cardClimateZones = extractClimateZoneCodes(card.getAttribute('data-climate-zone') || '');
		const cardInsulationMaterials = (card.getAttribute('data-insulation-materials') || '')
			.split('|')
			.map((material) => material.trim().toLowerCase())
			.filter((material) => material !== '');
		const cardAirBarrierValue = card.getAttribute('data-has-air-barrier') || '0';
		const matchesSearch = searchText.includes(searchTerm);
		const matchesClimateZone = selectedClimateZones.length === 0 || selectedClimateZones.some((zone) => cardClimateZones.includes(zone));
		const matchesInsulation = selectedInsulationOptions.length === 0 || selectedInsulationOptions.some((selectedMaterial) => cardInsulationMaterials.includes(selectedMaterial));
		const matchesAirBarrier = selectedAirBarrierOptions.length === 0 || selectedAirBarrierOptions.includes(cardAirBarrierValue);

		card.style.display = matchesSearch && matchesClimateZone && matchesInsulation && matchesAirBarrier ? '' : 'none';
	});
};

const getCheckboxLabel = (checkbox) => checkbox.closest('label')?.querySelector('span')?.textContent?.trim() || checkbox.value;

const renderSelectedFiltersSummary = () => {
	if (!selectedFiltersSummary) {
		return;
	}

	const selectedClimateCheckboxes = Array.from(climateZoneCheckboxes).filter((checkbox) => checkbox.checked);
	const selectedInsulationCheckboxes = Array.from(insulationFilterCheckboxes).filter((checkbox) => checkbox.checked);
	const selectedAirBarrierCheckboxes = Array.from(airBarrierFilterCheckboxes).filter((checkbox) => checkbox.checked);
	const selectedGroups = [
		{ label: 'Climate', values: selectedClimateCheckboxes, type: 'climate' },
		{ label: 'Insulation', values: selectedInsulationCheckboxes, type: 'insulation' },
		{ label: 'Air Barrier', values: selectedAirBarrierCheckboxes, type: 'air-barrier' },
	].filter((group) => group.values.length > 0);

	if (selectedGroups.length === 0) {
		selectedFiltersSummary.innerHTML = '<p class="text-sm text-zinc-500 dark:text-zinc-400">No filters selected.</p>';
		return;
	}

	selectedFiltersSummary.innerHTML = selectedGroups.map((group) => `
		<div class="flex flex-wrap gap-2">
			${group.values.map((checkbox) => `
				<button
					type="button"
					class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 transition hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
					data-filter-type="${group.type}"
					data-filter-value="${checkbox.value}"
					aria-label="Remove ${getCheckboxLabel(checkbox)}"
				>
					<span>${getCheckboxLabel(checkbox)}</span>
					<span aria-hidden="true">×</span>
				</button>
			`).join('')}
		</div>
	`).join('');

	selectedFiltersSummary.querySelectorAll('button[data-filter-type]').forEach((button) => {
		button.addEventListener('click', () => {
			const filterType = button.getAttribute('data-filter-type');
			const filterValue = button.getAttribute('data-filter-value');

			const checkboxSet = filterType === 'climate'
				? climateZoneCheckboxes
				: filterType === 'insulation'
					? insulationFilterCheckboxes
					: airBarrierFilterCheckboxes;

			checkboxSet.forEach((checkbox) => {
				if (checkbox.value === filterValue) {
					checkbox.checked = false;
				}
			});

			applyWallFilters();
			renderSelectedFiltersSummary();
		});
	});
};

const syncFilters = () => {
	applyWallFilters();
	renderSelectedFiltersSummary();
};

if (searchInput) {
	searchInput.addEventListener('input', syncFilters);
}

if (climateZoneFilter) {
	climateZoneFilter.addEventListener('change', syncFilters);
}

insulationFilterCheckboxes.forEach((checkbox) => {
	checkbox.addEventListener('change', syncFilters);
});

airBarrierFilterCheckboxes.forEach((checkbox) => {
	checkbox.addEventListener('change', syncFilters);
});

if (clearAllFiltersButton) {
	clearAllFiltersButton.addEventListener('click', () => {
		searchInput.value = '';

		[...climateZoneCheckboxes, ...insulationFilterCheckboxes, ...airBarrierFilterCheckboxes].forEach((checkbox) => {
			checkbox.checked = false;
		});

		syncFilters();
	});
}

syncFilters();

