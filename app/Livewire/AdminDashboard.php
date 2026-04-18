<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AdminDashboard extends Component
{
    public string $search = '';

    public string $category = 'all';

    public string $selectedPanel = 'users';

    public bool $showSuccessBanner = false;

    public string $lastActionMessage = '';

    /**
     * @var array<int, array{id: string, category: string, title: string, blurb: string, impact: string, action_label: string}>
     */
    private array $panels = [
        [
            'id' => 'users',
            'category' => 'security',
            'title' => 'User access controls',
            'blurb' => 'Review access and verification posture before operational changes.',
            'impact' => 'High',
            'action_label' => 'Audit user access',
        ],
        [
            'id' => 'imports',
            'category' => 'operations',
            'title' => 'Import queue monitor',
            'blurb' => 'Track import readiness and trigger queue checks for inbound data.',
            'impact' => 'Medium',
            'action_label' => 'Run import diagnostics',
        ],
        [
            'id' => 'config',
            'category' => 'configuration',
            'title' => 'Environment safeguards',
            'blurb' => 'Validate environment-sensitive settings used by core admin workflows.',
            'impact' => 'High',
            'action_label' => 'Verify safeguards',
        ],
        [
            'id' => 'content',
            'category' => 'operations',
            'title' => 'Material curation review',
            'blurb' => 'Review how material records are categorized before publication.',
            'impact' => 'Medium',
            'action_label' => 'Start curation review',
        ],
    ];

    public function updatedSearch(): void
    {
        $this->normalizeSelectedPanel();
    }

    public function updatedCategory(): void
    {
        $this->normalizeSelectedPanel();
    }

    public function selectPanel(string $panelId): void
    {
        if ($this->filteredPanels->contains(fn (array $panel) => $panel['id'] === $panelId)) {
            $this->selectedPanel = $panelId;
        }
    }

    public function runPanelAction(string $panelId): void
    {
        $panel = collect($this->panels)->firstWhere('id', $panelId);

        if (! is_array($panel)) {
            return;
        }

        $this->lastActionMessage = 'Action queued for '.$panel['title'].'.';
        $this->showSuccessBanner = true;
    }

    public function dismissSuccessBanner(): void
    {
        $this->showSuccessBanner = false;
    }

    #[Computed]
    public function filteredPanels(): Collection
    {
        $searchTerm = mb_strtolower(trim($this->search));

        return collect($this->panels)
            ->filter(function (array $panel) use ($searchTerm): bool {
                if ($this->category !== 'all' && $panel['category'] !== $this->category) {
                    return false;
                }

                if ($searchTerm === '') {
                    return true;
                }

                $panelText = mb_strtolower($panel['title'].' '.$panel['blurb'].' '.$panel['category']);

                return mb_stripos($panelText, $searchTerm) !== false;
            })
            ->values();
    }

    #[Computed]
    public function activePanel(): ?array
    {
        $selected = $this->filteredPanels->firstWhere('id', $this->selectedPanel);

        if (is_array($selected)) {
            return $selected;
        }

        $fallback = $this->filteredPanels->first();

        return is_array($fallback) ? $fallback : null;
    }

    private function normalizeSelectedPanel(): void
    {
        if ($this->activePanel !== null) {
            $this->selectedPanel = $this->activePanel['id'];
        }
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
