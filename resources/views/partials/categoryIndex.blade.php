<!-- Categories Table -->
<table id="categoriesTable">
    <thead>
        <tr>
            <th>{{__('ID')}}</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Color')}}</th>
            @role('leader')
                <th>{{__('Update')}}</th>
                <th>{{__('Delete')}}</th>
            @endrole
        </tr>
    </thead>


    <tbody>
        @forelse($categories as $category)
            <tr id="category-{{ $category->id }}">
                <td>{{ $category->id }}</td>
                <td>
                    @role('leader')
                        <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
                    @else
                        {{ $category->name }}
                    @endrole
                </td>
                <td>
                    @role('leader')
                        <input type="color" name="color" value="{{ $category->color }}" class="color-box" required>
                    @else
                        <div class="color-square" style="background-color: {{ $category->color }};"></div>
                    @endrole
                </td>
                @role('leader')
                    <td>
                        <button class="update-category-button edit-button" data-id="{{ $category->id }}">{{__('Update')}}</button>
                    </td>
                    <td>
                        <button class="delete-category-button edit-button" data-id="{{ $category->id }}">{{__('Delete')}}</button>
                    </td>
                @endrole
            </tr>
        @empty
            <tr>
                <td colspan="5">No categories found.</td>
            </tr>
        @endforelse
    </tbody>
</table>


