@foreach($categories as $category)
    <div class="category-card">
        <div class="category-header">
            <div class="color-box" style="background-color: {{ $category->color }};"></div>
            <div class="category-name">{{ $category->name }}</div>
        </div>

        <div class="task-list">
            @role('leader')
                @if($category->task->count())
                    @foreach($category->task as $catTask)
                        <div class="task-item">
                            <!-- Localized task description -->
                            {{ json_decode($catTask->task_description, true)[app()->getLocale()] }}
                            <!-- Localized task status -->


                        </div>
                    @endforeach
                @else
                    <p>{{ __('No tasks found for this category.') }}</p>
                @endif
            @else
                @if($category->tasks->count())
                    @foreach($category->tasks as $catTask)
                        @foreach($catTask->users as $user)
                            @if($user->id == $userId)
                                <div class="task-item">
                                    <!-- Localized task description -->
                                    {{ json_decode($catTask->task_description, true)[app()->getLocale()] }}
                                    <!-- Localized task status -->
                                    <span class="task-status">
                                        {{ App\Enums\TaskStatus::getLocalizedValues()[$catTask->status] }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                @else
                    <p>{{ __('No tasks found for this category.') }}</p>
                @endif
            @endrole
        </div>
    </div>
@endforeach
