<?php

use App\Models\Todo;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('Todoを編集')] class extends Component {
    public Todo $todo;

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

    public function mount(Todo $todo): void
    {
        // 自分が作ったイベントでなければ 403
        abort_unless($todo->isOwnedBy(auth()->user()), 403);

        $this->todo = $todo;
        $this->title = $todo->title;
        $this->memo = $todo->memo;
        $this->category = $todo->category;
        $this->start_at = $todo->start_at->format('Y-m-d\TH:i');
        $this->due_at = $todo->due_at->format('Y-m-d\TH:i');
    }

    public function save(): void
    {
        abort_unless($this->todo->isOwnedBy(auth()->user()), 403);

        $this->todo->update($this->validate());

        session()->flash('status', 'Todoを更新しました。');

        $this->redirectRoute('todos.index', navigate: true);
    }

    public function delete(): void
    {
        abort_unless($this->todo->isOwnedBy(auth()->user()), 403);

        $this->todo->delete();

        session()->flash('status', 'Todoを削除しました。');

        $this->redirectRoute('todos.index', navigate: true);
    }
}; 
?>

<div class="mx-auto max-w-2xl space-y-6">
    <flux:heading size="xl">Todoを編集</flux:heading>

    <form wire:submit="save" class="space-y-6">
        <flux:input wire:model="title" label="やること" />
        <flux:textarea wire:model="memo" label="メモ" rows="6" />
        <flux:input wire:model="category" label="カテゴリ" />

        <div class="grid gap-6 sm:grid-cols-2">
            <flux:input wire:model="start_at" label="着手日時" type="datetime-local" />
            <flux:input wire:model="due_at" label="期限" type="datetime-local" />
        </div>

        <div class="flex justify-between">
            <flux:button wire:click="delete" wire:confirm="このTodoを削除しますか？" variant="danger" icon="trash">削除</flux:button>
            <div class="flex gap-3">
                <flux:button :href="route('todos.index')" variant="ghost" wire:navigate>キャンセル</flux:button>
                <flux:button type="submit" variant="primary">更新する</flux:button>
            </div>
        </div>
    </form>
</div>
