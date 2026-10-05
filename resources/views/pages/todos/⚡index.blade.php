<?php

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Todo一覧')] class extends Component {
    /**
     * 期限が近い順にTodoを取り出す。
     *
     * @return Collection<int, Todo>
     */
    #[Computed]
    public function todos(): Collection
    {
        return Todo::query()->with('user')->orderBy('due_at')->get();
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Todo一覧</flux:heading>
        @auth
            <flux:button :href="route('todos.create')" variant="primary" icon="plus" wire:navigate>Todoを登録</flux:button>
        @endauth
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            <flux:callout.heading>{{ session('status') }}</flux:callout.heading>
        </flux:callout>
    @endif

    @if ($this->todos->isEmpty())
        <flux:text>Todoはまだありません。</flux:text>
    @else
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Todo</flux:table.column>
                <flux:table.column>期限</flux:table.column>
                <flux:table.column>カテゴリ</flux:table.column>
                <flux:table.column>作成者</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($this->todos as $todo)
                    <flux:table.row wire:key="todo-{{ $todo->id }}">
                        <flux:table.cell variant="strong">{{ $todo->title }}</flux:table.cell>
                        <flux:table.cell>{{ $todo->due_at->isoFormat('M月D日(ddd) HH:mm') }} </flux:table.cell>
                        <flux:table.cell>{{ $todo->category }}</flux:table.cell>
                        <flux:table.cell>{{ $todo->user->name }}</flux:table.cell>
                        <flux:table.cell>
                            @if ($todo->isOwnedBy(auth()->user()))
                                <flux:button :href="route('todos.edit', $todo)" size="sm" icon="pencil-square" wire:navigate>編集</flux:button>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    @endif
</div>
