<?php

use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('todoを登録')] class extends Component {
    #[Validate('required|string|max:100')]
    public string $title = '';

    #[Validate('required|string|max:2000')]
    public string $memo = '';

    #[Validate('required|string|max:100')]
    public string $category = '';

    #[Validate('required|date')]
    public string $start_at = '';

    #[Validate('required|date|after:start_at')]
    public string $due_at = '';

    public function save(): void
    {
        $validated = $this->validate();

        // ログイン中のユーザーのTodoとして保存する（user_id が自動で入る）
        auth()->user()->todos()->create($validated);
        session()->flash('status', 'Todoを登録しました。');

        $this->redirectRoute('todos.index', navigate: true);
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <flux:heading size="xl">todoを登録</flux:heading>


    <form wire:submit="save" class="space-y-6">
        <flux:input wire:model="title" label="やること" placeholder="" />
        <flux:textarea wire:model="memo" label="メモ" rows="6" />
        <flux:input wire:model="category" label="カテゴリ" placeholder="例: 仕事、買い物、勉強" />

        <div class="grid gap-6 sm:grid-cols-2">
            <flux:input wire:model="start_at" label="着手日時" type="datetime-local" />
            <flux:input wire:model="due_at" label="期限" type="datetime-local" />
        </div>

        {{-- <div class="flex justify-end"> --}}
        <div class="flex justify-end gap-3">
            <flux:button :href="route('todos.index')" variant="ghost" wire:navigate>キャンセル</flux:button>
            <flux:button type="submit" variant="primary">登録する</flux:button>
        </div>
    </form>
</div>
